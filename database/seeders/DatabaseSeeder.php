<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(LocationSeeder::class);

        User::updateOrCreate([
            'email' => 'admin@shg.local',
        ], [
            'name' => 'SHG Admin',
            'phone' => '9999999999',
            'district_name' => 'Head Office',
            'ulb_name' => 'Central ULB',
            'role' => 'admin',
            'password' => 'password123',
        ]);
    }
}
