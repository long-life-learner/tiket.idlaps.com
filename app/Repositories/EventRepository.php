<?php

namespace App\Repositories;

use App\Interfaces\EventRepositoryInterface;
use App\Models\Event;
use App\Models\EventClass;

class EventRepository implements EventRepositoryInterface
{
    public function getAllEvents(
        ?string $search,
        ?int $categoryId,
        ?int $venueId,
        ?bool $isFeatured,
        ?int $limit,
    ) {
        $query = Event::where(function ($query) use ($search, $categoryId, $venueId, $isFeatured) {
            if ($search) {
                $query->search($search);
            }

            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }

            if ($venueId) {
                $query->where('venue_id', $venueId);
            }

            if ($isFeatured) {
                $query->where('is_featured', $isFeatured);
            }
        })->with('category', 'venue');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public function getBySlug(string $slug)
    {
        return Event::where('slug', $slug)
            ->with('category', 'venue')
            ->withCount('bookings')
            ->first();
    }

    public function getEventClassPriceById(int $eventClassId)
    {
        $eventClass = EventClass::find($eventClassId);
        return $eventClass ? $eventClass->price : null;
    }
    public function getEventClassNameById(int $eventClassId)
    {
        $eventClass = EventClass::find($eventClassId);
        return $eventClass ? $eventClass->name : null;
    }
}
