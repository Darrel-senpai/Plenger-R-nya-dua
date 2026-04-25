<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // app/Http/Controllers/ReportController.php
    public function store(Request $request)
    {
        $request->validate([
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'category'     => 'required|string',
            'description'  => 'nullable|string|max:500',
            'fingerprint'  => 'required|string',
            'gps_lat'      => 'nullable|numeric',
            'gps_lng'      => 'nullable|numeric',
        ]);

        $fingerprint = $request->fingerprint;
        $ip = $request->ip();

        // Cek device fingerprint (24 jam terakhir)
        $deviceDuplicate = Report::where('device_fingerprint', $fingerprint)
            ->where('created_at', '>=', now()->subHours(24))
            ->exists();

        if ($deviceDuplicate) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan dari perangkat ini sudah diterima hari ini.'
            ], 429);
        }

        // Cek GPS radius 50 meter (jika GPS tersedia)
        if ($request->gps_lat && $request->gps_lng) {
            $nearbyDuplicate = Report::selectRaw("
                *,
                (6371000 * acos(
                    cos(radians(?)) * cos(radians(gps_lat)) *
                    cos(radians(gps_lng) - radians(?)) +
                    sin(radians(?)) * sin(radians(gps_lat))
                )) AS distance
            ", [$request->gps_lat, $request->gps_lng, $request->gps_lat])
            ->where('category', $request->category)
            ->where('created_at', '>=', now()->subHours(24))
            ->having('distance', '<', 50) // 50 meter radius
            ->exists();

            if ($nearbyDuplicate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sudah ada laporan serupa dari lokasi yang sangat dekat hari ini.'
                ], 429);
            }
        }

        // Simpan laporan
        $report = Report::create([
            'kelurahan_id'      => $request->kelurahan_id,
            'category'          => $request->category,
            'description'       => $request->description,
            'ip_address'        => $ip,
            'device_fingerprint'=> $fingerprint,
            'gps_lat'           => $request->gps_lat,
            'gps_lng'           => $request->gps_lng,
        ]);

        // Cek cluster alert
        $this->checkClusterAlert($request->kelurahan_id, $request->category);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim!'
        ]);
    }

    private function checkClusterAlert($kelurahanId, $category)
    {
        $count = Report::where('kelurahan_id', $kelurahanId)
            ->where('category', $category)
            ->where('created_at', '>=', now()->subHours(24))
            ->count();

        if ($count >= 3) {
            DB::table('kelurahans')
                ->where('id', $kelurahanId)
                ->update(['cluster_alert' => true]);
        }
    }
}
