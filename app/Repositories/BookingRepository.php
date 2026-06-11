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
            'event_id'      => $data['event_id'],
            'event_class_id'=> $data['event_class_id'],
            'name'          => $data['name'],
            'email'         => $data['email'],
            'phone'         => $data['phone'],
            'jersey_size'   => $data['jersey_size'] ?? null,
        ]]);
    }

    public function createBooking(Event $event)
    {
        $bookingData = session('booking_data');

        // Get event class price and name
        $classPrice = app(EventRepository::class)->getEventClassPriceById($bookingData['event_class_id']);
        $className = app(EventRepository::class)->getEventClassNameById($bookingData['event_class_id']);

        // Create booking record first
        $transaction = Booking::create([
            'event_id' => $event->id,
            'event_class_id' => $bookingData['event_class_id'],
            'name' => $bookingData['name'],
            'email' => $bookingData['email'],
            'phone' => $bookingData['phone'],
            'subtotal' => 0,
            'tax' => 0,
            'insurance' => 0,
            'total' => $classPrice,
        ]);

        // Build Pakasir payment URL
        // Format: https://app.pakasir.com/pay/{slug}/{amount}?order_id={order_id}
        $pakasirSlug = config('pakasir.slug');
        $amount = (int) $classPrice;
        $orderId = $transaction->code;

        // Build redirect URL back to our finished page
        $baseUrl = config('pakasir.base_url');
        $redirectUrl = urlencode(route('bookings.finished', ['order_id' => $transaction->code], true));

        // Construct payment URL
        $paymentUrl = "{$baseUrl}/pay/{$pakasirSlug}/{$amount}?order_id={$orderId}&redirect={$redirectUrl}";

        return $paymentUrl;
    }
}
