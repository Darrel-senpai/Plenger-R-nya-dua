@extends('admin.layouts.app')

@section('title', $selectedArea ? "Laporan {$selectedArea->kelurahan}" : 'Daftar Laporan')

@section('content')
<div class="mb-6">
    @if($selectedArea)
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-teal-600 hover:underline">
            ← Kembali ke dashboard
        </a>
        <div class="mt-2">
            <h1 class="text-2xl font-semibold text-gray-900">
                Laporan di Kel. {{ $selectedArea->kelurahan }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Kec. {{ $selectedArea->kecamatan }}, {{ $selectedArea->city }}
                · Total: {{ $reports->total() }} laporan
                @if(request()->filled('show_all'))
                    (semua status)
                @else
                    (laporan aktif)
                @endif
            </p>
        </div>
    @else
        <h1 class="text-2xl font-semibold text-gray-900">Daftar Laporan</h1>
        <p class="text-sm text-gray-500 mt-1">
            Total: {{ $reports->total() }} laporan
            @if(!request()->filled('show_all'))
                (laporan aktif)
            @endif
        </p>
    @endif
</div>

{{-- Filter Bar --}}
<div class="bg-white rounded-lg border border-gray-200 p-4 mb-4">
    <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3">
        @if($selectedArea)
            <input type="hidden" name="area_id" value="{{ $selectedArea->id }}">
        @endif
        
        <div>
            <label class="text-xs text-gray-600 mb-1 block">Status</label>
            <select name="status" class="w-full px-3 py-2 text-sm border border-gray-200 rounded focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Semua Status Aktif</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="acknowledged" {{ request('status') === 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Sedang Ditangani</option>
                <option value="extension_requested" {{ request('status') === 'extension_requested' ? 'selected' : '' }}>Extension Requested</option>
                <option value="awaiting_confirmation" {{ request('status') === 'awaiting_confirmation' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="dismissed" {{ request('status') === 'dismissed' ? 'selected' : '' }}>Dismissed</option>
            </select>
        </div>
        
        <div>
            <label class="text-xs text-gray-600 mb-1 block">Priority</label>
            <select name="priority" class="w-full px-3 py-2 text-sm border border-gray-200 rounded focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Semua</option>
                <option value="critical" {{ request('priority') === 'critical' ? 'selected' : '' }}>Critical</option>
                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                <option value="normal" {{ request('priority') === 'normal' ? 'selected' : '' }}>Normal</option>
                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
            </select>
        </div>
        
        <div>
            <label class="text-xs text-gray-600 mb-1 block">Kategori</label>
            <select name="category" class="w-full px-3 py-2 text-sm border border-gray-200 rounded focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Semua</option>
                <option value="bau" {{ request('category') === 'bau' ? 'selected' : '' }}>Bau</option>
                <option value="warna" {{ request('category') === 'warna' ? 'selected' : '' }}>Warna</option>
                <option value="sakit_perut" {{ request('category') === 'sakit_perut' ? 'selected' : '' }}>Sakit Perut</option>
                <option value="rasa_aneh" {{ request('category') === 'rasa_aneh' ? 'selected' : '' }}>Rasa Aneh</option>
                <option value="lainnya" {{ request('category') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
        </div>
        
        <div>
            <label class="text-xs text-gray-600 mb-1 block">Sumber Air</label>
            <select name="water_source" class="w-full px-3 py-2 text-sm border border-gray-200 rounded focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Semua</option>
                <option value="pdam" {{ request('water_source') === 'pdam' ? 'selected' : '' }}>PDAM</option>
                <option value="sumur" {{ request('water_source') === 'sumur' ? 'selected' : '' }}>Sumur</option>
                <option value="galon" {{ request('water_source') === 'galon' ? 'selected' : '' }}>Galon</option>
                <option value="air_isi_ulang" {{ request('water_source') === 'air_isi_ulang' ? 'selected' : '' }}>Air Isi Ulang</option>
                <option value="tidak_yakin" {{ request('water_source') === 'tidak_yakin' ? 'selected' : '' }}>Tidak Yakin</option>
            </select>
        </div>
        
        @if(!$selectedArea)
        <div>
            <label class="text-xs text-gray-600 mb-1 block">Kelurahan</label>
            <select name="area_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Semua</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>
                        {{ $area->kelurahan }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif
        
        <div class="flex items-end gap-2">
            <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded text-sm font-medium hover:bg-teal-700 transition flex-1">
                Filter
            </button>
            <a href="{{ route('admin.reports.index', $selectedArea ? ['area_id' => $selectedArea->id] : []) }}" 
               class="px-4 py-2 border border-gray-300 rounded text-sm font-medium hover:bg-gray-50 transition">
                Reset
            </a>
        </div>
    </form>
    
    <div class="mt-3 pt-3 border-t border-gray-100">
        <label class="inline-flex items-center text-xs text-gray-600">
            <input type="checkbox" 
                   onchange="window.location.href='{{ request()->fullUrlWithQuery(['show_all' => request()->filled('show_all') ? null : 1]) }}'"
                   {{ request()->filled('show_all') ? 'checked' : '' }}
                   class="mr-2 rounded">
            Tampilkan juga laporan resolved/dismissed
        </label>
    </div>
</div>

{{-- Reports List --}}
<div class="space-y-3">
    @forelse($reports as $report)
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:border-teal-300 transition">
            <div class="p-4">
                <div class="flex items-start justify-between gap-4">
                    {{-- Left: Report info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                            <span class="px-2 py-0.5 text-xs rounded font-medium
                                @if($report->priority === 'critical') bg-red-100 text-red-700
                                @elseif($report->priority === 'high') bg-orange-100 text-orange-700
                                @elseif($report->priority === 'normal') bg-blue-100 text-blue-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst($report->priority) }} ({{ number_format($report->priority_score, 0) }})
                            </span>
                            
                            <span class="px-2 py-0.5 text-xs rounded bg-slate-100 text-slate-700">
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
                            
                            <span class="text-xs text-gray-400 ml-auto">
                                {{ $report->created_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        <h3 class="font-semibold text-gray-900 capitalize mb-1">
                            {{ str_replace('_', ' ', $report->category) }} 
                            <span class="text-gray-500 font-normal">·</span> 
                            <span class="text-gray-700">{{ $report->area->kelurahan ?? '-' }}</span>
                        </h3>
                        
                        <p class="text-sm text-gray-600 mb-2 line-clamp-2">
                            {{ $report->description ?: '(tidak ada deskripsi)' }}
                        </p>
                        
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="text-gray-500">Sumber:</span>
                            @foreach($report->water_sources as $source)
                                <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded">
                                    {{ str_replace('_', ' ', $source) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    
                    {{-- Right: Quick Actions --}}
                    <div class="flex flex-col gap-2 flex-shrink-0">
                        <a href="{{ route('admin.reports.show', $report) }}" 
                           class="px-4 py-2 bg-teal-600 text-white rounded text-sm font-medium hover:bg-teal-700 transition text-center whitespace-nowrap">
                            Detail & Tindak Lanjut →
                        </a>
                        
                        @if($report->status === 'pending')
                            <form method="POST" action="{{ route('admin.reports.acknowledge', $report) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full px-4 py-2 bg-blue-50 text-blue-700 border border-blue-200 rounded text-sm font-medium hover:bg-blue-100 transition whitespace-nowrap">
                                    ✓ Acknowledge
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
            <p class="text-gray-500 mb-2">Tidak ada laporan ditemukan.</p>
            <p class="text-xs text-gray-400">
                @if(request()->hasAny(['status', 'priority', 'category', 'water_source', 'area_id']))
                    Coba reset filter untuk lihat semua laporan.
                @else
                    Belum ada laporan masuk.
                @endif
            </p>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($reports->hasPages())
<div class="mt-6">
    {{ $reports->links() }}
</div>
@endif
@endsection