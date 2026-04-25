<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\OverdueWaterProblemMail;
use App\Models\User;
use App\Notifications\EmailSentAlert;

class CheckOverdueTasks extends Command
{
    // Nama command yang akan kita panggil
    protected $signature = 'app:check-overdue-tasks';

    // Deskripsi command
    protected $description = 'Mengecek laporan air yang telat ditangani dan mengirim email peringatan';

    public function handle()
    {
        $this->info('[' . now()->format('H:i:s') . '] Mengecek laporan overdue di Surabaya...');

        $details = [
            'name' => 'Bapak Teknisi Lapangan',
            'location' => 'Kelurahan Rungkut',
            'problem' => 'Air Berubah Warna & Bau Menyengat',
            'reporter_count' => 5,
            'due_date' => '24 April 2026',
        ];

        $technicianEmail = 'teknisi@airlayak.com';

        // 1. Kirim email
        Mail::to($technicianEmail)->send(new \App\Mail\OverdueWaterProblemMail($details));
        $this->info('✅ Email peringatan berhasil dikirim!');

        // 2. Kirim Notifikasi ke Admin (Misalnya user dengan ID 1 adalah Admin)
        $admin = User::find(1); 
        
        if($admin) {
            $admin->notify(new EmailSentAlert($details));
            $this->info('🔔 Notifikasi sistem berhasil dicatat ke database!');
        }
    }
}