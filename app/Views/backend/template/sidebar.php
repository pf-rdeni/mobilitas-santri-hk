<!-- Sidebar - Menu Navigasi Kiri -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url('dashboard') ?>" class="brand-link">
        <i class="fas fa-bus-alt brand-image ml-3 text-warning" style="font-size: 1.5rem; line-height: 1.8;"></i>
        <span class="brand-text font-weight-light">Mobilitas Santri</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <?php
        // Load auth helper & get current user safely
        if (!function_exists('user')) {
            helper('auth');
        }
        $currentUser = (function_exists('user') && logged_in()) ? user() : null;
        $displayName = $currentUser ? ($currentUser->fullname ?? $currentUser->username) : 'User';
        $initials = strtoupper(substr($displayName, 0, 2));
        ?>
        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <div class="img-circle elevation-2 d-flex justify-content-center align-items-center bg-info text-white font-weight-bold" 
                     style="width: 34px; height: 34px; font-size: 0.85rem; user-select: none;">
                    <?= $initials ?>
                </div>
            </div>
            <div class="info">
                <a href="#" class="d-block" style="white-space: normal;">
                    <?= esc($displayName) ?>
                </a>
                <small class="text-muted" style="display: block; margin-top: 2px;">Panitia</small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>" class="nav-link <?= uri_string() == 'dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Separator OPERASIONAL -->
                <li class="nav-header">OPERASIONAL</li>
                
                <!-- Daftar Santri -->
                <li class="nav-item">
                    <a href="<?= base_url('santri') ?>" class="nav-link <?= strpos(uri_string(), 'santri') !== false && strpos(uri_string(), 'registrasi-tiket') === false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Daftar Santri</p>
                    </a>
                </li>

                <!-- Pengaturan Jadwal Mobilitas -->
                <li class="nav-item">
                    <a href="<?= base_url('admin-jadwal') ?>" class="nav-link <?= strpos(uri_string(), 'admin-jadwal') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-calendar-check text-warning"></i>
                        <p>Pengaturan Jadwal</p>
                    </a>
                </li>

                <!-- Manajemen Armada & Penugasan -->
                <li class="nav-item">
                    <a href="<?= base_url('admin-bus') ?>" class="nav-link <?= strpos(uri_string(), 'admin-bus') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-bus"></i>
                        <p>Armada & Penugasan</p>
                    </a>
                </li>

                <!-- Plotting Rombongan -->
                <li class="nav-item">
                    <a href="<?= base_url('admin-plotting') ?>" class="nav-link <?= strpos(uri_string(), 'admin-plotting') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-sitemap"></i>
                        <p>Plotting Rombongan</p>
                    </a>
                </li>

                <!-- Checklist Terminal -->
                <li class="nav-item">
                    <a href="<?= base_url('admin-checklist') ?>" class="nav-link <?= strpos(uri_string(), 'admin-checklist') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-clipboard-check"></i>
                        <p>Checklist Terminal</p>
                    </a>
                </li>

                <!-- Manajemen Orang Tua -->
                <li class="nav-item">
                    <a href="<?= base_url('orangtua-manage') ?>" class="nav-link <?= strpos(uri_string(), 'orangtua-manage') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>Manajemen Wali</p>
                    </a>
                </li>

                <!-- Manajemen Panitia -->
                <li class="nav-item">
                    <a href="<?= base_url('panitia-manage') ?>" class="nav-link <?= strpos(uri_string(), 'panitia-manage') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>Manajemen Panitia</p>
                    </a>
                </li>

                <!-- Dokumentasi Sistem -->
                <li class="nav-item">
                    <a href="<?= base_url('admin-doc') ?>" class="nav-link <?= strpos(uri_string(), 'admin-doc') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Dokumentasi Sistem</p>
                    </a>
                </li>

                <!-- Logout -->
                <li class="nav-item">
                    <a href="<?= base_url('logout') ?>" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                        <p class="text-danger">Logout</p>
                    </a>
                </li>
                
            </ul>
        </nav>
    </div>
</aside>
