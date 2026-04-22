<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Daftar Akun Wali Santri - Sistem Informasi Mobilitas Santri Husnul Khotimah">
    <title>Daftar Akun Wali | Mobilitas Santri HK</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 50%, #a5d6a7 100%);
            overflow: hidden;
            position: relative;
            padding: 20px;
        }

        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            opacity: 0.08;
            z-index: 0;
        }
        body::before {
            width: 400px; height: 400px;
            background: #1b5e20;
            top: -100px; right: -100px;
            animation: float-bg 20s ease-in-out infinite;
        }
        body::after {
            width: 300px; height: 300px;
            background: #2e7d32;
            bottom: -80px; left: -80px;
            animation: float-bg 25s ease-in-out infinite reverse;
        }

        @keyframes float-bg {
            0%, 100% { transform: translate(0,0) scale(1); }
            25%  { transform: translate(30px,-20px) scale(1.05); }
            50%  { transform: translate(-20px,30px) scale(0.95); }
            75%  { transform: translate(20px,20px) scale(1.02); }
        }

        .register-container {
            display: flex;
            width: 960px;
            max-width: 98vw;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(27,94,32,.15), 0 10px 20px rgba(0,0,0,.06);
            position: relative;
            z-index: 1;
            animation: slideUp .8s cubic-bezier(.16,1,.3,1);
        }

        @keyframes slideUp {
            from { opacity:0; transform:translateY(40px) scale(.96); }
            to   { opacity:1; transform:translateY(0) scale(1); }
        }

        /* ====== LEFT PANEL ====== */
        .reg-left {
            flex: 1;
            background: linear-gradient(160deg, #1b5e20 0%, #2e7d32 40%, #388e3c 70%, #43a047 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px 40px;
            position: relative;
            overflow: hidden;
            color: #fff;
        }

        .reg-left::before {
            content:'';
            position:absolute;
            width:280px; height:280px;
            background:rgba(255,255,255,.06);
            border-radius:60% 40% 30% 70% / 60% 30% 70% 40%;
            top:-40px; right:-60px;
            animation: morph 12s ease-in-out infinite;
        }
        .reg-left::after {
            content:'';
            position:absolute;
            width:200px; height:200px;
            background:rgba(255,255,255,.04);
            border-radius:40% 60% 70% 30% / 40% 70% 30% 60%;
            bottom:-30px; left:-40px;
            animation: morph 15s ease-in-out infinite reverse;
        }

        @keyframes morph {
            0%,100% { border-radius:60% 40% 30% 70% / 60% 30% 70% 40%; }
            25%  { border-radius:30% 60% 70% 40% / 50% 60% 30% 60%; }
            50%  { border-radius:50% 60% 30% 60% / 30% 40% 70% 60%; }
            75%  { border-radius:40% 30% 60% 50% / 60% 70% 40% 30%; }
        }

        .reg-left .icon-wrapper {
            width:80px; height:80px;
            background:rgba(255,255,255,.15);
            backdrop-filter:blur(10px);
            border-radius:20px;
            display:flex; align-items:center; justify-content:center;
            margin-bottom:24px;
            border:1px solid rgba(255,255,255,.2);
            animation: iconPulse 3s ease-in-out infinite;
            position:relative; z-index:2;
        }
        @keyframes iconPulse {
            0%,100% { transform:scale(1); box-shadow:0 0 0 0 rgba(255,255,255,.1); }
            50%     { transform:scale(1.05); box-shadow:0 0 20px 5px rgba(255,255,255,.05); }
        }
        .reg-left .icon-wrapper i { font-size:36px; color:#fff; }

        .reg-left .brand-name {
            font-size:13px; font-weight:500;
            letter-spacing:3px; text-transform:uppercase;
            margin-bottom:16px; opacity:.85;
            position:relative; z-index:2;
        }

        .reg-left h1 {
            font-size:28px; font-weight:800;
            margin-bottom:12px;
            position:relative; z-index:2;
            text-align:center; line-height:1.2;
        }

        .reg-left p {
            font-size:13px; opacity:.8;
            max-width:240px; text-align:center;
            line-height:1.7;
            position:relative; z-index:2;
        }

        .reg-left .steps {
            margin-top:28px;
            position:relative; z-index:2;
            width:100%;
        }

        .reg-left .step-item {
            display:flex; align-items:flex-start; gap:12px;
            margin-bottom:14px;
        }

        .step-num {
            width:24px; height:24px; min-width:24px;
            background:rgba(255,255,255,.2);
            border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            font-size:11px; font-weight:700;
            margin-top:1px;
        }

        .step-text { font-size:12px; opacity:.85; line-height:1.5; }

        /* ====== RIGHT PANEL ====== */
        .reg-right {
            flex: 1.3;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 45px;
            overflow-y: auto;
            max-height: 100vh;
        }

        .reg-right h2 {
            font-size:24px; font-weight:700;
            color:#1b5e20; margin-bottom:6px;
        }
        .reg-right .subtitle {
            font-size:12px; color:#888; margin-bottom:24px;
            text-align:center;
        }

        /* Alerts */
        .alert {
            width:100%; padding:12px 16px;
            border-radius:10px; font-size:13px;
            margin-bottom:16px; animation:fadeIn .4s ease;
        }
        .alert-success { background:#e8f5e9; color:#2e7d32; border:1px solid #c8e6c9; }
        .alert-danger  { background:#fce4ec; color:#c62828; border:1px solid #ffcdd2; }
        .alert-danger ul { margin:0; padding-left:18px; }
        .alert-warning { background:#fff8e1; color:#f57f17; border:1px solid #ffe082; }

        @keyframes fadeIn {
            from { opacity:0; transform:translateY(-8px); }
            to   { opacity:1; transform:translateY(0); }
        }

        .register-form { width:100%; max-width:380px; }

        .form-group { margin-bottom:16px; position:relative; }

        .form-group label {
            display:block; font-size:11px; font-weight:600;
            color:#555; margin-bottom:6px;
            text-transform:uppercase; letter-spacing:.5px;
        }

        .label-optional {
            font-size:10px; font-weight:400;
            color:#aaa; text-transform:none; letter-spacing:0;
            margin-left:4px;
        }

        .input-wrapper { position:relative; }

        .input-wrapper > i.field-icon {
            position:absolute; left:16px; top:50%;
            transform:translateY(-50%);
            color:#aaa; font-size:15px;
            transition:color .3s ease;
            pointer-events:none; z-index:1;
        }

        .form-control {
            width:100%;
            padding:12px 46px 12px 46px;
            border:2px solid #e0e0e0;
            border-radius:12px; font-size:13px;
            font-family:'Poppins', sans-serif;
            color:#333; background:#fafafa;
            transition:all .3s ease; outline:none;
        }
        .form-control::placeholder { color:#bbb; font-weight:300; }
        .form-control:focus {
            border-color:#43a047; background:#fff;
            box-shadow:0 0 0 4px rgba(67,160,71,.08);
        }
        .input-wrapper:focus-within i.field-icon { color:#43a047; }
        .form-control.is-invalid { border-color:#e53935; }
        .form-control.is-valid   { border-color:#43a047; }

        .password-toggle {
            position:absolute; right:14px; top:50%;
            transform:translateY(-50%);
            background:none; border:none; cursor:pointer;
            color:#bbb; font-size:16px;
            transition:color .3s; padding:4px;
            z-index:2; line-height:1;
        }
        .password-toggle:hover { color:#43a047; }

        /* Status indicator untuk No HP */
        .nohp-status {
            position:absolute; right:14px; top:50%;
            transform:translateY(-50%); font-size:15px;
            display:none;
        }
        .nohp-status.checking { color:#aaa; display:block; }
        .nohp-status.available { color:#43a047; display:block; }
        .nohp-status.taken { color:#e53935; display:block; }

        .field-hint {
            font-size:11px; color:#aaa;
            margin-top:4px; padding-left:4px;
            display:block;
        }
        .field-hint.error { color:#e53935; }
        .field-hint.success { color:#43a047; }

        .btn-register {
            width:100%; padding:13px;
            background:linear-gradient(135deg,#2e7d32 0%,#43a047 100%);
            color:#fff; border:none; border-radius:12px;
            font-size:14px; font-weight:600;
            font-family:'Poppins', sans-serif;
            cursor:pointer; transition:all .3s ease;
            letter-spacing:.5px; position:relative; overflow:hidden;
            margin-top:8px;
        }
        .btn-register::before {
            content:''; position:absolute;
            top:0; left:-100%; width:100%; height:100%;
            background:linear-gradient(90deg, transparent, rgba(255,255,255,.15), transparent);
            transition:left .5s ease;
        }
        .btn-register:hover {
            transform:translateY(-2px);
            box-shadow:0 8px 25px rgba(46,125,50,.35);
        }
        .btn-register:hover::before { left:100%; }
        .btn-register:active { transform:translateY(0); }
        .btn-register:disabled {
            opacity:.6; cursor:not-allowed; transform:none;
        }

        .login-link {
            text-align:center; margin-top:20px;
            font-size:13px; color:#888;
        }
        .login-link a {
            color:#43a047; text-decoration:none;
            font-weight:600; transition:color .3s;
        }
        .login-link a:hover { color:#2e7d32; text-decoration:underline; }

        /* ====== RESPONSIVE ====== */
        @media (max-width: 768px) {
            body { align-items:flex-start; padding-top:20px; }
            .register-container { flex-direction:column; }
            .reg-left { padding:30px 28px; }
            .reg-left h1 { font-size:22px; }
            .reg-left .steps { display:none; }
            .reg-right { padding:28px 24px 36px; max-height:none; }
        }
    </style>
</head>
<body>

<div class="register-container" id="register-container">

    <!-- Left Panel -->
    <div class="reg-left">
        <div class="icon-wrapper">
            <i class="fas fa-user-plus"></i>
        </div>
        <div class="brand-name">Mobilitas Santri Husnul Khotimah</div>
        <h1>Daftar Akun Wali</h1>
        <p>Buat akun untuk memantau perjalanan dan status kepulangan santri Anda.</p>

        <div class="steps">
            <div class="step-item">
                <div class="step-num">1</div>
                <div class="step-text">Isi formulir pendaftaran dengan No HP aktif Anda sebagai username</div>
            </div>
            <div class="step-item">
                <div class="step-num">2</div>
                <div class="step-text">Akun Anda akan diverifikasi oleh admin pondok</div>
            </div>
            <div class="step-item">
                <div class="step-num">3</div>
                <div class="step-text">Setelah diaktifkan, login dan lengkapi data santri Anda</div>
            </div>
        </div>
    </div>

    <!-- Right Panel -->
    <div class="reg-right">
        <h2>Buat Akun Baru</h2>
        <p class="subtitle">Registrasi Wali Santri · Akun perlu diaktifkan admin</p>

        <!-- Alerts -->
        <?php if (session()->has('message')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle" style="margin-right:6px;"></i><?= session('message') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle" style="margin-right:6px;"></i><?= session('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach (session('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="alert alert-warning" style="font-size:12px;">
            <i class="fas fa-info-circle" style="margin-right:6px;"></i>
            Akun yang berhasil didaftarkan <strong>belum bisa login</strong> sampai diaktifkan oleh admin pondok.
        </div>

        <form class="register-form" action="<?= base_url('register') ?>" method="post" id="register-form">
            <?= csrf_field() ?>

            <!-- Nama Lengkap -->
            <div class="form-group">
                <label for="fullname">Nama Lengkap Wali</label>
                <div class="input-wrapper">
                    <input type="text"
                           id="fullname"
                           name="fullname"
                           class="form-control <?= session('errors.fullname') ? 'is-invalid' : '' ?>"
                           placeholder="Nama lengkap Anda"
                           value="<?= old('fullname') ?>"
                           autocomplete="name"
                           required>
                    <i class="fas fa-user field-icon"></i>
                </div>
                <?php if(session('errors.fullname')): ?>
                    <span class="field-hint error"><?= session('errors.fullname') ?></span>
                <?php endif; ?>
            </div>

            <!-- No HP (Username) -->
            <div class="form-group">
                <label for="username">No HP <span class="label-optional">(digunakan sebagai username login)</span></label>
                <div class="input-wrapper">
                    <input type="tel"
                           id="username"
                           name="username"
                           class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>"
                           placeholder="Contoh: 08123456789"
                           value="<?= old('username') ?>"
                           autocomplete="username"
                           required
                           minlength="10" maxlength="20">
                    <i class="fas fa-phone field-icon"></i>
                    <span class="nohp-status" id="nohp-status">
                        <i class="fas fa-spinner fa-spin" id="nohp-spinner" style="display:none;"></i>
                        <i class="fas fa-check-circle" id="nohp-ok" style="display:none;"></i>
                        <i class="fas fa-times-circle" id="nohp-taken" style="display:none;"></i>
                    </span>
                </div>
                <span class="field-hint" id="nohp-hint">
                    <?php if(session('errors.username')): ?>
                        <span class="error"><?= session('errors.username') ?></span>
                    <?php else: ?>
                        Gunakan No HP aktif yang mudah Anda ingat.
                    <?php endif; ?>
                </span>
            </div>


            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password"
                           id="password"
                           name="password"
                           class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>"
                           placeholder="Minimal 6 karakter"
                           autocomplete="new-password"
                           required>
                    <i class="fas fa-lock field-icon"></i>
                    <button type="button" class="password-toggle" id="togglePassword" aria-label="Toggle">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <?php if(session('errors.password')): ?>
                    <span class="field-hint error"><?= session('errors.password') ?></span>
                <?php endif; ?>
            </div>

            <!-- Konfirmasi Password -->
            <div class="form-group">
                <label for="pass_confirm">Konfirmasi Password</label>
                <div class="input-wrapper">
                    <input type="password"
                           id="pass_confirm"
                           name="pass_confirm"
                           class="form-control <?= session('errors.pass_confirm') ? 'is-invalid' : '' ?>"
                           placeholder="Ulangi password"
                           autocomplete="new-password"
                           required>
                    <i class="fas fa-lock field-icon"></i>
                    <button type="button" class="password-toggle" id="togglePassConfirm" aria-label="Toggle">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <span class="field-hint" id="passconfirm-hint">
                    <?= session('errors.pass_confirm') ? '<span class="error">'.esc(session('errors.pass_confirm')).'</span>' : '' ?>
                </span>
            </div>

            <button type="submit" class="btn-register" id="btn-register">
                <i class="fas fa-user-plus" style="margin-right:8px;"></i>DAFTAR SEKARANG
            </button>
        </form>

        <div class="login-link">
            Sudah punya akun? <a href="<?= url_to('login') ?>">Masuk di sini</a>
        </div>
    </div>
</div>

<script>
    // Password Toggle
    function setupToggle(btnId, inputId) {
        const btn = document.getElementById(btnId);
        const input = document.getElementById(inputId);
        if (btn && input) {
            btn.addEventListener('click', function() {
                const type = input.type === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        }
    }
    setupToggle('togglePassword', 'password');
    setupToggle('togglePassConfirm', 'pass_confirm');

    // Konfirmasi password real-time
    const passInput    = document.getElementById('password');
    const confirmInput = document.getElementById('pass_confirm');
    const confirmHint  = document.getElementById('passconfirm-hint');

    confirmInput.addEventListener('input', function() {
        if (this.value && passInput.value !== this.value) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            confirmHint.innerHTML = '<span class="error">Password tidak cocok</span>';
        } else if (this.value) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
            confirmHint.innerHTML = '<span class="success">Password cocok ✓</span>';
        }
    });

    // Cek No HP real-time
    const nohpInput   = document.getElementById('username');
    const nohpHint    = document.getElementById('nohp-hint');
    const nohpSpinner = document.getElementById('nohp-spinner');
    const nohpOk      = document.getElementById('nohp-ok');
    const nohpTaken   = document.getElementById('nohp-taken');
    const btnRegister = document.getElementById('btn-register');

    let nohpTimer = null;
    let nohpAvailable = true;

    function showNohpStatus(state) {
        nohpSpinner.style.display = 'none';
        nohpOk.style.display      = 'none';
        nohpTaken.style.display   = 'none';
        if (state === 'checking') nohpSpinner.style.display = 'inline';
        if (state === 'ok')       nohpOk.style.display      = 'inline';
        if (state === 'taken')    nohpTaken.style.display   = 'inline';
    }

    nohpInput.addEventListener('input', function() {
        clearTimeout(nohpTimer);
        const val = this.value.trim();

        if (val.length < 10) {
            showNohpStatus('');
            nohpHint.innerHTML = 'Gunakan No HP aktif yang mudah Anda ingat.';
            nohpInput.classList.remove('is-valid', 'is-invalid');
            nohpAvailable = true;
            btnRegister.disabled = false;
            return;
        }

        showNohpStatus('checking');
        nohpHint.innerHTML = '<span style="color:#aaa">Memeriksa ketersediaan...</span>';

        nohpTimer = setTimeout(function() {
            const formData = new FormData();
            formData.append('username', val);
            // Ambil token dari hidden CSRF field di form
            const csrfInput = document.querySelector('input[name^="csrf_"]');
            if (csrfInput) formData.append(csrfInput.name, csrfInput.value);

            fetch('<?= base_url('register/check-nohp') ?>', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.exists) {
                    showNohpStatus('taken');
                    nohpInput.classList.add('is-invalid');
                    nohpInput.classList.remove('is-valid');
                    nohpHint.innerHTML = '<span class="error"><i class="fas fa-exclamation-triangle"></i> No HP ini sudah terdaftar. Hubungi admin jika lupa password.</span>';
                    nohpAvailable = false;
                    btnRegister.disabled = true;
                } else {
                    showNohpStatus('ok');
                    nohpInput.classList.remove('is-invalid');
                    nohpInput.classList.add('is-valid');
                    nohpHint.innerHTML = '<span class="success"><i class="fas fa-check"></i> No HP tersedia</span>';
                    nohpAvailable = true;
                    btnRegister.disabled = false;
                }
            })
            .catch(() => {
                showNohpStatus('');
                nohpAvailable = true;
            });
        }, 600);
    });

    // Prevent submit if nohp taken
    document.getElementById('register-form').addEventListener('submit', function(e) {
        if (!nohpAvailable) {
            e.preventDefault();
            nohpInput.focus();
        }
    });

    // Focus animation
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('focus', function() {
            this.closest('.form-group').style.transform = 'translateX(4px)';
            this.closest('.form-group').style.transition = 'transform 0.3s ease';
        });
        input.addEventListener('blur', function() {
            this.closest('.form-group').style.transform = 'translateX(0)';
        });
    });
</script>
</body>
</html>
