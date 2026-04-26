<?php

namespace App\Http\Controllers;

use App\Mail\OverdueWaterProblemMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class warnPDAM extends Controller
{
    public function warnTechnician()
    {
        // In a real app, you would fetch this from your Database based on the current date (April 26, 2026)
        $details = [
            'name' => 'Bapak Teknisi Lapangan',
            'location' => 'Kelurahan Rungkut',
            'problem' => 'Air Berubah Warna & Bau Menyengat',
            'reporter_count' => 5,
            'due_date' => '24 April 2026', // Overdue by 2 days!
        ];

        $technicianEmail = 'teknisi@airlayak.com';

        // Send the email directly (use ->queue() instead of ->send() in production for faster page loads)
        Mail::to($technicianEmail)->send(new OverdueWaterProblemMail($details));

        return back()->with('success', 'Email peringatan berhasil dikirim ke teknisi!');
    }
}