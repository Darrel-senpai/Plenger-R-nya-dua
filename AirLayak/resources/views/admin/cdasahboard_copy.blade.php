@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
    <p class="text-sm text-gray-500 mt-1">
        Selamat datang, {{ auth()->user()->name }}
    </p>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Pending</p>
        <p class="text-2xl font-semibold mt-1">{{ $stats['pending'] }}</p>
    </div>
    
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Sedang Ditangani</p>
        <p class="text-2xl font-semibold mt-1">{{ $stats['in_progress'] }}</p>
    </div>
    
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Menunggu Konfirmasi</p>
        <p class="text-2xl font-semibold mt-1">{{ $stats['awaiting_confirmation'] }}</p>
    </div>
    
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Selesai Hari Ini</p>
        <p class="text-2xl font-semibold mt-1 text-green-600">{{ $stats['resolved_today'] }}</p>
    </div>
</div>

{{-- Warning Cards --}}
@if($stats['overdue_acknowledgment'] > 0 || $stats['overdue_resolution'] > 0)
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
    @if($stats['overdue_acknowledgment'] > 0)
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <p class="text-sm font-medium text-red-800">⚠️ Belum Di-acknowledge (>12 jam)</p>
        <p class="text-2xl font-semibold text-red-900 mt-1">{{ $stats['overdue_acknowledgment'] }} laporan</p>
    </div>
    @endif
    
    @if($stats['overdue_resolution'] > 0)
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
        <p class="text-sm font-medium text-orange-800">⚠️ Melewati ETA</p>
        <p class="text-2xl font-semibold text-orange-900 mt-1">{{ $stats['overdue_resolution'] }} laporan</p>
    </div>
    @endif
</div>
@endif

{{-- Active Cluster Alerts --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-900 mb-4">Cluster Aktif (Top 5)</h2>
        @forelse($activeClusters as $cluster)
            <a href="{{ route('admin.clusters.show', $cluster) }}" 
               class="block p-3 rounded-lg hover:bg-gray-50 border border-gray-100 mb-2">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium text-sm">{{ $cluster->area->kelurahan }}</p>
                        <p class="text-xs text-gray-500">{{ $cluster->report_count }} laporan · {{ $cluster->dominant_category }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded font-medium
                        @if($cluster->severity_score >= 70) bg-red-100 text-red-700
                        @elseif($cluster->severity_score >= 40) bg-orange-100 text-orange-700
                        @else bg-yellow-100 text-yellow-700 @endif">
                        {{ number_format($cluster->severity_score, 0) }}
                    </span>
                </div>
            </a>
        @empty
            <p class="text-sm text-gray-500">Tidak ada cluster aktif.</p>
        @endforelse
    </div>
    
    {{-- Urgent Reports --}}
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-900 mb-4">Laporan Prioritas</h2>
        @forelse($urgentReports as $report)
            <a href="{{ route('admin.reports.show', $report) }}"
               class="block p-3 rounded-lg hover:bg-gray-50 border border-gray-100 mb-2">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <p class="font-medium text-sm">{{ $report->area->kelurahan }} · {{ ucfirst($report->category) }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $report->statusLabel() }} · {{ $report->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded font-medium
                        @if($report->priority === 'critical') bg-red-100 text-red-700
                        @elseif($report->priority === 'high') bg-orange-100 text-orange-700
                        @elseif($report->priority === 'normal') bg-blue-100 text-blue-700
                        @else bg-gray-100 text-gray-700 @endif">
                        {{ ucfirst($report->priority) }}
                    </span>
                </div>
            </a>
        @empty
            <p class="text-sm text-gray-500">Tidak ada laporan prioritas.</p>
        @endforelse
    </div>
</div>
@endsection