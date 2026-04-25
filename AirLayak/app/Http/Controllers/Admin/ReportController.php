<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Report;
use App\Models\ReportExtension;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Report::with('area')->latest();
        
        // Scoping per role
        if (!$user->isAdmin() && $user->city) {
            $query->whereHas('area', fn($q) => $q->where('city', $user->city));
        }
        
        if ($user->isPdam()) {
            $query->forRole('pdam');
        } elseif ($user->isDinkes()) {
            $query->forRole('dinkes');
        }
        
        // Filter by area (dari klik heatmap)
        if ($request->filled('area_id')) {
            $query->where('area_id', $request->area_id);
        }
        
        // Filter standar
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('priority')) $query->where('priority', $request->priority);
        if ($request->filled('category')) $query->where('category', $request->category);
        if ($request->filled('water_source')) {
            $query->whereJsonContains('water_sources', $request->water_source);
        }
        
        // Default: filter ke laporan aktif kalau tidak ada filter status spesifik
        if (!$request->filled('status') && !$request->filled('show_all')) {
            $query->active();
        }
        
        $reports = $query->orderByDesc('priority_score')
                         ->orderBy('created_at')
                         ->paginate(15)
                         ->withQueryString();
        
        // Get area info untuk header
        $selectedArea = null;
        if ($request->filled('area_id')) {
            $selectedArea = Area::find($request->area_id);
        }
        
        // Areas list untuk filter dropdown
        $areasQuery = Area::query();
        if (!$user->isAdmin() && $user->city) {
            $areasQuery->where('city', $user->city);
        }
        $areas = $areasQuery->orderBy('kelurahan')->get();
        
        return view('admin.reports.index', compact('reports', 'selectedArea', 'areas'));
    }

    public function show(Report $report): View
    {
        $report->load(['area', 'handler', 'acknowledgedBy', 'warnings', 'extensions', 'clusterAlerts']);
        return view('admin.reports.show', compact('report'));
    }

    public function acknowledge(Request $request, Report $report)
    {
        if ($report->status !== 'pending') {
            return back()->with('error', 'Laporan sudah pernah di-acknowledge.');
        }
        
        $report->update([
            'status' => 'acknowledged',
            'acknowledged_at' => now(),
            'acknowledged_by_user_id' => $request->user()->id,
        ]);
        
        return back()->with('success', 'Laporan berhasil di-acknowledge.');
    }

    public function start(Request $request, Report $report)
    {
        $request->validate([
            'eta_at' => 'required|date|after:now',
            'eta_reason' => 'required|string|max:500',
        ]);
        
        if ($report->status !== 'acknowledged') {
            return back()->with('error', 'Laporan harus di-acknowledge terlebih dahulu.');
        }
        
        $user = $request->user();
        
        $report->update([
            'status' => 'in_progress',
            'work_started_at' => now(),
            'eta_at' => $request->eta_at,
            'eta_reason' => $request->eta_reason,
            'handled_by_user_id' => $user->id,
            'handler_organization' => $user->organization,
        ]);
        
        return back()->with('success', 'Penanganan dimulai. ETA disimpan.');
    }

    public function complete(Request $request, Report $report)
    {
        $request->validate([
            'completion_notes' => 'required|string|max:1000',
        ]);
        
        if ($report->status !== 'in_progress') {
            return back()->with('error', 'Hanya laporan yang sedang ditangani yang bisa diselesaikan.');
        }
        
        $report->update([
            'status' => 'awaiting_confirmation',
            'completion_claimed_at' => now(),
            'completion_notes' => $request->completion_notes,
        ]);
        
        return back()->with('success', 'Laporan ditandai selesai. Menunggu konfirmasi pelapor.');
    }

    public function dismiss(Request $request, Report $report)
    {
        $request->validate([
            'dismissal_reason' => 'required|string|max:500',
        ]);
        
        if (!in_array($report->status, ['pending', 'acknowledged'])) {
            return back()->with('error', 'Status laporan tidak memungkinkan untuk ditolak.');
        }
        
        $report->update([
            'status' => 'dismissed',
            'dismissed_at' => now(),
            'dismissal_reason' => $request->dismissal_reason,
        ]);
        
        return back()->with('success', 'Laporan ditolak.');
    }

    public function requestExtension(Request $request, Report $report)
    {
        $request->validate([
            'proposed_eta_at' => 'required|date|after:now',
            'reason' => 'required|string|max:500',
        ]);
        
        if ($report->status !== 'in_progress') {
            return back()->with('error', 'Hanya laporan yang sedang ditangani yang bisa request extension.');
        }
        
        ReportExtension::create([
            'report_id' => $report->id,
            'requested_by_user_id' => $request->user()->id,
            'proposed_eta_at' => $request->proposed_eta_at,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);
        
        $report->update(['status' => 'extension_requested']);
        
        return back()->with('success', 'Permintaan perpanjangan dikirim ke pelapor.');
    }
}