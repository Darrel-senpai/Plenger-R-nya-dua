<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Space+Mono:wght@700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">

    @vite(['resources/css/app.css', 'resources/css/homepage.css', 'resources/js/app.js', 'resources/js/homepage.js'])

</head>

<body class="bg-slate-200 font-sans text-gray-900">

    <!-- NAV -->

    <nav class="fixed top-0 left-0 right-0 z-[1000] bg-white backdrop-blur-md border-b border-gray-200 flex items-center justify-between px-5 h-[54px]">
    <!-- Logo -->
    <div class="flex items-center gap-2">
        <img src="{{ asset('logo.jpeg') }}" alt="AirWarga Logo" class="h-[30px] w-auto">
        <span class="font-extrabold text-[17px] tracking-tight">Air<span class="text-blue">Warga</span></span>
    </div>

        <!-- Center badges -->
        <div class="absolute left-1/2 -translate-x-1/2 flex items-center gap-2">
            <div class="w-1.5 h-1.5 rounded-full bg-green-400 animate-blink-fast"></div>
            <span class="text-[11px] font-semibold text-gray-500">Live · Surabaya</span>
            <span id="nav-cluster-badge"
                class="font-mono text-[10px] bg-danger text-white px-2.5 py-0.5 rounded-full animate-blink"></span>
        </div>

        <!-- Right buttons -->
        <div class="flex items-center gap-2">
            <button
                class="border border-gray-200 text-gray-500 rounded-lg px-3 py-1.5 text-xs font-semibold hover:bg-gray-50 transition-colors"
                onclick="togglePanel()">📋 Laporan &amp; Info</button>
            <button
                class="bg-blue-600 text-white rounded-lg px-3.5 py-1.5 text-xs font-bold hover:bg-blue-800 transition-colors"
                onclick="openLapor()">+ Lapor Sekarang</button>
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
            <div id="panel-headline" class="text-[13px] font-bold"></div>
            <div id="panel-subline" class="text-[11px] text-gray-500 mt-0.5"></div>
        </div>
        </div>
        <div class="flex items-center gap-4">
        <!-- Stats -->
        <div class="flex items-center gap-3">
            <div class="text-center">
            <div id="stat-total" class="text-[15px] font-extrabold font-mono text-danger" style="color:#DC2626"></div>
            <div class="text-[9px] text-gray-500 whitespace-nowrap">Laporan</div>
            </div>
            <div class="w-px h-7 bg-gray-200"></div>
            <div class="text-center">
            <div id="stat-cluster" class="text-[15px] font-extrabold font-mono" style="color:#D97706"></div>
            <div class="text-[9px] text-gray-500">Cluster</div>
            </div>
            <div class="w-px h-7 bg-gray-200"></div>
            <div class="text-center">
            <div id="stat-kel" class="text-[15px] font-extrabold font-mono" style="color:#0D8C6E"></div>
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
            <p id="alert-text" class="text-xs leading-relaxed" style="color:#7F1D1D"></p>
        </div>

        <!-- Tabs -->
        <div class="flex gap-1 bg-gray-100 rounded-xl p-1">
            <button class="tab active flex-1 text-center py-1.5 text-xs font-semibold rounded-lg border-none cursor-pointer font-sans text-gray-500 transition-all" onclick="switchTab('lapor',this)">Filter</button>
            <button class="tab flex-1 text-center py-1.5 text-xs font-semibold rounded-lg border-none cursor-pointer font-sans text-gray-500 transition-all" onclick="switchTab('area',this)">Cek Area Saya</button>
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
            <button class="px-5 py-2 bg-blue text-white rounded-lg font-sans text-xs font-bold whitespace-nowrap hover:bg-blue-dark transition-colors" onclick="doFilter()">Search</button>
            </div>
        </div>

        {{-- <!-- AREA LIST -->
        <div class="subpanel hidden" id="sub-area">
            <div class="area-scroll flex flex-col gap-1.5 max-h-48 overflow-y-auto">

            <!-- Red areas -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 flex items-center gap-2.5 cursor-pointer hover:border-blue hover:bg-blue-light transition-all" onclick="fly([-7.223,112.748])">
                <div class="w-2 h-2 rounded-full flex-shrink-0" style="background:#EF4444"></div>
                <div class="flex-1">
                <div class="text-xs font-bold">Semampir</div>
                <div class="text-[10px] text-gray-500 mt-0.5">3× bau menyengat, 2× air keruh</div>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <!-- Stats -->
                <div class="flex items-center gap-3">
                    <div class="text-center">
                        <div id="stat-total" class="text-[15px] font-extrabold font-mono text-danger"
                            style="color:#DC2626"></div>
                        <div class="text-[9px] text-gray-500 whitespace-nowrap">Laporan</div>
                    </div>
                    <div class="w-px h-7 bg-gray-200"></div>
                    <div class="text-center">
                        <div id="stat-cluster" class="text-[15px] font-extrabold font-mono" style="color:#D97706"></div>
                        <div class="text-[9px] text-gray-500">Cluster</div>
                    </div>
                    <div class="w-px h-7 bg-gray-200"></div>
                    <div class="text-center">
                        <div id="stat-kel" class="text-[15px] font-extrabold font-mono" style="color:#0D8C6E"></div>
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
                <div class="bg-red-50 border border-red-500 rounded-xl p-3 flex gap-2.5 items-start"
                    style="border-color:#DC2626;background:#FEE2E2">
                    <span class="text-base flex-shrink-0 mt-0.5">🚨</span>
                    <p id="alert-text" class="text-xs leading-relaxed" style="color:#7F1D1D"></p>
                </div>

                <!-- Tabs -->
                <div class="flex gap-1 bg-gray-100 rounded-xl p-1">
                    <button
                        class="tab active flex-1 text-center py-1.5 text-xs font-semibold rounded-lg border-none cursor-pointer font-sans text-gray-500 transition-all"
                        onclick="switchTab('lapor',this)">📋 Laporkan Masalah</button>
                    <button
                        class="tab flex-1 text-center py-1.5 text-xs font-semibold rounded-lg border-none cursor-pointer font-sans text-gray-500 transition-all"
                        onclick="switchTab('area',this)">🗺 Cek Area Saya</button>
                </div>

                <!-- REPORT FORM -->
                <div class="subpanel active" id="sub-lapor">
                    <!-- Category grid -->
                    <div class="grid grid-cols-4 gap-1.5 mb-2.5 max-[500px]:grid-cols-2">
                        <button
                            class="cat-btn bg-gray-50 border-[1.5px] border-gray-200 rounded-xl py-2.5 px-1.5 cursor-pointer flex flex-col items-center gap-1 hover:border-blue hover:bg-blue-light transition-all w-full"
                            onclick="selCat(this)">
                            <span class="text-lg">💧</span><span
                                class="text-[10px] font-semibold text-gray-500 text-center leading-tight">Bau
                                Menyengat</span>
                        </button>
                        <button
                            class="cat-btn bg-gray-50 border-[1.5px] border-gray-200 rounded-xl py-2.5 px-1.5 cursor-pointer flex flex-col items-center gap-1 hover:border-blue hover:bg-blue-light transition-all w-full"
                            onclick="selCat(this)">
                            <span class="text-lg">🟡</span><span
                                class="text-[10px] font-semibold text-gray-500 text-center leading-tight">Air Berubah
                                Warna</span>
                        </button>
                        <button
                            class="cat-btn bg-gray-50 border-[1.5px] border-gray-200 rounded-xl py-2.5 px-1.5 cursor-pointer flex flex-col items-center gap-1 hover:border-blue hover:bg-blue-light transition-all w-full"
                            onclick="selCat(this)">
                            <span class="text-lg">🤢</span><span
                                class="text-[10px] font-semibold text-gray-500 text-center leading-tight">Sakit /
                                Diare</span>
                        </button>
                        <button
                            class="cat-btn bg-gray-50 border-[1.5px] border-gray-200 rounded-xl py-2.5 px-1.5 cursor-pointer flex flex-col items-center gap-1 hover:border-blue hover:bg-blue-light transition-all w-full"
                            onclick="selCat(this)">
                            <span class="text-lg">❓</span><span
                                class="text-[10px] font-semibold text-gray-500 text-center leading-tight">Lainnya</span>
                        </button>
                    </div>
                    <!-- Form row -->
                    <div class="flex gap-2">
                        <select id="kel"
                            class="flex-1 px-3 py-2 border-[1.5px] border-gray-200 rounded-lg font-sans text-xs text-gray-900 bg-white cursor-pointer focus:outline-none focus:border-blue transition-colors pr-8">
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
                        <button
                            class="px-5 py-2 bg-blue text-white rounded-lg font-sans text-xs font-bold whitespace-nowrap hover:bg-blue-dark transition-colors"
                            onclick="doSubmit()">Kirim Anonim →</button>
                    </div>
                </div>

                <!-- AREA LIST -->
                <div class="subpanel hidden" id="sub-area">
                    <div class="area-scroll flex flex-col gap-1.5 max-h-48 overflow-y-auto" id="area-list">
                        <!-- Diisi otomatis oleh JS -->
                    </div>
                </div>
            </div>
        </div>

    </div>



    <!-- LEGEND BOTTOM RIGHT -->
    <div
        class="fixed bottom-5 right-4 z-[400] bg-white/95 backdrop-blur-md border border-gray-200 rounded-xl px-3.5 py-2.5 shadow-lg">
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
    <div class="toast bg-gray-900 text-white rounded-xl px-4 py-3 text-xs font-semibold flex items-center gap-2 shadow-xl whitespace-nowrap"
        id="toast">
        ✅ &nbsp;<span id="toast-msg">Laporan diterima!</span>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script>
        var mapObj, selCatEl = null, panelOpen = false;

    var mapObj, selCatEl = null, panelOpen = false;

function togglePanel() {
    panelOpen = !panelOpen;
    document.getElementById('panelBody').classList.toggle('open', panelOpen);
    document.getElementById('chevron').classList.toggle('open', panelOpen);
}
function togglePanel() {
    panelOpen = !panelOpen;
    document.getElementById('panelBody').classList.toggle('open', panelOpen);
    document.getElementById('chevron').classList.toggle('open', panelOpen);
}

function openLapor() {
    if (!panelOpen) togglePanel();
    switchTabById('lapor');
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
function toast(msg) {
    var t = document.getElementById('toast');
    document.getElementById('toast-msg').textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 4000);
}

function doFilter() {
    // U can do filter logic here
    var kel = document.getElementById('kel').value;
    if (!selCatEl) { toast('Pilih kategori masalah dulu.'); return; }
    if (!kel) { toast('Pilih kelurahan Anda.'); return; }
    toast('Filter berhasil!');
}
function doSubmit() {
    var kel = document.getElementById('kel').value;
    if (!selCatEl) { toast('Pilih kategori masalah dulu.'); return; }
    if (!kel) { toast('Pilih kelurahan Anda.'); return; }
    toast('Laporan diterima! Jika ada 2 laporan lain di area kamu, kami akan mengeluarkan peringatan.');
}

function flyAndClose(coords) {
    // Tutup panel
    panelOpen = false;
    document.getElementById('panelBody').classList.remove('open');
    document.getElementById('chevron').classList.remove('open');

    // Zoom ke lokasi
    mapObj.flyTo(coords, 16, { duration: 1.4 });
}
function fly(coords) {
    mapObj.flyTo(coords, 14, { duration: 1.2 });
}

// function fly(coords) {
//     mapObj.flyTo(coords, 14, { duration: 1.2 });
// }

    </script>
    </script>
</body>

</html>