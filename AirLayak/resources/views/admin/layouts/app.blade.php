<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Instansi') — AirLayak</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex h-screen">
        {{-- Sidebar --}}
        <aside class="w-64 bg-slate-900 text-white flex flex-col flex-shrink-0">
            <div class="p-6 border-b border-slate-700">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-teal-400 rounded-lg flex items-center justify-center text-base">
                        💧
                    </div>
                    <div>
                        <h1 class="text-lg font-semibold leading-tight">AirLayak</h1>
                        <p class="text-xs text-slate-400">Panel Instansi</p>
                    </div>
                </div>
            </div>
            
            <nav class="flex-1 p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition
                          {{ request()->routeIs('admin.dashboard') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">
                    <span>📊</span> Dashboard
                </a>
                
                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition
                          {{ request()->routeIs('admin.reports.*') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">
                    <span>📋</span> Laporan
                </a>
                
                <a href="{{ route('admin.clusters.index') }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition
                          {{ request()->routeIs('admin.clusters.*') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">
                    <span>⚠️</span> Cluster Alerts
                </a>
                
                <a href="{{ route('admin.notifications.index') }}"
                   class="flex items-center justify-between gap-2 px-3 py-2 rounded-lg text-sm transition
                          {{ request()->routeIs('admin.notifications.*') ? 'bg-slate-700' : 'hover:bg-slate-800' }}">
                    <span class="flex items-center gap-2">
                        <span>🔔</span> Notifikasi
                    </span>
                    @php
                        $unreadCount = auth()->user()->unreadNotificationsCount() ?? 0;
                    @endphp
                    @if($unreadCount > 0)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-red-500">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </a>
            </nav>
            
            <div class="p-4 border-t border-slate-700">
                <div class="text-sm mb-3">
                    <p class="font-medium">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 capitalize">
                        {{ auth()->user()->role }}
                        @if(auth()->user()->city)
                            · {{ auth()->user()->city }}
                        @endif
                    </p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-slate-400 hover:text-white">
                        Logout
                    </button>
                </form>
            </div>
        </aside>
        
        {{-- Main content --}}
        <main class="flex-1 overflow-y-auto">
            <div class="p-8">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>