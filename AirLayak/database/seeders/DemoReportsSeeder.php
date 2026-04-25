<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoReportsSeeder extends Seeder
{
    public function run(): void
    {
        $findArea = function (string $kelurahanName) {
            return Area::where('city', 'Surabaya')
                ->where('kelurahan', $kelurahanName)
                ->first();
        };
        
        $pdamUser = User::where('email', 'pdam@airlayak.id')->first();
        
        // Akun Warga: Handy (Mapped via reporter_session_id)
        $handyId = 'e78ebffd-a8e3-4da9-be27-48c6475cba62';

        // ====================================================================
        // SCENARIO A: PDAM-DOMINANT CLUSTER (Tambaksari)
        // ====================================================================
        $tambaksari = $findArea('Tambaksari');
        $scenarioA = [
            // First 3 reports assigned to Handy via reporter_session_id
            ['lat' => -7.2542, 'lng' => 112.7639, 'category' => 'bau', 'water_sources' => ['pdam'], 'description' => 'Air ledeng bau klorin sangat menyengat sejak pagi', 'hours_ago' => 3, 'reporter_session_id' => $handyId],
            ['lat' => -7.2538, 'lng' => 112.7645, 'category' => 'bau', 'water_sources' => ['pdam'], 'description' => 'Bau aneh dari keran, seperti bau kaporit kuat', 'hours_ago' => 5, 'reporter_session_id' => $handyId],
            ['lat' => -7.2548, 'lng' => 112.7634, 'category' => 'bau', 'water_sources' => ['pdam'], 'description' => 'Air PDAM bau menyengat, anak saya tidak mau mandi', 'hours_ago' => 7, 'reporter_session_id' => $handyId],
            // Regular anonymous reports
            ['lat' => -7.2535, 'lng' => 112.7640, 'category' => 'bau', 'water_sources' => ['pdam', 'sumur'], 'description' => 'PDAM bau aneh, sumur agak ada bau juga', 'hours_ago' => 10],
            ['lat' => -7.2546, 'lng' => 112.7644, 'category' => 'rasa_aneh', 'water_sources' => ['pdam'], 'description' => 'Rasa air galon dimasak dari PDAM ada rasa kimia', 'hours_ago' => 12],
        ];
        foreach ($scenarioA as $data) {
            $this->createReport($tambaksari, $data);
        }
        
        // ====================================================================
        // SCENARIO B: MIXED WELL+PDAM CLUSTER (Wonokromo)
        // ====================================================================
        $wonokromo = $findArea('Wonokromo');
        $scenarioB = [
            ['lat' => -7.3033, 'lng' => 112.7378, 'category' => 'sakit_perut', 'water_sources' => ['sumur'], 'description' => 'Sekeluarga diare 2 hari, biasanya minum air sumur dimasak', 'hours_ago' => 2],
            ['lat' => -7.3045, 'lng' => 112.7385, 'category' => 'warna', 'water_sources' => ['sumur', 'pdam'], 'description' => 'Air sumur agak kekuningan, PDAM juga tidak sebening biasanya', 'hours_ago' => 4],
            ['lat' => -7.3028, 'lng' => 112.7370, 'category' => 'sakit_perut', 'water_sources' => ['sumur', 'pdam'], 'description' => 'Tetangga 3 rumah sama-sama diare, kami pakai sumur dan PDAM', 'hours_ago' => 6],
            ['lat' => -7.3050, 'lng' => 112.7392, 'category' => 'warna', 'water_sources' => ['sumur'], 'description' => 'Air sumur jadi keruh kekuningan padahal biasanya jernih', 'hours_ago' => 8],
            ['lat' => -7.3042, 'lng' => 112.7388, 'category' => 'sakit_perut', 'water_sources' => ['sumur'], 'description' => 'Anak muntah dan diare setelah minum dari air sumur dimasak', 'hours_ago' => 11],
            ['lat' => -7.3036, 'lng' => 112.7375, 'category' => 'rasa_aneh', 'water_sources' => ['sumur', 'pdam'], 'description' => 'Air ada rasa logam pahit, sumur dan PDAM sama-sama aneh', 'hours_ago' => 15],
        ];
        foreach ($scenarioB as $data) {
            $this->createReport($wonokromo, $data);
        }
        
        // ====================================================================
        // SCENARIO C: REFILL-DOMINANT CLUSTER (Sawahan)
        // ====================================================================
        $sawahan = $findArea('Sawahan');
        $scenarioC = [
            ['lat' => -7.2685, 'lng' => 112.7283, 'category' => 'sakit_perut', 'water_sources' => ['air_isi_ulang'], 'description' => 'Setelah ganti depot air isi ulang baru, sekeluarga diare', 'hours_ago' => 4],
            ['lat' => -7.2692, 'lng' => 112.7290, 'category' => 'sakit_perut', 'water_sources' => ['air_isi_ulang'], 'description' => 'Mual dan sakit perut, hanya minum air isi ulang dari depot', 'hours_ago' => 7],
            ['lat' => -7.2680, 'lng' => 112.7278, 'category' => 'rasa_aneh', 'water_sources' => ['air_isi_ulang'], 'description' => 'Air isi ulang ada rasa aneh, agak masam, beli dari depot dekat sini', 'hours_ago' => 10],
            ['lat' => -7.2698, 'lng' => 112.7295, 'category' => 'sakit_perut', 'water_sources' => ['air_isi_ulang', 'galon'], 'description' => 'Tetangga juga diare, biasa pakai isi ulang dari depot RT 03', 'hours_ago' => 14],
        ];
        foreach ($scenarioC as $data) {
            $this->createReport($sawahan, $data);
        }
        
        // ====================================================================
        // SCENARIO D: OVERDUE ACKNOWLEDGMENT (Genteng)
        // ====================================================================
        $genteng = $findArea('Genteng');
        $this->createReport($genteng, [
            'lat' => -7.2614, 'lng' => 112.7479,
            'category' => 'warna',
            'water_sources' => ['pdam'],
            'description' => 'Air PDAM agak kekuningan sejak kemarin malam',
            'hours_ago' => 18,
            'status' => 'pending',
            'priority' => 'high',
            'priority_score' => 70,
            'warning_count' => 1,
        ]);
        
        // ====================================================================
        // SCENARIO E: IN PROGRESS dengan ETA (Mojo)
        // ====================================================================
        $mojo = $findArea('Mojo');
        $this->createReport($mojo, [
            'lat' => -7.2728, 'lng' => 112.7647,
            'category' => 'bau',
            'water_sources' => ['pdam'],
            'description' => 'Air bau seperti tanah lumpur',
            'hours_ago' => 8,
            'status' => 'in_progress',
            'priority' => 'normal',
            'priority_score' => 55,
            'acknowledged_at' => now()->subHours(6),
            'acknowledged_by_user_id' => $pdamUser?->id,
            'work_started_at' => now()->subHours(5),
            'eta_at' => now()->addHours(6),
            'eta_reason' => 'Tim teknisi sedang dalam perjalanan ke titik pipa untuk inspeksi',
            'handled_by_user_id' => $pdamUser?->id,
            'handler_organization' => 'PDAM Surya Sembada',
        ]);
        
        // ====================================================================
        // SCENARIO F: AWAITING CONFIRMATION (Tegalsari)
        // ====================================================================
        $tegalsari = $findArea('Tegalsari');
        $this->createReport($tegalsari, [
            'lat' => -7.2719, 'lng' => 112.7372,
            'category' => 'bau',
            'water_sources' => ['pdam'],
            'description' => 'Air PDAM bau tidak normal',
            'hours_ago' => 30,
            'status' => 'awaiting_confirmation',
            'priority' => 'normal',
            'priority_score' => 50,
            'acknowledged_at' => now()->subHours(28),
            'acknowledged_by_user_id' => $pdamUser?->id,
            'work_started_at' => now()->subHours(26),
            'eta_at' => now()->subHours(2),
            'eta_reason' => 'Pembersihan saluran distribusi',
            'completion_claimed_at' => now()->subMinutes(30),
            'completion_notes' => 'Saluran distribusi sudah dibersihkan, klorinasi ulang dilakukan. Air seharusnya sudah normal.',
            'handled_by_user_id' => $pdamUser?->id,
            'handler_organization' => 'PDAM Surya Sembada',
        ]);
        
        // ====================================================================
        // BACKGROUND NOISE
        // ====================================================================
        $noiseData = [
            ['kelurahan' => 'Embong Kaliasin', 'category' => 'warna', 'sources' => ['pdam'], 'desc' => 'Air agak keruh sebentar'],
            ['kelurahan' => 'Airlangga', 'category' => 'lainnya', 'sources' => ['sumur'], 'desc' => 'Pompa sumur lambat'],
            ['kelurahan' => 'Gubeng', 'category' => 'bau', 'sources' => ['pdam'], 'desc' => 'Sedikit bau'],
            ['kelurahan' => 'Wonorejo', 'category' => 'rasa_aneh', 'sources' => ['galon'], 'desc' => 'Galon merk baru rasa aneh'],
            ['kelurahan' => 'Krembangan Selatan', 'category' => 'lainnya', 'sources' => ['tidak_yakin'], 'desc' => 'Tidak yakin sumber masalah'],
        ];
        foreach ($noiseData as $data) {
            $area = $findArea($data['kelurahan']);
            if (!$area) continue;
            
            $centroid = $area->getPoint('centroid');
            if (!$centroid) continue;
            
            $this->createReport($area, [
                'lat' => $centroid['lat'] + (mt_rand(-50, 50) / 10000),
                'lng' => $centroid['lng'] + (mt_rand(-50, 50) / 10000),
                'category' => $data['category'],
                'water_sources' => $data['sources'],
                'description' => $data['desc'],
                'hours_ago' => mt_rand(20, 72),
            ]);
        }
        
        $totalReports = Report::count();
        $this->command->info("Created {$totalReports} demo reports across 6 scenarios + background noise.");
        $this->command->info('Demo scenarios:');
        $this->command->info('  A. PDAM-dominant: Kel. Tambaksari (5 reports) - First 3 assigned to Handy');
        $this->command->info('  B. Mixed well+PDAM: Kel. Wonokromo (6 reports)');
        $this->command->info('  C. Refill-dominant: Kel. Sawahan (4 reports)');
        $this->command->info('  D. Overdue ack: Kel. Genteng (1 report, priority: high)');
        $this->command->info('  E. In progress: Kel. Mojo (1 report, ETA active)');
        $this->command->info('  F. Awaiting confirmation: Kel. Tegalsari (1 report)');
    }
    
    private function createReport(Area $area, array $data): Report
    {
        $createdAt = Carbon::now()->subHours($data['hours_ago']);
        
        // Build attributes
        $attributes = [
            'area_id' => $area->id,
            'category' => $data['category'],
            'water_sources' => $data['water_sources'],
            'description' => $data['description'],
            'status' => $data['status'] ?? 'pending',
            'priority' => $data['priority'] ?? 'normal',
            'priority_score' => $data['priority_score'] ?? 50,
            'initial_priority_score' => $data['priority_score'] ?? 50,
            'target_role' => in_array($data['category'], ['sakit_perut', 'rasa_aneh']) ? 'both' : 'pdam',
            'warning_count' => $data['warning_count'] ?? 0,
            // Assign custom session ID if provided, otherwise generate one
            'reporter_session_id' => $data['reporter_session_id'] ?? 'demo_seed_' . uniqid(),
            'reporter_confirm_token' => Str::random(48),
            'ip_address' => '127.0.0.1',
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
        
        // Add optional lifecycle fields
        $optionalFields = [
            'acknowledged_at',
            'acknowledged_by_user_id',
            'work_started_at',
            'eta_at',
            'eta_reason',
            'completion_claimed_at',
            'completion_notes',
            'handled_by_user_id',
            'handler_organization',
        ];
        
        foreach ($optionalFields as $field) {
            if (isset($data[$field])) {
                $attributes[$field] = $data[$field];
            }
        }
        
        return Report::createWithPoint(
            attributes: $attributes,
            spatialColumns: [
                'location' => ['lat' => $data['lat'], 'lng' => $data['lng']],
            ]
        );
    }
}