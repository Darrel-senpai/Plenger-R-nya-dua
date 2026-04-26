<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' => Str::uuid()->toString(),
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'), // Default password
            'auth_type' => 'guest',
            'phone' => '081234567890',
            'region_id' => 'ID-JI', // Example region ID
            'default_lat' => -7.250445, // Example: Surabaya Latitude
            'default_lng' => 112.768845, // Example: Surabaya Longitude
            'profile_completed' => true,
        ]);
    }
}