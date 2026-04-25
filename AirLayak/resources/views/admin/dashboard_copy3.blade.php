@extends('admin.layouts.app')

@section('title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
<style>
    /* Styling popup peta */
    .leaflet-popup-content-wrapper { border-radius: 8px; padding: 0; }
    .leaflet-popup-content { margin: 0; min-width: 220px; }
</style>
@endpush

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

{{-- Active Cluster Alerts & Peta Prioritas --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    
    {{-- Active Cluster Alerts --}}
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
    
    {{-- Peta Prioritas --}}
    <div class="bg-white rounded-lg border border-gray-200 p-6 flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-semibold text-gray-900">Peta Persebaran Urgensi Area</h2>
            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">Surabaya</span>
        </div>
        
        <div id="priorityMap" class="w-full rounded-lg border border-gray-200" style="height: 350px; z-index: 1;"></div>
        
        <div class="flex gap-4 mt-4 text-[10px] font-medium text-gray-600 justify-center">
            <div class="flex items-center gap-1.5"><div class="w-3 h-3 rounded-full bg-red-600"></div> Critical</div>
            <div class="flex items-center gap-1.5"><div class="w-3 h-3 rounded-full bg-orange-500"></div> High</div>
            <div class="flex items-center gap-1.5"><div class="w-3 h-3 rounded-full bg-blue-600"></div> Normal</div>
            <div class="flex items-center gap-1.5"><div class="w-3 h-3 rounded-full bg-gray-400"></div> Low</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Transformasi Data PHP murni untuk menghindari error parsing Blade --}}
@php
    $mappedReports = $urgentReports->map(function($report) {
        return [
            'id'         => $report->id,
            'lat'        => (float) ($report->lat ?? 0), 
            'lng'        => (float) ($report->lng ?? 0),
            'priority'   => $report->priority,
            'category'   => $report->category,
            'created_at' => $report->created_at,
            'area'       => [
                'kelurahan' => $report->area ? $report->area->kelurahan : 'Lokasi Tidak Diketahui'
            ]
        ];
    })->values();
@endphp

<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const map = L.map('priorityMap').setView([-7.2575, 112.7521], 12);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Paksa kalkulasi ukuran ulang untuk mencegah peta abu-abu sebagian
    setTimeout(() => { map.invalidateSize(); }, 300);

    const reports = @json($mappedReports);
    const bounds = [];

    reports.forEach(report => {
        if (!isNaN(report.lat) && !isNaN(report.lng) && report.lat !== 0) {
            let markerColor = '#9CA3AF';
            let priorityLabel = 'Low';
            
            if (report.priority === 'critical') { markerColor = '#DC2626'; priorityLabel = 'Critical'; } 
            else if (report.priority === 'high') { markerColor = '#F97316'; priorityLabel = 'High'; } 
            else if (report.priority === 'normal') { markerColor = '#2563EB'; priorityLabel = 'Normal'; }

            const customIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background-color: ${markerColor}; width: 16px; height: 16px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 4px rgba(0,0,0,0.4);"></div>`,
                iconSize: [16, 16],
                iconAnchor: [8, 8]
            });

            const timeString = new Date(report.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

            const popupContent = `
                <div class="p-3 font-sans">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold text-white uppercase" style="background-color: ${markerColor}">
                            ${priorityLabel}
                        </span>
                        <span class="text-xs text-gray-500">${timeString}</span>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm mt-1 mb-0">${report.area.kelurahan}</h3>
                    <p class="text-xs text-gray-600 mb-3 capitalize">${report.category ? report.category.replace('_', ' ') : ''}</p>
                    
                    <a href="/admin/reports/${report.id}" 
                       class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold py-2 px-3 rounded transition-colors" style="color: white; text-decoration: none;">
                       Tindak Lanjuti →
                    </a>
                </div>
            `;

            L.marker([report.lat, report.lng], { icon: customIcon })
                .addTo(map)
                .bindPopup(popupContent);

            bounds.push([report.lat, report.lng]);
        }
    });

    if (bounds.length > 0) {
        map.fitBounds(bounds, { padding: [30, 30], maxZoom: 15 });
    }
});
</script>
@endpush