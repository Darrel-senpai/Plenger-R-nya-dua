<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $surabaya = City::where('name', 'Surabaya')->first();

        // Kecamatan-kecamatan Surabaya yang relevan untuk demo
        $districts = [
            ['city_id' => $surabaya->id, 'name' => 'Tambaksari', 'code' => '357801'],
            ['city_id' => $surabaya->id, 'name' => 'Wonokromo', 'code' => '357802'],
            ['city_id' => $surabaya->id, 'name' => 'Genteng', 'code' => '357803'],
            ['city_id' => $surabaya->id, 'name' => 'Sawahan', 'code' => '357804'],
            ['city_id' => $surabaya->id, 'name' => 'Krembangan', 'code' => '357805'],
            ['city_id' => $surabaya->id, 'name' => 'Rungkut', 'code' => '357806'],
            ['city_id' => $surabaya->id, 'name' => 'Gubeng', 'code' => '357807'],
            ['city_id' => $surabaya->id, 'name' => 'Tegalsari', 'code' => '357808'],
        ];

        foreach ($districts as $data) {
            District::updateOrCreate(
                ['city_id' => $data['city_id'], 'name' => $data['name']],
                $data
            );
        }

        $this->command->info('Created ' . count($districts) . ' districts in Surabaya.');
    }
}