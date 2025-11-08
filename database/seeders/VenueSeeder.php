<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rikza = User::where('email', 'rikza@bagipanen.com')->first();
        $rizky = User::where('email', 'rizky@bagipanen.com')->first();

        Venue::create([
            'user_id' => $rikza->id,
            'name' => 'Pasar Modern Intermoda, BSD',
            'address' => 'Jl. Pahlawan Seribu, BSD City, Tangerang Selatan',
            'postal_code' => '15310'
        ]);

        Venue::create([
            'user_id' => $rizky->id,
            'name' => 'JPM XCO Track, Pamulang',
            'address' => 'Jl. Raya Siliwangi, Pamulang, Tangerang Selatan',
            'postal_code' => '15417'
        ]);

        Venue::create([
            'user_id' => $rikza->id,
            'name' => 'Bunder HI Kelapa Tiga',
            'address' => 'Jl. Sudirman, Jakarta Pusat',
            'postal_code' => '19038481'
        ]);

        Venue::create([
            'user_id' => $rikza->id,
            'name' => 'Gelora Bung Karno',
            'address' => 'Jl. Pintu Satu Senayan, Jakarta Selatan',
            'postal_code' => '12190'
        ]);

        Venue::create([
            'user_id' => $rizky->id,
            'name' => 'Tennis Indoor Senayan',
            'address' => 'Jl. Asia Afrika, Jakarta Pusat',
            'postal_code' => '10270'
        ]);
    }
}
