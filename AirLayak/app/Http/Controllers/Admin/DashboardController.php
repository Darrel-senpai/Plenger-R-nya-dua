<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use App\Models\Area;
use App\Models\ClusterAlert;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        // Build base queries dengan scoping per role
        $reportsQuery = Report::query();
        $clustersQuery = ClusterAlert::query();

        if (!$user->isAdmin() && $user->city) {
            $reportsQuery->whereHas('area', fn($q) => $q->where('city', 'like', '%' . $user->city . '%'));
            $clustersQuery->whereHas('area', fn($q) => $q->where('city', 'like', '%' . $user->city . '%'));
        }

        $urgentReports = (clone $reportsQuery)
            ->with('area')
            ->orderByDesc('priority_score')
            ->limit(20)
            ->get();

        if ($user->isPdam()) {
            $reportsQuery->forRole('pdam');
        } elseif ($user->isDinkes()) {
            $reportsQuery->forRole('dinkes');
        }

        // Stats
        $stats = [
            'pending' => (clone $reportsQuery)->where('status', 'pending')->count(),
            'in_progress' => (clone $reportsQuery)->where('status', 'in_progress')->count(),
            'awaiting_confirmation' => (clone $reportsQuery)->where('status', 'awaiting_confirmation')->count(),
            'resolved_today' => (clone $reportsQuery)
                ->where('status', 'resolved')
                ->whereDate('resolved_at', today())
                ->count(),
            'overdue_acknowledgment' => (clone $reportsQuery)->overdueAcknowledgment()->count(),
            'overdue_resolution' => (clone $reportsQuery)->overdueResolution()->count(),
            'active_clusters' => (clone $clustersQuery)->where('status', 'active')->count(),
            'critical_priority' => (clone $reportsQuery)->where('priority', 'critical')->active()->count(),
        ];

        // Heatmap kelurahan: aggregate count laporan aktif per area
        $areasWithCount = $this->getAreasWithReportCount($user);

        // Active cluster alerts
        $activeClusters = (clone $clustersQuery)
            ->where('status', 'active')
            ->with('area')
            ->orderByDesc('severity_score')
            ->get();

        // Cluster centroids untuk peta
        $clusterMarkers = $activeClusters->map(function ($cluster) {
            $centroid = $cluster->getPoint('centroid');
            if (!$centroid)
                return null;

            return [
                'id' => $cluster->id,
                'lat' => $centroid['lat'],
                'lng' => $centroid['lng'],
                'radius' => $cluster->radius_meters,
                'severity' => $cluster->severity_score,
                'count' => $cluster->report_count,
                'category' => $cluster->dominant_category,
                'kelurahan' => $cluster->area->kelurahan ?? '-',
                'detail_url' => route('admin.clusters.show', $cluster),
            ];
        })->filter()->values();

        // Priority Reports
        $priorityReports = (clone $reportsQuery)
            ->active()
            ->with('area')
            ->orderByDesc('priority_score')
            ->orderBy('created_at')
            ->limit(25)
            ->get();

        // Unread notifications
        $unreadNotifications = AppNotification::forUser($user)
            ->unread()
            ->latest()
            ->limit(5)
            ->get();

        // Map center
        $mapCenter = match ($user->city) {
            'Surabaya' => ['lat' => -7.2575, 'lng' => 112.7521],
            'Bandung' => ['lat' => -6.9175, 'lng' => 107.6191],
            'Jakarta' => ['lat' => -6.2088, 'lng' => 106.8456],
            default => ['lat' => -7.2575, 'lng' => 112.7521],
        };

        return view('admin.dashboard', compact(
            'stats',
            'activeClusters',
            'priorityReports',
            'unreadNotifications',
            'areasWithCount',
            'clusterMarkers',
            'mapCenter'
        ));
    }

    /**
     * Aggregate count laporan aktif per kelurahan dengan koordinat centroid.
     */
    private function getAreasWithReportCount($user): array
    {
        $areasQuery = Area::query();

        if (!$user->isAdmin() && $user->city) {
            $areasQuery->where('city', $user->city);
        }

        $areas = $areasQuery->get();

        return $areas->map(function ($area) use ($user) {
            $reportQuery = Report::where('area_id', $area->id)
                ->whereNotIn('status', ['resolved', 'dismissed']);

            if ($user->isPdam()) {
                $reportQuery->forRole('pdam');
            } elseif ($user->isDinkes()) {
                $reportQuery->forRole('dinkes');
            }

            $count = $reportQuery->count();
            $centroid = $area->getPoint('centroid');

            if (!$centroid)
                return null;

            return [
                'id' => $area->id,
                'kelurahan' => $area->kelurahan,
                'kecamatan' => $area->kecamatan,
                'lat' => $centroid['lat'],
                'lng' => $centroid['lng'],
                'count' => $count,
                'reports_url' => route('admin.reports.index', ['area_id' => $area->id]),
            ];
        })->filter()->values()->toArray();
    }
}