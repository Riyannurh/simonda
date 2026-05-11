<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SIMONDA - Sistem Informasi Monitoring Data UMKM Kabupaten Purworejo</title>
    <meta name="description" content="Sistem Informasi Monitoring Data UMKM Kabupaten Purworejo" />
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/icons/Lambang_Kabupaten_Purworejo.ico') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/remixicon/remixicon.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <style>
        :root { --primary: #696cff; --primary-dark: #5558e3; }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; margin: 0; color: #333; overflow-x: hidden; }

        /* NAVBAR */
        .lp-navbar { position: fixed; top: 0; left: 0; right: 0; z-index: 1000; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(0,0,0,.08); padding: 0 2rem; display: flex; align-items: center; justify-content: space-between; height: 64px; }
        .lp-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .lp-brand img { height: 40px; }
        .lp-brand-text { font-size: 1.2rem; font-weight: 700; color: var(--primary); letter-spacing: -.5px; }
        .lp-brand-sub { font-size: .7rem; color: #666; line-height: 1; }
        .lp-nav-links { display: flex; gap: 2rem; list-style: none; margin: 0; padding: 0; }
        .lp-nav-links a { text-decoration: none; color: #555; font-weight: 500; font-size: .9rem; transition: color .2s; }
        .lp-nav-links a:hover { color: var(--primary); }
        .btn-login { background: var(--primary); color: #fff; border: none; padding: .5rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; font-size: .9rem; transition: background .2s; }
        .btn-login:hover { background: var(--primary-dark); color: #fff; }

        /* HERO */
        .lp-hero { min-height: 100vh; padding-top: 64px; position: relative; overflow: hidden; display: flex; align-items: center; }
        .hero-bg { position: absolute; inset: 0; background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); }
        .hero-bg-img { position: absolute; inset: 0; background: url('{{ asset("assets/img/Alun-Alun_Purworejo_(3).jpg") }}') center/cover no-repeat; opacity: .25; }
        .hero-overlay { position: absolute; inset: 0; background: linear-gradient(to right, rgba(26,26,46,.95) 50%, rgba(26,26,46,.5)); }
        .hero-content { position: relative; z-index: 2; width: 100%; max-width: 1200px; margin: 0 auto; padding: 4rem 2rem; display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; }
        .hero-left { color: #fff; min-width: 0; }
        .hero-badge { display: inline-flex; align-items: center; gap: 6px; background: rgba(105,108,255,.2); border: 1px solid rgba(105,108,255,.4); color: #a5a7ff; padding: .35rem .9rem; border-radius: 50px; font-size: .8rem; font-weight: 600; margin-bottom: 1.5rem; max-width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .hero-title { font-size: 3rem; font-weight: 800; line-height: 1.15; margin: 0 0 1rem; word-break: break-word; }
        .hero-title span { color: var(--primary); }
        .hero-subtitle { font-size: 1.05rem; color: rgba(255,255,255,.7); line-height: 1.7; margin-bottom: 2rem; word-break: break-word; }
        .hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; }
        .btn-hero-primary { background: var(--primary); color: #fff; padding: .85rem 2rem; border-radius: 10px; font-weight: 700; text-decoration: none; font-size: 1rem; transition: all .2s; display: inline-flex; align-items: center; gap: 8px; white-space: nowrap; }
        .btn-hero-primary:hover { background: var(--primary-dark); color: #fff; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(105,108,255,.4); }
        .btn-hero-outline { border: 2px solid rgba(255,255,255,.3); color: #fff; padding: .85rem 2rem; border-radius: 10px; font-weight: 600; text-decoration: none; font-size: 1rem; transition: all .2s; white-space: nowrap; }
        .btn-hero-outline:hover { border-color: #fff; color: #fff; background: rgba(255,255,255,.1); }
        .hero-right { display: flex; justify-content: center; }
        .hero-logo-card { background: rgba(255,255,255,.08); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,.15); border-radius: 24px; padding: 3rem; text-align: center; }
        .hero-logo-card img { width: 180px; filter: drop-shadow(0 8px 32px rgba(105,108,255,.4)); }
        .hero-logo-card h3 { color: #fff; margin: 1.5rem 0 .5rem; font-size: 1.3rem; }
        .hero-logo-card p { color: rgba(255,255,255,.6); font-size: .85rem; margin: 0; }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="lp-navbar">
        <a href="{{ route('landing') }}" class="lp-brand">
            <img src="{{ asset('assets/img/Lambang_Kabupaten_Purworejo.png') }}" alt="Logo Purworejo">
            <div>
                <div class="lp-brand-text">SIMONDA</div>
                <div class="lp-brand-sub">Kabupaten Purworejo</div>
            </div>
        </a>
        <ul class="lp-nav-links d-none d-md-flex">
            <li><a href="#hero">Beranda</a></li>
            <li><a href="#statistik">Statistik</a></li>
            <li><a href="#fitur">Fitur</a></li>
            <li><a href="#wilayah">Wilayah</a></li>
            <li><a href="#tentang">Tentang</a></li>
            <li><a href="#dokumentasi">Unduhan</a></li>
        </ul>
        <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="btn-login">
            <i class="{{ auth()->check() ? 'ri-dashboard-line' : 'ri-login-box-line' }} me-1"></i>
            {{ auth()->check() ? 'Dashboard' : 'Masuk' }}
        </a>
    </nav>

    <!-- HERO -->
    <section id="hero" class="lp-hero">
        <div class="hero-bg"></div>
        <div class="hero-bg-img"></div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="hero-left">
                <div class="hero-badge">
                    <i class="ri-government-line"></i>
                    Dinas Koperasi & UMKM Kabupaten Purworejo
                </div>
                <h1 class="hero-title">
                    Sistem Informasi<br>
                    <span>Monitoring Data</span><br>
                    UMKM
                </h1>
                <p class="hero-subtitle">
                    Platform terintegrasi untuk pendataan, monitoring, dan evaluasi
                    perkembangan UMKM di seluruh wilayah Kabupaten Purworejo secara
                    real-time dan akurat.
                </p>
                <div class="hero-actions">
                    <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="btn-hero-primary">
                        <i class="{{ auth()->check() ? 'ri-dashboard-line' : 'ri-login-box-line' }}"></i>
                        {{ auth()->check() ? 'Ke Dashboard' : 'Masuk ke Sistem' }}
                    </a>
                    <a href="#statistik" class="btn-hero-outline">
                        Lihat Statistik
                    </a>
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-logo-card">
                    <img src="{{ asset('assets/img/Lambang_Kabupaten_Purworejo.png') }}" alt="Lambang Purworejo">
                    <h3>Kabupaten Purworejo</h3>
                    <p>Jawa Tengah, Indonesia</p>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* STATISTIK */
        .lp-stats { background: #fff; padding: 5rem 2rem; }
        .section-label { text-align: center; color: var(--primary); font-weight: 700; font-size: .85rem; letter-spacing: 2px; text-transform: uppercase; margin-bottom: .75rem; }
        .section-title { text-align: center; font-size: 2rem; font-weight: 800; color: #1a1a2e; margin: 0 0 .75rem; }
        .section-sub { text-align: center; color: #666; font-size: 1rem; margin: 0 0 3rem; }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; max-width: 1000px; margin: 0 auto; }
        .stat-card { background: linear-gradient(135deg, #f8f9ff, #fff); border: 1px solid #e8e9ff; border-radius: 16px; padding: 2rem 1.5rem; text-align: center; transition: transform .2s, box-shadow .2s; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(105,108,255,.15); }
        .stat-icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.5rem; }
        .stat-number { font-size: 2.2rem; font-weight: 800; color: #1a1a2e; line-height: 1; margin-bottom: .4rem; }
        .stat-label { color: #666; font-size: .9rem; font-weight: 500; }

        /* FITUR */
        .lp-features { background: #f8f9ff; padding: 5rem 2rem; }
        .features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; max-width: 1100px; margin: 0 auto; }
        .feature-card { background: #fff; border-radius: 16px; padding: 2rem; border: 1px solid #eee; transition: all .2s; }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,.08); border-color: var(--primary); }
        .feature-icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; margin-bottom: 1.25rem; }
        .feature-title { font-size: 1.05rem; font-weight: 700; color: #1a1a2e; margin: 0 0 .6rem; }
        .feature-desc { color: #666; font-size: .9rem; line-height: 1.6; margin: 0; }

        /* WILAYAH */
        .lp-wilayah { background: #fff; padding: 5rem 2rem; }
        .wilayah-content { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; max-width: 1100px; margin: 0 auto; }
        .wilayah-map { border-radius: 20px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,.12); }
        .wilayah-map img { width: 100%; display: block; }
        .wilayah-info h2 { font-size: 2rem; font-weight: 800; color: #1a1a2e; margin: 0 0 1rem; }
        .wilayah-info p { color: #666; line-height: 1.7; margin-bottom: 1.5rem; }
        .wilayah-list { list-style: none; padding: 0; margin: 0; display: grid; grid-template-columns: 1fr 1fr; gap: .6rem; }
        .wilayah-list li { display: flex; align-items: center; gap: 8px; color: #555; font-size: .9rem; }
        .wilayah-list li i { color: var(--primary); font-size: 1rem; }

        /* TENTANG */
        .lp-tentang { background: linear-gradient(135deg, #1a1a2e, #0f3460); padding: 5rem 2rem; color: #fff; }
        .tentang-content { max-width: 900px; margin: 0 auto; text-align: center; }
        .tentang-content h2 { font-size: 2.2rem; font-weight: 800; margin: 0 0 1.5rem; }
        .tentang-content p { color: rgba(255,255,255,.75); font-size: 1.05rem; line-height: 1.8; margin-bottom: 1.5rem; }
        .tentang-roles { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-top: 3rem; }
        .role-card { background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.15); border-radius: 16px; padding: 1.75rem; text-align: center; }
        .role-card i { font-size: 2rem; color: var(--primary); margin-bottom: 1rem; display: block; }
        .role-card h4 { color: #fff; margin: 0 0 .5rem; font-size: 1rem; }
        .role-card p { color: rgba(255,255,255,.6); font-size: .85rem; margin: 0; line-height: 1.5; }

        /* FOOTER */
        .lp-footer { background: #0d0d1a; color: rgba(255,255,255,.5); padding: 2rem; text-align: center; font-size: .85rem; }
        .lp-footer a { color: var(--primary); text-decoration: none; }

        /* DOKUMENTASI */
        .lp-docs { background: #f8f9ff; padding: 5rem 2rem; }
        .docs-content { max-width: 900px; margin: 0 auto; }
        .docs-grid { display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem; }
        .doc-card { background: #fff; border: 1px solid #e8e9ff; border-radius: 14px; padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1.25rem; transition: box-shadow .2s; }
        .doc-card:hover { box-shadow: 0 6px 24px rgba(105,108,255,.1); }
        .doc-icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; }
        .doc-info { flex: 1; }
        .doc-info h4 { font-size: 1rem; font-weight: 700; color: #1a1a2e; margin: 0 0 .25rem; }
        .doc-info p { font-size: .85rem; color: #666; margin: 0 0 .4rem; line-height: 1.5; }
        .doc-badge { font-size: .75rem; color: #888; background: #f0f0f0; padding: .2rem .6rem; border-radius: 20px; }
        .doc-btn { width: 42px; height: 42px; border-radius: 10px; border: 1.5px solid #e0e0e0; background: #fff; color: #888; font-size: 1.1rem; cursor: not-allowed; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .doc-btn.disabled { opacity: .5; }
        .docs-note { text-align: center; color: #888; font-size: .85rem; margin: 0; }

        @media (max-width: 768px) {
            /* Navbar */
            .lp-navbar { padding: 0 1rem; height: 56px; }
            .lp-brand img { height: 32px; }
            .lp-brand-text { font-size: 1rem; }
            .lp-brand-sub { display: none; }
            .btn-login { padding: .4rem .9rem; font-size: .8rem; }

            /* Hero — fix overflow */
            .lp-hero { padding-top: 56px; }
            .hero-overlay { background: rgba(26,26,46,.9); }
            .hero-content { grid-template-columns: 1fr; gap: 0; padding: 2.5rem 1.25rem; }
            .hero-right { display: none; }
            .hero-badge { font-size: .72rem; padding: .3rem .7rem; max-width: calc(100vw - 2.5rem); }
            .hero-title { font-size: 1.75rem; }
            .hero-subtitle { font-size: .9rem; }
            .hero-actions { flex-direction: column; gap: .75rem; }
            .btn-hero-primary, .btn-hero-outline { width: 100%; justify-content: center; padding: .75rem 1rem; font-size: .95rem; box-sizing: border-box; }

            /* Sections */
            .lp-stats, .lp-features, .lp-wilayah, .lp-tentang { padding: 3rem 1.25rem; }

            /* Stats */
            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: .75rem; max-width: 100%; }
            .stat-card { padding: 1.25rem 1rem; }
            .stat-number { font-size: 1.5rem; }

            /* Features */
            .features-grid { grid-template-columns: 1fr; gap: 1rem; max-width: 100%; }

            /* Wilayah */
            .wilayah-content { grid-template-columns: 1fr; gap: 2rem; max-width: 100%; }
            .wilayah-list { grid-template-columns: 1fr 1fr; }
            .wilayah-info h2 { font-size: 1.4rem; }

            /* Tentang */
            .tentang-roles { grid-template-columns: 1fr; gap: 1rem; }
            .tentang-content h2 { font-size: 1.5rem; }

            /* Section titles */
            .section-title { font-size: 1.4rem; }
            .section-sub { font-size: .88rem; margin-bottom: 2rem; }

            /* Nav links */
            .lp-nav-links { display: none !important; }
        }

        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr 1fr; gap: .6rem; }
            .stat-icon { width: 40px; height: 40px; font-size: 1.1rem; }
            .stat-number { font-size: 1.3rem; }
            .hero-title { font-size: 1.5rem; }
            .wilayah-list { grid-template-columns: 1fr 1fr; }
            .feature-card { padding: 1.25rem; }
        }
    </style>

    <!-- STATISTIK -->
    <section id="statistik" class="lp-stats">
        <p class="section-label">Data Real-time</p>
        <h2 class="section-title">Statistik UMKM Kabupaten Purworejo</h2>
        <p class="section-sub">Data terkini yang tercatat dalam sistem SIMONDA</p>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background:#eef0ff; color:var(--primary)"><i class="ri-store-3-line"></i></div>
                <div class="stat-number">{{ number_format($totalUmkm) }}</div>
                <div class="stat-label">Total UMKM Terdaftar</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#e8f8ee; color:#28a745"><i class="ri-map-pin-2-line"></i></div>
                <div class="stat-number">{{ $totalKecamatan }}</div>
                <div class="stat-label">Kecamatan Terjangkau</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#fff8e8; color:#ffab00"><i class="ri-team-line"></i></div>
                <div class="stat-number">{{ number_format($totalKaryawan) }}</div>
                <div class="stat-label">Total Tenaga Kerja</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#e8f4ff; color:#03c3ec"><i class="ri-user-star-line"></i></div>
                <div class="stat-number">{{ $totalPendamping }}</div>
                <div class="stat-label">Pendamping Aktif</div>
            </div>
        </div>
    </section>

    <!-- FITUR -->
    <section id="fitur" class="lp-features">
        <p class="section-label">Fitur Unggulan</p>
        <h2 class="section-title">Semua yang Anda Butuhkan</h2>
        <p class="section-sub">Sistem lengkap untuk pengelolaan data UMKM dari hulu ke hilir</p>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon" style="background:#eef0ff; color:var(--primary)"><i class="ri-database-2-line"></i></div>
                <h3 class="feature-title">Pendataan Terstruktur</h3>
                <p class="feature-desc">Input data UMKM secara lengkap mulai dari profil pemilik, data usaha, legalitas, hingga media sosial dalam satu formulir terintegrasi.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:#e8f8ee; color:#28a745"><i class="ri-bar-chart-2-line"></i></div>
                <h3 class="feature-title">Laporan & Analitik</h3>
                <p class="feature-desc">Generate laporan dalam format Excel dan PDF dengan visualisasi grafik distribusi kelas usaha, kecamatan, dan tren pendataan.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:#fff8e8; color:#ffab00"><i class="ri-user-settings-line"></i></div>
                <h3 class="feature-title">Multi Peran</h3>
                <p class="feature-desc">Sistem mendukung tiga peran: Admin, Pendamping, dan Kepala Bagian dengan hak akses yang berbeda sesuai kebutuhan.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:#e8f4ff; color:#03c3ec"><i class="ri-map-2-line"></i></div>
                <h3 class="feature-title">Berbasis Wilayah</h3>
                <p class="feature-desc">Data terorganisir berdasarkan wilayah kecamatan dan desa di Kabupaten Purworejo untuk monitoring yang lebih terarah.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:#ffeef0; color:#ff3e1d"><i class="ri-file-text-line"></i></div>
                <h3 class="feature-title">Manajemen Legalitas</h3>
                <p class="feature-desc">Pantau status legalitas UMKM seperti NIB, NPWP, SIUP, Sertifikat Halal, PIRT, dan HKI secara terpusat.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:#f0e8ff; color:#8c57ff"><i class="ri-line-chart-line"></i></div>
                <h3 class="feature-title">Klasifikasi Otomatis</h3>
                <p class="feature-desc">Sistem otomatis mengklasifikasikan UMKM ke dalam kelas Mikro, Kecil, atau Menengah berdasarkan omset dan aset yang diinput.</p>
            </div>
        </div>
    </section>

    <!-- WILAYAH -->
    <section id="wilayah" class="lp-wilayah">
        <div class="wilayah-content">
            <div class="wilayah-map">
                <img src="{{ asset('assets/img/peta.png') }}" alt="Peta Kabupaten Purworejo">
            </div>
            <div class="wilayah-info">
                <p class="section-label" style="text-align:left">Cakupan Wilayah</p>
                <h2>Seluruh Kecamatan di Kabupaten Purworejo</h2>
                <p>SIMONDA mencakup seluruh 16 kecamatan di Kabupaten Purworejo, memastikan setiap UMKM di pelosok wilayah dapat terdata dan terpantau dengan baik.</p>
                <ul class="wilayah-list">
                    <li><i class="ri-map-pin-fill"></i> Purworejo</li>
                    <li><i class="ri-map-pin-fill"></i> Kutoarjo</li>
                    <li><i class="ri-map-pin-fill"></i> Banyuurip</li>
                    <li><i class="ri-map-pin-fill"></i> Bayan</li>
                    <li><i class="ri-map-pin-fill"></i> Bagelen</li>
                    <li><i class="ri-map-pin-fill"></i> Kaligesing</li>
                    <li><i class="ri-map-pin-fill"></i> Purwodadi</li>
                    <li><i class="ri-map-pin-fill"></i> Ngombol</li>
                    <li><i class="ri-map-pin-fill"></i> Grabag</li>
                    <li><i class="ri-map-pin-fill"></i> Kemiri</li>
                    <li><i class="ri-map-pin-fill"></i> Bruno</li>
                    <li><i class="ri-map-pin-fill"></i> Pituruh</li>
                    <li><i class="ri-map-pin-fill"></i> Loano</li>
                    <li><i class="ri-map-pin-fill"></i> Bener</li>
                    <li><i class="ri-map-pin-fill"></i> Butuh</li>
                    <li><i class="ri-map-pin-fill"></i> Gebang</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- TENTANG -->
    <section id="tentang" class="lp-tentang">
        <div class="tentang-content">
            <p class="section-label" style="color:#a5a7ff">Tentang Sistem</p>
            <h2>SIMONDA — Solusi Digital untuk UMKM Purworejo</h2>
            <p>SIMONDA (Sistem Informasi Monitoring Data UMKM) adalah platform digital yang dikembangkan oleh Dinas Koperasi dan UMKM Kabupaten Purworejo untuk mempermudah proses pendataan, monitoring, dan evaluasi perkembangan UMKM di seluruh wilayah Kabupaten Purworejo.</p>
        </div>
    </section>

    <!-- DOKUMENTASI -->
    <section id="dokumentasi" class="lp-docs">
        <div class="docs-content">
            <p class="section-label" style="text-align:center">Panduan Penggunaan</p>
            <h2 class="section-title">Dokumentasi SIMONDA</h2>
            <p class="section-sub">Unduh panduan penggunaan sistem untuk membantu operasional sehari-hari</p>
            <div class="docs-grid">
                <div class="doc-card">
                    <div class="doc-icon" style="background:#eef0ff; color:var(--primary)">
                        <i class="ri-file-pdf-line"></i>
                    </div>
                    <div class="doc-info">
                        <h4>Dokumentasi SIMONDA</h4>
                        <p>Panduan lengkap penggunaan sistem SIMONDA untuk semua pengguna — Admin, Pendamping, dan Kepala Bagian</p>
                        <span class="doc-badge">PDF &bull; Segera Tersedia</span>
                    </div>
                    <button class="doc-btn disabled" disabled title="Segera tersedia">
                        <i class="ri-download-line"></i>
                    </button>
                </div>
            </div>
            <p class="docs-note">
                <i class="ri-information-line me-1"></i>
                Dokumentasi sedang dalam proses penyusunan. Hubungi admin untuk informasi lebih lanjut.
            </p>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="lp-footer">
        <p>© {{ date('Y') }} <strong style="color:#fff">SIMONDA</strong> — Dinas Koperasi & UMKM Kabupaten Purworejo. All rights reserved.</p>
        <p>Dikembangkan untuk mendukung pertumbuhan UMKM di <a href="#">Kabupaten Purworejo</a></p>
    </footer>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const target = document.querySelector(a.getAttribute('href'));
                if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
            });
        });

        // Navbar active on scroll
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.lp-nav-links a');
        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(s => { if (window.scrollY >= s.offsetTop - 80) current = s.id; });
            navLinks.forEach(a => {
                a.style.color = a.getAttribute('href') === '#' + current ? 'var(--primary)' : '';
            });
        });
    </script>
</body>
</html>
""