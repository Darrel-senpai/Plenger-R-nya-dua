<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OperatorUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin AirLayak',
                'email' => 'admin@airlayak.id',
                'password' => Hash::make('password'),
                'auth_type' => 'instansi',
                'profile_completed' => true,
                'role' => 'admin',
                'city' => null,
                'organization' => 'AirLayak Platform',
                'is_active' => true,
            ],
            [
                'name' => 'Operator PDAM Surabaya',
                'email' => 'pdam@airlayak.id',
                'password' => Hash::make('password'),
                'auth_type' => 'instansi',
                'profile_completed' => true,
                'role' => 'pdam',
                'city' => 'Surabaya',
                'organization' => 'PDAM Surya Sembada',
                'is_active' => true,
            ],
            [
                'name' => 'Petugas Dinkes Surabaya',
                'email' => 'dinkes@airlayak.id',
                'password' => Hash::make('password'),
                'auth_type' => 'instansi',
                'profile_completed' => true,
                'role' => 'dinkes',
                'city' => 'Surabaya',
                'organization' => 'Dinas Kesehatan Kota Surabaya',
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info('Created ' . count($users) . ' instansi users.');
    }
}