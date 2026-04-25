<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
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

    /* Category button active state */
    .cat-btn.active { border-color: #1A6BCC; background: #EBF3FF; }

    /* Panel body collapse */
    .panel-body { max-height: 0; overflow: hidden; transition: max-height 0.35s ease; }
    .panel-body.open { max-height: 520px; }

    /* Chevron */
    .chevron { transition: transform 0.25s; }
    .chevron.open { transform: rotate(180deg); }

    /* Toast */
    .toast {
        position: fixed; bottom: 20px; left: 50%;
        transform: translateX(-50%) translateY(80px);
        opacity: 0; transition: all 0.3s ease; z-index: 2000;
    }
    .toast.show { transform: translateX(-50%) translateY(0); opacity: 1; }

    /* Select arrow */
    select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%23666' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        appearance: none;
    }

    /* Leaflet popup */
    .leaflet-popup-content-wrapper { border-radius: 10px !important; padding: 0 !important; overflow: hidden; }
    .leaflet-popup-content { margin: 0 !important; }

    /* Scrollbar */
    .area-scroll::-webkit-scrollbar { width: 3px; }
    .area-scroll::-webkit-scrollbar-thumb { background: #E8EAED; border-radius: 3px; }

    /* Tab active */
    .tab.active { background: #fff; color: #1A6BCC; box-shadow: 0 1px 4px rgba(0,0,0,.08); }

    /* Float panel transition */
    .float-panel { transition: all 0.3s ease; }
    </style>
</head>
<body class="bg-slate-200 font-sans text-gray-900">

    <!-- NAV -->
    <nav class="fixed top-0 left-0 right-0 z-[1000] bg-white/97 backdrop-blur-md border-b border-gray-200 flex items-center justify-between px-5 h-[54px]">
    <!-- Logo -->
    <div class="flex items-center gap-2">
        <div class="w-[30px] h-[30px] rounded-lg bg-gradient-to-br from-blue to-teal flex items-center justify-center text-xs text-white font-extrabold font-mono">AL</div>
        <span class="font-extrabold text-[17px] tracking-tight">Air<span class="text-blue">Layak</span></span>
    </div>

    <!-- Center badges -->
    <div class="absolute left-1/2 -translate-x-1/2 flex items-center gap-2">
        <div class="w-1.5 h-1.5 rounded-full bg-green-400 animate-blink-fast"></div>
        <span class="text-[11px] font-semibold text-gray-500">Live · Surabaya</span>
        <span class="font-mono text-[10px] bg-danger text-white px-2.5 py-0.5 rounded-full animate-blink">⚠ 3 Cluster Aktif</span>
    </div>

    <!-- Right buttons -->
    <div class="flex items-center gap-2">
        <button class="border border-gray-200 text-gray-500 rounded-lg px-3 py-1.5 text-xs font-semibold hover:bg-gray-50 transition-colors" onclick="togglePanel()">📋 Laporan &amp; Info</button>
        <button class="bg-blue text-white rounded-lg px-3.5 py-1.5 text-xs font-bold hover:bg-blue-dark transition-colors" onclick="openLapor()">+ Lapor Sekarang</button>
    </div>
    </nav>

    <!-- FULLSCREEN MAP -->
    <div id="map"></div>

    <!-- FLOATING PANEL -->
    <div class="float-panel fixed left-1/2 -translate-x-1/2 z-[500] w-[min(680px,calc(100vw-32px))] bg-white/97 backdrop-blur-lg border border-gray-200 rounded-2xl shadow-2xl overflow-hidden" style="top: calc(54px + 14px)">

    <!-- Header (always visible) -->
    <div id="panelHeader" class="flex items-center justify-between px-4 py-3 cursor-pointer select-none border-b border-transparent hover:bg-gray-50/50 transition-colors" onclick="togglePanel()">
        <div class="flex items-center gap-2.5">
        <div class="w-2 h-2 rounded-full bg-danger-DEFAULT flex-shrink-0 animate-blink-slow" style="background:#DC2626"></div>
        <div>
            <div class="text-[13px] font-bold">⚠ Kec. Semampir — 5 laporan bau menyengat dalam 24 jam</div>
            <div class="text-[11px] text-gray-500 mt-0.5">Klik untuk membuka dashboard · Laporkan atau cek area Anda</div>
        </div>
        </div>
        <div class="flex items-center gap-4">
        <!-- Stats -->
        <div class="flex items-center gap-3">
            <div class="text-center">
            <div class="text-[15px] font-extrabold font-mono text-danger" style="color:#DC2626">18</div>
            <div class="text-[9px] text-gray-500 whitespace-nowrap">Laporan</div>
            </div>
            <div class="w-px h-7 bg-gray-200"></div>
            <div class="text-center">
            <div class="text-[15px] font-extrabold font-mono" style="color:#D97706">3</div>
            <div class="text-[9px] text-gray-500">Cluster</div>
            </div>
            <div class="w-px h-7 bg-gray-200"></div>
            <div class="text-center">
            <div class="text-[15px] font-extrabold font-mono" style="color:#0D8C6E">12</div>
            <div class="text-[9px] text-gray-500">Kelurahan</div>
            </div>
        </div>
        <span class="chevron text-xs text-gray-500 ml-2" id="chevron">▼</span>
        </div>
    </div>

    <!-- Collapsible body -->
    <div class="panel-body" id="panelBody">
        <div class="p-4 flex flex-col gap-3">

        <!-- Alert -->
        <div class="bg-red-50 border border-red-500 rounded-xl p-3 flex gap-2.5 items-start" style="border-color:#DC2626;background:#FEE2E2">
            <span class="text-base flex-shrink-0 mt-0.5">🚨</span>
            <p class="text-xs leading-relaxed" style="color:#7F1D1D"><strong class="font-bold">Cluster Alert Aktif — Kec. Semampir, Surabaya.</strong> 5 laporan bau menyengat terdeteksi dalam radius 500m / 24 jam. Kemungkinan ada gangguan distribusi PDAM Surya. Warga disarankan tidak mengonsumsi air langsung.</p>
        </div>

        <!-- Tabs -->
        <div class="flex gap-1 bg-gray-100 rounded-xl p-1">
            <button class="tab active flex-1 text-center py-1.5 text-xs font-semibold rounded-lg border-none cursor-pointer font-sans text-gray-500 transition-all" onclick="switchTab('lapor',this)">📋 Laporkan Masalah</button>
            <button class="tab flex-1 text-center py-1.5 text-xs font-semibold rounded-lg border-none cursor-pointer font-sans text-gray-500 transition-all" onclick="switchTab('area',this)">🗺 Cek Area Saya</button>
        </div>

        <!-- REPORT FORM -->
        <div class="subpanel active" id="sub-lapor">
            <!-- Category grid -->
            <div class="grid grid-cols-4 gap-1.5 mb-2.5 max-[500px]:grid-cols-2">
            <button class="cat-btn bg-gray-50 border-[1.5px] border-gray-200 rounded-xl py-2.5 px-1.5 cursor-pointer flex flex-col items-center gap-1 hover:border-blue hover:bg-blue-light transition-all w-full" onclick="selCat(this)">
                <span class="text-lg">💧</span><span class="text-[10px] font-semibold text-gray-500 text-center leading-tight">Bau Menyengat</span>
            </button>
            <button class="cat-btn bg-gray-50 border-[1.5px] border-gray-200 rounded-xl py-2.5 px-1.5 cursor-pointer flex flex-col items-center gap-1 hover:border-blue hover:bg-blue-light transition-all w-full" onclick="selCat(this)">
                <span class="text-lg">🟡</span><span class="text-[10px] font-semibold text-gray-500 text-center leading-tight">Air Berubah Warna</span>
            </button>
            <button class="cat-btn bg-gray-50 border-[1.5px] border-gray-200 rounded-xl py-2.5 px-1.5 cursor-pointer flex flex-col items-center gap-1 hover:border-blue hover:bg-blue-light transition-all w-full" onclick="selCat(this)">
                <span class="text-lg">🤢</span><span class="text-[10px] font-semibold text-gray-500 text-center leading-tight">Sakit / Diare</span>
            </button>
            <button class="cat-btn bg-gray-50 border-[1.5px] border-gray-200 rounded-xl py-2.5 px-1.5 cursor-pointer flex flex-col items-center gap-1 hover:border-blue hover:bg-blue-light transition-all w-full" onclick="selCat(this)">
                <span class="text-lg">❓</span><span class="text-[10px] font-semibold text-gray-500 text-center leading-tight">Lainnya</span>
            </button>
            </div>
            <!-- Form row -->
            <div class="flex gap-2">
            <select id="kel" class="flex-1 px-3 py-2 border-[1.5px] border-gray-200 rounded-lg font-sans text-xs text-gray-900 bg-white cursor-pointer focus:outline-none focus:border-blue transition-colors pr-8">
                <option value="">Pilih Kelurahan...</option>
                <option>Semampir</option>
                <option>Tambaksari</option>
                <option>Kenjeran</option>
                <option>Bubutan</option>
                <option>Wonokromo</option>
                <option>Tenggilis Mejoyo</option>
                <option>Mulyorejo</option>
                <option>Rungkut</option>
            </select>
            <button class="px-5 py-2 bg-blue text-white rounded-lg font-sans text-xs font-bold whitespace-nowrap hover:bg-blue-dark transition-colors" onclick="doSubmit()">Kirim Anonim →</button>
            </div>
        </div>

        <!-- AREA LIST -->
        <div class="subpanel hidden" id="sub-area">
            <div class="area-scroll flex flex-col gap-1.5 max-h-48 overflow-y-auto">

            <!-- Red areas -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 flex items-center gap-2.5 cursor-pointer hover:border-blue hover:bg-blue-light transition-all" onclick="fly([-7.223,112.748])">
                <div class="w-2 h-2 rounded-full flex-shrink-0" style="background:#EF4444"></div>
                <div class="flex-1">
                <div class="text-xs font-bold">Semampir</div>
                <div class="text-[10px] text-gray-500 mt-0.5">3× bau menyengat, 2× air keruh</div>
                </div>
                <div class="text-[17px] font-extrabold font-mono" style="color:#DC2626">5</div>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 flex items-center gap-2.5 cursor-pointer hover:border-blue hover:bg-blue-light transition-all" onclick="fly([-7.247,112.762])">
                <div class="w-2 h-2 rounded-full flex-shrink-0" style="background:#EF4444"></div>
                <div class="flex-1">
                <div class="text-xs font-bold">Tambaksari</div>
                <div class="text-[10px] text-gray-500 mt-0.5">2× sakit perut, 2× air berbau</div>
                </div>
                <div class="text-[17px] font-extrabold font-mono" style="color:#DC2626">4</div>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 flex items-center gap-2.5 cursor-pointer hover:border-blue hover:bg-blue-light transition-all" onclick="fly([-7.225,112.775])">
                <div class="w-2 h-2 rounded-full flex-shrink-0" style="background:#EF4444"></div>
                <div class="flex-1">
                <div class="text-xs font-bold">Kenjeran</div>
                <div class="text-[10px] text-gray-500 mt-0.5">3× air berwarna kuning</div>
                </div>
                <div class="text-[17px] font-extrabold font-mono" style="color:#DC2626">3</div>
            </div>

            <!-- Amber areas -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 flex items-center gap-2.5 cursor-pointer hover:border-blue hover:bg-blue-light transition-all" onclick="fly([-7.265,112.730])">
                <div class="w-2 h-2 rounded-full flex-shrink-0" style="background:#F59E0B"></div>
                <div class="flex-1">
                <div class="text-xs font-bold">Tegalsari</div>
                <div class="text-[10px] text-gray-500 mt-0.5">2× berbau klorin</div>
                </div>
                <div class="text-[17px] font-extrabold font-mono" style="color:#D97706">2</div>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 flex items-center gap-2.5 cursor-pointer hover:border-blue hover:bg-blue-light transition-all" onclick="fly([-7.240,112.730])">
                <div class="w-2 h-2 rounded-full flex-shrink-0" style="background:#F59E0B"></div>
                <div class="flex-1">
                <div class="text-xs font-bold">Bubutan</div>
                <div class="text-[10px] text-gray-500 mt-0.5">1× bau, 1× warna</div>
                </div>
                <div class="text-[17px] font-extrabold font-mono" style="color:#D97706">2</div>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 flex items-center gap-2.5 cursor-pointer hover:border-blue hover:bg-blue-light transition-all" onclick="fly([-7.298,112.735])">
                <div class="w-2 h-2 rounded-full flex-shrink-0" style="background:#F59E0B"></div>
                <div class="flex-1">
                <div class="text-xs font-bold">Wonokromo</div>
                <div class="text-[10px] text-gray-500 mt-0.5">1× air keruh</div>
                </div>
                <div class="text-[17px] font-extrabold font-mono" style="color:#D97706">1</div>
            </div>

            <!-- Gray -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 flex items-center gap-2.5 cursor-pointer hover:border-blue hover:bg-blue-light transition-all" onclick="fly([-7.318,112.780])">
                <div class="w-2 h-2 rounded-full flex-shrink-0 bg-gray-400"></div>
                <div class="flex-1">
                <div class="text-xs font-bold">Rungkut</div>
                <div class="text-[10px] text-gray-500 mt-0.5">Tidak ada laporan</div>
                </div>
                <div class="text-[17px] font-extrabold font-mono text-gray-400">0</div>
            </div>
        </div>
        </div>
    </div>
</div>
</div>

    <!-- LEGEND BOTTOM RIGHT -->
    <div class="fixed bottom-5 right-4 z-[400] bg-white/95 backdrop-blur-md border border-gray-200 rounded-xl px-3.5 py-2.5 shadow-lg">
    <div class="text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-wide font-mono">Status Air</div>
    <div class="flex flex-col gap-1.5">
        <div class="flex items-center gap-1.5 text-[11px] text-gray-900">
        <div class="w-2.5 h-2.5 rounded-sm bg-gray-500"></div>Normal
    </div>
    <div class="flex items-center gap-1.5 text-[11px] text-gray-900">
        <div class="w-2.5 h-2.5 rounded-sm" style="background:#F59E0B"></div>Waspada (1–2)
    </div>
    <div class="flex items-center gap-1.5 text-[11px] text-gray-900">
        <div class="w-2.5 h-2.5 rounded-sm" style="background:#EF4444"></div>Cluster Alert (3+)
    </div>
</div>
</div>

    <!-- TOAST -->
    <div class="toast bg-gray-900 text-white rounded-xl px-4 py-3 text-xs font-semibold flex items-center gap-2 shadow-xl whitespace-nowrap" id="toast">
    ✅ &nbsp;<span id="toast-msg">Laporan diterima!</span>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script>
        var mapObj, selCatEl = null, panelOpen = false;

        function togglePanel() {
            panelOpen = !panelOpen;
            document.getElementById('panelBody').classList.toggle('open', panelOpen);
            document.getElementById('chevron').classList.toggle('open', panelOpen);
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
            toast('Laporan diterima! Jika ada 2 laporan lain di area kamu, kami akan mengeluarkan peringatan.');
        }

        function fly(coords) {
            mapObj.flyTo(coords, 14, { duration: 1.2 });
        }

        // 1. Inisialisasi Peta
        // 1. Inisialisasi Peta
mapObj = L.map('map', { center: [-7.257, 112.752], zoom: 12, zoomControl: false });
L.control.zoom({ position: 'bottomleft' }).addTo(mapObj);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(mapObj);

// 2. Data Status Tahap Kelurahan
// Saya telah menambah lebih banyak data kelurahan untuk visualisasi yang lebih padat
var reportData = {
    // === 🔴 CLUSTER ALERT (15 AREA - KRITIS > 3 LAPORAN) ===
    'Semampir': { n: 25, d: 'KRITIS: Kontaminasi limbah industri; air berwarna hitam & berminyak' },
    'Sidotopo': { n: 20, d: 'DARURAT: Wabah gatal-gatal; air berbau bangkai pekat' },
    'Putat Jaya': { n: 18, d: 'Pipa Utama Pecah: Air bercampur lumpur hitam & cacing' },
    'Gading': { n: 15, d: 'Klorin Berlebih: Bau menyengat & mata perih di 3 blok' },
    'Kaliasin': { n: 14, d: 'Mati Air Total (18 Jam): Warga mulai kesulitan air bersih' },
    'Morokrembangan': { n: 13, d: 'Air Berbusa Deterjen: Beracun jika dikonsumsi' },
    'Wonokromo': { n: 12, d: 'Waspada Diare: 10 warga dilarikan ke puskesmas' },
    'Nyamplungan': { n: 11, d: 'Intrusi Air Laut: Air terasa sangat asin & keruh' },
    'Tembok Dukuh': { n: 10, d: 'Air Berkarat: Warna oranye pekat, merusak filter' },
    'Kapasari': { n: 9,  d: 'Ditemukan larva & jentik nyamuk massal di saluran' },
    'Sawahan': { n: 8,  d: 'Air berbau sulfur/belerang sangat tajam' },
    'Pabean Cantian': { n: 7, d: 'Pasokan macet; air keluar hanya berupa udara' },
    'Bubutan': { n: 6,  d: 'Endapan lumpur pasir menyumbat semua keran' },
    'Tegalsari': { n: 5, d: 'Air berlendir & sulit dibilas sabun' },
    'Genteng': { n: 4,  d: 'Rasa logam tajam; air meninggalkan bekas hitam' },

    // === 🟡 WASPADA (60 AREA - GANGGUAN 1-3 LAPORAN) ===
    'Siwalankerto': { n: 3, d: 'Tekanan air menurun drastis' },
    'Menur Pumpungan': { n: 2, d: 'Air berbau tanah saat pagi' },
    'Keputih': { n: 1, d: 'Sedikit endapan kapur' },
    'Klampis Ngasem': { n: 3, d: 'Air berwarna kekuningan ringan' },
    'Wonorejo': { n: 2, d: 'Aliran air tersendat-sendat' },
    'Medokan Ayu': { n: 3, d: 'Air terasa agak pahit' },
    'Ketabang': { n: 1, d: 'Bau kaporit tipis' },
    'Sukolilo': { n: 2, d: 'Air berminyak di permukaan' },
    'Gubeng': { n: 3, d: 'Keruh saat jam sibuk sore' },
    'Embong Kaliasin': { n: 1, d: 'Laporan bau besi ringan' },
    'Baratajaya': { n: 2, d: 'Ditemukan pasir halus' },
    'Airlangga': { n: 1, d: 'Tekanan air tidak stabil' },
    'Mulyorejo': { n: 3, d: 'Air berbau amis samar' },
    'Tandes': { n: 2, d: 'Warna coklat teh transparan' },
    'Suko Manunggal': { n: 1, d: 'Mati air singkat 30 menit' },
    'Rungkut Kidul': { n: 3, d: 'Laporan air berpasir' },
    'Kalirungkut': { n: 2, d: 'Debit air kecil di lantai 2' },
    'Kedung Baruk': { n: 3, d: 'Air berkapur putih' },
    'Penjaringan Sari': { n: 1, d: 'Air berbau tanah' },
    'Gunung Anyar': { n: 2, d: 'Endapan coklat di bak' },
    'Jambangan': { n: 1, d: 'Laporan air berasa' },
    'Karah': { n: 2, d: 'Keruh setelah perbaikan pipa' },
    'Kebonsari': { n: 3, d: 'Bau lumpur tipis' },
    'Gayungan': { n: 1, d: 'Air keluar udara saja' },
    'Menanggal': { n: 2, d: 'Laporan air agak panas' },
    'Dukuh Pakis': { n: 3, d: 'Air berbau klorin' },
    'Pradah Kalikendal': { n: 1, d: 'Debit air mengecil' },
    'Gunung Sari': { n: 2, d: 'Air berwarna kuning' },
    'Lontar': { n: 3, d: 'Pasir halus di keran' },
    'Sambikerep': { n: 2, d: 'Bau tanah menyengat' },
    'Made': { n: 1, d: 'Air keruh sedikit' },
    'Lakarsantri': { n: 3, d: 'Laporan air berminyak' },
    'Jeruk': { n: 2, d: 'Air berasa pahit' },
    'Benowo': { n: 1, d: 'Tekanan air rendah' },
    'Sememi': { n: 3, d: 'Endapan putih/kapur' },
    'Kandangan': { n: 2, d: 'Air berbau besi' },
    'Tambak Osowilangun': { n: 1, d: 'Laporan air payau' },
    'Romokalisari': { n: 2, d: 'Air agak keruh' },
    'Banjarsugihan': { n: 3, d: 'Laporan cacing 1 titik' },
    'Manukan Kulon': { n: 1, d: 'Bau lumpur pagi hari' },
    'Manukan Wetan': { n: 2, d: 'Debit air kecil sore hari' },
    'Balongsari': { n: 3, d: 'Air kuning transparan' },
    'Kupang Krajan': { n: 1, d: 'Laporan bau besi' },
    'Petemon': { n: 2, d: 'Air berpasir halus' },
    'Banyu Urip': { n: 3, d: 'Mati air berulang' },
    'Putat Gede': { n: 1, d: 'Sedikit berminyak' },
    'Sonokwijenan': { n: 2, d: 'Tekanan menurun' },
    'Simomulyo': { n: 3, d: 'Warna coklat muda' },
    'Gading': { n: 2, d: 'Bau kaporit' },
    'Kenjeran': { n: 3, d: 'Laporan air asin' },
    'Bulak': { n: 1, d: 'Air keruh sedikit' },
    'Kedung Cowek': { n: 2, d: 'Endapan pasir' },
    'Tanah Kali Kedinding': { n: 3, d: 'Air berbau amis' },
    'Sidotopo Wetan': { n: 1, d: 'Debit kecil' },
    'Bulak Banteng': { n: 2, d: 'Warna kuning teh' },
    'Tambak Wedi': { n: 3, d: 'Air payau ringan' },
    'Ujung': { n: 1, d: 'Bau lumpur' },
    'Perak Utara': { n: 2, d: 'Air berminyak' },
    'Perak Timur': { n: 3, d: 'Endapan karat' },
    'Krembangan Utara': { n: 1, d: 'Laporan air berbau' }
};

var styleConfig = {
    cluster: { fill: '#EF4444', stroke: '#DC2626', op: 0.6 },
    waspada: { fill: '#F59E0B', stroke: '#D97706', op: 0.4 },
    normal:  { fill: '#6B7280', stroke: '#4B5563', op: 0.1 }
};

// 3. Load GeoJSON Kelurahan
// 3. Load GeoJSON Kelurahan
fetch('35.78_kelurahan_simple.geojson') 
    .then(res => res.json())
    .then(geojsonData => {
        
        // PERBAIKAN: Definisikan geoLayer agar .getBounds() tidak error
        var geoLayer = L.geoJSON(geojsonData, {
            style: function(feature) {
                var name = feature.properties.nm_kelurahan;
                var info = reportData[name] || { n: 0 };
                var n = info.n;

                var fillColor, strokeColor, op;

                if (n === 0) {
                    fillColor = '#6B7280'; strokeColor = '#4B5563'; op = 0.1;
                } else if (n <= 3) {
                    fillColor = '#F59E0B'; strokeColor = '#D97706'; op = 0.4;
                } else {
                    // Gradasi Merah ke Maroon
                    if (n > 15) fillColor = '#7F1D1D';
                    else if (n > 10) fillColor = '#991B1B';
                    else if (n > 6) fillColor = '#B91C1C';
                    else fillColor = '#EF4444';
                    
                    strokeColor = '#450a0a'; // Stroke coklat gelap untuk area kritis
                    op = 0.7;
                }

                return {
                    fillColor: fillColor,
                    color: strokeColor,
                    weight: n > 3 ? 2 : 1,
                    opacity: 1,
                    fillOpacity: op,
                    dashArray: n === 0 ? '3, 3' : '0'
                };
            },
            onEachFeature: function(feature, layer) {
                var name = feature.properties.nm_kelurahan;
                var info = reportData[name] || { n: 0, d: 'Tiada laporan aktif' };
                
                // Tentukan status secara dinamis untuk Popup
                var statusLabel, statusColor;
                if (info.n === 0) {
                    statusLabel = '⚪ Normal'; statusColor = '#6B7280';
                } else if (info.n <= 3) {
                    statusLabel = '🟡 Waspada'; statusColor = '#D97706';
                } else {
                    statusLabel = '🔴 Cluster Alert'; statusColor = '#DC2626';
                }

                var pop = `<div style="padding:14px;font-family:Plus Jakarta Sans,sans-serif;min-width:210px">
                    <div style="font-size:14px;font-weight:800;margin-bottom:2px">Kel. ${name}</div>
                    <div style="font-size:11px;color:#666;margin-bottom:10px">Surabaya, Jawa Timur</div>
                    <div style="font-size:11px;border-top:1px solid #f0f0f0;padding-top:8px">
                        <div style="display:flex;justify-content:space-between;padding:4px 0"><span style="color:#666">Status</span><span style="font-weight:700;color:${statusColor}">${statusLabel}</span></div>
                        <div style="display:flex;justify-content:space-between;padding:4px 0"><span style="color:#666">Laporan (24j)</span><span style="font-weight:700;color:${statusColor}">${info.n} case</span></div>
                        <div style="padding:4px 0;color:#444;line-height:1.4;font-style:italic">"${info.d}"</div>
                    </div>
                    <a href="#" style="display:block;margin-top:8px;width:100%;background:#1A6BCC;color:#fff;border-radius:7px;padding:8px;font-size:11px;font-weight:700;text-align:center;text-decoration:none">Tindakan Kecemasan →</a>
                </div>`;
                
                layer.bindPopup(pop, { maxWidth: 260 });
                
                // Efek Hover
                layer.on('mouseover', function() {
                    this.setStyle({ fillOpacity: 0.9, weight: 3 });
                });
                layer.on('mouseout', function() {
                    geoLayer.resetStyle(this); // Cara termudah mengembalikan style awal
                });
            }
        }).addTo(mapObj);

        // --- PEMBATASAN MAP ---
        var surabayaBounds = geoLayer.getBounds();
        mapObj.fitBounds(surabayaBounds);
        mapObj.setMaxBounds(surabayaBounds.pad(0.5));
        mapObj.options.maxBoundsViscosity = 0.5;
        mapObj.options.minZoom = mapObj.getZoom() - 1;
    });
        </script>
    </body>
</html>