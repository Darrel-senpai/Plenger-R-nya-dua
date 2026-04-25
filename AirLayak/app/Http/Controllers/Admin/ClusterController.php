<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClusterAlert;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClusterController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $query = ClusterAlert::with('area')->latest('triggered_at');
        
        if (!$user->isAdmin() && $user->city) {
            $query->whereHas('area', fn($q) => $q->where('city', $user->city));
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $clusters = $query->paginate(20)->withQueryString();
        return view('admin.clusters.index', compact('clusters'));
    }

    public function show(ClusterAlert $cluster): View
    {
        $cluster->load(['area', 'reports.area', 'analysisLogs']);
        return view('admin.clusters.show', compact('cluster'));
    }

    public function analyze(Request $request, ClusterAlert $cluster)
    {
        return back()->with('success', 'Analisis AI sedang diproses.');
    }

    public function updateStatus(Request $request, ClusterAlert $cluster)
    {
        $cluster->update($request->only(['status', 'resolution_notes']));
        return back()->with('success', 'Status diupdate.');
    }
}