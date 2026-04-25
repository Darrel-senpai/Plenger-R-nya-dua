<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\ClusterAlert;
use App\Models\Report;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function homepage()
    {
        // 1. Ambil semua kelurahan dan hitung jumlah laporan aktif (bukan resolved/dismissed)
        $areas = Area::all();
        $areasWithCount = $areas->map(function ($area) {
            // Hitung laporan
            $count = Report::where('area_id', $area->id)
                ->whereNotIn('status', ['resolved', 'dismissed'])
                ->count();
                
            $centroid = $area->getPoint('centroid');
            if (!$centroid) return null;
            
            return [
                'id' => $area->id,
                'kelurahan' => $area->kelurahan,
                'kecamatan' => $area->kecamatan,
                'lat' => $centroid['lat'],
                'lng' => $centroid['lng'],
                'count' => $count,
                // Ambil 1 deskripsi keluhan terbaru untuk ditampilkan di UI warga
                'latest_desc' => Report::where('area_id', $area->id)
                    ->whereNotIn('status', ['resolved', 'dismissed'])
                    ->latest()->value('description') ?? 'Gangguan dilaporkan'
            ];
        })->filter()->values()->toArray();

        // 2. Ambil Cluster Aktif (Sama persis dengan instansi)
        $activeClusters = ClusterAlert::where('status', 'active')
            ->with('area')
            ->orderByDesc('severity_score')
            ->get();

        $clusterMarkers = $activeClusters->map(function ($cluster) {
            $centroid = $cluster->getPoint('centroid');
            if (!$centroid) return null;
            
            return [
                'id' => $cluster->id,
                'lat' => $centroid['lat'],
                'lng' => $centroid['lng'],
                'radius' => $cluster->radius_meters,
                'severity' => $cluster->severity_score,
                'count' => $cluster->report_count,
                'category' => $cluster->dominant_category,
                'kelurahan' => $cluster->area->kelurahan ?? '-',
            ];
        })->filter()->values();

        // 3. Kalkulasi Statistik Global untuk UI Homepage
        $totalLaporan = array_sum(array_column($areasWithCount, 'count'));
        $totalCluster = count($clusterMarkers);
        $worstArea = collect($areasWithCount)->sortByDesc('count')->first();

        return view('homepage', compact('areasWithCount', 'clusterMarkers', 'totalLaporan', 'totalCluster', 'worstArea'));
    }
}