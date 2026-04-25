// 1. Inisialisasi Peta
var mapObj = L.map('map', { center: [-7.257, 112.752], zoom: 12, zoomControl: false });
L.control.zoom({ position: 'bottomleft' }).addTo(mapObj);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(mapObj);

// --- TANGKAP DATA DARI BACKEND (Database Real-time) ---
var dynamicReportData = {};
var areasData = window.appData ? window.appData.areasWithCount : [];
var clusterData = window.appData ? window.appData.clusterMarkers : [];
var statsData = window.appData ? window.appData.stats : { totalLaporan: 0, totalCluster: 0, worstArea: null };

// Konversi format database menjadi object lookup yang dibutuhkan GeoJSON
areasData.forEach(function(area) {
    dynamicReportData[area.kelurahan] = {
        n: area.count,
        d: area.latest_desc,
        coords: [area.lat, area.lng]
    };
});

// Helper: Pencarian nama kelurahan (Case-Insensitive)
function getCaseInsensitiveData(name) {
    var keys = Object.keys(dynamicReportData);
    var match = keys.find(k => k.toLowerCase() === name.toLowerCase());
    return match ? dynamicReportData[match] : null;
}

// 2. Load GeoJSON Kelurahan
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
                    if (n > 15) fillColor = '#7F1D1D';
                    else if (n > 10) fillColor = '#991B1B';
                    else if (n > 6) fillColor = '#B91C1C';
                    else fillColor = '#EF4444';
                    
                    strokeColor = '#450a0a'; 
                    op = 0.7;
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
                        <div style="display:flex;justify-content:space-between;padding:4px 0"><span style="color:#666">Laporan Aktif</span><span style="font-weight:700;color:${statusColor}">${info.n} case</span></div>
                        ${info.n > 0 ? `<div style="padding:4px 0;color:#444;line-height:1.4;font-style:italic">"${info.d}"</div>` : ''}
                    </div>
                </div>`;
                
                layer.bindPopup(pop, { maxWidth: 260 });
                
                layer.on('mouseover', function() { this.setStyle({ fillOpacity: 0.9, weight: 3 }); });
                layer.on('mouseout', function() { geoLayer.resetStyle(this); });
            }
        }).addTo(mapObj);

        // 3. Tambahkan Cluster Marker Lingkaran (Sama seperti Dashboard Instansi)
        clusterData.forEach(cluster => {
            const severityColor = cluster.severity >= 70 ? '#dc2626' : cluster.severity >= 40 ? '#ea580c' : '#eab308';
            L.circle([cluster.lat, cluster.lng], {
                radius: cluster.radius || 500,
                fillColor: 'transparent', fillOpacity: 0,
                color: severityColor, weight: 3, dashArray: '8, 8',
            }).addTo(mapObj);
        });

        // 4. --- UPDATE STATISTIK UI ---
        document.getElementById('stat-total').innerText = statsData.totalLaporan;
        document.getElementById('stat-cluster').innerText = statsData.totalCluster;
        document.getElementById('stat-kel').innerText = areasData.length > 0 ? areasData.length : 153;
        document.getElementById('nav-cluster-badge').textContent = '⚠ ' + statsData.totalCluster + ' Cluster Aktif';

        if (statsData.worstArea) {
            document.getElementById('panel-headline').innerText = `⚠ Kel. ${statsData.worstArea.kelurahan} — ${statsData.worstArea.count} laporan gangguan air`;
            document.getElementById('alert-text').innerHTML = `<strong class="font-bold">Cluster Alert Aktif — Kel. ${statsData.worstArea.kelurahan}.</strong> ${statsData.worstArea.count} laporan terdeteksi. ${statsData.worstArea.latest_desc}`;
            document.getElementById('panel-subline').innerText = `Terdeteksi ${statsData.totalCluster} Cluster Alert di Surabaya. Cek area Anda sekarang.`;
        } else {
            document.getElementById('panel-headline').innerText = `✅ Kondisi Air Terpantau Normal`;
            document.getElementById('alert-text').innerHTML = `Saat ini tidak ada laporan gangguan distribusi air yang signifikan.`;
            document.getElementById('panel-subline').innerText = `Semua sistem berjalan normal.`;
        }

        // 5. --- UPDATE LIST AREA (Diurutkan dari laporan terbanyak) ---
        var areaListEl = document.getElementById('area-list');
        areaListEl.innerHTML = '';
        var sortedAreas = [...areasData].sort((a, b) => b.count - a.count).filter(a => a.count > 0);

        if(sortedAreas.length === 0) {
            areaListEl.innerHTML = '<div class="text-xs text-gray-500 text-center py-4">Semua wilayah normal. Tidak ada keluhan masuk.</div>';
        }

        sortedAreas.forEach(function(area) {
            var color = area.count > 3 ? '#EF4444' : '#F59E0B';
            var textColor = area.count > 3 ? '#DC2626' : '#D97706';

            var div = document.createElement('div');
            div.className = 'bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 flex items-center gap-2.5 cursor-pointer hover:border-blue-400 transition-all';
            div.onclick = function() { 
                if (typeof flyAndClose === "function") { flyAndClose([area.lat, area.lng]); }
                else { mapObj.flyTo([area.lat, area.lng], 15); }
            };

            div.innerHTML =
                '<div class="w-2 h-2 rounded-full flex-shrink-0" style="background:' + color + '"></div>' +
                '<div class="flex-1 min-w-0">' +
                    '<div class="text-xs font-bold truncate">' + area.kelurahan + '</div>' +
                    '<div class="text-[10px] text-gray-500 mt-0.5 truncate">' + (area.latest_desc || 'Gangguan air') + '</div>' +
                '</div>' +
                '<div class="text-[17px] font-extrabold font-mono" style="color:' + textColor + '">' + area.count + '</div>';
                
            areaListEl.appendChild(div);
        });

        // 6. --- PEMBATASAN MAP ---
        var surabayaBounds = geoLayer.getBounds();
        if(surabayaBounds.isValid()){
            mapObj.fitBounds(surabayaBounds);
            mapObj.setMaxBounds(surabayaBounds.pad(0.5));
            mapObj.options.minZoom = mapObj.getZoom() - 1;
        }
    });