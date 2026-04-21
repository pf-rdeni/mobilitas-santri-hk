<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Login - Sistem Informasi Mobilitas Santri Hubungkan Keluarga">
    <title>Login | Mobilitas Santri HK</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 50%, #a5d6a7 100%);
            overflow: hidden;
            position: relative;
        }

        /* Floating background particles */
        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            opacity: 0.08;
            z-index: 0;
        }

        body::before {
            width: 400px;
            height: 400px;
            background: #1b5e20;
            top: -100px;
            right: -100px;
            animation: float-bg 20s ease-in-out infinite;
        }

        body::after {
            width: 300px;
            height: 300px;
            background: #2e7d32;
            bottom: -80px;
            left: -80px;
            animation: float-bg 25s ease-in-out infinite reverse;
        }

        @keyframes float-bg {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(30px, -20px) scale(1.05); }
            50% { transform: translate(-20px, 30px) scale(0.95); }
            75% { transform: translate(20px, 20px) scale(1.02); }
        }

        .login-container {
            display: flex;
            width: 900px;
            max-width: 95vw;
            min-height: 520px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow:
                0 25px 60px rgba(27, 94, 32, 0.15),
                0 10px 20px rgba(0, 0, 0, 0.06);
            position: relative;
            z-index: 1;
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.96);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* ====== LEFT PANEL ====== */
        .login-left {
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

        /* Decorative organic blobs */
        .login-left::before {
            content: '';
            position: absolute;
            width: 280px;
            height: 280px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
            top: -40px;
            right: -60px;
            animation: morph 12s ease-in-out infinite;
        }

        .login-left::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 40% 60% 70% 30% / 40% 70% 30% 60%;
            bottom: -30px;
            left: -40px;
            animation: morph 15s ease-in-out infinite reverse;
        }

        @keyframes morph {
            0%, 100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
            25% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; }
            50% { border-radius: 50% 60% 30% 60% / 30% 40% 70% 60%; }
            75% { border-radius: 40% 30% 60% 50% / 60% 70% 40% 30%; }
        }

        .login-left .icon-wrapper {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 28px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: iconPulse 3s ease-in-out infinite;
            position: relative;
            z-index: 2;
        }

        @keyframes iconPulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255,255,255,0.1); }
            50% { transform: scale(1.05); box-shadow: 0 0 20px 5px rgba(255,255,255,0.05); }
        }

        .login-left .icon-wrapper i {
            font-size: 36px;
            color: #fff;
        }

        .login-left .brand-name {
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 20px;
            opacity: 0.85;
            position: relative;
            z-index: 2;
        }

        .login-left h1 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 12px;
            position: relative;
            z-index: 2;
            text-align: center;
            line-height: 1.2;
        }

        .login-left p {
            font-size: 14px;
            opacity: 0.8;
            max-width: 260px;
            text-align: center;
            line-height: 1.7;
            position: relative;
            z-index: 2;
        }

        /* ====== RIGHT PANEL (FORM) ====== */
        .login-right {
            flex: 1;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px 45px;
        }

        .login-right h2 {
            font-size: 28px;
            font-weight: 700;
            color: #1b5e20;
            margin-bottom: 8px;
        }

        .login-right .subtitle {
            font-size: 13px;
            color: #888;
            margin-bottom: 32px;
        }

        /* Alert messages */
        .alert {
            width: 100%;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 18px;
            animation: fadeIn 0.4s ease;
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }

        .alert-danger {
            background: #fce4ec;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }

        .alert-danger ul {
            margin: 0;
            padding-left: 18px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-form {
            width: 100%;
            max-width: 320px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper > i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 15px;
            transition: color 0.3s ease;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 14px 46px 14px 46px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            color: #333;
            background: #fafafa;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-control::placeholder {
            color: #bbb;
            font-weight: 300;
        }

        .form-control:focus {
            border-color: #43a047;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(67, 160, 71, 0.08);
        }

        .form-control:focus ~ i,
        .form-control:focus + i {
            color: #43a047;
        }

        .input-wrapper:focus-within i {
            color: #43a047;
        }

        .form-control.is-invalid {
            border-color: #e53935;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(229, 57, 53, 0.08);
        }

        .invalid-feedback {
            display: none;
            font-size: 12px;
            color: #e53935;
            margin-top: 6px;
            padding-left: 4px;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #43a047;
            cursor: pointer;
        }

        .form-check label {
            font-size: 13px;
            color: #666;
            cursor: pointer;
            margin: 0;
        }

        .forgot-link {
            font-size: 13px;
            color: #43a047;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: #2e7d32;
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #2e7d32 0%, #43a047 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(46, 125, 50, 0.35);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .register-link {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: #888;
        }

        .register-link a {
            color: #43a047;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .register-link a:hover {
            color: #2e7d32;
            text-decoration: underline;
        }

        .footer-text {
            margin-top: 28px;
            font-size: 11px;
            color: #bbb;
            text-align: center;
        }

        /* ====== PASSWORD TOGGLE ====== */
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #bbb;
            font-size: 16px;
            transition: color 0.3s;
            padding: 4px;
            z-index: 2;
            line-height: 1;
        }

        .password-toggle:hover {
            color: #43a047;
        }

        /* ====== RESPONSIVE ====== */
        @media (max-width: 768px) {
            body {
                padding: 16px;
                align-items: flex-start;
                padding-top: 30px;
            }

            .login-container {
                flex-direction: column;
                width: 100%;
                max-width: 420px;
                min-height: auto;
                border-radius: 20px;
            }

            .login-left {
                padding: 36px 30px 30px;
                min-height: auto;
            }

            .login-left .icon-wrapper {
                width: 60px;
                height: 60px;
                border-radius: 16px;
                margin-bottom: 16px;
            }

            .login-left .icon-wrapper i {
                font-size: 26px;
            }

            .login-left h1 {
                font-size: 24px;
            }

            .login-left p {
                font-size: 13px;
            }

            .login-left .brand-name {
                font-size: 12px;
                margin-bottom: 12px;
            }

            .login-right {
                padding: 30px 28px 36px;
            }

            .login-right h2 {
                font-size: 22px;
            }
        }

        @media (max-width: 400px) {
            .login-right {
                padding: 24px 20px 30px;
            }

            .form-options {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

<div class="login-container" id="login-container">
    <!-- Left Panel - Branding -->
    <div class="login-left">
        <div class="icon-wrapper">
            <i class="fas fa-bus-alt"></i>
        </div>
        <div class="brand-name">Mobilitas Santri</div>
        <h1>Selamat Datang!</h1>
        <p>Silakan login dengan akun Anda untuk mengakses Sistem Informasi Mobilitas Santri</p>
    </div>

    <!-- Right Panel - Form -->
    <div class="login-right">
        <h2>Login</h2>
        <p class="subtitle">Masuk ke akun Anda untuk melanjutkan</p>

        <!-- Alert Messages -->
        <?php if (session()->has('message')) : ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle" style="margin-right:6px;"></i><?= session('message') ?>
            </div>
        <?php endif ?>

        <?php if (session()->has('error')) : ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle" style="margin-right:6px;"></i><?= session('error') ?>
            </div>
        <?php endif ?>

        <?php if (session()->has('errors')) : ?>
            <div class="alert alert-danger">
                <ul>
                <?php foreach (session('errors') as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

        <form class="login-form" action="<?= url_to('login') ?>" method="post" id="login-form">
            <?= csrf_field() ?>

            <!-- Email / Username -->
            <div class="form-group">
                <label for="login">
                    <?php if ($config->validFields === ['email']): ?>
                        Email
                    <?php else: ?>
                        Username atau No. HP
                    <?php endif; ?>
                </label>
                <div class="input-wrapper">
                    <input type="<?= $config->validFields === ['email'] ? 'email' : 'text' ?>"
                           id="login"
                           name="login"
                           class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>"
                           placeholder="Masukkan email atau username"
                           autocomplete="username"
                           required>
                    <i class="fas fa-envelope"></i>
                </div>
                <?php if (session('errors.login')) : ?>
                <div class="invalid-feedback" style="display: block;">
                    <?= session('errors.login') ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password"
                           id="password"
                           name="password"
                           class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>"
                           placeholder="Masukkan password"
                           autocomplete="current-password"
                           required>
                    <i class="fas fa-lock"></i>
                    <button type="button" class="password-toggle" id="togglePassword" aria-label="Toggle password visibility">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <?php if (session('errors.password')) : ?>
                <div class="invalid-feedback" style="display: block;">
                    <?= session('errors.password') ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Options Row -->
            <div class="form-options">
                <?php if ($config->allowRemembering): ?>
                <div class="form-check">
                    <input type="checkbox" name="remember" id="remember" <?php if (old('remember')) : ?> checked <?php endif ?>>
                    <label for="remember">Ingat saya</label>
                </div>
                <?php else: ?>
                <div></div>
                <?php endif; ?>

                <?php if ($config->activeResetter): ?>
                    <a href="<?= url_to('forgot') ?>" class="forgot-link">Lupa password?</a>
                <?php endif; ?>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-login" id="btn-login">
                <i class="fas fa-sign-in-alt" style="margin-right:8px;"></i>LOG IN
            </button>
        </form>

        <?php if ($config->allowRegistration) : ?>
            <div class="register-link">
                Belum punya akun? <a href="<?= url_to('register') ?>">Daftar di sini</a>
            </div>
        <?php endif; ?>

        <div class="footer-text">
            &copy; <?= date('Y') ?> Mobilitas Santri &middot; Hubungkan Keluarga
        </div>
    </div>
</div>

<script>
    // Password visibility toggle
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }

    // Add subtle focus animation
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
