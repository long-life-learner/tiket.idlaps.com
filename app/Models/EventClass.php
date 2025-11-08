<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventClass extends Model
{
    protected $fillable = [
        'event_id',
        'image',
        'name',
        'gender',
        'price',
        'max_participants',
        'description'

    ];

    protected $casts = [
        'price' => 'decimal:2'
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
