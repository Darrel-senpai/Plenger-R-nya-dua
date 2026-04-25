@extends('admin.layouts.app')

@section('title', 'Detail Laporan')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
<style>
    #report-map {
        height: 280px;
        border-radius: 0.5rem;
        z-index: 0;
    }
    .timeline-line {
        position: absolute;
        left: 0.4rem;
        top: 1rem;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }
</style>
@endpush

@section('content')
<div class="mb-6">
    <a href="{{ url()->previous() }}" class="text-sm text-teal-600 hover:underline">
        ← Kembali
    </a>
    <div class="flex items-start justify-between mt-3 gap-4 flex-wrap">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2 py-0.5 text-xs rounded font-medium
                    @if($report->priority === 'critical') bg-red-100 text-red-700
                    @elseif($report->priority === 'high') bg-orange-100 text-orange-700
                    @elseif($report->priority === 'normal') bg-blue-100 text-blue-700
                    @else bg-gray-100 text-gray-700 @endif">
                    {{ ucfirst($report->priority) }} ({{ number_format($report->priority_score, 0) }})
                </span>
                <span class="px-2 py-0.5 text-xs rounded font-medium bg-slate-100 text-slate-700">
                    {{ $report->statusLabel() }}
                </span>
                @if($report->isOverdueAcknowledgment())
                    <span class="px-2 py-0.5 text-xs bg-red-100 text-red-700 rounded">
                        ⚠️ Overdue ack
                    </span>
                @endif
                @if($report->isOverdueResolution())
                    <span class="px-2 py-0.5 text-xs bg-orange-100 text-orange-700 rounded">
                        ⚠️ Overdue ETA
                    </span>
                @endif
            </div>
            <h1 class="text-2xl font-semibold text-gray-900 capitalize">
                Laporan {{ str_replace('_', ' ', $report->category) }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ $report->area->full_address ?? '-' }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
                Dilaporkan {{ $report->created_at->format('d M Y, H:i') }} ({{ $report->created_at->diffForHumans() }})
            </p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main detail (2/3) --}}
    <div class="lg:col-span-2 space-y-6">
        
        {{-- Info card --}}
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="font-semibold text-gray-900 mb-4">Informasi Laporan</h2>
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-gray-500 text-xs uppercase tracking-wide mb-1">Kategori</dt>
                    <dd class="font-medium capitalize">{{ str_replace('_', ' ', $report->category) }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 text-xs uppercase tracking-wide mb-1">Sumber Air Bermasalah</dt>
                    <dd>
                        @foreach($report->water_sources as $source)
                            <span class="inline-block px-2 py-0.5 mr-1 mb-1 text-xs bg-blue-50 text-blue-700 rounded">
                                {{ str_replace('_', ' ', $source) }}
                            </span>
                        @endforeach
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-500 text-xs uppercase tracking-wide mb-1">Target Penanganan</dt>
                    <dd class="font-medium uppercase">{{ $report->target_role }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500 text-xs uppercase tracking-wide mb-1">Priority Score</dt>
                    <dd class="font-medium">{{ number_format($report->priority_score, 1) }} / 100</dd>
                </div>
                <div class="col-span-2">
                    <dt class="text-gray-500 text-xs uppercase tracking-wide mb-1">Deskripsi</dt>
                    <dd class="text-gray-900 leading-relaxed">{{ $report->description ?: '(tidak ada deskripsi)' }}</dd>
                </div>
            </dl>
        </div>
        
        {{-- Location & Map --}}
        @if($report->location)
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <h2 class="font-semibold text-gray-900">Lokasi</h2>
                <span class="text-xs text-gray-500 font-mono">
                    {{ number_format($report->location['lat'], 5) }}, {{ number_format($report->location['lng'], 5) }}
                </span>
            </div>
            <div id="report-map"></div>
            <p class="text-xs text-gray-500 mt-3">
                Koordinat presisi dari pelapor. Klik marker untuk navigasi.
            </p>
        </div>
        @endif
        
        {{-- Lifecycle Timeline --}}
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="font-semibold text-gray-900 mb-4">Timeline Penanganan</h2>
            <div class="relative ml-3 space-y-5">
                <div class="absolute left-0 top-2 bottom-0 w-px bg-gray-200"></div>
                
                {{-- Created --}}
                <div class="relative pl-6">
                    <div class="absolute -left-1.5 top-1 w-4 h-4 rounded-full bg-green-500 border-2 border-white shadow"></div>
                    <p class="font-medium text-sm">Laporan masuk</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $report->created_at->format('d M Y, H:i:s') }}</p>
                </div>
                
                {{-- Acknowledged --}}
                @if($report->acknowledged_at)
                <div class="relative pl-6">
                    <div class="absolute -left-1.5 top-1 w-4 h-4 rounded-full bg-blue-500 border-2 border-white shadow"></div>
                    <p class="font-medium text-sm">Acknowledged</p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $report->acknowledged_at->format('d M Y, H:i:s') }}
                        @if($report->acknowledgedBy)
                            · oleh {{ $report->acknowledgedBy->name }}
                        @endif
                    </p>
                </div>
                @endif
                
                {{-- Work started --}}
                @if($report->work_started_at)
                <div class="relative pl-6">
                    <div class="absolute -left-1.5 top-1 w-4 h-4 rounded-full bg-yellow-500 border-2 border-white shadow"></div>
                    <p class="font-medium text-sm">Penanganan dimulai</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $report->work_started_at->format('d M Y, H:i:s') }}</p>
                    @if($report->eta_at)
                        <p class="text-xs text-gray-600 mt-1">
                            <span class="text-gray-500">ETA:</span> 
                            <span class="font-medium">{{ $report->eta_at->format('d M Y, H:i') }}</span>
                            @if($report->eta_at->isPast())
                                <span class="ml-2 text-red-600">(terlewat {{ $report->eta_at->diffForHumans() }})</span>
                            @else
                                <span class="ml-2 text-gray-500">({{ $report->eta_at->diffForHumans() }})</span>
                            @endif
                        </p>
                    @endif
                    @if($report->eta_reason)
                        <p class="text-xs text-gray-600 mt-1 italic">"{{ $report->eta_reason }}"</p>
                    @endif
                </div>
                @endif
                
                {{-- Extension requests --}}
                @foreach($report->extensions as $extension)
                <div class="relative pl-6">
                    <div class="absolute -left-1.5 top-1 w-4 h-4 rounded-full 
                        @if($extension->status === 'approved') bg-green-500
                        @elseif($extension->status === 'rejected') bg-red-500
                        @else bg-orange-500 @endif
                        border-2 border-white shadow"></div>
                    <p class="font-medium text-sm">
                        Request Extension 
                        <span class="text-xs font-normal text-gray-500">({{ ucfirst($extension->status) }})</span>
                    </p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $extension->created_at->format('d M Y, H:i:s') }}</p>
                    <p class="text-xs text-gray-600 mt-1">
                        ETA baru: {{ $extension->proposed_eta_at->format('d M Y, H:i') }}
                    </p>
                    @if($extension->reason)
                        <p class="text-xs text-gray-600 mt-1 italic">"{{ $extension->reason }}"</p>
                    @endif
                </div>
                @endforeach
                
                {{-- Completion claimed --}}
                @if($report->completion_claimed_at)
                <div class="relative pl-6">
                    <div class="absolute -left-1.5 top-1 w-4 h-4 rounded-full bg-purple-500 border-2 border-white shadow"></div>
                    <p class="font-medium text-sm">Klaim selesai oleh operator</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $report->completion_claimed_at->format('d M Y, H:i:s') }}</p>
                    @if($report->completion_notes)
                        <p class="text-xs text-gray-600 mt-1 italic">"{{ $report->completion_notes }}"</p>
                    @endif
                </div>
                @endif
                
                {{-- Resolved --}}
                @if($report->resolved_at)
                <div class="relative pl-6">
                    <div class="absolute -left-1.5 top-1 w-4 h-4 rounded-full bg-green-600 border-2 border-white shadow"></div>
                    <p class="font-medium text-sm text-green-700">✓ Resolved</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $report->resolved_at->format('d M Y, H:i:s') }}</p>
                </div>
                @endif
                
                {{-- Dismissed --}}
                @if($report->dismissed_at)
                <div class="relative pl-6">
                    <div class="absolute -left-1.5 top-1 w-4 h-4 rounded-full bg-gray-400 border-2 border-white shadow"></div>
                    <p class="font-medium text-sm text-gray-600">Dismissed</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $report->dismissed_at->format('d M Y, H:i:s') }}</p>
                    @if($report->dismissal_reason)
                        <p class="text-xs text-gray-600 mt-1 italic">"{{ $report->dismissal_reason }}"</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
        
        {{-- Warnings --}}
        @if($report->warnings->count() > 0)
        <div class="bg-orange-50/50 rounded-lg border border-orange-200 p-6">
            <h2 class="font-semibold text-orange-900 mb-4">⚠️ Riwayat Peringatan ({{ $report->warnings->count() }})</h2>
            <div class="space-y-2 text-sm">
                @foreach($report->warnings as $warning)
                    <div class="p-3 bg-white rounded border border-orange-100">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-medium text-orange-800 capitalize">
                                    {{ str_replace('_', ' ', $warning->warning_type) }}
                                </p>
                                @if($warning->priority_impact)
                                    <p class="text-xs text-orange-600 mt-1">
                                        Priority Impact: +{{ number_format($warning->priority_impact, 1) }}
                                    </p>
                                @endif
                            </div>
                            <span class="text-xs text-gray-500">
                                {{ $warning->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    
    {{-- Action Sidebar (1/3) --}}
    <div class="lg:col-span-1 space-y-4">
        
        {{-- Action Buttons berdasarkan status --}}
        <div class="bg-white rounded-lg border border-gray-200 p-6 sticky top-4">
            <h2 class="font-semibold text-gray-900 mb-4">Aksi</h2>
            
            @if($report->status === 'pending')
                <form method="POST" action="{{ route('admin.reports.acknowledge', $report) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2.5 rounded text-sm font-medium hover:bg-blue-700 transition">
                        ✓ Acknowledge Laporan
                    </button>
                </form>
                <p class="text-xs text-gray-500 mt-2">
                    Tandai bahwa Anda sudah melihat laporan ini dan akan menindaklanjuti.
                </p>
                
            @elseif($report->status === 'acknowledged')
                <form method="POST" action="{{ route('admin.reports.start', $report) }}" class="space-y-3">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Estimasi Waktu Selesai (ETA) <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="eta_at" required
                               min="{{ now()->format('Y-m-d\TH:i') }}"
                               value="{{ now()->addHours(6)->format('Y-m-d\TH:i') }}"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Rencana Tindakan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="eta_reason" required rows="3"
                                  class="w-full px-3 py-2 text-sm border border-gray-200 rounded focus:outline-none focus:ring-2 focus:ring-teal-500"
                                  placeholder="Mis: Tim teknisi dispatch ke lokasi untuk inspeksi pipa..."></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-yellow-600 text-white px-4 py-2.5 rounded text-sm font-medium hover:bg-yellow-700 transition">
                        🔧 Mulai Penanganan
                    </button>
                </form>
                
            @elseif($report->status === 'in_progress')
                <form method="POST" action="{{ route('admin.reports.complete', $report) }}" class="space-y-3 mb-4">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Catatan Penyelesaian <span class="text-red-500">*</span>
                        </label>
                        <textarea name="completion_notes" required rows="3"
                                  class="w-full px-3 py-2 text-sm border border-gray-200 rounded focus:outline-none focus:ring-2 focus:ring-teal-500"
                                  placeholder="Mis: Saluran sudah dibersihkan, klorinasi ulang dilakukan..."></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2.5 rounded text-sm font-medium hover:bg-green-700 transition">
                        ✓ Tandai Selesai
                    </button>
                </form>
                
                <details class="border-t border-gray-100 pt-3">
                    <summary class="text-sm text-gray-600 cursor-pointer hover:text-gray-900">
                        Butuh perpanjangan waktu?
                    </summary>
                    <form method="POST" action="{{ route('admin.reports.extension', $report) }}" class="space-y-3 mt-3">
                        @csrf
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">ETA Baru</label>
                            <input type="datetime-local" name="proposed_eta_at" required
                                   min="{{ now()->format('Y-m-d\TH:i') }}"
                                   value="{{ now()->addHours(12)->format('Y-m-d\TH:i') }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Alasan</label>
                            <textarea name="reason" required rows="2"
                                      class="w-full px-3 py-2 text-sm border border-gray-200 rounded focus:outline-none focus:ring-2 focus:ring-teal-500"
                                      placeholder="Mis: Diperlukan inspeksi tambahan..."></textarea>
                        </div>
                        
                        <button type="submit" class="w-full bg-orange-600 text-white px-4 py-2 rounded text-sm font-medium hover:bg-orange-700 transition">
                            Kirim Permintaan Perpanjangan
                        </button>
                    </form>
                </details>
                
            @elseif($report->status === 'extension_requested')
                <div class="p-4 bg-orange-50 border border-orange-200 rounded text-sm text-orange-800">
                    <p class="font-medium">⏳ Menunggu respond pelapor</p>
                    <p class="text-xs mt-1">Permintaan perpanjangan ETA sedang menunggu jawaban dari pelapor.</p>
                </div>
                
            @elseif($report->status === 'awaiting_confirmation')
                <div class="p-4 bg-blue-50 border border-blue-200 rounded text-sm text-blue-800">
                    <p class="font-medium">📨 Menunggu konfirmasi pelapor</p>
                    <p class="text-xs mt-1">
                        Pelapor akan mengkonfirmasi apakah masalah sudah benar-benar selesai.
                    </p>
                    @if($report->completion_claimed_at)
                        <p class="text-xs text-blue-600 mt-2">
                            Diklaim selesai: {{ $report->completion_claimed_at->diffForHumans() }}
                        </p>
                    @endif
                </div>
                
            @elseif($report->status === 'resolved')
                <div class="p-4 bg-green-50 border border-green-200 rounded text-sm text-green-800">
                    <p class="font-medium">✓ Laporan sudah selesai</p>
                    @if($report->resolved_at)
                        <p class="text-xs mt-1">{{ $report->resolved_at->format('d M Y, H:i') }}</p>
                    @endif
                </div>
                
            @elseif($report->status === 'dismissed')
                <div class="p-4 bg-gray-50 border border-gray-200 rounded text-sm text-gray-700">
                    <p class="font-medium">Laporan ditolak</p>
                    @if($report->dismissal_reason)
                        <p class="text-xs mt-1 italic">"{{ $report->dismissal_reason }}"</p>
                    @endif
                </div>
                
            @elseif($report->status === 'reopened')
                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded text-sm text-yellow-800">
                    <p class="font-medium">🔄 Dibuka kembali</p>
                    <p class="text-xs mt-1">Pelapor menolak klaim selesai, perlu penanganan ulang.</p>
                </div>
                
                <form method="POST" action="{{ route('admin.reports.start', $report) }}" class="space-y-3 mt-4">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            ETA Baru
                        </label>
                        <input type="datetime-local" name="eta_at" required
                               min="{{ now()->format('Y-m-d\TH:i') }}"
                               value="{{ now()->addHours(6)->format('Y-m-d\TH:i') }}"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Rencana Penanganan Ulang</label>
                        <textarea name="eta_reason" required rows="3"
                                  class="w-full px-3 py-2 text-sm border border-gray-200 rounded"></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-yellow-600 text-white px-4 py-2 rounded text-sm font-medium hover:bg-yellow-700">
                        🔧 Tangani Kembali
                    </button>
                </form>
            @endif
            
            {{-- Dismiss option --}}
            @if(in_array($report->status, ['pending', 'acknowledged']))
                <details class="border-t border-gray-100 pt-3 mt-4">
                    <summary class="text-sm text-red-600 cursor-pointer hover:text-red-800">
                        Tolak laporan ini
                    </summary>
                    <form method="POST" action="{{ route('admin.reports.dismiss', $report) }}" class="space-y-3 mt-3">
                        @csrf
                        @method('PATCH')
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Alasan Penolakan</label>
                            <textarea name="dismissal_reason" required rows="3"
                                      class="w-full px-3 py-2 text-sm border border-gray-200 rounded focus:outline-none focus:ring-2 focus:ring-red-500"
                                      placeholder="Mis: Laporan duplikat / di luar scope kami..."></textarea>
                        </div>
                        
                        <button type="submit" 
                                onclick="return confirm('Yakin ingin menolak laporan ini? Aksi ini tidak dapat dibatalkan.')"
                                class="w-full bg-red-600 text-white px-4 py-2 rounded text-sm font-medium hover:bg-red-700 transition">
                            Tolak Laporan
                        </button>
                    </form>
                </details>
            @endif
        </div>
        
        {{-- Handler Info --}}
        @if($report->handler)
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Penanganan</p>
            <p class="text-sm font-medium">{{ $report->handler->name }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $report->handler_organization }}</p>
            <p class="text-xs text-gray-400 mt-1 capitalize">Role: {{ $report->handler->role }}</p>
        </div>
        @endif
        
        {{-- Cluster Info --}}
        @if($report->clusterAlerts->count() > 0)
        <div class="bg-white rounded-lg border border-orange-200 p-4">
            <p class="text-xs text-orange-700 uppercase tracking-wide mb-3 font-medium">
                ⚠️ Bagian dari Cluster
            </p>
            @foreach($report->clusterAlerts as $cluster)
                <a href="{{ route('admin.clusters.show', $cluster) }}" 
                   class="block p-2 rounded hover:bg-orange-50 transition mb-1">
                    <p class="text-sm font-medium text-orange-700">
                        Cluster {{ $cluster->dominant_category }}
                    </p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $cluster->report_count }} laporan · severity {{ number_format($cluster->severity_score, 0) }}
                    </p>
                </a>
            @endforeach
        </div>
        @endif
        
        {{-- Reporter Info --}}
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Info Pelapor</p>
            <p class="text-xs text-gray-600">
                <span class="text-gray-500">Session ID:</span> 
                <span class="font-mono text-xs">{{ Str::limit($report->reporter_session_id, 16) }}</span>
            </p>
            @if($report->ip_address)
                <p class="text-xs text-gray-600 mt-1">
                    <span class="text-gray-500">IP:</span> {{ $report->ip_address }}
                </p>
            @endif
            <p class="text-xs text-gray-400 mt-2">Pelapor anonymous, identitas tidak disimpan.</p>
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
    zoom: 17,
});

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap',
}).addTo(reportMap);

const marker = L.marker([reportLocation.lat, reportLocation.lng]).addTo(reportMap);
marker.bindPopup('Lokasi laporan presisi').openPopup();

// Optional: tambahkan circle radius 50m untuk privacy area approximation
L.circle([reportLocation.lat, reportLocation.lng], {
    radius: 50,
    fillColor: '#0d9488',
    fillOpacity: 0.1,
    color: '#0d9488',
    weight: 1,
    dashArray: '4, 4',
}).addTo(reportMap);
</script>
@endpush
@endif
@endsection