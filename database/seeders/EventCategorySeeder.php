<?php

namespace Database\Seeders;

use App\Models\EventCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EventCategory::create([
            'name' => 'Running',
            'slug' => 'running',
            'description' => 'Marathon, Fun Run, Sprint',
            'icon' => '/event-category-icons/running-category.webp'
        ]);

        EventCategory::create([
            'name' => 'Cycling',
            'slug' => 'cycling',
            'description' => 'Road Cycling, Mountain Biking, BMX',
            'icon' => '/event-category-icons/mtb-category.webp'
        ]);
        EventCategory::create([
            'name' => 'Triathlon',
            'slug' => 'triathlon',
            'description' => 'Swimming, Cycling, Running',
            'icon' => '/event-category-icons/triathlon-category.webp'
        ]);

        EventCategory::create([
            'name' => 'Swimming',
            'slug' => 'swimming',
            'description' => 'Freestyle, Butterfly, Breaststroke',
            'icon' => 'assets/image/swim.png'
        ]);

        EventCategory::create([
            'name' => 'Boxing',
            'slug' => 'boxing',
            'description' => 'Amateur Boxing, Professional Boxing',
            'icon' => 'assets/image/boxing.png'
        ]);

        EventCategory::create([
            'name' => 'Tennis',
            'slug' => 'tennis',
            'description' => 'Singles, Doubles, Mixed Doubles',
            'icon' => 'assets/image/tennis.png'
        ]);

        EventCategory::create([
            'name' => 'Powerlifting',
            'slug' => 'powerlifting',
            'description' => 'Deadlift, Squat, Bench Press',
            'icon' => 'assets/image/power.png'
        ]);



        EventCategory::create([
            'name' => 'Badminton',
            'slug' => 'badminton',
            'description' => 'Singles, Doubles, Mixed Doubles',
            'icon' => 'assets/image/badminton.png'
        ]);
    }
}
