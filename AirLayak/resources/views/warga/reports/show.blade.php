@extends('layouts.app') {{-- Sesuaikan dengan layout warga Anda --}}

@section('title', 'Detail Laporan Saya')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
<style>
    #report-map { height: 250px; border-radius: 0.5rem; z-index: 0; }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('laporan.index') }}" class="text-sm text-teal-600 hover:underline">
            ← Kembali ke Laporan Saya
        </a>
        <div class="mt-4">
            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                {{ $report->statusLabel() }}
            </span>
            <h1 class="text-2xl font-bold text-gray-900 capitalize mt-2">
                Keluhan {{ str_replace('_', ' ', $report->category) }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Dilaporkan pada {{ $report->created_at->format('d M Y, H:i') }}
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Kolom Kiri: Detail & Map --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h2 class="font-semibold text-gray-900 mb-4">Detail Laporan</h2>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Deskripsi</p>
                        <p class="text-gray-900 mt-1">{{ $report->description ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase tracking-wide">Sumber Air</p>
                        <div class="mt-1">
                            @foreach($report->water_sources as $source)
                                <span class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded mr-1">
                                    {{ str_replace('_', ' ', $source) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            @if($report->location)
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h2 class="font-semibold text-gray-900 mb-3">Lokasi Laporan</h2>
                <div id="report-map"></div>
            </div>
            @endif
        </div>

        {{-- Kolom Kanan: Timeline Penanganan --}}
        <div>
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h2 class="font-semibold text-gray-900 mb-6">Track Penanganan Instansi</h2>
                
                <div class="relative ml-3 space-y-6">
                    <div class="absolute left-0 top-2 bottom-0 w-px bg-gray-200"></div>
                    
                    {{-- Laporan Masuk --}}
                    <div class="relative pl-6">
                        <div class="absolute -left-1.5 top-1 w-4 h-4 rounded-full bg-green-500 border-2 border-white shadow"></div>
                        <p class="font-medium text-sm">Laporan Diterima Sistem</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $report->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    
                    {{-- Dilihat Instansi --}}
                    @if($report->acknowledged_at)
                    <div class="relative pl-6">
                        <div class="absolute -left-1.5 top-1 w-4 h-4 rounded-full bg-blue-500 border-2 border-white shadow"></div>
                        <p class="font-medium text-sm">Laporan Dibaca oleh {{ strtoupper($report->target_role) }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $report->acknowledged_at->format('d M Y, H:i') }}</p>
                    </div>
                    @endif
                    
                    {{-- Mulai Dikerjakan --}}
                    @if($report->work_started_at)
                    <div class="relative pl-6">
                        <div class="absolute -left-1.5 top-1 w-4 h-4 rounded-full bg-yellow-500 border-2 border-white shadow"></div>
                        <p class="font-medium text-sm">Instansi Memulai Penanganan</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $report->work_started_at->format('d M Y, H:i') }}</p>
                        @if($report->eta_reason)
                            <div class="mt-2 p-3 bg-gray-50 rounded text-xs text-gray-700 italic border border-gray-100">
                                "{{ $report->eta_reason }}"
                            </div>
                        @endif
                        @if($report->eta_at)
                            <p class="text-xs font-semibold text-teal-700 mt-2">
                                Estimasi Selesai: {{ $report->eta_at->format('d M Y, H:i') }}
                            </p>
                        @endif
                    </div>
                    @endif
                    
                    {{-- Instansi Mengklaim Selesai --}}
                    @if($report->completion_claimed_at)
                    <div class="relative pl-6">
                        <div class="absolute -left-1.5 top-1 w-4 h-4 rounded-full bg-purple-500 border-2 border-white shadow"></div>
                        <p class="font-medium text-sm">Instansi Mengklaim Masalah Selesai</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $report->completion_claimed_at->format('d M Y, H:i') }}</p>
                        @if($report->completion_notes)
                            <div class="mt-2 p-3 bg-purple-50 rounded text-xs text-purple-800 italic border border-purple-100">
                                Catatan petugas: "{{ $report->completion_notes }}"
                            </div>
                        @endif
                    </div>
                    @endif
                    
                    {{-- Selesai Sepenuhnya --}}
                    @if($report->resolved_at)
                    <div class="relative pl-6">
                        <div class="absolute -left-1.5 top-1 w-4 h-4 rounded-full bg-green-600 border-2 border-white shadow"></div>
                        <p class="font-medium text-sm text-green-700">Laporan Selesai Ditutup</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $report->resolved_at->format('d M Y, H:i') }}</p>
                    </div>
                    @endif

                    {{-- Ditolak --}}
                    @if($report->dismissed_at)
                    <div class="relative pl-6">
                        <div class="absolute -left-1.5 top-1 w-4 h-4 rounded-full bg-red-500 border-2 border-white shadow"></div>
                        <p class="font-medium text-sm text-red-700">Laporan Ditolak / Dibatalkan</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $report->dismissed_at->format('d M Y, H:i') }}</p>
                        @if($report->dismissal_reason)
                            <p class="text-xs mt-1 italic text-red-600">Alasan: "{{ $report->dismissal_reason }}"</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($report->location)
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
    const reportLocation = @json($report->location);
    const reportMap = L.map('report-map', {
        center: [reportLocation.lat, reportLocation.lng],
        zoom: 16,
        zoomControl: true
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(reportMap);

    L.marker([reportLocation.lat, reportLocation.lng]).addTo(reportMap)
      .bindPopup('Lokasi Air Bermasalah Anda').openPopup();
</script>
@endpush
@endif
@endsection