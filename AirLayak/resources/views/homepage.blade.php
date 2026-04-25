<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>AirLayak - Pantauan Air Surabaya</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
        extend: {
            fontFamily: {
            sans: ['Plus Jakarta Sans', 'sans-serif'],
            mono: ['Space Mono', 'monospace'],
            },
            colors: {
            blue: { DEFAULT: '#1A6BCC', light: '#EBF3FF', dark: '#1558AA' },
            teal: { DEFAULT: '#0D8C6E', light: '#E0F5EF' },
            amber: { DEFAULT: '#D97706', light: '#FEF3C7' },
            danger: { DEFAULT: '#DC2626', light: '#FEE2E2', ring: '#EF4444' },
            },
            animation: {
            blink: 'blink 2s infinite',
            'blink-fast': 'blink 1.5s infinite',
            'blink-slow': 'blink 1s infinite',
            },
            keyframes: {
            blink: { '0%,100%': { opacity: 1 }, '50%': { opacity: 0.5 } },
            },
        }
        }
    }
    </script>
    <style>
    /* Non-Tailwind necessities */
    html, body { height: 100%; overflow: hidden; }
    #map { position: fixed; top: 54px; left: 0; right: 0; bottom: 0; z-index: 0; }
    .cat-btn.active { border-color: #1A6BCC; background: #EBF3FF; }
    .panel-body { max-height: 0; overflow: hidden; transition: max-height 0.35s ease; }
    .panel-body.open { max-height: 520px; }
    .chevron { transition: transform 0.25s; }
    .chevron.open { transform: rotate(180deg); }
    .toast {
        position: fixed; bottom: 20px; left: 50%;
        transform: translateX(-50%) translateY(80px);
        opacity: 0; transition: all 0.3s ease; z-index: 2000;
    }
    .toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }
    select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%23666' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        appearance: none;
    }
    .leaflet-popup-content-wrapper { border-radius: 10px !important; padding: 0 !important; overflow: hidden; }
    .leaflet-popup-content { margin: 0 !important; }
    .area-scroll::-webkit-scrollbar { width: 3px; }
    .area-scroll::-webkit-scrollbar-thumb { background: #E8EAED; border-radius: 3px; }
    .tab.active { background: #fff; color: #1A6BCC; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
    .float-panel { transition: all 0.3s ease; }
    </style>
</head>
<body class="bg-slate-200 font-sans text-gray-900">

    <nav class="fixed top-0 left-0 right-0 z-[1000] bg-white/97 backdrop-blur-md border-b border-gray-200 flex items-center justify-between px-5 h-[54px]">
        <div class="flex items-center gap-2">
            <div class="w-[30px] h-[30px] rounded-lg bg-gradient-to-br from-blue to-teal flex items-center justify-center text-xs text-white font-extrabold font-mono">AL</div>
            <span class="font-extrabold text-[17px] tracking-tight">Air<span class="text-blue">Layak</span></span>
        </div>

        <div class="absolute left-1/2 -translate-x-1/2 flex items-center gap-2">
            <div class="w-1.5 h-1.5 rounded-full bg-green-400 animate-blink-fast"></div>
            <span class="text-[11px] font-semibold text-gray-500">Live · Surabaya</span>
        </div>

        <div class="flex items-center gap-2">
            <button class="border border-gray-200 text-gray-500 rounded-lg px-3 py-1.5 text-xs font-semibold hover:bg-gray-50 transition-colors" onclick="toggleHistory()">📋 Histori Laporan</button>
            <button class="bg-blue text-white rounded-lg px-3.5 py-1.5 text-xs font-bold hover:bg-blue-dark transition-colors" onclick="openLapor()">+ Lapor Sekarang</button>
        </div>
    </nav>

    <div id="map"></div>

    <div class="float-panel fixed left-1/2 -translate-x-1/2 z-[500] w-[min(680px,calc(100vw-32px))] bg-white/97 backdrop-blur-lg border border-gray-200 rounded-2xl shadow-2xl overflow-hidden" style="top: calc(54px + 14px)">

        <div id="panelHeader" class="flex items-center justify-between px-4 py-3 cursor-pointer select-none border-b border-transparent hover:bg-gray-50/50 transition-colors" onclick="togglePanel()">
            <div class="flex items-center gap-2.5">
                <div class="w-2 h-2 rounded-full bg-danger-DEFAULT flex-shrink-0 animate-blink-slow" style="background:#DC2626"></div>
                <div>
                    <div class="text-[13px] font-bold" id="panel-headline">Memuat data terkini...</div>
                    <div class="text-[11px] text-gray-500 mt-0.5">Klik untuk membuka panel · Laporkan atau cek area Anda</div>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="text-center">
                        <div class="text-[15px] font-extrabold font-mono text-danger" id="stat-total" style="color:#DC2626">0</div>
                        <div class="text-[9px] text-gray-500 whitespace-nowrap">Laporan Aktif</div>
                    </div>
                    <div class="w-px h-7 bg-gray-200"></div>
                    <div class="text-center">
                        <div class="text-[15px] font-extrabold font-mono" id="stat-kel" style="color:#0D8C6E">0</div>
                        <div class="text-[9px] text-gray-500">Kelurahan</div>
                    </div>
                </div>
                <span class="chevron text-xs text-gray-500 ml-2" id="chevron">▼</span>
            </div>
        </div>

        <div class="panel-body" id="panelBody">
            <div class="p-4 flex flex-col gap-3">

                <div class="bg-red-50 border border-red-500 rounded-xl p-3 gap-2.5 items-start hidden" style="border-color:#DC2626;background:#FEE2E2" id="alert-box">
                    <span class="text-base flex-shrink-0 mt-0.5">🚨</span>
                    <p class="text-xs leading-relaxed" style="color:#7F1D1D" id="alert-text">Memuat detail...</p>
                </div>

                <div class="flex gap-1 bg-gray-100 rounded-xl p-1">
                    <button class="tab active flex-1 text-center py-1.5 text-xs font-semibold rounded-lg border-none cursor-pointer font-sans text-gray-500 transition-all" onclick="switchTab('lapor',this)">📋 Laporkan Masalah</button>
                    <button class="tab flex-1 text-center py-1.5 text-xs font-semibold rounded-lg border-none cursor-pointer font-sans text-gray-500 transition-all" onclick="switchTab('area',this)">🗺 Cek Area Saya</button>
                </div>

                <div class="subpanel active" id="sub-lapor">
                    <div class="grid grid-cols-4 gap-1.5 mb-2.5 max-[500px]:grid-cols-2">
                        <button class="cat-btn bg-gray-50 border-[1.5px] border-gray-200 rounded-xl py-2.5 px-1.5 cursor-pointer flex flex-col items-center gap-1 hover:border-blue hover:bg-blue-light transition-all w-full" onclick="selCat(this)">
                            <span class="text-lg">💧</span><span class="text-[10px] font-semibold text-gray-500 text-center leading-tight">Air Berbau</span>
                        </button>
                        <button class="cat-btn bg-gray-50 border-[1.5px] border-gray-200 rounded-xl py-2.5 px-1.5 cursor-pointer flex flex-col items-center gap-1 hover:border-blue hover:bg-blue-light transition-all w-full" onclick="selCat(this)">
                            <span class="text-lg">🟡</span><span class="text-[10px] font-semibold text-gray-500 text-center leading-tight">Air Keruh/Kotor</span>
                        </button>
                        <button class="cat-btn bg-gray-50 border-[1.5px] border-gray-200 rounded-xl py-2.5 px-1.5 cursor-pointer flex flex-col items-center gap-1 hover:border-blue hover:bg-blue-light transition-all w-full" onclick="selCat(this)">
                            <span class="text-lg">🚫</span><span class="text-[10px] font-semibold text-gray-500 text-center leading-tight">Air Mati</span>
                        </button>
                        <button class="cat-btn bg-gray-50 border-[1.5px] border-gray-200 rounded-xl py-2.5 px-1.5 cursor-pointer flex flex-col items-center gap-1 hover:border-blue hover:bg-blue-light transition-all w-full" onclick="selCat(this)">
                            <span class="text-lg">❓</span><span class="text-[10px] font-semibold text-gray-500 text-center leading-tight">Lainnya</span>
                        </button>
                    </div>
                    <div class="flex gap-2">
                        <select id="kel" class="flex-1 px-3 py-2 border-[1.5px] border-gray-200 rounded-lg font-sans text-xs text-gray-900 bg-white cursor-pointer focus:outline-none focus:border-blue transition-colors pr-8">
                            <option value="">Pilih Kelurahan...</option>
                            @foreach($areasWithCount ?? [] as $area)
                                <option value="{{ $area['id'] }}">{{ $area['kelurahan'] }}</option>
                            @endforeach
                        </select>
                        <button class="px-5 py-2 bg-blue text-white rounded-lg font-sans text-xs font-bold whitespace-nowrap hover:bg-blue-dark transition-colors" onclick="doSubmit()">Kirim Laporan →</button>
                    </div>
                </div>

                <div class="subpanel hidden" id="sub-area">
                    <div class="area-scroll flex flex-col gap-1.5 max-h-48 overflow-y-auto" id="area-list">
                        </div>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed bottom-5 right-4 z-[400] bg-white/95 backdrop-blur-md border border-gray-200 rounded-xl px-3.5 py-2.5 shadow-lg">
        <div class="text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-wide font-mono">Status Kualitas</div>
        <div class="flex flex-col gap-1.5">
            <div class="flex items-center gap-1.5 text-[11px] text-gray-900">
                <div class="w-2.5 h-2.5 rounded-sm bg-gray-500"></div>Normal (0)
            </div>
            <div class="flex items-center gap-1.5 text-[11px] text-gray-900">
                <div class="w-2.5 h-2.5 rounded-sm" style="background:#F59E0B"></div>Waspada (1–3)
            </div>
            <div class="flex items-center gap-1.5 text-[11px] text-gray-900">
                <div class="w-2.5 h-2.5 rounded-sm" style="background:#EF4444"></div>Darurat (>3)
            </div>
        </div>
    </div>

    <div id="historyPanel" class="fixed right-0 top-[54px] bottom-0 w-full max-w-[400px] bg-white shadow-2xl z-[1500] transform translate-x-full transition-transform duration-300 overflow-y-auto border-l border-gray-100">
        <div class="p-5">
            <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-3">
                <h2 class="text-lg font-bold text-gray-900">Histori Laporan Anda</h2>
                <button onclick="toggleHistory()" class="text-gray-400 hover:text-gray-900 text-2xl leading-none transition-colors">&times;</button>
            </div>

            @auth
                <div class="flex flex-col gap-4">
                    @forelse(auth()->user()->reports()->latest()->get() as $report)
                        <div class="border border-gray-100 rounded-xl p-4 hover:border-blue transition-colors bg-white shadow-sm hover:shadow-md">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-[10px] font-mono font-bold text-gray-400">#{{ substr($report->id, 0, 8) }}</span>
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase 
                                    @if($report->status == 'resolved') bg-green-100 text-green-700 
                                    @elseif($report->status == 'pending') bg-yellow-100 text-yellow-700 
                                    @else bg-blue-100 text-blue-700 @endif">
                                    {{ str_replace('_', ' ', $report->status) }}
                                </span>
                            </div>
                            <h3 class="font-bold text-sm text-gray-900 capitalize">{{ str_replace('_', ' ', $report->category) }}</h3>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $report->description }}</p>
                            <div class="mt-3 pt-3 border-t border-gray-50 flex justify-between items-center text-[10px] text-gray-400">
                                <span>{{ $report->created_at->diffForHumans() }}</span>
                                <a href="#" class="text-blue font-bold hover:underline">Lihat Detail &rarr;</a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16">
                            <span class="text-4xl block mb-3">📭</span>
                            <p class="text-gray-500 text-sm font-medium">Belum ada laporan yang Anda buat.</p>
                            <button onclick="toggleHistory(); openLapor();" class="mt-4 text-blue text-xs font-bold hover:underline">Buat Laporan Baru</button>
                        </div>
                    @endforelse
                </div>
            @else
                <div class="text-center py-16 bg-blue-50/50 rounded-2xl border border-dashed border-blue-200">
                    <span class="text-4xl mb-3 block">🔒</span>
                    <p class="text-sm text-blue-800 px-4">Silakan login untuk melihat riwayat laporan yang tertaut dengan akun Anda.</p>
                    <a href="{{ route('login') }}" class="inline-block mt-5 bg-blue text-white px-6 py-2.5 rounded-lg text-xs font-bold shadow hover:bg-blue-dark transition-colors">Login Sekarang</a>
                </div>
            @endauth
        </div>
    </div>

    <div class="toast bg-gray-900 text-white rounded-xl px-4 py-3 text-xs font-semibold flex items-center gap-2 shadow-xl whitespace-nowrap" id="toast">
        ✅ &nbsp;<span id="toast-msg">Laporan diterima!</span>
    </div>

    <script>
        window.appData = {
            areasWithCount: @json($areasWithCount ?? []),
            stats: {
                totalLaporan: {{ $totalLaporan ?? 0 }},
                worstArea: @json($worstArea ?? null)
            }
        };
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script>
        var mapObj, selCatEl = null, panelOpen = false;

        function togglePanel() {
            panelOpen = !panelOpen;
            document.getElementById('panelBody').classList.toggle('open', panelOpen);
            document.getElementById('chevron').classList.toggle('open', panelOpen);
        }

        function toggleHistory() {
            const panel = document.getElementById('historyPanel');
            if (!panel) {
                console.error("Elemen 'historyPanel' tidak ditemukan!");
                return;
            }
            panel.classList.toggle('translate-x-full');
        }

        function openLapor() {
            if (!panelOpen) togglePanel();
            switchTabById('lapor');
        }

        function switchTab(id, btn) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.subpanel').forEach(p => { p.classList.add('hidden'); p.classList.remove('active'); });
            btn.classList.add('active');
            var el = document.getElementById('sub-' + id);
            el.classList.remove('hidden');
            el.classList.add('active');
        }

        function switchTabById(id) {
            var tabs = document.querySelectorAll('.tab');
            tabs.forEach((t, i) => t.classList.toggle('active', (id === 'lapor' && i === 0) || (id === 'area' && i === 1)));
            document.querySelectorAll('.subpanel').forEach(p => { p.classList.add('hidden'); p.classList.remove('active'); });
            var el = document.getElementById('sub-' + id);
            el.classList.remove('hidden');
            el.classList.add('active');
        }

        function selCat(el) {
            document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
            el.classList.add('active');
            selCatEl = el;
        }

        function toast(msg) {
            var t = document.getElementById('toast');
            document.getElementById('toast-msg').textContent = msg;
            t.classList.add('show');
            setTimeout(() => t.classList.remove('show'), 4000);
        }

        function doSubmit() {
            var kel = document.getElementById('kel').value;
            if (!selCatEl) { toast('Pilih kategori masalah dulu.'); return; }
            if (!kel) { toast('Pilih kelurahan Anda.'); return; }
            toast('Laporan sedang dikirim ke instansi terkait...');
            // Disini Anda bisa menambahkan logic form submit POST ke /laporan jika diperlukan
        }

        function fly(coords) {
            mapObj.flyTo(coords, 14, { duration: 1.2 });
        }

        // --- MAP INIT ---
        mapObj = L.map('map', { center: [-7.257, 112.752], zoom: 12, zoomControl: false });
        L.control.zoom({ position: 'bottomleft' }).addTo(mapObj);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(mapObj);

        // --- TANGKAP DATA DARI BACKEND ---
        var areasData = window.appData.areasWithCount;
        var statsData = window.appData.stats;
        var dynamicReportData = {};

        // Konversi ke format yang mudah dicari
        areasData.forEach(function(area) {
            dynamicReportData[area.kelurahan] = {
                n: area.count,
                d: area.latest_desc || 'Gangguan dilaporkan',
                lat: area.lat,
                lng: area.lng
            };
        });

        function getCaseInsensitiveData(name) {
            var keys = Object.keys(dynamicReportData);
            var match = keys.find(k => k.toLowerCase() === name.toLowerCase());
            return match ? dynamicReportData[match] : null;
        }

        // --- LOAD GEOJSON KELURAHAN ---
        fetch('35.78_kelurahan_simple.geojson') 
            .then(res => res.json())
            .then(geojsonData => {
                
                var geoLayer = L.geoJSON(geojsonData, {
                    style: function(feature) {
                        var name = feature.properties.nm_kelurahan;
                        var info = getCaseInsensitiveData(name) || { n: 0 };
                        var n = info.n;
                        var fillColor, strokeColor, op;

                        if (n === 0) {
                            fillColor = '#6B7280'; strokeColor = '#4B5563'; op = 0.1;
                        } else if (n <= 3) {
                            fillColor = '#F59E0B'; strokeColor = '#D97706'; op = 0.4;
                        } else {
                            fillColor = '#EF4444'; strokeColor = '#991B1B'; op = 0.65;
                        }

                        return {
                            fillColor: fillColor, color: strokeColor,
                            weight: n > 3 ? 2 : 1, opacity: 1, fillOpacity: op,
                            dashArray: n === 0 ? '3, 3' : '0'
                        };
                    },
                    onEachFeature: function(feature, layer) {
                        var name = feature.properties.nm_kelurahan;
                        var info = getCaseInsensitiveData(name) || { n: 0, d: 'Tidak ada laporan' };
                        
                        var statusLabel = info.n === 0 ? '⚪ Normal' : (info.n <= 3 ? '🟡 Waspada' : '🔴 Darurat');
                        var statusColor = info.n === 0 ? '#6B7280' : (info.n <= 3 ? '#D97706' : '#DC2626');

                        var pop = `<div style="padding:14px;font-family:Plus Jakarta Sans,sans-serif;min-width:210px">
                            <div style="font-size:14px;font-weight:800;margin-bottom:2px">Kel. ${name}</div>
                            <div style="font-size:11px;color:#666;margin-bottom:10px">Surabaya</div>
                            <div style="font-size:11px;border-top:1px solid #f0f0f0;padding-top:8px">
                                <div style="display:flex;justify-content:space-between;padding:4px 0"><span style="color:#666">Status</span><span style="font-weight:700;color:${statusColor}">${statusLabel}</span></div>
                                <div style="display:flex;justify-content:space-between;padding:4px 0"><span style="color:#666">Laporan</span><span style="font-weight:700;color:${statusColor}">${info.n} laporan</span></div>
                                ${info.n > 0 ? `<div style="padding:4px 0;color:#444;line-height:1.4;font-style:italic">"${info.d}"</div>` : ''}
                            </div>
                        </div>`;
                        
                        layer.bindPopup(pop, { maxWidth: 260 });
                        layer.on('mouseover', function() { this.setStyle({ fillOpacity: 0.8, weight: 2 }); });
                        layer.on('mouseout', function() { geoLayer.resetStyle(this); });
                    }
                }).addTo(mapObj);

                // --- UPDATE STATISTIK UI ---
                document.getElementById('stat-total').innerText = statsData.totalLaporan;
                document.getElementById('stat-kel').innerText = areasData.filter(a => a.count > 0).length;

                var alertBox = document.getElementById('alert-box');
                if (statsData.worstArea && statsData.worstArea.count > 0) {
                    document.getElementById('panel-headline').innerText = `⚠ Kel. ${statsData.worstArea.kelurahan} — ${statsData.worstArea.count} laporan aktif`;
                    document.getElementById('alert-text').innerHTML = `<strong class="font-bold">Waspada — Kel. ${statsData.worstArea.kelurahan}.</strong> ${statsData.worstArea.count} laporan masuk terkait: ${statsData.worstArea.latest_desc}. Harap warga berhati-hati.`;
                    alertBox.style.display = 'flex';
                } else {
                    document.getElementById('panel-headline').innerText = `✅ Kondisi Kualitas Air Normal`;
                    alertBox.style.display = 'none';
                }

                // --- UPDATE LIST AREA (Diurutkan dari laporan terbanyak) ---
                var areaListEl = document.getElementById('area-list');
                areaListEl.innerHTML = '';
                var sortedAreas = [...areasData].sort((a, b) => b.count - a.count).filter(a => a.count > 0);

                if(sortedAreas.length === 0) {
                    areaListEl.innerHTML = '<div class="text-xs text-gray-500 text-center py-4">Semua wilayah dalam kondisi aman.</div>';
                }

                sortedAreas.forEach(function(area) {
                    var color = area.count > 3 ? '#EF4444' : '#F59E0B';
                    var textColor = area.count > 3 ? '#DC2626' : '#D97706';

                    var div = document.createElement('div');
                    div.className = 'bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 flex items-center gap-2.5 cursor-pointer hover:border-blue-400 transition-all';
                    div.onclick = function() { fly([area.lat, area.lng]); };

                    div.innerHTML =
                        '<div class="w-2 h-2 rounded-full flex-shrink-0" style="background:' + color + '"></div>' +
                        '<div class="flex-1 min-w-0">' +
                            '<div class="text-xs font-bold truncate">' + area.kelurahan + '</div>' +
                            '<div class="text-[10px] text-gray-500 mt-0.5 truncate">' + (area.latest_desc || 'Gangguan air') + '</div>' +
                        '</div>' +
                        '<div class="text-[17px] font-extrabold font-mono" style="color:' + textColor + '">' + area.count + '</div>';
                        
                    areaListEl.appendChild(div);
                });

                // Auto-fit bounds
                var bounds = geoLayer.getBounds();
                if(bounds.isValid()){
                    mapObj.fitBounds(bounds);
                }
            });
    </script>
</body>
</html>