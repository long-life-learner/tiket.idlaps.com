<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eventId = Event::where('title', 'PGT 10K Run')->first()->id;
        EventClass::create([
            'event_id' => $eventId,
            'image' => '',
            'name' => 'Kelas Pria Dewasa',
            'gender' => 'pria',
            'price' => 150000
        ]);

        EventClass::create([
            'event_id' => $eventId,
            'image' => '/event-classes/01K9GE9J4PGDMZ88P0MJ0B31KM.png',
            'name' => 'Kelas Wanita Dewasa',
            'gender' => 'wanita',
            'price' => 200000
        ]);
    }
}
