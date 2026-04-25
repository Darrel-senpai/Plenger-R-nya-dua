<?php

namespace Database\Seeders;

use App\Models\IndustrialFacility;
use Illuminate\Database\Seeder;

class IndustrialFacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            ['PT Tjiwi Kimia (Pulp & Kertas)', -7.3450, 112.7872, 'chemical', 'Rungkut'],
            ['PT Rungkut Industri', -7.3402, 112.7901, 'other', 'Rungkut'],
            ['PT SIER Tekstil', -7.3478, 112.7889, 'textile', 'Rungkut'],
            ['PT Bumi Menara Internusa', -7.3500, 112.7920, 'food', 'Rungkut'],
            ['PT Maspion (Metal)', -7.3389, 112.7950, 'metal', 'Rungkut'],
            ['PT Wings Surya (Detergen)', -7.3422, 112.7858, 'chemical', 'Rungkut'],
            ['PT Ajinomoto Indonesia', -7.3467, 112.7878, 'food', 'Rungkut'],
            
            ['PT Indofood Sukses Makmur', -7.2389, 112.6989, 'food', 'Asemrowo'],
            ['PT Sinar Sosro', -7.2350, 112.7001, 'food', 'Asemrowo'],
            ['PT Coca-Cola Bottling', -7.2412, 112.7050, 'food', 'Asemrowo'],
            ['PT Berlina Plastik', -7.2367, 112.7022, 'plastic', 'Asemrowo'],
            
            ['Industri Konveksi Pacar Keling', -7.2520, 112.7625, 'textile', 'Tambaksari'],
            ['Pabrik Roti Karya', -7.2545, 112.7670, 'food', 'Tambaksari'],
            
            ['PT Tekstil Wonokromo', -7.3040, 112.7400, 'textile', 'Wonokromo'],
            ['PT Pewarna Indah', -7.3050, 112.7420, 'chemical', 'Wonokromo'],
            ['Pabrik Sablon Jagir', -7.3095, 112.7458, 'textile', 'Wonokromo'],
            
            ['PT Galangan Kapal Surabaya', -7.2289, 112.7301, 'metal', 'Krembangan'],
            ['Pergudangan Dupak', -7.2378, 112.7245, 'other', 'Krembangan'],
            ['PT Logam Jaya', -7.2334, 112.7256, 'metal', 'Krembangan'],
            
            ['PT Pakuwon Manufacturing', -7.2589, 112.6878, 'electronics', 'Tandes'],
            ['PT Petrokimia Gresik (cabang)', -7.2433, 112.6900, 'chemical', 'Tandes'],
            ['PT Pharmaceutical Sby', -7.2511, 112.6945, 'pharmaceutical', 'Tandes'],
            
            ['PT Wilmar Cahaya Indonesia', -7.3567, 112.7100, 'food', 'Karang Pilang'],
            ['PT Plastik Murni', -7.3601, 112.7150, 'plastic', 'Karang Pilang'],
            ['PT Cement Surabaya', -7.3645, 112.7178, 'other', 'Karang Pilang'],
            
            ['PT Sampoerna Tobacco', -7.2456, 112.7012, 'other', 'Asemrowo'],
            ['PT Karya Logam', -7.2489, 112.7045, 'metal', 'Asemrowo'],
            
            ['Konveksi Gubeng', -7.2645, 112.7550, 'textile', 'Gubeng'],
            ['Percetakan Modern', -7.2678, 112.7589, 'other', 'Gubeng'],
            
            ['PT Plastik Sawahan', -7.2700, 112.7290, 'plastic', 'Sawahan'],
            ['Industri Cat Petemon', -7.2755, 112.7295, 'chemical', 'Sawahan'],
            
            ['PT Komponen Otomotif', -7.2367, 112.6845, 'metal', 'Tandes'],
            ['PT Elektronik Maju', -7.2401, 112.6878, 'electronics', 'Tandes'],
            ['PT Furnitur Berkualitas', -7.2434, 112.6912, 'other', 'Tandes'],
        ];

        foreach ($facilities as [$name, $lat, $lng, $type, $kecamatan]) {
            IndustrialFacility::createWithPoint(
                attributes: [
                    'name' => $name,
                    'industry_type' => $type,
                    'kecamatan' => $kecamatan,
                    'city' => 'Surabaya',
                    'province' => 'Jawa Timur',
                    'source' => 'manual',
                ],
                spatialColumns: [
                    'location' => ['lat' => $lat, 'lng' => $lng],
                ]
            );
        }

        $this->command->info('Created ' . count($facilities) . ' industrial facilities in Surabaya.');
    }
}