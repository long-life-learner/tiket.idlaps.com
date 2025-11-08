<?php

namespace App\Repositories;

use App\Interfaces\BookingRepositoryInterface;
use App\Models\Booking;
use App\Models\Event;

class BookingRepository implements BookingRepositoryInterface
{
    public function getAllBookings(
        ?string $search,
        ?int $eventId,
        ?string $status,
        ?string $email,
        ?int $limit,
    ) {
        $query = Booking::where(function ($query) use ($search, $eventId, $status, $email, $limit) {
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', $search)
                        ->orWhere('name', 'LIKE', '%' . $search . '%');
                });
            }

            if ($eventId) {
                $query->where('event_id', $eventId);
            }

            if ($status) {
                $query->where('status', $status);
            }

            if ($email) {
                $query->where('email', $email);
            }
        })->with('event');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public function getByCode(string $code)
    {
        return Booking::where('code', $code)->with('event', 'eventClass')->first();
    }

    public function getByCodeAndEmail(string $code, string $email)
    {
        return Booking::where('code', $code)
            ->where('email', $email)
            ->with('event')
            ->first();
    }

    public function saveInformation(array $data)
    {
        session(['booking_data' => [
            'event_id' => $data['event_id'],
            'event_class_id' => $data['event_class_id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
        ]]);
    }

    public function createBooking(Event $event)
    {
        // $subTotal = $event->price;
        // $tax = $subTotal * 0.11;
        // $insurance = 0;
        $bookingData = session('booking_data');
        // GET EVENT CLASS PRICE BY ID
        $classPrice = app(EventRepository::class)->getEventClassPriceById($bookingData['event_class_id']);
        // GET CLASS NAME BY ID
        $className = app(EventRepository::class)->getEventClassNameById($bookingData['event_class_id']);
        $grandTotal = $classPrice;

        $transaction = Booking::create([
            'event_id' => $event->id,
            'event_class_id' => $bookingData['event_class_id'],
            'name' => $bookingData['name'],
            'email' => $bookingData['email'],
            'phone' => $bookingData['phone'],
            'subtotal' => 0,
            'tax' => 0,
            'insurance' => 0,
            'total' => $grandTotal,
        ]);

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = config('midtrans.is3ds');

        $params = [
            'transaction_details' => [
                'order_id' => $transaction->code,
                'gross_amount' => $transaction->total,
            ],
            'customer_details' => [
                'first_name' => $transaction->name,
                'email' => $transaction->email,
                'phone' => $transaction->phone,
            ],
            'item_details' => [
                [
                    'id' => $bookingData['event_class_id'],
                    'price' => $transaction->total,
                    'quantity' => 1,
                    'name' => $className . ' - ' . $event->title,
                ],
            ],
        ];

        $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;

        return $paymentUrl;
    }
}
