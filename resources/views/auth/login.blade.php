<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login — SIMONDA</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/remixicon/remixicon.css') }}" />
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; height: 100vh; display: flex; overflow: hidden; }

        /* LEFT PANEL — gambar */
        .login-left {
            flex: 1;
            position: relative;
            display: none;
        }
        @media (min-width: 768px) { .login-left { display: block; } }

        .login-left-bg {
            position: absolute; inset: 0;
            background: url('{{ asset("assets/img/Alun-Alun_Purworejo_(3).jpg") }}') center/cover no-repeat;
        }
        .login-left-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(15,30,80,.85) 0%, rgba(26,26,46,.7) 100%);
        }
        .login-left-content {
            position: relative; z-index: 2;
            height: 100%; display: flex; flex-direction: column;
            justify-content: space-between; padding: 2.5rem;
            color: #fff;
        }
        .login-brand { display: flex; align-items: center; gap: 12px; }
        .login-brand img { height: 48px; filter: drop-shadow(0 2px 8px rgba(0,0,0,.4)); }
        .login-brand-text h2 { font-size: 1.4rem; font-weight: 700; line-height: 1.2; }
        .login-brand-text p { font-size: .8rem; opacity: .75; margin-top: 2px; }

        .login-left-body { text-align: center; }
        .login-left-body h1 { font-size: 2.2rem; font-weight: 800; line-height: 1.25; margin-bottom: 1rem; }
        .login-left-body h1 span { color: #a5a7ff; }
        .login-left-body p { font-size: 1rem; opacity: .8; line-height: 1.7; max-width: 380px; margin: 0 auto; }

        .login-left-footer { text-align: center; font-size: .8rem; opacity: .5; }

        /* RIGHT PANEL — form */
        .login-right {
            width: 100%; max-width: 480px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            background: #fff; padding: 2rem;
            overflow-y: auto;
        }
        @media (min-width: 768px) { .login-right { width: 420px; } }

        .login-form-wrap { width: 100%; max-width: 360px; }

        .login-logo-mobile {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 2rem; justify-content: center;
        }
        .login-logo-mobile img { height: 40px; }
        .login-logo-mobile span { font-size: 1.3rem; font-weight: 700; color: #696cff; }
        @media (min-width: 768px) { .login-logo-mobile { display: none; } }

        .login-form-wrap h3 { font-size: 1.5rem; font-weight: 700; color: #1a1a2e; margin-bottom: .4rem; }
        .login-form-wrap .subtitle { color: #888; font-size: .9rem; margin-bottom: 2rem; }

        .form-group { margin-bottom: 1.25rem; }
        .form-group label { display: block; font-size: .85rem; font-weight: 600; color: #444; margin-bottom: .4rem; }
        .input-wrap { position: relative; }
        .input-wrap { position: relative; display: flex; align-items: center; }
        .input-wrap .input-icon { position: absolute; left: 14px; color: #aaa; font-size: 1.1rem; pointer-events: none; }
        .input-wrap input {
            width: 100%; padding: .75rem 2.75rem .75rem 2.75rem;
            border: 1.5px solid #e0e0e0; border-radius: 10px;
            font-size: .95rem; font-family: inherit; outline: none;
            transition: border-color .2s, box-shadow .2s; background: #fafafa;
        }
        .input-wrap input:focus { border-color: #696cff; box-shadow: 0 0 0 3px rgba(105,108,255,.12); background: #fff; }
        .toggle-pw-btn {
            position: absolute; right: 10px;
            background: none; border: none; padding: 6px; cursor: pointer;
            color: #aaa; font-size: 1.1rem; line-height: 1;
            display: flex; align-items: center; justify-content: center;
        }
        .toggle-pw-btn:hover { color: #696cff; }

        .alert-error {
            background: #fff0f0; border: 1px solid #ffc5c5; border-radius: 10px;
            padding: .75rem 1rem; color: #c0392b; font-size: .85rem; margin-bottom: 1.25rem;
            display: flex; align-items: center; gap: 8px;
        }

        .btn-login {
            width: 100%; padding: .85rem; background: #696cff; color: #fff;
            border: none; border-radius: 10px; font-size: 1rem; font-weight: 700;
            cursor: pointer; font-family: inherit; transition: all .2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-login:hover { background: #5558e3; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(105,108,255,.35); }
        .btn-login:active { transform: translateY(0); }

        .login-back { text-align: center; margin-top: 1.5rem; font-size: .85rem; color: #888; }
        .login-back a { color: #696cff; text-decoration: none; font-weight: 600; }
        .login-back a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <!-- LEFT -->
    <div class="login-left">
        <div class="login-left-bg"></div>
        <div class="login-left-overlay"></div>
        <div class="login-left-content">
            <div class="login-brand">
                <img src="{{ asset('assets/img/Lambang_Kabupaten_Purworejo.png') }}" alt="Logo">
                <div class="login-brand-text">
                    <h2>SIMONDA</h2>
                    <p>Kabupaten Purworejo</p>
                </div>
            </div>
            <div class="login-left-body">
                <h1>Sistem Informasi<br><span>Monitoring Data</span><br>UMKM</h1>
                <p>Platform terintegrasi untuk pendataan dan monitoring perkembangan UMKM di seluruh wilayah Kabupaten Purworejo.</p>
            </div>
            <div class="login-left-footer">
                © {{ date('Y') }} Dinas Koperasi & UMKM Kabupaten Purworejo
            </div>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="login-right">
        <div class="login-form-wrap">

            <div class="login-logo-mobile">
                <img src="{{ asset('assets/img/Lambang_Kabupaten_Purworejo.png') }}" alt="Logo">
                <span>SIMONDA</span>
            </div>

            <h3>Selamat Datang</h3>
            <p class="subtitle">Masuk ke sistem SIMONDA Kabupaten Purworejo</p>

            @if($errors->any())
            <div class="alert-error">
                <i class="ri-error-warning-line"></i>
                {{ $errors->first() }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert-error">
                <i class="ri-error-warning-line"></i>
                {{ session('error') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <i class="ri-mail-line input-icon"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            placeholder="nama@email.com" required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <i class="ri-lock-line input-icon"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                        <button type="button" class="toggle-pw-btn" onclick="togglePassword()" tabindex="-1" aria-label="Toggle password">
                            <i id="toggleIcon" class="ri-eye-line"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn-login">
                    <i class="ri-login-box-line"></i> Masuk
                </button>
            </form>

            <div class="login-back">
                <a href="{{ route('landing') }}"><i class="ri-arrow-left-line"></i> Kembali ke Beranda</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('toggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'ri-eye-off-line';
            } else {
                input.type = 'password';
                icon.className = 'ri-eye-line';
            }
            input.focus();
        }
    </script>
</body>
</html>
