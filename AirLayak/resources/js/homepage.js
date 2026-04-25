// 1. Inisialisasi Peta
mapObj = L.map('map', { center: [-7.257, 112.752], zoom: 12, zoomControl: false });
L.control.zoom({ position: 'bottomleft' }).addTo(mapObj);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(mapObj);

// 2. Data Status Tahap Kelurahan
var reportData = {
    // === 🔴 CLUSTER ALERT (15 KELURAHAN KRITIS) ===
    'Ampel': { n: 25, d: 'KRITIS: Air berbau bangkai pekat di sekitar pemukiman' },
    'Sidotopo': { n: 20, d: 'DARURAT: Wabah gatal-gatal; air berminyak hitam' },
    'Putat Jaya': { n: 18, d: 'Pipa Utama Pecah: Air bercampur lumpur hitam' },
    'Gading': { n: 15, d: 'Klorin Berlebih: Bau menyengat & mata perih' },
    'Embong Kaliasin': { n: 14, d: 'Mati Air Total (18 Jam): Warga kesulitan air bersih' },
    'Morokrembangan': { n: 13, d: 'Air Berbusa Deterjen: Beracun jika dikonsumsi' },
    'Wonokromo': { n: 12, d: 'Waspada Diare: 10 warga dilarikan ke puskesmas' },
    'Nyamplungan': { n: 11, d: 'Intrusi Air Laut: Air terasa sangat asin' },
    'Tembok Dukuh': { n: 10, d: 'Air Berkarat: Warna oranye pekat merusak filter' },
    'Kapasari': { n: 9,  d: 'Ditemukan larva & jentik nyamuk massal' },
    'Sawahan': { n: 8,  d: 'Air berbau sulfur/belerang sangat tajam' },
    'Bongkaran': { n: 7, d: 'Pasokan macet; hanya keluar udara' },
    'Bubutan': { n: 6,  d: 'Endapan lumpur pasir menyumbat keran' },
    'Tegalsari': { n: 5, d: 'Air berlendir & sulit dibilas sabun' },
    'Genteng': { n: 4,  d: 'Rasa logam tajam; air meninggalkan bekas hitam' },

    // === 🟡 WASPADA (60 KELURAHAN GANGGUAN RINGAN) ===
    'Siwalankerto': { n: 3, d: 'Tekanan air menurun drastis' },
    'Menur Pumpungan': { n: 2, d: 'Air berbau tanah saat pagi' },
    'Keputih': { n: 1, d: 'Sedikit endapan kapur' },
    'Klampis Ngasem': { n: 3, d: 'Air berwarna kekuningan ringan' },
    'Wonorejo': { n: 2, d: 'Aliran air tersendat-sendat' },
    'Medokan Ayu': { n: 3, d: 'Air terasa agak pahit' },
    'Ketabang': { n: 1, d: 'Bau kaporit tipis' },
    'Sukolilo': { n: 2, d: 'Air berminyak di permukaan' },
    'Gubeng': { n: 3, d: 'Keruh saat jam sibuk sore' },
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
    'Krembangan Utara': { n: 1, d: 'Laporan air berbau' },
    'Krembangan Selatan': { n: 2, d: 'Warna coklat muda' },
    'Kemayoran': { n: 1, d: 'Tekanan air rendah' },
    'Bongkaran': { n: 3, d: 'Laporan air payau' }
};

var styleConfig = {
    cluster: { fill: '#EF4444', stroke: '#DC2626', op: 0.6 },
    waspada: { fill: '#F59E0B', stroke: '#D97706', op: 0.4 },
    normal:  { fill: '#6B7280', stroke: '#4B5563', op: 0.1 }
};

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

        // --- LOGIKA UPDATE STATISTIK PANEL ---
        function updateStats() {
            let totalLaporan = 0;
            let totalCluster = 0;
            let totalKelurahan = Object.keys(reportData).length;
            let topKelurahan = "";
            let maxReports = 0;

            for (let kel in reportData) {
                let n = reportData[kel].n;
                totalLaporan += n;
                if (n > 3) totalCluster++;
                
                // Cari kelurahan dengan laporan terbanyak untuk Headline
                if (n > maxReports) {
                    maxReports = n;
                    topKelurahan = kel;
                }
            }

            // Update Angka di UI
            document.getElementById('stat-total').innerText = totalLaporan;
            document.getElementById('stat-cluster').innerText = totalCluster;
            document.getElementById('stat-kel').innerText = 153;

            // Update Headline Panel
            document.getElementById('panel-headline').innerText = 
                `⚠ Kel. ${topKelurahan} — ${maxReports} laporan gangguan air dalam 24 jam`;
            document.getElementById('panel-subline').innerText = 
                `Terdeteksi ${totalCluster} Cluster Alert di Surabaya. Cek area Anda sekarang.`;
        }

        // Jalankan fungsi update
        updateStats();

        // --- PEMBATASAN MAP ---
        var surabayaBounds = geoLayer.getBounds();
        mapObj.fitBounds(surabayaBounds);
        mapObj.setMaxBounds(surabayaBounds.pad(0.5));
        mapObj.options.maxBoundsViscosity = 0.5;
        mapObj.options.minZoom = mapObj.getZoom() - 1;
    });

// ─── COMPUTED STATS ──────────────────────────────────────────────────────
// Hitung dari reportData secara otomatis
var entries = Object.entries(reportData);

// Total semua laporan
var totalLaporan = entries.reduce(function(s, e) { return s + e[1].n; }, 0);

// Cluster = kelurahan dengan n > 3
var clusterList = entries.filter(function(e) { return e[1].n > 3; });
var totalCluster = clusterList.length;

// Kelurahan terdampak = kelurahan dengan n >= 1
var kelTerdampak = entries.filter(function(e) { return e[1].n >= 1; }).length;

// Kelurahan terparah (n terbesar)
var worst = entries.reduce(function(a, b) { return b[1].n > a[1].n ? b : a; });
var worstName = worst[0];
var worstN    = worst[1].n;
var worstDesc = worst[1].d;

// Kalimat ringkas dari kelurahan cluster alert terparah
// Ambil kata kunci pertama sebelum titik dua
function shortDesc(d) {
    var colon = d.indexOf(':');
    return colon > -1 ? d.substring(0, colon).toLowerCase() : d.split(' ').slice(0, 4).join(' ').toLowerCase();
}

document.getElementById('nav-cluster-badge').textContent = '⚠ ' + totalCluster + ' Cluster Aktif';

// ⑦ Alert box
document.getElementById('alert-text').innerHTML =
    '<strong class="font-bold">Cluster Alert Aktif — Kec. ' + worstName + ', Surabaya.</strong> ' +
    worstN + ' laporan ' + shortDesc(worstDesc) + ' terdeteksi dalam radius 500m / 24 jam. ' +
    'Kemungkinan ada gangguan distribusi PDAM Surya. Warga disarankan tidak mengonsumsi air langsung.';

// ─── AREA LIST (Cek Area Saya) ───────────────────────────────────────────
// Diurutkan dari terbanyak ke terkecil, render otomatis
var sortedEntries = entries.slice().sort(function(a, b) { return b[1].n - a[1].n; });

var areaListEl = document.getElementById('area-list');
sortedEntries.forEach(function(entry) {
    var name = entry[0];
    var info = entry[1];
    var color, textColor;

    if (info.n === 0) {
        color = '#9CA3AF'; textColor = '#6B7280';
    } else if (info.n <= 3) {
        color = '#F59E0B'; textColor = '#D97706';
    } else {
        color = '#EF4444'; textColor = '#DC2626';
    }

    var div = document.createElement('div');
    div.className = 'bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 flex items-center gap-2.5 cursor-pointer hover:border-blue hover:bg-blue-light transition-all';
    div.innerHTML =
        '<div class="w-2 h-2 rounded-full flex-shrink-0" style="background:' + color + '"></div>' +
        '<div class="flex-1">' +
            '<div class="text-xs font-bold">' + name + '</div>' +
            '<div class="text-[10px] text-gray-500 mt-0.5">' + info.d + '</div>' +
        '</div>' +
        '<div class="text-[17px] font-extrabold font-mono" style="color:' + textColor + '">' + info.n + '</div>';
    areaListEl.appendChild(div);
});