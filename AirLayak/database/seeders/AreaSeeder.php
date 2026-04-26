<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            // === 1. KEC. ASEMROWO ===
            ['kelurahan' => 'Asemrowo', 'kecamatan' => 'Asemrowo', 'lat' => -7.2503, 'lng' => 112.7092],
            ['kelurahan' => 'Genting Kalianak', 'kecamatan' => 'Asemrowo', 'lat' => -7.2451, 'lng' => 112.7011],
            ['kelurahan' => 'Tambak Sarioso', 'kecamatan' => 'Asemrowo', 'lat' => -7.2398, 'lng' => 112.6955],

            // === 2. KEC. BENOWO ===
            ['kelurahan' => 'Kandangan', 'kecamatan' => 'Benowo', 'lat' => -7.2412, 'lng' => 112.6455],
            ['kelurahan' => 'Romokalisari', 'kecamatan' => 'Benowo', 'lat' => -7.2155, 'lng' => 112.6511],
            ['kelurahan' => 'Sememi', 'kecamatan' => 'Benowo', 'lat' => -7.2488, 'lng' => 112.6355],
            ['kelurahan' => 'Tambak Osowilangun', 'kecamatan' => 'Benowo', 'lat' => -7.2211, 'lng' => 112.6412],

            // === 3. KEC. BUBUTAN ===
            ['kelurahan' => 'Alun-alun Contong', 'kecamatan' => 'Bubutan', 'lat' => -7.2564, 'lng' => 112.7381],
            ['kelurahan' => 'Bubutan', 'kecamatan' => 'Bubutan', 'lat' => -7.2511, 'lng' => 112.7369],
            ['kelurahan' => 'Gundih', 'kecamatan' => 'Bubutan', 'lat' => -7.2485, 'lng' => 112.7311],
            ['kelurahan' => 'Jepara', 'kecamatan' => 'Bubutan', 'lat' => -7.2452, 'lng' => 112.7299],
            ['kelurahan' => 'Tembok Dukuh', 'kecamatan' => 'Bubutan', 'lat' => -7.2522, 'lng' => 112.7255],

            // === 4. KEC. BULAK ===
            ['kelurahan' => 'Bulak', 'kecamatan' => 'Bulak', 'lat' => -7.2411, 'lng' => 112.7855],
            ['kelurahan' => 'Kedung Cowek', 'kecamatan' => 'Bulak', 'lat' => -7.2355, 'lng' => 112.7811],
            ['kelurahan' => 'Kenjeran', 'kecamatan' => 'Bulak', 'lat' => -7.2344, 'lng' => 112.7911],
            ['kelurahan' => 'Sukolilo Baru', 'kecamatan' => 'Bulak', 'lat' => -7.2455, 'lng' => 112.7955],

            // === 5. KEC. DUKUH PAKIS ===
            ['kelurahan' => 'Dukuh Kupang', 'kecamatan' => 'Dukuh Pakis', 'lat' => -7.2861, 'lng' => 112.7144],
            ['kelurahan' => 'Dukuh Pakis', 'kecamatan' => 'Dukuh Pakis', 'lat' => -7.2911, 'lng' => 112.7055],
            ['kelurahan' => 'Gunungsari', 'kecamatan' => 'Dukuh Pakis', 'lat' => -7.3014, 'lng' => 112.7183],
            ['kelurahan' => 'Pradah Kali Kendal', 'kecamatan' => 'Dukuh Pakis', 'lat' => -7.2855, 'lng' => 112.6955],

            // === 6. KEC. GAYUNGAN ===
            ['kelurahan' => 'Dukuh Menanggal', 'kecamatan' => 'Gayungan', 'lat' => -7.3411, 'lng' => 112.7211],
            ['kelurahan' => 'Gayungan', 'kecamatan' => 'Gayungan', 'lat' => -7.3314, 'lng' => 112.7269],
            ['kelurahan' => 'Ketintang', 'kecamatan' => 'Gayungan', 'lat' => -7.3155, 'lng' => 112.7255],
            ['kelurahan' => 'Menanggal', 'kecamatan' => 'Gayungan', 'lat' => -7.3388, 'lng' => 112.7288],

            // === 7. KEC. GENTENG ===
            ['kelurahan' => 'Embong Kaliasin', 'kecamatan' => 'Genteng', 'lat' => -7.2664, 'lng' => 112.7426],
            ['kelurahan' => 'Genteng', 'kecamatan' => 'Genteng', 'lat' => -7.2614, 'lng' => 112.7479],
            ['kelurahan' => 'Kapasari', 'kecamatan' => 'Genteng', 'lat' => -7.2555, 'lng' => 112.7455],
            ['kelurahan' => 'Ketabang', 'kecamatan' => 'Genteng', 'lat' => -7.2622, 'lng' => 112.7411],
            ['kelurahan' => 'Peneleh', 'kecamatan' => 'Genteng', 'lat' => -7.2588, 'lng' => 112.7388],

            // === 8. KEC. GUBENG ===
            ['kelurahan' => 'Airlangga', 'kecamatan' => 'Gubeng', 'lat' => -7.2697, 'lng' => 112.7583],
            ['kelurahan' => 'Barata Jaya', 'kecamatan' => 'Gubeng', 'lat' => -7.2855, 'lng' => 112.7555],
            ['kelurahan' => 'Gubeng', 'kecamatan' => 'Gubeng', 'lat' => -7.2628, 'lng' => 112.7544],
            ['kelurahan' => 'Kertajaya', 'kecamatan' => 'Gubeng', 'lat' => -7.2755, 'lng' => 112.7555],
            ['kelurahan' => 'Mojo', 'kecamatan' => 'Gubeng', 'lat' => -7.2728, 'lng' => 112.7647],
            ['kelurahan' => 'Pucang Sewu', 'kecamatan' => 'Gubeng', 'lat' => -7.2811, 'lng' => 112.7511],

            // === 9. KEC. GUNUNG ANYAR ===
            ['kelurahan' => 'Gunung Anyar', 'kecamatan' => 'Gunung Anyar', 'lat' => -7.3361, 'lng' => 112.7983],
            ['kelurahan' => 'Gunung Anyar Tambak', 'kecamatan' => 'Gunung Anyar', 'lat' => -7.3411, 'lng' => 112.8055],
            ['kelurahan' => 'Rungkut Menanggal', 'kecamatan' => 'Gunung Anyar', 'lat' => -7.3355, 'lng' => 112.7855],
            ['kelurahan' => 'Rungkut Tengah', 'kecamatan' => 'Gunung Anyar', 'lat' => -7.3255, 'lng' => 112.7888],

            // === 10. KEC. JAMBANGAN ===
            ['kelurahan' => 'Jambangan', 'kecamatan' => 'Jambangan', 'lat' => -7.3219, 'lng' => 112.7183],
            ['kelurahan' => 'Karah', 'kecamatan' => 'Jambangan', 'lat' => -7.3111, 'lng' => 112.7155],
            ['kelurahan' => 'Kebonsari', 'kecamatan' => 'Jambangan', 'lat' => -7.3255, 'lng' => 112.7111],
            ['kelurahan' => 'Pagesangan', 'kecamatan' => 'Jambangan', 'lat' => -7.3355, 'lng' => 112.7155],

            // === 11. KEC. KARANG PILANG ===
            ['kelurahan' => 'Karang Pilang', 'kecamatan' => 'Karang Pilang', 'lat' => -7.3364, 'lng' => 112.6914],
            ['kelurahan' => 'Kebraon', 'kecamatan' => 'Karang Pilang', 'lat' => -7.3311, 'lng' => 112.6855],
            ['kelurahan' => 'Kedurus', 'kecamatan' => 'Karang Pilang', 'lat' => -7.3211, 'lng' => 112.6955],
            ['kelurahan' => 'Warugunung', 'kecamatan' => 'Karang Pilang', 'lat' => -7.3455, 'lng' => 112.6811],

            // === 12. KEC. KENJERAN ===
            ['kelurahan' => 'Bulak Banteng', 'kecamatan' => 'Kenjeran', 'lat' => -7.2255, 'lng' => 112.7655],
            ['kelurahan' => 'Sidotopo Wetan', 'kecamatan' => 'Kenjeran', 'lat' => -7.2355, 'lng' => 112.7611],
            ['kelurahan' => 'Tambak Wedi', 'kecamatan' => 'Kenjeran', 'lat' => -7.2155, 'lng' => 112.7711],
            ['kelurahan' => 'Tanah Kali Kedinding', 'kecamatan' => 'Kenjeran', 'lat' => -7.2183, 'lng' => 112.7667],

            // === 13. KEC. KREMBANGAN ===
            ['kelurahan' => 'Dupak', 'kecamatan' => 'Krembangan', 'lat' => -7.2400, 'lng' => 112.7236],
            ['kelurahan' => 'Kemayoran', 'kecamatan' => 'Krembangan', 'lat' => -7.2355, 'lng' => 112.7288],
            ['kelurahan' => 'Krembangan Selatan', 'kecamatan' => 'Krembangan', 'lat' => -7.2347, 'lng' => 112.7322],
            ['kelurahan' => 'Morokrembangan', 'kecamatan' => 'Krembangan', 'lat' => -7.2255, 'lng' => 112.7155],
            ['kelurahan' => 'Perak Barat', 'kecamatan' => 'Krembangan', 'lat' => -7.2155, 'lng' => 112.7255],

            // === 14. KEC. LAKARSANTRI ===
            ['kelurahan' => 'Bangkingan', 'kecamatan' => 'Lakarsantri', 'lat' => -7.3155, 'lng' => 112.6455],
            ['kelurahan' => 'Jeruk', 'kecamatan' => 'Lakarsantri', 'lat' => -7.2955, 'lng' => 112.6355],
            ['kelurahan' => 'Lakarsantri', 'kecamatan' => 'Lakarsantri', 'lat' => -7.2994, 'lng' => 112.6514],
            ['kelurahan' => 'Lidah Kulon', 'kecamatan' => 'Lakarsantri', 'lat' => -7.3050, 'lng' => 112.6603],
            ['kelurahan' => 'Lidah Wetan', 'kecamatan' => 'Lakarsantri', 'lat' => -7.3011, 'lng' => 112.6655],
            ['kelurahan' => 'Sumur Welut', 'kecamatan' => 'Lakarsantri', 'lat' => -7.3255, 'lng' => 112.6411],

            // === 15. KEC. MULYOREJO ===
            ['kelurahan' => 'Dukuh Sutorejo', 'kecamatan' => 'Mulyorejo', 'lat' => -7.2655, 'lng' => 112.7955],
            ['kelurahan' => 'Kalijudan', 'kecamatan' => 'Mulyorejo', 'lat' => -7.2555, 'lng' => 112.7855],
            ['kelurahan' => 'Kalisari', 'kecamatan' => 'Mulyorejo', 'lat' => -7.2455, 'lng' => 112.8055],
            ['kelurahan' => 'Kejawan Putih Tambak', 'kecamatan' => 'Mulyorejo', 'lat' => -7.2755, 'lng' => 112.8011],
            ['kelurahan' => 'Manyar Sabrangan', 'kecamatan' => 'Mulyorejo', 'lat' => -7.2755, 'lng' => 112.7755],
            ['kelurahan' => 'Mulyorejo', 'kecamatan' => 'Mulyorejo', 'lat' => -7.2672, 'lng' => 112.7875],

            // === 16. KEC. PABEAN CANTIAN ===
            ['kelurahan' => 'Bongkaran', 'kecamatan' => 'Pabean Cantian', 'lat' => -7.2389, 'lng' => 112.7403],
            ['kelurahan' => 'Krembangan Utara', 'kecamatan' => 'Pabean Cantian', 'lat' => -7.2288, 'lng' => 112.7355],
            ['kelurahan' => 'Nyamplungan', 'kecamatan' => 'Pabean Cantian', 'lat' => -7.2319, 'lng' => 112.7408],
            ['kelurahan' => 'Perak Timur', 'kecamatan' => 'Pabean Cantian', 'lat' => -7.2155, 'lng' => 112.7355],
            ['kelurahan' => 'Perak Utara', 'kecamatan' => 'Pabean Cantian', 'lat' => -7.2055, 'lng' => 112.7311],

            // === 17. KEC. PAKAL ===
            ['kelurahan' => 'Babat Jerawat', 'kecamatan' => 'Pakal', 'lat' => -7.2455, 'lng' => 112.6255],
            ['kelurahan' => 'Benowo', 'kecamatan' => 'Pakal', 'lat' => -7.2300, 'lng' => 112.6289],
            ['kelurahan' => 'Pakal', 'kecamatan' => 'Pakal', 'lat' => -7.2358, 'lng' => 112.6167],
            ['kelurahan' => 'Sumberejo', 'kecamatan' => 'Pakal', 'lat' => -7.2255, 'lng' => 112.6111],

            // === 18. KEC. RUNGKUT ===
            ['kelurahan' => 'Kalirungkut', 'kecamatan' => 'Rungkut', 'lat' => -7.3325, 'lng' => 112.7811],
            ['kelurahan' => 'Kedung Baruk', 'kecamatan' => 'Rungkut', 'lat' => -7.3155, 'lng' => 112.7855],
            ['kelurahan' => 'Medokan Ayu', 'kecamatan' => 'Rungkut', 'lat' => -7.3255, 'lng' => 112.7955],
            ['kelurahan' => 'Penjaringan Sari', 'kecamatan' => 'Rungkut', 'lat' => -7.3211, 'lng' => 112.7888],
            ['kelurahan' => 'Rungkut Kidul', 'kecamatan' => 'Rungkut', 'lat' => -7.3389, 'lng' => 112.7861],
            ['kelurahan' => 'Wonorejo', 'kecamatan' => 'Rungkut', 'lat' => -7.3111, 'lng' => 112.8055],

            // === 19. KEC. SAMBIKEREP ===
            ['kelurahan' => 'Bringin', 'kecamatan' => 'Sambikerep', 'lat' => -7.2655, 'lng' => 112.6411],
            ['kelurahan' => 'Lontar', 'kecamatan' => 'Sambikerep', 'lat' => -7.2764, 'lng' => 112.6653],
            ['kelurahan' => 'Made', 'kecamatan' => 'Sambikerep', 'lat' => -7.2755, 'lng' => 112.6355],
            ['kelurahan' => 'Sambikerep', 'kecamatan' => 'Sambikerep', 'lat' => -7.2694, 'lng' => 112.6511],

            // === 20. KEC. SAWAHAN ===
            ['kelurahan' => 'Banyu Urip', 'kecamatan' => 'Sawahan', 'lat' => -7.2778, 'lng' => 112.7197],
            ['kelurahan' => 'Kupang Krajan', 'kecamatan' => 'Sawahan', 'lat' => -7.2711, 'lng' => 112.7211],
            ['kelurahan' => 'Pakis', 'kecamatan' => 'Sawahan', 'lat' => -7.2855, 'lng' => 112.7255],
            ['kelurahan' => 'Petemon', 'kecamatan' => 'Sawahan', 'lat' => -7.2747, 'lng' => 112.7283],
            ['kelurahan' => 'Putat Jaya', 'kecamatan' => 'Sawahan', 'lat' => -7.2811, 'lng' => 112.7155],
            ['kelurahan' => 'Sawahan', 'kecamatan' => 'Sawahan', 'lat' => -7.2685, 'lng' => 112.7283],

            // === 21. KEC. SEMAMPIR ===
            ['kelurahan' => 'Ampel', 'kecamatan' => 'Semampir', 'lat' => -7.2281, 'lng' => 112.7428],
            ['kelurahan' => 'Pegirian', 'kecamatan' => 'Semampir', 'lat' => -7.2333, 'lng' => 112.7456],
            ['kelurahan' => 'Sidotopo', 'kecamatan' => 'Semampir', 'lat' => -7.2288, 'lng' => 112.7511],
            ['kelurahan' => 'Ujung', 'kecamatan' => 'Semampir', 'lat' => -7.2055, 'lng' => 112.7411],
            ['kelurahan' => 'Wonokusumo', 'kecamatan' => 'Semampir', 'lat' => -7.2211, 'lng' => 112.7488],

            // === 22. KEC. SIMOKERTO ===
            ['kelurahan' => 'Kapasan', 'kecamatan' => 'Simokerto', 'lat' => -7.2488, 'lng' => 112.7488],
            ['kelurahan' => 'Sidodadi', 'kecamatan' => 'Simokerto', 'lat' => -7.2455, 'lng' => 112.7455],
            ['kelurahan' => 'Simokerto', 'kecamatan' => 'Simokerto', 'lat' => -7.2433, 'lng' => 112.7533],
            ['kelurahan' => 'Simolawang', 'kecamatan' => 'Simokerto', 'lat' => -7.2388, 'lng' => 112.7488],
            ['kelurahan' => 'Tambakrejo', 'kecamatan' => 'Simokerto', 'lat' => -7.2455, 'lng' => 112.7588],

            // === 23. KEC. SUKOLILO ===
            ['kelurahan' => 'Gebang Putih', 'kecamatan' => 'Sukolilo', 'lat' => -7.2855, 'lng' => 112.7888],
            ['kelurahan' => 'Keputih', 'kecamatan' => 'Sukolilo', 'lat' => -7.2955, 'lng' => 112.8011],
            ['kelurahan' => 'Klampis Ngasem', 'kecamatan' => 'Sukolilo', 'lat' => -7.2886, 'lng' => 112.7789],
            ['kelurahan' => 'Medokan Semampir', 'kecamatan' => 'Sukolilo', 'lat' => -7.3055, 'lng' => 112.7855],
            ['kelurahan' => 'Menur Pumpungan', 'kecamatan' => 'Sukolilo', 'lat' => -7.2911, 'lng' => 112.7655],
            ['kelurahan' => 'Nginden Jangkungan', 'kecamatan' => 'Sukolilo', 'lat' => -7.3011, 'lng' => 112.7688],
            ['kelurahan' => 'Semolowaru', 'kecamatan' => 'Sukolilo', 'lat' => -7.3014, 'lng' => 112.7761],

            // === 24. KEC. SUKOMANUNGGAL ===
            ['kelurahan' => 'Putat Gede', 'kecamatan' => 'Sukomanunggal', 'lat' => -7.2755, 'lng' => 112.6955],
            ['kelurahan' => 'Simomulyo', 'kecamatan' => 'Sukomanunggal', 'lat' => -7.2611, 'lng' => 112.7055],
            ['kelurahan' => 'Simomulyo Baru', 'kecamatan' => 'Sukomanunggal', 'lat' => -7.2588, 'lng' => 112.7011],
            ['kelurahan' => 'Sono Kwijenan', 'kecamatan' => 'Sukomanunggal', 'lat' => -7.2655, 'lng' => 112.7088],
            ['kelurahan' => 'Sukomanunggal', 'kecamatan' => 'Sukomanunggal', 'lat' => -7.2661, 'lng' => 112.7003],
            ['kelurahan' => 'Tanjungsari', 'kecamatan' => 'Sukomanunggal', 'lat' => -7.2555, 'lng' => 112.6911],

            // === 25. KEC. TAMBAKSARI ===
            ['kelurahan' => 'Dukuh Setro', 'kecamatan' => 'Tambaksari', 'lat' => -7.2355, 'lng' => 112.7755],
            ['kelurahan' => 'Gading', 'kecamatan' => 'Tambaksari', 'lat' => -7.2411, 'lng' => 112.7655],
            ['kelurahan' => 'Kapas Madya', 'kecamatan' => 'Tambaksari', 'lat' => -7.2355, 'lng' => 112.7688],
            ['kelurahan' => 'Pacar Keling', 'kecamatan' => 'Tambaksari', 'lat' => -7.2502, 'lng' => 112.7615],
            ['kelurahan' => 'Pacar Kembang', 'kecamatan' => 'Tambaksari', 'lat' => -7.2555, 'lng' => 112.7655],
            ['kelurahan' => 'Ploso', 'kecamatan' => 'Tambaksari', 'lat' => -7.2461, 'lng' => 112.7708],
            ['kelurahan' => 'Rangkah', 'kecamatan' => 'Tambaksari', 'lat' => -7.2455, 'lng' => 112.7588],
            ['kelurahan' => 'Tambaksari', 'kecamatan' => 'Tambaksari', 'lat' => -7.2542, 'lng' => 112.7639],

            // === 26. KEC. TANDES ===
            ['kelurahan' => 'Balongsari', 'kecamatan' => 'Tandes', 'lat' => -7.2611, 'lng' => 112.6755],
            ['kelurahan' => 'Banjar Sugihan', 'kecamatan' => 'Tandes', 'lat' => -7.2511, 'lng' => 112.6611],
            ['kelurahan' => 'Karang Poh', 'kecamatan' => 'Tandes', 'lat' => -7.2655, 'lng' => 112.6711],
            ['kelurahan' => 'Manukan Kulon', 'kecamatan' => 'Tandes', 'lat' => -7.2561, 'lng' => 112.6669],
            ['kelurahan' => 'Manukan Wetan', 'kecamatan' => 'Tandes', 'lat' => -7.2588, 'lng' => 112.6711],
            ['kelurahan' => 'Tandes', 'kecamatan' => 'Tandes', 'lat' => -7.2611, 'lng' => 112.6844],

            // === 27. KEC. TEGALSARI ===
            ['kelurahan' => 'Dr. Sutomo', 'kecamatan' => 'Tegalsari', 'lat' => -7.2811, 'lng' => 112.7355],
            ['kelurahan' => 'Kedungdoro', 'kecamatan' => 'Tegalsari', 'lat' => -7.2655, 'lng' => 112.7311],
            ['kelurahan' => 'Keputran', 'kecamatan' => 'Tegalsari', 'lat' => -7.2755, 'lng' => 112.7411],
            ['kelurahan' => 'Tegalsari', 'kecamatan' => 'Tegalsari', 'lat' => -7.2719, 'lng' => 112.7372],
            ['kelurahan' => 'Wonorejo', 'kecamatan' => 'Tegalsari', 'lat' => -7.2783, 'lng' => 112.7411],

            // === 28. KEC. TENGGILIS MEJOYO ===
            ['kelurahan' => 'Kendangsari', 'kecamatan' => 'Tenggilis Mejoyo', 'lat' => -7.3228, 'lng' => 112.7481],
            ['kelurahan' => 'Kutisari', 'kecamatan' => 'Tenggilis Mejoyo', 'lat' => -7.3355, 'lng' => 112.7455],
            ['kelurahan' => 'Panjang Jiwo', 'kecamatan' => 'Tenggilis Mejoyo', 'lat' => -7.3155, 'lng' => 112.7588],
            ['kelurahan' => 'Tenggilis Mejoyo', 'kecamatan' => 'Tenggilis Mejoyo', 'lat' => -7.3211, 'lng' => 112.7555],

            // === 29. KEC. WIYUNG ===
            ['kelurahan' => 'Babatan', 'kecamatan' => 'Wiyung', 'lat' => -7.3055, 'lng' => 112.6855],
            ['kelurahan' => 'Balas Klumprik', 'kecamatan' => 'Wiyung', 'lat' => -7.3255, 'lng' => 112.6811],
            ['kelurahan' => 'Jajar Tunggal', 'kecamatan' => 'Wiyung', 'lat' => -7.3155, 'lng' => 112.7055],
            ['kelurahan' => 'Wiyung', 'kecamatan' => 'Wiyung', 'lat' => -7.3114, 'lng' => 112.6953],

            // === 30. KEC. WONOCOLO ===
            ['kelurahan' => 'Bendul Merisi', 'kecamatan' => 'Wonocolo', 'lat' => -7.3155, 'lng' => 112.7411],
            ['kelurahan' => 'Jemur Wonosari', 'kecamatan' => 'Wonocolo', 'lat' => -7.3255, 'lng' => 112.7355],
            ['kelurahan' => 'Margorejo', 'kecamatan' => 'Wonocolo', 'lat' => -7.3188, 'lng' => 112.7388],
            ['kelurahan' => 'Sidosermo', 'kecamatan' => 'Wonocolo', 'lat' => -7.3155, 'lng' => 112.7488],
            ['kelurahan' => 'Siwalankerto', 'kecamatan' => 'Wonocolo', 'lat' => -7.3355, 'lng' => 112.7311],

            // === 31. KEC. WONOKROMO ===
            ['kelurahan' => 'Darmo', 'kecamatan' => 'Wonokromo', 'lat' => -7.2911, 'lng' => 112.7355],
            ['kelurahan' => 'Jagir', 'kecamatan' => 'Wonokromo', 'lat' => -7.3088, 'lng' => 112.7444],
            ['kelurahan' => 'Ngagel', 'kecamatan' => 'Wonokromo', 'lat' => -7.2979, 'lng' => 112.7461],
            ['kelurahan' => 'Ngagelrejo', 'kecamatan' => 'Wonokromo', 'lat' => -7.3055, 'lng' => 112.7488],
            ['kelurahan' => 'Sawunggaling', 'kecamatan' => 'Wonokromo', 'lat' => -7.3011, 'lng' => 112.7311],
            ['kelurahan' => 'Wonokromo', 'kecamatan' => 'Wonokromo', 'lat' => -7.3033, 'lng' => 112.7378],
        ];

        foreach ($areas as $data) {
            // Randomizer realistis untuk data kepadatan & sumber air agar heatmap di map lebih dinamis
            $pop_density = rand(10000, 28000);
            
            $water_sources_pool = [
                ['pdam'], 
                ['pdam', 'sumur'], 
                ['pdam', 'air_isi_ulang'], 
                ['sumur', 'pdam'], 
                ['pdam', 'galon']
            ];
            $random_water_source = $water_sources_pool[array_rand($water_sources_pool)];

            Area::createWithPoint(
                attributes: [
                    'kelurahan' => $data['kelurahan'],
                    'kecamatan' => $data['kecamatan'],
                    'city' => 'Surabaya',
                    'city_type' => 'kota',
                    'province' => 'Jawa Timur',
                    'population_density' => $pop_density,
                    'dominant_water_sources' => $random_water_source,
                ],
                spatialColumns: [
                    'centroid' => ['lat' => $data['lat'], 'lng' => $data['lng']],
                ]
            );
        }

        $this->command->info('SUKSES! Berhasil meng-generate ' . count($areas) . ' Kelurahan di Surabaya, Jawa Timur.');
    }
}