<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistem Informasi Mobilitas Santri Hari Keluarga">
    <title><?= $title ?? 'SI Mobilitas Santri' ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <style>
        /* Desktop Default */
        .bottom-nav { display: none; }
        
        /* Mobile View Customization */
        @media (max-width: 768px) {
            /* Hide standard navbar links */
            .navbar-nav { display: none; }
            .navbar-toggler { display: none; }
            
            /* Center Brand */
            .navbar-brand { 
                margin: 0 auto; 
                display: flex;
                align-items: center;
            }

            /* Adjust Content Padding for Bottom Nav */
            body { padding-bottom: 70px; }

            /* Bottom Navigation Bar */
            .bottom-nav {
                display: flex;
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 65px;
                background: #ffffff;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
                z-index: 1000;
                justify-content: space-around;
                align-items: center;
                padding-bottom: env(safe-area-inset-bottom); /* iOS Safe Area */
            }

            .bottom-nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-decoration: none !important;
                color: #6c757d;
                font-size: 0.75rem;
                flex: 1;
                padding: 8px 0;
            }

            .bottom-nav-item i {
                font-size: 1.25rem;
                margin-bottom: 4px;
            }

            .bottom-nav-item.active {
                color: #007bff;
                font-weight: 600;
            }
        }
    </style>
    <?= $this->renderSection('styles'); ?>
</head>
<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <!-- Navbar (Top) -->
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white border-bottom-0 shadow-sm">
            <div class="container">
                <a href="<?= base_url('/') ?>" class="navbar-brand">
                    <i class="fas fa-bus-alt text-primary mr-2"></i>
                    <span class="brand-text font-weight-bold text-dark">Mobilitas Santri</span>
                </a>
                
                <!-- Desktop Menu (Hidden on Mobile via CSS) -->
                <button class="navbar-toggler p-0 border-0" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <ul class="navbar-nav ml-auto">
                        <?php if (function_exists('logged_in') && logged_in()): ?>
                            <?php if (in_groups('orangtua')): ?>
                                <li class="nav-item">
                                    <a href="<?= base_url('orangtua') ?>" class="nav-link">Beranda</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('orangtua/ubah-password') ?>" class="nav-link">Ubah Password</a>
                                </li>
                            <?php else: ?>
                                <li class="nav-item">
                                    <a href="<?= base_url('/') ?>" class="nav-link">Beranda</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('dashboard') ?>" class="nav-link">Dashboard</a>
                                </li>
                            <?php endif; ?>
                        <li class="nav-item">
                            <a href="<?= base_url('logout') ?>" class="nav-link btn btn-danger text-white ml-2 px-3 shadow-sm rounded-pill">
                                <i class="fas fa-sign-out-alt mr-1"></i> Logout
                            </a>
                        </li>
                        <?php else: ?>
                        <li class="nav-item">
                            <a href="<?= base_url('login') ?>" class="nav-link btn btn-primary text-white ml-2 px-3 shadow-sm rounded-pill">
                                <i class="fas fa-sign-in-alt mr-1"></i> Login
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <div class="content-wrapper" style="min-height: 80vh; background-color: #f4f6f9;">
            <div class="content">
                <div class="container py-4">
                    <?= $this->renderSection('content'); ?>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <div class="container">
                <div class="float-right d-none d-sm-inline">
                    Sistem Informasi Mobilitas Santri
                </div>
                <strong>&copy; <?= date('Y') ?> Mobilitas Santri - Hari Keluarga.</strong>
            </div>
        </footer>

        <!-- Mobile Bottom Nav -->
        <div class="bottom-nav">
            <?php if (function_exists('logged_in') && logged_in()): ?>
                <?php if (in_groups('orangtua')): ?>
                    <a href="<?= base_url('orangtua') ?>" class="bottom-nav-item active">
                        <i class="fas fa-home"></i>
                        <span>Beranda</span>
                    </a>
                    <a href="<?= base_url('orangtua/ubah-password') ?>" class="bottom-nav-item">
                        <i class="fas fa-key"></i>
                        <span>Password</span>
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('/') ?>" class="bottom-nav-item active">
                        <i class="fas fa-home"></i>
                        <span>Beranda</span>
                    </a>
                    <a href="<?= base_url('dashboard') ?>" class="bottom-nav-item">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                <?php endif; ?>
            <a href="<?= base_url('logout') ?>" class="bottom-nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
            <?php else: ?>
            <a href="<?= base_url('login') ?>" class="bottom-nav-item">
                <i class="fas fa-user-circle"></i>
                <span>Login</span>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    
    <!-- Additional Scripts from Child Views -->
    <?= $this->renderSection('scripts'); ?>
</body>
</html>
