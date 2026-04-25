<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\OverdueWaterProblemMail;

class CheckOverdueTasks extends Command
{
    // Nama command yang akan kita panggil
    protected $signature = 'app:check-overdue-tasks';

    // Deskripsi command
    protected $description = 'Mengecek laporan air yang telat ditangani dan mengirim email peringatan';

    public function handle()
    {
        $this->info('[' . now()->format('H:i:s') . '] Mengecek laporan overdue di Surabaya...');

        // PROTOTYPE: Pura-puranya kita ambil data dari database yang due_date-nya sudah lewat
        $details = [
            'name' => 'Bapak Teknisi Lapangan',
            'location' => 'Kelurahan Rungkut', // Area Surabaya
            'problem' => 'Air Berubah Warna & Bau Menyengat',
            'reporter_count' => 5,
            'due_date' => '24 April 2026', // Telat 2 hari dari sekarang!
        ];

        $technicianEmail = 'teknisi@airlayak.com';

        // Kirim email
        Mail::to($technicianEmail)->send(new OverdueWaterProblemMail($details));

        $this->info('✅ Email peringatan berhasil dikirim!');
    }
}