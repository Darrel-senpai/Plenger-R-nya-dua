@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Tracking Laporan Saya</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-4 rounded mb-4">{{ session('error') }}</div>
    @endif

    <div class="grid gap-6">
        @forelse($reports as $report)
            <div class="border rounded-lg p-6 shadow-sm bg-white">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $report->statusLabel() }}
                        </span>
                        <h2 class="text-xl font-semibold mt-2 capitalize">{{ str_replace('_', ' ', $report->category) }}</h2>
                        <p class="text-gray-600 text-sm mt-1">{{ $report->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                
                <p class="text-gray-800 mb-4">{{ $report->description }}</p>

                {{-- Notifikasi Extend Waktu dari Instansi --}}
                @if($report->status === 'extension_requested' && $report->activeExtension)
                    <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-md mb-4">
                        <p class="font-semibold text-yellow-800">Instansi meminta tambahan waktu penanganan.</p>
                        <p class="text-sm text-yellow-700">Alasan: {{ $report->activeExtension->reason }}</p>
                        <p class="text-sm text-yellow-700">Estimasi Baru: {{ $report->activeExtension->proposed_eta_at->format('d M Y, H:i') }}</p>
                        
                        <form action="{{ route('laporan.extension.respond', $report->activeExtension->id) }}" method="POST" class="mt-3 flex gap-2">
                            @csrf
                            <button type="submit" name="action" value="approve" class="bg-yellow-600 text-white px-4 py-2 rounded text-sm hover:bg-yellow-700">Setujui</button>
                            <button type="submit" name="action" value="reject" class="bg-gray-200 text-gray-800 px-4 py-2 rounded text-sm hover:bg-gray-300">Tolak</button>
                        </form>
                    </div>
                @endif

                {{-- Action Buttons --}}
                <div class="flex gap-3 border-t pt-4 mt-2">
                    @if($report->status === 'pending')
                        <form action="{{ route('laporan.cancel', $report->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium" onclick="return confirm('Yakin ingin membatalkan laporan ini?')">Batalkan Laporan</button>
                        </form>
                    @endif

                    @if(in_array($report->status, ['awaiting_confirmation', 'in_progress']))
                        <form action="{{ route('laporan.resolve', $report->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition" onclick="return confirm('Konfirmasi bahwa masalah air sudah teratasi?')">Tandai Selesai</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">
                Belum ada laporan yang Anda buat.
            </div>
        @endforelse
    </div>
</div>
@endsection