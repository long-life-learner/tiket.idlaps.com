<?php

namespace App\Http\Controllers;

use App\Interfaces\EventRepositoryInterface;
use App\Interfaces\BookingRepositoryInterface;
use App\Http\Requests\BookingInformationRequest;
use App\Http\Requests\CheckBookingRequest;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;

class BookingController extends Controller
{
    private EventRepositoryInterface $eventRepository;
    private BookingRepositoryInterface $bookingRepository;

    public function __construct(
        EventRepositoryInterface $eventRepository,
        BookingRepositoryInterface $bookingRepository
    ) {
        $this->eventRepository = $eventRepository;
        $this->bookingRepository = $bookingRepository;
    }

    public function show(string $slug, Request $request)
    {
        $event = $this->eventRepository->getBySlug($slug);

        return view('bookings.step-one', [
            'event' => $event,
            'status' => $request->query('status', 'event'),
            'booking' => $request->session()->get('booking')
        ]);
    }

    public function information(string $slug)
    {
        $event = $this->eventRepository->getBySlug($slug);

        return view('bookings.information', compact('event'));
    }

    public function saveInformation(BookingInformationRequest $request, string $slug)
    {
        $event = $this->eventRepository->getBySlug($slug);

        $request->merge([
            'event_id' => $event->id,
        ]);

        $this->bookingRepository->saveInformation($request->all());

        return redirect()->route('bookings.checkout', $slug);
    }

    public function checkout(string $slug)
    {
        $event = $this->eventRepository->getBySlug($slug);
        $bookingData = session('booking_data');

        // GET EVENT CLASS PRICE BY ID
        $classPrice = $this->eventRepository->getEventClassPriceById($bookingData['event_class_id']);
        // GET CLASS NAME BY ID
        $className = $this->eventRepository->getEventClassNameById($bookingData['event_class_id']);


        // $subTotal = $event->price;
        // $tax = $subTotal * 0.11;
        // $insurance = 0;
        // $grandTotal = $subTotal + $tax + $insurance;

        return view('bookings.step-two', [
            'event' => $event,
            // 'subTotal' => $subTotal,
            // 'tax' => $tax,
            // 'insurance' => $insurance,
            // 'grandTotal' => $grandTotal,
            // 'bookingData' => $bookingData,
            'classPrice' => $classPrice,
            'className' => $className,
        ]);
    }

    public function payment(string $slug)
    {
        $event = $this->eventRepository->getBySlug($slug);

        $paymentUrl = $this->bookingRepository->createBooking($event);

        return redirect($paymentUrl);
    }

    public function finished(Request $request)
    {
        $booking = $this->bookingRepository->getByCode($request->order_id);


        $class = $booking->eventClass;

        return view('bookings.finished', compact('booking', 'class'));
    }

    public function check()
    {
        return view('bookings.check');
    }

    public function checkBooking(CheckBookingRequest $request)
    {
        $booking = $this->bookingRepository->getByCodeAndEmail($request->booking_id, $request->email);


        if (!$booking) {
            return redirect()->route('bookings.check')->with('error', 'Booking not found');
        }

        return redirect()->route('bookings.ticket', $booking->code);
    }

    public function ticket(string $slug)
    {
        $booking = $this->bookingRepository->getByCode($slug);

        return view('bookings.show', compact('booking'));
    }
}
