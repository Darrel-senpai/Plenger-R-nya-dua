@extends('admin.layouts.app')

@section('title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
    <style>
        #cluster-map {
            height: 500px;
            border-radius: 0.5rem;
            z-index: 0;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 0.5rem !important;
        }

        .leaflet-popup-content {
            margin: 0.75rem 1rem !important;
            font-family: inherit;
        }

        .priority-list {
            max-height: 500px;
            overflow-y: auto;
        }

        .priority-list::-webkit-scrollbar {
            width: 4px;
        }

        .priority-list::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }
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
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
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

    {{-- Map + Priority Reports Side by Side --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Heatmap Map (2/3 width) --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between flex-wrap gap-2">
                    <div>
                        <h2 class="font-semibold text-gray-900">Peta Sebaran Laporan</h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            @php
                                $totalReportsActive = collect($areasWithCount)->sum('count');
                                $kelurahanWithReports = collect($areasWithCount)->where('count', '>', 0)->count();
                            @endphp
                            {{ $totalReportsActive }} laporan aktif di {{ $kelurahanWithReports }} kelurahan
                        </p>
                    </div>
                    <div class="flex items-center gap-3 text-xs">
                        <div class="flex items-center gap-1.5">
                            <div class="w-3 h-3 rounded-full" style="background: #9ca3af; opacity: 0.5;"></div>
                            <span class="text-gray-600">Aman</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-3 h-3 rounded-full" style="background: #fbbf24; opacity: 0.7;"></div>
                            <span class="text-gray-600">1-2 laporan</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-3 h-3 rounded-full" style="background: #ef4444; opacity: 0.7;"></div>
                            <span class="text-gray-600">3+ laporan</span>
                        </div>
                    </div>
                </div>
                <div id="cluster-map"></div>
            </div>
        </div>

        {{-- Priority Reports (1/3 width) --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg border border-gray-200 h-full flex flex-col">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold text-gray-900">Laporan Prioritas</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Diurutkan dari paling urgent</p>
                    </div>
                    <a href="{{ route('admin.reports.index') }}" class="text-xs text-teal-600 hover:underline">
                        Semua →
                    </a>
                </div>

                <div class="priority-list flex-1 overflow-y-auto p-2">
                    @forelse($priorityReports as $report)
                        <a href="{{ route('admin.reports.show', $report) }}"
                            class="block p-3 rounded-lg hover:bg-gray-50 border border-transparent hover:border-gray-100 mb-1 transition group">
                            <div class="flex items-start gap-2">
                                <div class="w-1 self-stretch rounded-full
                                        @if($report->priority === 'critical') bg-red-500
                                        @elseif($report->priority === 'high') bg-orange-500
                                        @elseif($report->priority === 'normal') bg-blue-500
                                        @else bg-gray-300 @endif">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 mb-1">
                                        <p class="font-medium text-sm truncate">
                                            {{ $report->area->kelurahan ?? '-' }}
                                        </p>
                                        <span class="text-xs font-mono text-gray-400 flex-shrink-0">
                                            {{ number_format($report->priority_score, 0) }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-600 mb-1 capitalize">
                                        {{ str_replace('_', ' ', $report->category) }}
                                    </p>
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-gray-500">{{ $report->statusLabel() }}</span>
                                        <span class="text-gray-400">{{ $report->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-8 text-sm text-gray-500">
                            Tidak ada laporan aktif.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script>
        const areasWithCount = @json($areasWithCount);
        const mapCenter = @json($mapCenter);

        const map = L.map('cluster-map', {
            center: [mapCenter.lat, mapCenter.lng],
            zoom: 12,
            zoomControl: true,
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap',
            maxZoom: 19
        }).addTo(map);

        function getHeatColor(count) {
            if (count >= 3) return '#ef4444';
            if (count >= 1) return '#fbbf24';
            return '#9ca3af';
        }

        function getHeatOpacity(count) {
            if (count >= 3) return 0.55;
            if (count >= 1) return 0.4;
            return 0.15;
        }

        function getHeatRadius(count) {
            if (count >= 5) return 1200;
            if (count >= 3) return 900;
            if (count >= 1) return 700;
            return 500;
        }

        areasWithCount.forEach(area => {
            const color = getHeatColor(area.count);
            const opacity = getHeatOpacity(area.count);
            const radius = getHeatRadius(area.count);

            const circle = L.circle([area.lat, area.lng], {
                radius: radius,
                fillColor: color,
                fillOpacity: opacity,
                color: color,
                weight: area.count > 0 ? 2 : 1,
                opacity: area.count > 0 ? 0.8 : 0.3,
            }).addTo(map);

            const statusLabel = area.count >= 3 ? '🔴 Darurat (3+ laporan)'
                : area.count >= 1 ? '🟡 Waspada'
                    : '⚪ Normal';

            const popupHtml = `
            <div style="min-width: 220px; padding: 4px;">
                <div style="font-weight: 700; font-size: 14px; margin-bottom: 2px;">
                    Kel. ${area.kelurahan}
                </div>
                <div style="font-size: 11px; color: #6b7280; margin-bottom: 10px;">
                    Kec. ${area.kecamatan}
                </div>
                <div style="border-top: 1px solid #f0f0f0; padding-top: 8px;">
                    <div style="display: flex; justify-content: space-between; padding: 4px 0; font-size: 12px;">
                        <span style="color: #6b7280;">Status</span>
                        <span style="font-weight: 700; color: ${color};">${statusLabel}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 4px 0; font-size: 12px;">
                        <span style="color: #6b7280;">Laporan aktif</span>
                        <span style="font-weight: 700; color: ${color};">${area.count} laporan</span>
                    </div>
                </div>
            </div>
        `;
            circle.bindPopup(popupHtml, { maxWidth: 280 });
        });

        if (areasWithCount.length > 0) {
            const bounds = L.latLngBounds(areasWithCount.map(a => [a.lat, a.lng]));
            map.fitBounds(bounds, { padding: [40, 40], maxZoom: 13 });
        }
    </script>
@endpush