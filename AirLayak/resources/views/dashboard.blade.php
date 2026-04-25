<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>LaporIn — Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg: #f7f6f3;
      --surface: #ffffff;
      --border: #e8e5df;
      --text: #1a1a18;
      --muted: #8a8880;
      --accent: #2d6a4f;
      --accent-light: #e9f2ee;
      --accent-hover: #245a41;
      --high: #c0392b;
      --high-bg: #fdf0ef;
      --medium: #d4750a;
      --medium-bg: #fef6e7;
      --low: #2d6a4f;
      --low-bg: #e9f2ee;
      --shadow-sm: 0 1px 3px rgba(0,0,0,.07);
      --shadow-md: 0 4px 16px rgba(0,0,0,.08);
      --nav-w: 240px;
    }

    html, body { height: 100%; }
    body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; }

    /* ── SIDEBAR ── */
    .sidebar {
      width: var(--nav-w);
      background: var(--surface);
      border-right: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      padding: 24px 16px;
      position: fixed;
      top: 0; left: 0; bottom: 0;
      z-index: 100;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 0 8px 24px;
      border-bottom: 1px solid var(--border);
      margin-bottom: 20px;
    }
    .brand-icon { font-size: 22px; }
    .brand-name { font-family: 'Instrument Serif', serif; font-size: 20px; letter-spacing: -.3px; }

    .nav-label {
      font-size: 10px;
      font-weight: 600;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: var(--muted);
      padding: 0 10px;
      margin-bottom: 6px;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 10px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 500;
      color: var(--muted);
      cursor: pointer;
      transition: all .15s;
      text-decoration: none;
      margin-bottom: 2px;
    }
    .nav-item:hover { background: var(--bg); color: var(--text); }
    .nav-item.active { background: var(--accent-light); color: var(--accent); }
    .nav-icon { font-size: 17px; width: 22px; text-align: center; }

    .sidebar-bottom { margin-top: auto; }
    .user-chip {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px;
      border-radius: 10px;
      background: var(--bg);
    }
    .avatar {
      width: 32px; height: 32px;
      border-radius: 50%;
      background: var(--accent);
      display: flex; align-items: center; justify-content: center;
      font-size: 13px; color: #fff; font-weight: 600;
    }
    .user-info { flex: 1; min-width: 0; }
    .user-name { font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .user-type { font-size: 11px; color: var(--muted); }

    /* ── MAIN ── */
    .main {
      margin-left: var(--nav-w);
      flex: 1;
      min-height: 100vh;
      padding: 32px 36px;
      max-width: calc(100vw - var(--nav-w));
    }

    /* ── TOPBAR ── */
    .topbar {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      margin-bottom: 28px;
      gap: 16px;
    }

    .topbar-left h1 {
      font-family: 'Instrument Serif', serif;
      font-size: 28px;
      letter-spacing: -.4px;
      margin-bottom: 4px;
    }
    .topbar-left p { font-size: 14px; color: var(--muted); }

    .topbar-right { display: flex; gap: 10px; align-items: center; flex-shrink: 0; }

    .region-select {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 9px 14px;
      background: var(--surface);
      border: 1.5px solid var(--border);
      border-radius: 10px;
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
      font-weight: 500;
      color: var(--text);
      cursor: pointer;
      box-shadow: var(--shadow-sm);
    }

    .btn-report {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 9px 18px;
      background: var(--accent);
      color: #fff;
      border: none;
      border-radius: 10px;
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      transition: all .2s;
      text-decoration: none;
      box-shadow: 0 2px 8px rgba(45,106,79,.3);
    }
    .btn-report:hover { background: var(--accent-hover); transform: translateY(-1px); }

    /* ── STATS ROW ── */
    .stats-row {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 14px;
      margin-bottom: 24px;
    }

    .stat-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 18px 20px;
      box-shadow: var(--shadow-sm);
    }
    .stat-card-label { font-size: 12px; color: var(--muted); font-weight: 500; margin-bottom: 6px; }
    .stat-card-value { font-family: 'Instrument Serif', serif; font-size: 30px; letter-spacing: -.5px; }
    .stat-card-sub { font-size: 12px; color: var(--muted); margin-top: 4px; }
    .trend-up { color: var(--high); }
    .trend-ok { color: var(--accent); }

    /* ── FILTERS ── */
    .filter-bar {
      display: flex;
      gap: 8px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    .filter-chip {
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 500;
      border: 1.5px solid var(--border);
      background: var(--surface);
      color: var(--muted);
      cursor: pointer;
      transition: all .15s;
    }
    .filter-chip:hover { border-color: #c0bdb5; color: var(--text); }
    .filter-chip.active { background: var(--accent); border-color: var(--accent); color: #fff; }
    .filter-chip.high { border-color: var(--high); color: var(--high); }
    .filter-chip.high.active { background: var(--high); color: #fff; }
    .filter-chip.medium { border-color: var(--medium); color: var(--medium); }
    .filter-chip.medium.active { background: var(--medium); color: #fff; }

    /* ── REPORT LIST ── */
    .reports-grid {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .report-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 18px 20px;
      box-shadow: var(--shadow-sm);
      display: flex;
      align-items: flex-start;
      gap: 16px;
      transition: all .2s;
      cursor: pointer;
      animation: fadeUp .4s ease both;
    }
    .report-card:hover { box-shadow: var(--shadow-md); transform: translateY(-1px); }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(8px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .report-card:nth-child(1) { animation-delay: .05s; }
    .report-card:nth-child(2) { animation-delay: .10s; }
    .report-card:nth-child(3) { animation-delay: .15s; }
    .report-card:nth-child(4) { animation-delay: .20s; }

    .report-thumb {
      width: 64px; height: 64px;
      border-radius: 10px;
      background: var(--bg);
      flex-shrink: 0;
      overflow: hidden;
      display: flex; align-items: center; justify-content: center;
      font-size: 26px;
    }

    .report-body { flex: 1; min-width: 0; }
    .report-meta { display: flex; align-items: center; gap: 8px; margin-bottom: 4px; flex-wrap: wrap; }

    .severity-badge {
      font-size: 11px; font-weight: 600;
      padding: 2px 8px; border-radius: 6px;
      text-transform: uppercase; letter-spacing: .04em;
    }
    .sev-high { background: var(--high-bg); color: var(--high); }
    .sev-medium { background: var(--medium-bg); color: var(--medium); }
    .sev-low { background: var(--low-bg); color: var(--low); }

    .status-badge {
      font-size: 11px; font-weight: 500;
      padding: 2px 8px; border-radius: 6px;
      background: var(--bg); color: var(--muted);
    }

    .report-time { font-size: 12px; color: var(--muted); margin-left: auto; white-space: nowrap; }

    .report-title {
      font-weight: 600; font-size: 15px;
      margin-bottom: 4px;
      white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    .report-desc {
      font-size: 13px; color: var(--muted); line-height: 1.5;
      display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }

    .report-footer {
      display: flex; align-items: center; gap: 14px; margin-top: 10px;
    }

    .report-loc { font-size: 12px; color: var(--muted); display: flex; align-items: center; gap: 4px; }

    .upvote-btn {
      display: flex; align-items: center; gap: 5px;
      font-size: 13px; color: var(--muted);
      background: var(--bg); border: 1px solid var(--border);
      padding: 4px 10px; border-radius: 8px;
      cursor: pointer; transition: all .15s;
      font-family: 'DM Sans', sans-serif;
    }
    .upvote-btn:hover { background: var(--accent-light); color: var(--accent); border-color: var(--accent); }
    .upvote-btn.voted { background: var(--accent-light); color: var(--accent); border-color: var(--accent); }

    .escalation-banner {
      display: flex; align-items: center; gap: 8px;
      margin-top: 10px;
      padding: 8px 12px;
      background: var(--high-bg);
      border: 1px solid #f5c6c3;
      border-radius: 8px;
      font-size: 12px;
      color: var(--high);
      font-weight: 500;
    }

    /* ── NOTIFICATION TOAST ── */
    .notif-toast {
      position: fixed; top: 20px; right: 20px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-left: 4px solid var(--high);
      border-radius: 12px;
      padding: 14px 16px;
      box-shadow: var(--shadow-md);
      max-width: 320px;
      z-index: 999;
      animation: slideIn .4s ease;
    }
    @keyframes slideIn {
      from { opacity: 0; transform: translateX(20px); }
      to { opacity: 1; transform: translateX(0); }
    }
    .notif-title { font-size: 13px; font-weight: 600; margin-bottom: 4px; }
    .notif-body { font-size: 12px; color: var(--muted); line-height: 1.5; }
    .notif-action { font-size: 12px; color: var(--accent); font-weight: 500; margin-top: 8px; cursor: pointer; }

    .duplicate-tag {
      font-size: 11px; color: var(--muted);
      background: var(--bg); padding: 2px 8px; border-radius: 6px;
      border: 1px solid var(--border);
    }
  </style>
</head>
<body>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="brand">
      <span class="brand-icon">📍</span>
      <span class="brand-name">LaporIn</span>
    </div>

    <p class="nav-label">Menu</p>
    <a href="#" class="nav-item active"><span class="nav-icon">🏠</span> Dashboard</a>
    <a href="#" class="nav-item"><span class="nav-icon">📋</span> Laporan Saya</a>
    <a href="#" class="nav-item"><span class="nav-icon">🗺</span> Peta</a>
    <a href="#" class="nav-item"><span class="nav-icon">🔔</span> Notifikasi <span style="margin-left:auto;background:var(--high);color:#fff;font-size:11px;padding:1px 7px;border-radius:10px;">2</span></a>

    <p class="nav-label" style="margin-top:20px;">Pengaturan</p>
    <a href="#" class="nav-item"><span class="nav-icon">⚙️</span> Pengaturan</a>
    <a href="#" class="nav-item"><span class="nav-icon">🚪</span> Keluar</a>

    <div class="sidebar-bottom">
      <div class="user-chip">
        <div class="avatar">B</div>
        <div class="user-info">
          <div class="user-name">Budi Santoso</div>
          <div class="user-type">Google Account</div>
        </div>
      </div>
    </div>
  </aside>

  <!-- MAIN -->
  <main class="main">

    <!-- TOPBAR -->
    <div class="topbar">
      <div class="topbar-left">
        <h1>Dashboard Laporan</h1>
        <p>Hari ini, Jumat 25 April 2025 · Surabaya, Jawa Timur</p>
      </div>
      <div class="topbar-right">
        <select class="region-select">
          <option>📍 Surabaya Selatan</option>
          <option>📍 Surabaya Utara</option>
          <option>📍 Surabaya Timur</option>
          <option>📍 Surabaya Barat</option>
          <option>📍 Semua Wilayah</option>
        </select>
        <a href="/report" class="btn-report">+ Buat Laporan</a>
      </div>
    </div>

    <!-- STATS -->
    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-card-label">Total Hari Ini</div>
        <div class="stat-card-value">24</div>
        <div class="stat-card-sub trend-up">↑ 6 dari kemarin</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Belum Ditindak</div>
        <div class="stat-card-value" style="color:var(--high)">8</div>
        <div class="stat-card-sub">3 mendekati eskalasi</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Sedang Diproses</div>
        <div class="stat-card-value" style="color:var(--medium)">11</div>
        <div class="stat-card-sub">Rata-rata 1.5 hari</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Selesai</div>
        <div class="stat-card-value trend-ok">5</div>
        <div class="stat-card-sub trend-ok">↑ 87% success rate</div>
      </div>
    </div>

    <!-- FILTER BAR -->
    <div class="filter-bar">
      <button class="filter-chip active" onclick="setFilter(this)">Semua</button>
      <button class="filter-chip high" onclick="setFilter(this)">🔴 Tinggi</button>
      <button class="filter-chip medium" onclick="setFilter(this)">🟡 Sedang</button>
      <button class="filter-chip" onclick="setFilter(this)">🟢 Rendah</button>
      <button class="filter-chip" onclick="setFilter(this)">Belum Ditindak</button>
      <button class="filter-chip" onclick="setFilter(this)">Diproses</button>
      <button class="filter-chip" onclick="setFilter(this)">Selesai</button>
    </div>

    <!-- REPORT CARDS -->
    <div class="reports-grid">

      <!-- Card 1: High severity, escalated -->
      <div class="report-card">
        <div class="report-thumb">🚗</div>
        <div class="report-body">
          <div class="report-meta">
            <span class="severity-badge sev-high">Tinggi</span>
            <span class="status-badge">Belum Ditindak</span>
            <span class="duplicate-tag">+3 laporan serupa</span>
            <span class="report-time">2 jam lalu</span>
          </div>
          <div class="report-title">Kecelakaan di Jl. Raya Darmo — kendaraan masih di jalan</div>
          <div class="report-desc">Terjadi tabrakan antara motor dan angkot di depan Taman Bungkul. Tidak ada korban jiwa, namun jalanan terblokir sebagian dan membahayakan pengguna jalan lain.</div>
          <div class="report-footer">
            <span class="report-loc">📍 Jl. Raya Darmo, Surabaya Selatan</span>
            <button class="upvote-btn voted">👍 47 Saya juga alami</button>
          </div>
          <div class="escalation-banner">
            ⏰ Laporan ini telah <strong>2 hari</strong> belum ditindak — segera hubungi <strong>Dishub Surabaya: (031) 548-1111</strong>
          </div>
        </div>
      </div>

      <!-- Card 2: Medium severity -->
      <div class="report-card">
        <div class="report-thumb">🚦</div>
        <div class="report-body">
          <div class="report-meta">
            <span class="severity-badge sev-medium">Sedang</span>
            <span class="status-badge">Diproses</span>
            <span class="report-time">5 jam lalu</span>
          </div>
          <div class="report-title">Kemacetan parah di Jl. Ahmad Yani arah Wonokromo</div>
          <div class="report-desc">Macet panjang dari bundaran Dolog hingga Joyoboyo sejak pagi. Penyebab diduga perbaikan trotoar di sisi kiri jalan yang memakan satu lajur.</div>
          <div class="report-footer">
            <span class="report-loc">📍 Jl. Ahmad Yani, Surabaya Selatan</span>
            <button class="upvote-btn" onclick="this.classList.toggle('voted'); this.textContent = this.classList.contains('voted') ? '👍 32 Saya juga alami' : '👍 31 Saya juga alami'">👍 31 Saya juga alami</button>
          </div>
        </div>
      </div>

      <!-- Card 3: High severity -->
      <div class="report-card">
        <div class="report-thumb">🚧</div>
        <div class="report-body">
          <div class="report-meta">
            <span class="severity-badge sev-high">Tinggi</span>
            <span class="status-badge">Belum Ditindak</span>
            <span class="report-time">1 hari lalu</span>
          </div>
          <div class="report-title">Jalan berlubang besar di Jl. Gubeng Pojok — berbahaya malam hari</div>
          <div class="report-desc">Lubang sedalam sekitar 20cm di tengah jalan tanpa rambu peringatan. Sudah ada 2 motor yang jatuh menurut warga sekitar.</div>
          <div class="report-footer">
            <span class="report-loc">📍 Jl. Gubeng Pojok, Surabaya Timur</span>
            <button class="upvote-btn">👍 19 Saya juga alami</button>
          </div>
        </div>
      </div>

      <!-- Card 4: Low severity -->
      <div class="report-card">
        <div class="report-thumb">💡</div>
        <div class="report-body">
          <div class="report-meta">
            <span class="severity-badge sev-low">Rendah</span>
            <span class="status-badge">Selesai ✓</span>
            <span class="report-time">2 hari lalu</span>
          </div>
          <div class="report-title">Lampu PJU mati di Jl. Ngagel Rejo Kidul</div>
          <div class="report-desc">3 lampu jalan mati berturut-turut sejak seminggu lalu, mengakibatkan jalan gelap di malam hari.</div>
          <div class="report-footer">
            <span class="report-loc">📍 Jl. Ngagel Rejo, Surabaya Timur</span>
            <button class="upvote-btn">👍 8 Saya juga alami</button>
          </div>
        </div>
      </div>

    </div>

    @auth
    <a href="{{ route('auth.logout') }}" class="mt-4 px-4 py-2 bg-black text-white inline-block">
            Logout
        </a>   
    @endauth
  </main>

  <!-- NOTIFICATION TOAST -->
  {{-- <div class="notif-toast">
    <div class="notif-title">⏰ Laporan Belum Ditindak</div>
    <div class="notif-body">Laporan "Kecelakaan di Jl. Raya Darmo" sudah 2 hari belum ada tindakan.</div>
    <div class="notif-action">📞 Hubungi Dishub Surabaya →</div>
  </div> --}}

  <script>
    function setFilter(el) {
      document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
      el.classList.add('active');
    }
  </script>

</body>
</html>