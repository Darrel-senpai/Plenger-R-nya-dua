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

    // Menerima submit dari formlaporan.blade.php
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|in:' . implode(',', Report::CATEGORIES),
            'water_sources' => 'required|array',
            'description' => 'required|string',
            'location_lat' => 'required|numeric',
            'location_lng' => 'required|numeric',
            'photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('reports', 'public');
        }

        $report = new Report([
            'category' => $validated['category'],
            'water_sources' => $validated['water_sources'],
            'description' => $validated['description'],
            'photo_path' => $photoPath,
            'status' => 'pending',
            'priority' => 'normal',
            'reporter_session_id' => session()->getId(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        // Simpan titik kordinat menggunakan trait HasSpatialAttributes
        $report->setPoint('location', $validated['location_lat'], $validated['location_lng']);
        $report->save();

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dikirim dan tersinkronisasi ke Instansi.');
    }

    // Warga membatalkan laporannya sendiri
    public function cancel(Report $report)
    {
        if ($report->reporter_session_id !== session()->getId()) {
            abort(403, 'Akses ditolak.');
        }

        if ($report->status === 'pending') {
            $report->update([
                'status' => 'dismissed',
                'dismissal_reason' => 'Dibatalkan oleh pelapor',
                'dismissed_at' => now()
            ]);
            return back()->with('success', 'Laporan berhasil dibatalkan.');
        }

        return back()->with('error', 'Laporan yang sudah diproses instansi tidak dapat dibatalkan.');
    }

    public function resolve(Report $report)
    {
        if ($report->reporter_session_id !== session()->getId()) {
            abort(403);
        }

        if (in_array($report->status, ['awaiting_confirmation', 'in_progress'])) {
            $report->update([
                'status' => 'resolved',
                'resolved_at' => now(),
                'completion_notes' => 'Diselesaikan dan dikonfirmasi oleh warga.'
            ]);
            return back()->with('success', 'Terima kasih, laporan telah ditandai selesai.');
        }

        return back()->with('error', 'Laporan tidak dapat diselesaikan pada status saat ini.');
    }

    // Warga merespons permintaan perpanjangan waktu (extend) dari Instansi
    public function respondExtension(Request $request, ReportExtension $extension)
    {
        $report = $extension->report;
        
        if ($report->reporter_session_id !== session()->getId()) {
            abort(403);
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string'
        ]);

        if ($extension->status !== 'pending' || $extension->isExpired()) {
            return back()->with('error', 'Permintaan perpanjangan ini sudah diproses atau kadaluarsa.');
        }

        if ($validated['action'] === 'approve') {
            $extension->update([
                'status' => 'approved',
                'responded_at' => now(),
                'user_response_notes' => $validated['notes']
            ]);
            $report->update([
                'status' => 'in_progress',
                'eta_at' => $extension->proposed_eta_at // Waktu ETA diperbarui ke waktu yang diajukan instansi
            ]);
            return back()->with('success', 'Anda telah menyetujui perpanjangan waktu penanganan.');
        } else {
            $extension->update([
                'status' => 'rejected',
                'responded_at' => now(),
                'user_response_notes' => $validated['notes']
            ]);
            $report->update([
                'status' => 'in_progress' // Status kembali in_progress, waktu tidak ditambah
            ]);
            return back()->with('success', 'Anda menolak perpanjangan waktu. Instansi akan diberitahu.');
        }
    }
}