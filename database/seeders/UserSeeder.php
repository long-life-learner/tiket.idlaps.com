<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@tiket.idlaps.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Rikza',
            'email' => 'rikza@bagipanen.com',
            'password' => bcrypt('password'),
            'role' => 'venue_owner',
        ]);

        User::create([
            'name' => 'Rizky',
            'email' => 'rizky@bagipanen.com',
            'password' => bcrypt('password'),
            'role' => 'venue_owner',
        ]);
    }
}
