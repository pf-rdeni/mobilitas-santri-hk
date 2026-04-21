<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Panitia | Mobilitas Santri</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <style>
        body.login-page {
            background: linear-gradient(135deg, #007bff 0%, #00d2ff 100%);
        }
        .login-box {
            width: 360px;
        }
        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2) !important;
        }
        .login-logo a {
            color: #fff !important;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .btn-primary {
            border-radius: 8px;
            padding: 10px;
            font-weight: 600;
        }
        .form-control {
            border-radius: 8px;
            height: 45px;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="#"><i class="fas fa-bus-alt mr-2"></i><b>PANITIA</b> LOGIN</a>
    </div>
    <!-- /.login-logo -->
    <div class="card border-0">
        <div class="card-body login-card-body p-4">
            <p class="login-box-msg font-weight-bold">Silakan login untuk memulai tugas</p>

            <?= view('App\Views\Auth\_message_block') ?>

            <form action="<?= url_to('login') ?>" method="post">
                <?= csrf_field() ?>

                <div class="input-group mb-3">
                    <input type="text" name="login" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>" placeholder="Email atau Username">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback">
                        <?= session('errors.login') ?>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback">
                        <?= session('errors.password') ?>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block shadow">
                            <i class="fas fa-sign-in-alt mr-2"></i>MASUK KE DASHBOARD
                        </button>
                    </div>
                </div>
            </form>

            <div class="text-center mt-4">
                <small class="text-muted text-uppercase font-weight-bold">&copy; <?= date('Y') ?> Mobilitas Santri Hubungkan Keluarga</small>
            </div>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
</body>
</html>
