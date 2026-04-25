<!-- 
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>AirLayak - Operator Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
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
                        danger: { DEFAULT: '#DC2626', light: '#FEE2E2' },
                    }
                }
            }
        }
    </script>
    <style>
        html, body { height: 100%; overflow: hidden; }
        .leaflet-popup-content-wrapper { border-radius: 12px; padding: 0; overflow: hidden; }
        .leaflet-popup-content { margin: 0; }
        /* Custom Scrollbar for Priority List */
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
    </style>
</head>
<body class="bg-gray-50 font-sans text-gray-900 flex h-screen overflow-hidden">

    <aside class="w-[420px] bg-white border-r border-gray-200 flex flex-col z-[1000] shadow-[4px_0_24px_rgba(0,0,0,0.04)] relative">
        
        <div class="px-5 py-4 border-b border-gray-200 bg-white">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue to-teal flex items-center justify-center text-[10px] text-white font-extrabold font-mono">OP</div>
                    <div>
                        <h1 class="font-extrabold text-sm tracking-tight leading-none">AirLayak <span class="text-blue">Ops</span></h1>
                        <p class="text-[10px] text-gray-500 font-medium mt-1">PDAM Surya Sembada</p>
                    </div>
                </div>
                <div class="bg-danger-light text-danger text-[10px] font-bold px-2 py-1 rounded-md border border-red-200">
                    2 Overdue
                </div>
            </div>
            
            <div class="flex gap-2 mt-2">
                <select class="flex-1 text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-blue">
                    <option>Semua Area</option>
                    <option>Surabaya Pusat</option>
                    <option>Surabaya Timur</option>
                </select>
                <select class="flex-1 text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-blue">
                    <option>Urut: Severity Score</option>
                    <option>Urut: Terbaru</option>
                </select>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto custom-scroll p-3 flex flex-col gap-2" id="report-list">
            </div>
    </aside>

    <main class="flex-1 relative">
        <div id="map" class="w-full h-full z-0"></div>

        <div id="action-panel" class="absolute bottom-6 left-1/2 -translate-x-1/2 w-[500px] bg-white/95 backdrop-blur-xl border border-gray-200 rounded-2xl shadow-2xl z-[1000] transform translate-y-[150%] transition-transform duration-300 flex flex-col overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex justify-between items-start">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span id="ap-badge" class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide"></span>
                        <span id="ap-id" class="font-mono text-[11px] text-gray-500 font-semibold"></span>
                    </div>
                    <h2 id="ap-title" class="font-bold text-gray-900 text-sm"></h2>
                    <p id="ap-desc" class="text-xs text-gray-600 mt-1 line-clamp-2"></p>
                </div>
                <button onclick="closeActionPanel()" class="text-gray-400 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full w-7 h-7 flex items-center justify-center transition-colors">✕</button>
            </div>
            
            <div class="p-4 bg-gray-50/50 flex flex-col gap-3">
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="bg-white p-2.5 rounded-xl border border-gray-100">
                        <span class="text-gray-500 block mb-0.5 text-[10px] font-semibold uppercase">Sumber Air</span>
                        <strong id="ap-source" class="text-gray-900"></strong>
                    </div>
                    <div class="bg-white p-2.5 rounded-xl border border-gray-100">
                        <span class="text-gray-500 block mb-0.5 text-[10px] font-semibold uppercase">Status Saat Ini</span>
                        <strong id="ap-status" class="text-gray-900 capitalize"></strong>
                    </div>
                </div>

                <div id="ap-actions" class="flex gap-2 mt-1">
                    </div>
            </div>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
    <script>
        // 1. Data Mockup Sesuai Skenario Dokumen
        const reports = [
            {
                id: "REP-A01",
                score: 95,
                priority: "critical",
                status: "pending",
                category: "Bau Menyengat & Warna",
                source: "PDAM",
                area: "Genteng",
                address: "Jl. Genteng Besar No. 12",
                lat: -7.257, lng: 112.742,
                warning: "overdue_acknowledgment",
                desc: "Air PDAM bau kaporit sangat menyengat dan berwarna sedikit kecoklatan sejak pagi."
            },
            {
                id: "REP-A02",
                score: 82,
                priority: "high",
                status: "in_progress",
                category: "Diare / Sakit Perut",
                source: "Sumur + PDAM",
                area: "Mojo",
                address: "Gg. Mojo Kidul III",
                lat: -7.275, lng: 112.766,
                warning: null,
                desc: "Satu keluarga mengalami diare setelah menggunakan air untuk masak."
            },
            {
                id: "REP-A03",
                score: 65,
                priority: "normal",
                status: "acknowledged",
                category: "Air Berwarna Kuning",
                source: "PDAM",
                area: "Tambaksari",
                address: "Jl. Pacar Keling",
                lat: -7.252, lng: 112.762,
                warning: null,
                desc: "Air keruh kuning tidak bisa dipakai mencuci pakaian putih."
            },
            {
                id: "REP-A04",
                score: 40,
                priority: "low",
                status: "awaiting_confirmation",
                category: "Rasa Aneh",
                source: "Air Isi Ulang",
                area: "Tegalsari",
                address: "Jl. Kedung Doro",
                lat: -7.265, lng: 112.730,
                warning: null,
                desc: "Air galon isi ulang dari depot X rasanya agak pahit."
            }
        ];

        let map, markers = {}, activeReportId = null;

        // Utility: Color Mapping
        const priorityColors = {
            critical: { bg: 'bg-danger', text: 'text-white', border: 'border-danger', hex: '#DC2626' },
            high: { bg: 'bg-amber', text: 'text-white', border: 'border-amber', hex: '#D97706' },
            normal: { bg: 'bg-blue', text: 'text-white', border: 'border-blue', hex: '#1A6BCC' },
            low: { bg: 'bg-gray-400', text: 'text-white', border: 'border-gray-400', hex: '#9CA3AF' }
        };

        const statusLabels = {
            pending: "Menunggu Respons",
            acknowledged: "Diakui (Menunggu ETA)",
            in_progress: "Sedang Ditangani",
            awaiting_confirmation: "Menunggu Konfirmasi Warga"
        };

        function initMap() {
            map = L.map('map', { zoomControl: false }).setView([-7.265, 112.750], 13);
            L.control.zoom({ position: 'topright' }).addTo(map);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            reports.forEach(r => {
                const color = priorityColors[r.priority].hex;
                // Create custom marker matching priority
                const markerHtml = `
                    <div style="background-color: ${color}; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>
                `;
                const icon = L.divIcon({ html: markerHtml, className: '', iconSize: [24, 24], iconAnchor: [12, 12] });
                
                const marker = L.marker([r.lat, r.lng], { icon: icon }).addTo(map);
                marker.on('click', () => selectReport(r.id));
                markers[r.id] = marker;

                // Add small cluster radius simulation
                L.circle([r.lat, r.lng], { radius: 150, color: color, weight: 1, fillColor: color, fillOpacity: 0.1 }).addTo(map);
            });
        }

        function renderList() {
            const container = document.getElementById('report-list');
            container.innerHTML = reports.map(r => {
                const pColor = priorityColors[r.priority];
                const warningTag = r.warning ? `<span class="bg-red-100 text-red-700 text-[9px] px-1.5 py-0.5 rounded font-bold uppercase block mt-1 animate-pulse">⚠ ${r.warning.replace('_', ' ')}</span>` : '';
                
                return `
                <div id="card-${r.id}" onclick="selectReport('${r.id}')" class="bg-white border border-gray-200 rounded-xl p-3 cursor-pointer hover:border-blue hover:shadow-md transition-all flex gap-3 relative overflow-hidden group">
                    <div class="absolute top-0 left-0 bottom-0 w-1 ${pColor.bg}"></div>
                    <div class="flex-1 ml-1">
                        <div class="flex justify-between items-start mb-1">
                            <div class="font-mono text-[10px] text-gray-400 font-bold">${r.id}</div>
                            <div class="text-[10px] font-bold ${pColor.text} ${pColor.bg} px-1.5 py-0.5 rounded uppercase tracking-wider">${r.priority} (${r.score})</div>
                        </div>
                        <h3 class="font-bold text-sm text-gray-900 leading-tight">${r.area} <span class="text-gray-400 font-normal ml-1">— ${r.category}</span></h3>
                        <p class="text-[11px] text-gray-500 mt-1 line-clamp-1">${r.address}</p>
                        ${warningTag}
                    </div>
                </div>`;
            }).join('');
        }

        function selectReport(id) {
            const report = reports.find(r => r.id === id);
            if (!report) return;

            // Highlight card
            document.querySelectorAll('[id^="card-"]').forEach(el => el.classList.remove('border-blue', 'ring-2', 'ring-blue-light'));
            document.getElementById(`card-${id}`).classList.add('border-blue', 'ring-2', 'ring-blue-light');

            // Fly Map
            map.flyTo([report.lat, report.lng], 16, { duration: 1.5 });

            // Open Action Panel
            openActionPanel(report);
        }

        function openActionPanel(report) {
            const panel = document.getElementById('action-panel');
            const pColor = priorityColors[report.priority];

            document.getElementById('ap-badge').className = `px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide ${pColor.bg} ${pColor.text}`;
            document.getElementById('ap-badge').innerText = report.priority;
            document.getElementById('ap-id').innerText = report.id;
            document.getElementById('ap-title').innerText = `${report.category} di ${report.area}`;
            document.getElementById('ap-desc').innerText = report.desc;
            document.getElementById('ap-source').innerText = report.source;
            document.getElementById('ap-status').innerText = statusLabels[report.status];

            // Render Contextual Actions based on Lifecycle
            let actionHtml = '';
            if (report.status === 'pending') {
                actionHtml = `<button class="flex-1 bg-blue text-white text-xs font-bold py-2.5 rounded-lg hover:bg-blue-dark transition" onclick="alert('Laporan Diakui!')">Acknowledge (Ambil Alih)</button>`;
            } else if (report.status === 'acknowledged') {
                actionHtml = `<button class="flex-1 bg-teal text-white text-xs font-bold py-2.5 rounded-lg hover:bg-teal-700 transition" onclick="alert('ETA diset!')">Set Estimasi Selesai (ETA)</button>`;
            } else if (report.status === 'in_progress') {
                actionHtml = `
                    <button class="flex-1 bg-teal text-white text-xs font-bold py-2.5 rounded-lg hover:bg-teal-700 transition" onclick="alert('Klaim Selesai!')">Klaim Selesai</button>
                    <button class="flex-1 bg-amber text-white text-xs font-bold py-2.5 rounded-lg hover:bg-amber-600 transition" onclick="alert('Request Extension!')">Request Perpanjangan</button>
                `;
            } else if (report.status === 'awaiting_confirmation') {
                actionHtml = `<div class="flex-1 bg-gray-100 text-gray-500 text-xs font-bold py-2.5 rounded-lg text-center cursor-not-allowed">Menunggu Warga Mengonfirmasi Resolusi</div>`;
            }

            document.getElementById('ap-actions').innerHTML = actionHtml;
            
            // Slide up
            panel.classList.remove('translate-y-[150%]');
        }

        function closeActionPanel() {
            document.getElementById('action-panel').classList.add('translate-y-[150%]');
            document.querySelectorAll('[id^="card-"]').forEach(el => el.classList.remove('border-blue', 'ring-2', 'ring-blue-light'));
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            initMap();
            renderList();
        });
    </script>
</body>
</html> -->