<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
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
                    },
                    animation: {
                        blink: 'blink 2s infinite',
                        'blink-fast': 'blink 1.5s infinite',
                    },
                    keyframes: {
                        blink: { '0%,100%': { opacity: 1 }, '50%': { opacity: 0.5 } },
                    },
                }
            }
        }
        </script>
        <style>
            html, body { min-height: 100%; font-family: 'Plus Jakarta Sans', sans-serif; }

            /* Select arrow */
            select {
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%23666' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 12px center;
                appearance: none;
            }

            /* Category card active state */
            .cat-card { transition: all 0.18s ease; cursor: pointer; }
            .cat-card:hover { border-color: #1A6BCC; background: #EBF3FF; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(26,107,204,0.12); }
            .cat-card.active { border-color: #1A6BCC; background: #EBF3FF; box-shadow: 0 0 0 3px rgba(26,107,204,0.15); }
            .cat-card.active .cat-check { opacity: 1; transform: scale(1); }
            .cat-check { opacity: 0; transform: scale(0.5); transition: all 0.15s ease; }

            /* Water source chip */
            .source-chip { transition: all 0.15s ease; cursor: pointer; }
            .source-chip:hover { border-color: #0D8C6E; background: #E0F5EF; }
            .source-chip.active { border-color: #0D8C6E; background: #E0F5EF; color: #0D8C6E; font-weight: 700; }

            /* Photo upload */
            .upload-zone { transition: all 0.2s ease; }
            .upload-zone:hover { border-color: #1A6BCC; background: #EBF3FF; }
            .upload-zone.drag { border-color: #1A6BCC; background: #EBF3FF; transform: scale(1.01); }

            /* Step indicator */
            .step-dot { transition: all 0.3s ease; }
            .step-line { transition: width 0.4s ease; }

            /* Input focus */
            input:focus, select:focus, textarea:focus {
                outline: none;
                border-color: #1A6BCC;
                box-shadow: 0 0 0 3px rgba(26,107,204,0.1);
            }

            /* Animate in */
            @keyframes slideUp {
                from { opacity: 0; transform: translateY(16px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .slide-up { animation: slideUp 0.4s ease forwards; }
            .slide-up-delay-1 { animation: slideUp 0.4s ease 0.05s both; }
            .slide-up-delay-2 { animation: slideUp 0.4s ease 0.1s both; }
            .slide-up-delay-3 { animation: slideUp 0.4s ease 0.15s both; }
            .slide-up-delay-4 { animation: slideUp 0.4s ease 0.2s both; }
            .slide-up-delay-5 { animation: slideUp 0.4s ease 0.25s both; }
            .slide-up-delay-6 { animation: slideUp 0.4s ease 0.3s both; }

            /* Success state */
            @keyframes popIn {
                0% { opacity: 0; transform: scale(0.8); }
                70% { transform: scale(1.05); }
                100% { opacity: 1; transform: scale(1); }
            }
            .pop-in { animation: popIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }

            /* Privacy note */
            .privacy-badge { background: linear-gradient(135deg, #f0fdf4, #dcfce7); }
        </style>
    </head>
    <body class="bg-slate-100 text-gray-900">

        <!-- NAV — sama persis dengan homepage -->
        <nav class="sticky top-0 z-[1000] bg-white/97 backdrop-blur-md border-b border-gray-200 flex items-center justify-between px-5 h-[54px]">
            <div class="flex items-center gap-2">
                <img src="{{ asset('logo.jpeg') }}" alt="AirWarga Logo" class="h-[30px] w-auto">
                <span class="font-extrabold text-[17px] tracking-tight">Air<span class="text-blue">Warga</span></span>
            </div>
            <div class="absolute left-1/2 -translate-x-1/2 hidden sm:flex items-center gap-2">
                <span class="text-[11px] font-semibold text-gray-500">Form Laporan Masalah Air</span>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('homepage') }}" class="flex items-center gap-1.5 border border-gray-200 text-gray-500 rounded-lg px-3 py-1.5 text-xs font-semibold hover:bg-gray-50 transition-colors">
                    ← Kembali ke Peta
                </a>
            </div>
        </nav>

        <!-- MAIN CONTENT -->
        <div class="min-h-[calc(100vh-54px)] flex flex-col items-center justify-start py-8 px-4">

            <!-- Header -->
            <div class="w-full max-w-xl mb-6 slide-up">
                <h1 class="text-[22px] font-extrabold leading-tight mb-1">Laporkan Masalah Air</h1>
                <p class="text-[13px] text-gray-500">Laporan dari kamu akan membantu warga sekitar.</p>
            </div>

            <!-- FORM CARD -->
            <div class="w-full max-w-xl">
                <form id="reportForm" action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- ── STEP 1: KATEGORI ── -->
                    <div id="step1" class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-4 slide-up-delay-1">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-blue text-white text-[10px] font-bold font-mono flex items-center justify-center flex-shrink-0">1</span>
                                <h2 class="text-[13px] font-bold">Apa masalah air yang kamu alami?</h2>
                                <span class="ml-auto text-[10px] text-danger font-semibold">* Wajib</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <input type="hidden" name="category" id="categoryInput" required>
                            <div class="grid grid-cols-2 gap-2.5 sm:grid-cols-3">

                                <label class="cat-card relative bg-gray-50 border-[1.5px] border-gray-200 rounded-xl p-3.5 flex flex-col items-center gap-2 text-center" onclick="selectCategory(this, 'bau')">
                                    <div class="cat-check absolute top-2 right-2 w-4 h-4 rounded-full bg-blue flex items-center justify-center">
                                        <svg width="8" height="6" viewBox="0 0 8 6" fill="none"><path d="M1 3l2 2 4-4" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                    <span class="text-2xl">💧</span>
                                    <span class="text-[11px] font-semibold text-gray-700 leading-tight">Bau Menyengat</span>
                                    <span class="text-[10px] text-gray-400 leading-tight">Bau klorin, belerang, atau busuk</span>
                                </label>

                                <label class="cat-card relative bg-gray-50 border-[1.5px] border-gray-200 rounded-xl p-3.5 flex flex-col items-center gap-2 text-center" onclick="selectCategory(this, 'warna')">
                                    <div class="cat-check absolute top-2 right-2 w-4 h-4 rounded-full bg-blue flex items-center justify-center">
                                        <svg width="8" height="6" viewBox="0 0 8 6" fill="none"><path d="M1 3l2 2 4-4" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                    <span class="text-2xl">🟡</span>
                                    <span class="text-[11px] font-semibold text-gray-700 leading-tight">Air Berubah Warna</span>
                                    <span class="text-[10px] text-gray-400 leading-tight">Kuning, coklat, keruh, atau hitam</span>
                                </label>

                                <label class="cat-card relative bg-gray-50 border-[1.5px] border-gray-200 rounded-xl p-3.5 flex flex-col items-center gap-2 text-center" onclick="selectCategory(this, 'sakit_perut')">
                                    <div class="cat-check absolute top-2 right-2 w-4 h-4 rounded-full bg-blue flex items-center justify-center">
                                        <svg width="8" height="6" viewBox="0 0 8 6" fill="none"><path d="M1 3l2 2 4-4" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                    <span class="text-2xl">🤢</span>
                                    <span class="text-[11px] font-semibold text-gray-700 leading-tight">Sakit / Diare</span>
                                    <span class="text-[10px] text-gray-400 leading-tight">Setelah mengonsumsi air</span>
                                </label>

                                <label class="cat-card relative bg-gray-50 border-[1.5px] border-gray-200 rounded-xl p-3.5 flex flex-col items-center gap-2 text-center" onclick="selectCategory(this, 'rasa_aneh')">
                                    <div class="cat-check absolute top-2 right-2 w-4 h-4 rounded-full bg-blue flex items-center justify-center">
                                        <svg width="8" height="6" viewBox="0 0 8 6" fill="none"><path d="M1 3l2 2 4-4" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                    <span class="text-2xl">👅</span>
                                    <span class="text-[11px] font-semibold text-gray-700 leading-tight">Rasa Aneh</span>
                                    <span class="text-[10px] text-gray-400 leading-tight">Pahit, asin, logam, atau berbusa</span>
                                </label>

                                <label class="cat-card relative bg-gray-50 border-[1.5px] border-gray-200 rounded-xl p-3.5 flex flex-col items-center gap-2 text-center sm:col-span-2" onclick="selectCategory(this, 'lainnya')">
                                    <div class="cat-check absolute top-2 right-2 w-4 h-4 rounded-full bg-blue flex items-center justify-center">
                                        <svg width="8" height="6" viewBox="0 0 8 6" fill="none"><path d="M1 3l2 2 4-4" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                    <span class="text-2xl">❓</span>
                                    <span class="text-[11px] font-semibold text-gray-700 leading-tight">Masalah Lainnya</span>
                                    <span class="text-[10px] text-gray-400 leading-tight">Mati air, tekanan lemah, dll.</span>
                                </label>

                            </div>
                            @error('category')
                                <p class="mt-2 text-[11px] text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- ── STEP 2: SUMBER AIR ── -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-4 slide-up-delay-2">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-blue text-white text-[10px] font-bold font-mono flex items-center justify-center flex-shrink-0">2</span>
                                <h2 class="text-[13px] font-bold">Sumber air yang bermasalah?</h2>
                                <span class="ml-auto text-[10px] text-gray-400 font-medium">Boleh pilih lebih dari satu</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="flex flex-wrap gap-2">
                                <label class="source-chip border-[1.5px] border-gray-200 rounded-lg px-3 py-2 text-xs font-semibold text-gray-600 bg-gray-50 flex items-center gap-1.5">
                                    <input type="checkbox" name="water_sources[]" value="pdam" class="hidden" onchange="toggleChip(this)">
                                    🚰 PDAM / Air Ledeng
                                </label>
                                <label class="source-chip border-[1.5px] border-gray-200 rounded-lg px-3 py-2 text-xs font-semibold text-gray-600 bg-gray-50 flex items-center gap-1.5">
                                    <input type="checkbox" name="water_sources[]" value="sumur" class="hidden" onchange="toggleChip(this)">
                                    🪣 Sumur
                                </label>
                                <label class="source-chip border-[1.5px] border-gray-200 rounded-lg px-3 py-2 text-xs font-semibold text-gray-600 bg-gray-50 flex items-center gap-1.5">
                                    <input type="checkbox" name="water_sources[]" value="galon" class="hidden" onchange="toggleChip(this)">
                                    💧 Galon / Isi Ulang
                                </label>
                                <label class="source-chip border-[1.5px] border-gray-200 rounded-lg px-3 py-2 text-xs font-semibold text-gray-600 bg-gray-50 flex items-center gap-1.5">
                                    <input type="checkbox" name="water_sources[]" value="air_isi_ulang" class="hidden" onchange="toggleChip(this)">
                                    🔁 Air Isi Ulang
                                </label>
                                <label class="source-chip border-[1.5px] border-gray-200 rounded-lg px-3 py-2 text-xs font-semibold text-gray-600 bg-gray-50 flex items-center gap-1.5">
                                    <input type="checkbox" name="water_sources[]" value="tidak_yakin" class="hidden" onchange="toggleChip(this)">
                                    ❓ Tidak Yakin
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- ── STEP 3: LOKASI ── -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-4 slide-up-delay-3">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-blue text-white text-[10px] font-bold font-mono flex items-center justify-center flex-shrink-0">3</span>
                                <h2 class="text-[13px] font-bold">Di mana lokasimu?</h2>
                                <span class="ml-auto text-[10px] text-danger font-semibold">* Wajib</span>
                            </div>
                        </div>
                        <div class="p-4 flex flex-col gap-3">
                            <div class="grid grid-cols-2 gap-2.5">
                                <div class="flex flex-col gap-1">
                                    <label class="text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Kecamatan</label>
                                    <select name="kecamatan" class="px-3 py-2.5 border-[1.5px] border-gray-200 rounded-lg text-xs text-gray-900 bg-white pr-8 transition-all" onchange="loadKelurahan(this.value)" required>
                                        <option value="">Pilih Kecamatan...</option>
                                        <option>Bubutan</option>
                                        <option>Genteng</option>
                                        <option>Gubernur Suryo</option>
                                        <option>Kenjeran</option>
                                        <option>Krembangan</option>
                                        <option>Lakarsantri</option>
                                        <option>Mulyorejo</option>
                                        <option>Pabean Cantian</option>
                                        <option>Rungkut</option>
                                        <option>Sambikerep</option>
                                        <option>Sawahan</option>
                                        <option>Semampir</option>
                                        <option>Simokerto</option>
                                        <option>Sukolilo</option>
                                        <option>Sukomanunggal</option>
                                        <option>Tambaksari</option>
                                        <option>Tandes</option>
                                        <option>Tegalsari</option>
                                        <option>Tenggilis Mejoyo</option>
                                        <option>Wiyung</option>
                                        <option>Wonocolo</option>
                                        <option>Wonokromo</option>
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-[11px] font-semibold text-gray-500 uppercase tracking-wide">Kelurahan</label>
                                    <select name="area_id" id="kelSelect" class="px-3 py-2.5 border-[1.5px] border-gray-200 rounded-lg text-xs text-gray-900 bg-white pr-8 transition-all" required>
                                        <option value="">Pilih Kelurahan...</option>
                                    </select>
                                </div>
                            </div>

                            <!-- RT/RW opsional -->
                            <div class="grid grid-cols-2 gap-2.5">
                                <div class="flex flex-col gap-1">
                                    <label class="text-[11px] font-semibold text-gray-500 uppercase tracking-wide">RT <span class="font-normal text-gray-400">(opsional)</span></label>
                                    <input type="text" name="rt" placeholder="Cth: 05" maxlength="3"
                                        class="px-3 py-2.5 border-[1.5px] border-gray-200 rounded-lg text-xs text-gray-900 bg-white transition-all placeholder-gray-300">
                                </div>
                                <div class="flex flex-col gap-1">
                                    <label class="text-[11px] font-semibold text-gray-500 uppercase tracking-wide">RW <span class="font-normal text-gray-400">(opsional)</span></label>
                                    <input type="text" name="rw" placeholder="Cth: 02" maxlength="3"
                                        class="px-3 py-2.5 border-[1.5px] border-gray-200 rounded-lg text-xs text-gray-900 bg-white transition-all placeholder-gray-300">
                                </div>
                            </div>

                            @error('area_id')
                                <p class="text-[11px] text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- ── STEP 4: DESKRIPSI ── -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-4 slide-up-delay-4">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-blue text-white text-[10px] font-bold font-mono flex items-center justify-center flex-shrink-0">4</span>
                                <h2 class="text-[13px] font-bold">Ceritakan lebih lanjut</h2>
                                <span class="ml-auto text-[10px] text-gray-400 font-medium">Opsional</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <textarea name="description" id="descTextarea" rows="3" maxlength="500"
                                placeholder="Contoh: Sejak pagi tadi air berbau tidak sedap, warnanya agak kekuningan. Sudah 3 tetangga yang mengalami hal yang sama..."
                                class="w-full px-3 py-2.5 border-[1.5px] border-gray-200 rounded-lg text-xs text-gray-900 bg-white resize-none transition-all placeholder-gray-300 leading-relaxed"></textarea>
                            <div class="flex justify-end mt-1">
                                <span class="text-[10px] text-gray-400 font-mono" id="charCount">0 / 500</span>
                            </div>
                        </div>
                    </div>

                    <!-- ── STEP 5: FOTO ── -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-4 slide-up-delay-5">
                        <div class="px-5 py-4 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-blue text-white text-[10px] font-bold font-mono flex items-center justify-center flex-shrink-0">5</span>
                                <h2 class="text-[13px] font-bold">Tambahkan foto</h2>
                                <span class="ml-auto text-[10px] text-danger font-semibold">* Wajib</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <label class="upload-zone block border-2 border-dashed border-gray-200 rounded-xl p-6 text-center cursor-pointer" id="uploadZone">
                                <input type="file" name="photo_path" id="photoInput" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                                <div id="uploadPlaceholder">
                                    <div class="text-3xl mb-2">📸</div>
                                    <p class="text-[12px] font-semibold text-gray-600">Klik atau seret foto ke sini</p>
                                    <p class="text-[10px] text-gray-400 mt-1">JPG, PNG — max 5MB</p>
                                </div>
                                <div id="photoPreview" class="hidden">
                                    <img id="previewImg" src="" alt="Preview" class="max-h-40 mx-auto rounded-lg object-cover">
                                    <p class="text-[11px] text-blue font-semibold mt-2">Foto dipilih ✓ — klik untuk ganti</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- ── SUBMIT BUTTON ── -->
                    <div class="slide-up-delay-6">
                        <button type="submit" id="submitBtn"
                            class="w-full bg-blue hover:bg-blue-dark text-white font-bold text-[14px] py-3.5 rounded-xl transition-all shadow-lg hover:shadow-blue/25 hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed disabled:translate-y-0"
                            disabled>
                            <span id="submitLabel">Pilih kategori masalah dulu ↑</span>
                        </button>
                        <p class="text-center text-[11px] text-gray-400 mt-3">
                            Dengan mengirim, kamu menyetujui
                            <a href="{{ route('homepage') }}" class="text-blue underline">syarat penggunaan</a> AirWarga.
                        </p>
                    </div>

                </form>
            </div>

        </div>

        <!-- SUCCESS OVERLAY (shown after submit) -->
        @if(session('success'))
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl p-8 max-w-sm w-full text-center shadow-2xl pop-in">
                <div class="text-5xl mb-4">✅</div>
                <h2 class="text-xl font-extrabold mb-2">Laporan Terkirim!</h2>
                <p class="text-[13px] text-gray-500 leading-relaxed mb-6">
                    Terima kasih. Jika ada 2 laporan lain di area kamu dalam 24 jam, sistem akan otomatis mengeluarkan <strong class="text-danger">Cluster Alert</strong>.
                </p>
                <div class="bg-blue-light border border-blue/20 rounded-xl p-3 mb-5 text-left">
                    <p class="text-[11px] text-blue font-semibold mb-0.5">Kode laporan kamu:</p>
                    <p class="text-[13px] font-mono font-bold text-gray-800">{{ session('report_token') ?? 'AW-' . strtoupper(substr(md5(time()), 0, 8)) }}</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Simpan kode ini untuk memantau status laporan</p>
                </div>
                <a href="{{ route('homepage') }}" class="block w-full bg-blue text-white font-bold text-[13px] py-3 rounded-xl text-center hover:bg-blue-dark transition-colors">
                    Lihat di Peta →
                </a>
            </div>
        </div>
        @endif

        <!-- Kelurahan data per kecamatan -->
        <script>
        const kelurahanMap = {
            'Bubutan':          [{ id: 1, name: 'Alun-alun Contong' }, { id: 2, name: 'Bubutan' }, { id: 3, name: 'Gundih' }, { id: 4, name: 'Jepara' }, { id: 5, name: 'Tembok Dukuh' }],
            'Genteng':          [{ id: 6, name: 'Embong Kaliasin' }, { id: 7, name: 'Genteng' }, { id: 8, name: 'Kapasari' }, { id: 9, name: 'Ketabang' }, { id: 10, name: 'Tegalsari' }],
            'Kenjeran':         [{ id: 11, name: 'Bulak Banteng' }, { id: 12, name: 'Kedung Cowek' }, { id: 13, name: 'Kenjeran' }, { id: 14, name: 'Tanah Kali Kedinding' }],
            'Krembangan':       [{ id: 15, name: 'Dupak' }, { id: 16, name: 'Kemayoran' }, { id: 17, name: 'Krembangan Selatan' }, { id: 18, name: 'Morokrembangan' }, { id: 19, name: 'Perak Barat' }],
            'Mulyorejo':        [{ id: 20, name: 'Dukuh Sutorejo' }, { id: 21, name: 'Kalijudan' }, { id: 22, name: 'Kalisari' }, { id: 23, name: 'Kejawan Putih Tambak' }, { id: 24, name: 'Manyar Sabrangan' }, { id: 25, name: 'Mulyorejo' }],
            'Pabean Cantian':   [{ id: 26, name: 'Bongkaran' }, { id: 27, name: 'Krembangan Utara' }, { id: 28, name: 'Nyamplungan' }, { id: 29, name: 'Perak Timur' }, { id: 30, name: 'Perak Utara' }],
            'Rungkut':          [{ id: 31, name: 'Kalirungkut' }, { id: 32, name: 'Kedung Baruk' }, { id: 33, name: 'Medokan Ayu' }, { id: 34, name: 'Penjaringan Sari' }, { id: 35, name: 'Rungkut Kidul' }, { id: 36, name: 'Wonorejo' }],
            'Sambikerep':       [{ id: 37, name: 'Bringin' }, { id: 38, name: 'Lontar' }, { id: 39, name: 'Made' }, { id: 40, name: 'Sambikerep' }],
            'Sawahan':          [{ id: 41, name: 'Banyu Urip' }, { id: 42, name: 'Kupang Krajan' }, { id: 43, name: 'Pakis' }, { id: 44, name: 'Petemon' }, { id: 45, name: 'Putat Jaya' }, { id: 46, name: 'Sawahan' }],
            'Semampir':         [{ id: 47, name: 'Ampel' }, { id: 48, name: 'Pegirian' }, { id: 49, name: 'Sidotopo' }, { id: 50, name: 'Sidotopo Wetan' }, { id: 51, name: 'Ujung' }],
            'Simokerto':        [{ id: 52, name: 'Kapasan' }, { id: 53, name: 'Sidodadi' }, { id: 54, name: 'Simokerto' }, { id: 55, name: 'Simolawang' }, { id: 56, name: 'Tambakrejo' }],
            'Sukolilo':         [{ id: 57, name: 'Gebang Putih' }, { id: 58, name: 'Keputih' }, { id: 59, name: 'Klampis Ngasem' }, { id: 60, name: 'Menur Pumpungan' }, { id: 61, name: 'Nginden Jangkungan' }, { id: 62, name: 'Semolowaru' }],
            'Sukomanunggal':    [{ id: 63, name: 'Putat Gede' }, { id: 64, name: 'Simomulyo' }, { id: 65, name: 'Simomulyo Baru' }, { id: 66, name: 'Sonokwijenan' }, { id: 67, name: 'Sukomanunggal' }, { id: 68, name: 'Tanjungsari' }],
            'Tambaksari':       [{ id: 69, name: 'Dukuh Setro' }, { id: 70, name: 'Gading' }, { id: 71, name: 'Kapas Madya Baru' }, { id: 72, name: 'Pacar Keling' }, { id: 73, name: 'Pacar Kembang' }, { id: 74, name: 'Ploso' }, { id: 75, name: 'Rangkah' }, { id: 76, name: 'Tambaksari' }],
            'Tandes':           [{ id: 77, name: 'Balongsari' }, { id: 78, name: 'Banjar Sugihan' }, { id: 79, name: 'Karang Poh' }, { id: 80, name: 'Manukan Kulon' }, { id: 81, name: 'Manukan Wetan' }, { id: 82, name: 'Tandes' }],
            'Tegalsari':        [{ id: 83, name: 'Kedungdoro' }, { id: 84, name: 'Keputran' }, { id: 85, name: 'Tegalsari' }, { id: 86, name: 'Dr. Soetomo' }],
            'Tenggilis Mejoyo': [{ id: 87, name: 'Kendangsari' }, { id: 88, name: 'Kutisari' }, { id: 89, name: 'Panjang Jiwo' }, { id: 90, name: 'Tenggilis Mejoyo' }],
            'Wiyung':           [{ id: 91, name: 'Babatan' }, { id: 92, name: 'Balas Klumprik' }, { id: 93, name: 'Jajar Tunggal' }, { id: 94, name: 'Wiyung' }],
            'Wonocolo':         [{ id: 95, name: 'Bendul Merisi' }, { id: 96, name: 'Jemur Wonosari' }, { id: 97, name: 'Margorejo' }, { id: 98, name: 'Sidosermo' }, { id: 99, name: 'Siwalankerto' }],
            'Wonokromo':        [{ id: 100, name: 'Darmo' }, { id: 101, name: 'Jagir' }, { id: 102, name: 'Ngagel' }, { id: 103, name: 'Ngagelrejo' }, { id: 104, name: 'Sawunggaling' }, { id: 105, name: 'Wonokromo' }],
        };

        // ── CATEGORY SELECT ──
        var selectedCategory = null;
        function selectCategory(el, value) {
            document.querySelectorAll('.cat-card').forEach(c => c.classList.remove('active'));
            el.classList.add('active');
            selectedCategory = value;
            document.getElementById('categoryInput').value = value;
            updateSubmitBtn();
        }

        // ── WATER SOURCE CHIPS ──
        function toggleChip(input) {
            input.parentElement.classList.toggle('active', input.checked);
        }

        // ── KELURAHAN LOADER ──
        function loadKelurahan(kecamatan) {
            var sel = document.getElementById('kelSelect');
            sel.innerHTML = '<option value="">Pilih Kelurahan...</option>';
            if (!kecamatan || !kelurahanMap[kecamatan]) return;
            kelurahanMap[kecamatan].forEach(function(k) {
                var opt = document.createElement('option');
                opt.value = k.id;
                opt.textContent = k.name;
                sel.appendChild(opt);
            });
        }

        // ── CHAR COUNT ──
        document.getElementById('descTextarea').addEventListener('input', function() {
            document.getElementById('charCount').textContent = this.value.length + ' / 500';
        });

        // ── PHOTO PREVIEW ──
        function previewPhoto(input) {
            if (!input.files || !input.files[0]) return;
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('uploadPlaceholder').classList.add('hidden');
                document.getElementById('photoPreview').classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }

        // Drag and drop
        var zone = document.getElementById('uploadZone');
        zone.addEventListener('dragover', function(e) { e.preventDefault(); zone.classList.add('drag'); });
        zone.addEventListener('dragleave', function() { zone.classList.remove('drag'); });
        zone.addEventListener('drop', function(e) {
            e.preventDefault();
            zone.classList.remove('drag');
            var file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                document.getElementById('photoInput').files = e.dataTransfer.files;
                previewPhoto(document.getElementById('photoInput'));
            }
        });

        // ── SUBMIT BUTTON STATE ──
        function updateSubmitBtn() {
            var btn = document.getElementById('submitBtn');
            var label = document.getElementById('submitLabel');
            if (!selectedCategory) {
                btn.disabled = true;
                label.textContent = 'Pilih kategori masalah dulu ↑';
            } else {
                btn.disabled = false;
                var catLabels = { bau: 'Bau Menyengat', warna: 'Air Berubah Warna', sakit_perut: 'Sakit / Diare', rasa_aneh: 'Rasa Aneh', lainnya: 'Masalah Lainnya' };
                label.textContent = 'Kirim Laporan Anonim →';
            }
        }

        // ── LOADING STATE ON SUBMIT ──
        document.getElementById('reportForm').addEventListener('submit', function() {
            var btn = document.getElementById('submitBtn');
            btn.disabled = true;
            document.getElementById('submitLabel').textContent = 'Mengirim...';
        });
        </script>
    </body>
</html>