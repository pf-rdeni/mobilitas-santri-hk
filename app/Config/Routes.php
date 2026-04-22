<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// ============================================================
// REGISTRASI MANDIRI WALI SANTRI
// ============================================================
$routes->get('/register', 'RegisterOrangtuaController::index');
$routes->post('/register', 'RegisterOrangtuaController::store');
$routes->post('/register/check-nohp', 'RegisterOrangtuaController::checkNohp');

// Special Login for Panitia (Simplified)
$routes->get('login-panitia', 'AuthPanitiaController::login');

// ============================================================
// ROUTES ORANGTUA (hanya role: orangtua)
// ============================================================
$routes->group('', ['filter' => 'role:orangtua'], function ($routes) {
    // Dashboard Orangtua
    $routes->get('/orangtua', 'OrangtuaDashboardController::index');

    // Registrasi Tiket (hanya orangtua)
    $routes->get('/registrasi-tiket', 'RegistrasiTiketController::create');
    $routes->post('/registrasi-tiket', 'RegistrasiTiketController::store');
    $routes->get('/registrasi-tiket/edit/(:num)', 'RegistrasiTiketController::edit/$1');
    $routes->post('/registrasi-tiket/update/(:num)', 'RegistrasiTiketController::update/$1');
    $routes->post('/registrasi-tiket/delete/(:num)', 'RegistrasiTiketController::delete/$1');

    // Pengaturan Data Santri (Orangtua)
    $routes->get('/orangtua/santri', 'Orangtua\SantriController::index');
    $routes->get('/orangtua/santri/create', 'Orangtua\SantriController::create');
    $routes->post('/orangtua/santri/store', 'Orangtua\SantriController::store');
    $routes->get('/orangtua/santri/edit/(:num)', 'Orangtua\SantriController::edit/$1');
    $routes->post('/orangtua/santri/update/(:num)', 'Orangtua\SantriController::update/$1');
    $routes->post('/orangtua/santri/delete/(:num)', 'Orangtua\SantriController::delete/$1');

    // Pengaturan Riwayat Santri
    $routes->post('/orangtua/santri/riwayat', 'Orangtua\SantriController::storeRiwayat');
    $routes->post('/orangtua/santri/riwayat/delete/(:num)', 'Orangtua\SantriController::deleteRiwayat/$1');

    // Ubah Password Orangtua
    $routes->get('/orangtua/ubah-password', 'OrangtuaDashboardController::ubahPassword');
    $routes->post('/orangtua/ubah-password/update', 'OrangtuaDashboardController::updatePassword');
});

// ============================================================
// ROUTES BACKEND / PANITIA (hanya role: superadmin, admin, panitia)
// ============================================================
$routes->group('', ['filter' => 'role:superadmin,admin,panitia'], function ($routes) {
    // Dashboard Panitia
    $routes->get('/dashboard', 'DashboardPanitiaController::index');
    $routes->get('/panitia', 'PanitiaDashboardController::index');
    $routes->post('dashboard/update-penugasan', 'DashboardPanitiaController::updatePenugasan');
    $routes->post('dashboard/update-settings', 'DashboardPanitiaController::saveGroups');
    $routes->post('dashboard/verify-payment/(:num)', 'DashboardPanitiaController::verifyPayment/$1');
    $routes->get('/admin-doc', 'AdminDocController::index');

    // Manajemen Santri (Admin/Panitia)
    $routes->get('/santri', 'AdminSantriController::index');
    $routes->get('/santri/edit/(:num)', 'AdminSantriController::edit/$1');
    $routes->post('/santri/update/(:num)', 'AdminSantriController::update/$1');
    $routes->post('/santri/delete/(:num)', 'AdminSantriController::delete/$1');

    // Manajemen Orang Tua (Admin/Panitia)
    $routes->group('orangtua-manage', function($routes) {
        $routes->get('/', 'AdminOrangtuaController::index');
        $routes->get('create', 'AdminOrangtuaController::create');
        $routes->post('store', 'AdminOrangtuaController::store');
        $routes->get('edit/(:num)', 'AdminOrangtuaController::edit/$1');
        $routes->post('update/(:num)', 'AdminOrangtuaController::update/$1');
        $routes->post('delete/(:num)', 'AdminOrangtuaController::delete/$1');
        $routes->post('activate/(:num)', 'AdminOrangtuaController::activate/$1');
        $routes->post('deactivate/(:num)', 'AdminOrangtuaController::deactivate/$1');
    });

    // Manajemen Panitia (Admin/Panitia)
    $routes->group('panitia-manage', function($routes) {
        $routes->get('/', 'AdminPanitiaManageController::index');
        $routes->get('create', 'AdminPanitiaManageController::create');
        $routes->post('store', 'AdminPanitiaManageController::store');
        $routes->get('edit/(:num)', 'AdminPanitiaManageController::edit/$1');
        $routes->post('update/(:num)', 'AdminPanitiaManageController::update/$1');
        $routes->post('delete/(:num)', 'AdminPanitiaManageController::delete/$1');
    });

    // Manajemen Armada Bus & Rombongan (Admin/Panitia)
    $routes->group('admin-bus', function($routes) {
        $routes->get('/', 'AdminBusController::index');
        $routes->get('create', 'AdminBusController::create');
        $routes->post('store', 'AdminBusController::store');
        $routes->get('edit/(:num)', 'AdminBusController::edit/$1');
        $routes->post('update/(:num)', 'AdminBusController::update/$1');
        $routes->get('delete/(:num)', 'AdminBusController::delete/$1');
        $routes->post('update-pendamping', 'AdminBusController::updatePendamping'); // AJAX
        $routes->post('assign-terminal', 'AdminBusController::assignTerminalPanitia');
        $routes->post('remove-terminal', 'AdminBusController::removeTerminalPanitia');
    });

    // Plotting & Mapping Rombongan
    $routes->group('admin-plotting', function($routes) {
        $routes->get('/', 'AdminPlottingController::index');
        $routes->post('assign', 'AdminPlottingController::assign');
        $routes->post('unassign', 'AdminPlottingController::unassign');
    });

    // Checklist Terminal Keberangkatan
    $routes->group('admin-checklist', function($routes) {
        $routes->get('/', 'AdminChecklistController::index');
        $routes->post('toggle', 'AdminChecklistController::toggleStage');
    });

    // Manajemen Jadwal Mobilitas (Setting Tanggal Utama)
    $routes->group('admin-jadwal', function($routes) {
        $routes->get('/', 'AdminJadwalController::index');
        $routes->get('create', 'AdminJadwalController::create');
        $routes->post('store', 'AdminJadwalController::store');
        $routes->get('edit/(:num)', 'AdminJadwalController::edit/$1');
        $routes->post('update/(:num)', 'AdminJadwalController::update/$1');
        $routes->get('delete/(:num)', 'AdminJadwalController::delete/$1');
        $routes->get('get-summary/(:num)', 'AdminJadwalController::getDependencySummary/$1');
        $routes->post('set-aktif/(:num)', 'AdminJadwalController::setAktif/$1');
    });

    // API Routes (Backend)
    $routes->group('api', function($routes) {
        $routes->post('check-username', 'ApiController::checkUsername');
    });
});
