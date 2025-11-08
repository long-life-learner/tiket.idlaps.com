<?php

namespace Database\Seeders;

use App\Models\EventCategory;
use App\Models\EventClass;
use App\Models\PromoCode;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            UserSeeder::class,
            VenueSeeder::class,
            EventCategorySeeder::class,
            EventSeeder::class,
            EventClassSeeder::class,
            EventPrizeSeeder::class,
            PromoCodeSeeder::class,

        ]);
    }
}
