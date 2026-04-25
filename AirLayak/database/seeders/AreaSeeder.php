<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            ['kelurahan' => 'Tambaksari', 'kecamatan' => 'Tambaksari', 'lat' => -7.2542, 'lng' => 112.7639, 'population_density' => 22000, 'dominant_water_sources' => ['pdam', 'sumur']],
            ['kelurahan' => 'Pacar Keling', 'kecamatan' => 'Tambaksari', 'lat' => -7.2502, 'lng' => 112.7615, 'population_density' => 24000, 'dominant_water_sources' => ['pdam', 'sumur']],
            ['kelurahan' => 'Ploso', 'kecamatan' => 'Tambaksari', 'lat' => -7.2461, 'lng' => 112.7708, 'population_density' => 20000, 'dominant_water_sources' => ['pdam']],
            
            ['kelurahan' => 'Wonokromo', 'kecamatan' => 'Wonokromo', 'lat' => -7.3033, 'lng' => 112.7378, 'population_density' => 18000, 'dominant_water_sources' => ['sumur', 'pdam']],
            ['kelurahan' => 'Jagir', 'kecamatan' => 'Wonokromo', 'lat' => -7.3088, 'lng' => 112.7444, 'population_density' => 19500, 'dominant_water_sources' => ['sumur', 'pdam']],
            ['kelurahan' => 'Ngagel', 'kecamatan' => 'Wonokromo', 'lat' => -7.2979, 'lng' => 112.7461, 'population_density' => 17000, 'dominant_water_sources' => ['pdam', 'sumur']],
            
            ['kelurahan' => 'Genteng', 'kecamatan' => 'Genteng', 'lat' => -7.2614, 'lng' => 112.7479, 'population_density' => 15000, 'dominant_water_sources' => ['pdam']],
            ['kelurahan' => 'Embong Kaliasin', 'kecamatan' => 'Genteng', 'lat' => -7.2664, 'lng' => 112.7426, 'population_density' => 14000, 'dominant_water_sources' => ['pdam']],
            
            ['kelurahan' => 'Sawahan', 'kecamatan' => 'Sawahan', 'lat' => -7.2685, 'lng' => 112.7283, 'population_density' => 25000, 'dominant_water_sources' => ['pdam', 'air_isi_ulang']],
            ['kelurahan' => 'Petemon', 'kecamatan' => 'Sawahan', 'lat' => -7.2747, 'lng' => 112.7283, 'population_density' => 23000, 'dominant_water_sources' => ['pdam', 'air_isi_ulang']],
            ['kelurahan' => 'Banyu Urip', 'kecamatan' => 'Sawahan', 'lat' => -7.2778, 'lng' => 112.7197, 'population_density' => 21000, 'dominant_water_sources' => ['pdam', 'air_isi_ulang']],
            
            ['kelurahan' => 'Krembangan Selatan', 'kecamatan' => 'Krembangan', 'lat' => -7.2347, 'lng' => 112.7322, 'population_density' => 16000, 'dominant_water_sources' => ['pdam', 'sumur']],
            ['kelurahan' => 'Dupak', 'kecamatan' => 'Krembangan', 'lat' => -7.2400, 'lng' => 112.7236, 'population_density' => 18500, 'dominant_water_sources' => ['pdam', 'sumur']],
            
            ['kelurahan' => 'Rungkut Kidul', 'kecamatan' => 'Rungkut', 'lat' => -7.3389, 'lng' => 112.7861, 'population_density' => 12000, 'dominant_water_sources' => ['pdam', 'sumur']],
            ['kelurahan' => 'Kalirungkut', 'kecamatan' => 'Rungkut', 'lat' => -7.3325, 'lng' => 112.7811, 'population_density' => 13000, 'dominant_water_sources' => ['pdam', 'sumur']],
            
            ['kelurahan' => 'Gubeng', 'kecamatan' => 'Gubeng', 'lat' => -7.2628, 'lng' => 112.7544, 'population_density' => 17500, 'dominant_water_sources' => ['pdam', 'galon', 'air_isi_ulang']],
            ['kelurahan' => 'Airlangga', 'kecamatan' => 'Gubeng', 'lat' => -7.2697, 'lng' => 112.7583, 'population_density' => 16500, 'dominant_water_sources' => ['pdam', 'air_isi_ulang']],
            ['kelurahan' => 'Mojo', 'kecamatan' => 'Gubeng', 'lat' => -7.2728, 'lng' => 112.7647, 'population_density' => 18000, 'dominant_water_sources' => ['pdam', 'galon']],
            
            ['kelurahan' => 'Tegalsari', 'kecamatan' => 'Tegalsari', 'lat' => -7.2719, 'lng' => 112.7372, 'population_density' => 19000, 'dominant_water_sources' => ['pdam']],
            ['kelurahan' => 'Wonorejo', 'kecamatan' => 'Tegalsari', 'lat' => -7.2783, 'lng' => 112.7411, 'population_density' => 20500, 'dominant_water_sources' => ['pdam', 'sumur']],
        ];

        foreach ($areas as $data) {
            Area::createWithPoint(
                attributes: [
                    'kelurahan' => $data['kelurahan'],
                    'kecamatan' => $data['kecamatan'],
                    'city' => 'Surabaya',
                    'city_type' => 'kota',
                    'province' => 'Jawa Timur',
                    'population_density' => $data['population_density'],
                    'dominant_water_sources' => $data['dominant_water_sources'],
                ],
                spatialColumns: [
                    'centroid' => ['lat' => $data['lat'], 'lng' => $data['lng']],
                ]
            );
        }

        $this->command->info('Created ' . count($areas) . ' areas (kelurahan) in Surabaya, Jawa Timur.');
    }
}