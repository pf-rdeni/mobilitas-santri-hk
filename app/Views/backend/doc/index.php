<?= $this->extend('backend/template/template'); ?>

<?= $this->section('content'); ?>

<style>
    .ipo-card { transition: transform 0.2s; border: none; }
    .ipo-card:hover { transform: translateY(-5px); }
    .flow-container { background: #f4f6f9; padding: 30px; border-radius: 15px; position: relative; }
    .flow-step { 
        background: #fff; border-left: 5px solid #007bff; padding: 15px; 
        border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
        margin-bottom: 20px; position: relative;
    }
    .flow-step::after {
        content: '\2193'; position: absolute; bottom: -25px; left: 50%;
        font-size: 20px; color: #adb5bd; font-weight: bold;
    }
    .flow-step:last-child::after { display: none; }
    .step-icon { width: 40px; height: 40px; background: #e7f1ff; color: #007bff; 
                 border-radius: 50%; display: flex; align-items: center; 
                 justify-content: center; font-size: 1.2rem; margin-right: 15px; }
</style>

<div class="container-fluid">
    <!-- Navigation Tabs -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header p-0">
            <ul class="nav nav-tabs px-3 pt-2 bg-white" id="docTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active font-weight-bold" id="overview-tab" data-toggle="tab" href="#overview" role="tab"><i class="fas fa-eye mr-2"></i> Ringkasan Sistem (IPO)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold text-orange" id="parent-tab" data-toggle="tab" href="#parent" role="tab"><i class="fas fa-user-friends mr-2"></i> Panduan Orang Tua</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold text-primary" id="staff-tab" data-toggle="tab" href="#staff" role="tab"><i class="fas fa-clipboard-check mr-2"></i> Panduan Panitia</a>
                </li>
            </ul>
        </div>
        <div class="card-body p-4">
            <div class="tab-content" id="docTabsContent">
                
                <!-- TAB 1: OVERVIEW (IPO & FLOWCHART) -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <!-- IPO Section -->
                    <div class="row mb-5 mt-2">
                        <div class="col-md-4">
                            <div class="card ipo-card shadow-sm border-left-info h-100">
                                <div class="card-header bg-info py-3"><h5 class="card-title m-0 font-weight-bold text-white">1. INPUT</h5></div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li class="mb-3"><i class="fas fa-check-circle text-success mr-2"></i> <strong>Data Master Santri</strong>: Import data dasar santri.</li>
                                        <li class="mb-3"><i class="fas fa-check-circle text-success mr-2"></i> <strong>Jadwal Mobilitas</strong>: Tentukan jenis & aktifkan jadwal.</li>
                                        <li class="mb-3"><i class="fas fa-check-circle text-success mr-2"></i> <strong>Armada Bus</strong>: Input data armada & kapasitas.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card ipo-card shadow-sm border-left-primary h-100">
                                <div class="card-header bg-primary py-3"><h5 class="card-title m-0 font-weight-bold text-white">2. PROSES</h5></div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li class="mb-3"><i class="fas fa-cog text-primary mr-2"></i> <strong>Plotting Rombongan</strong>: Memetakan santri ke bus.</li>
                                        <li class="mb-3"><i class="fas fa-cog text-primary mr-2"></i> <strong>Penugasan Panitia</strong>: Menunjuk petugas khusus dampingi Bus/Terminal.</li>
                                        <li class="mb-3"><i class="fas fa-cog text-primary mr-2"></i> <strong>Checklist Operasional</strong>: Verifikasi kehadiran real-time.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card ipo-card shadow-sm border-left-success h-100">
                                <div class="card-header bg-success py-3"><h5 class="card-title m-0 font-weight-bold text-white">3. OUTPUT</h5></div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li class="mb-3"><i class="fas fa-file-alt text-success mr-2"></i> <strong>Manifest Penumpang</strong>: Daftar cetak santri per armada.</li>
                                        <li class="mb-3"><i class="fas fa-file-alt text-success mr-2"></i> <strong>Presence Report</strong>: Laporan kehadiran secara instan.</li>
                                        <li class="mb-3"><i class="fas fa-file-alt text-success mr-2"></i> <strong>Statistik Real-time</strong>: Pantau pergerakan santri di pusat.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Flowchart Section -->
                    <div class="px-md-5 pb-3">
                        <h5 class="font-weight-bold mb-4"><i class="fas fa-project-diagram mr-2 text-primary"></i> Alur Operasional Sistem</h5>
                        <div class="flow-container">
                            <div class="flow-step d-flex align-items-center">
                                <div class="step-icon"><i class="fas fa-database"></i></div>
                                <div><h6 class="font-weight-bold mb-1 text-sm">Persiapan Data (Admin)</h6><p class="text-xs text-muted mb-0">Input santri, jadwal aktif, dan armada bus.</p></div>
                            </div>
                            <div class="flow-step d-flex align-items-center">
                                <div class="step-icon"><i class="fas fa-map-marked-alt"></i></div>
                                <div><h6 class="font-weight-bold mb-1 text-sm">Pemetaan & Penugasan (Admin)</h6><p class="text-xs text-muted mb-0">Plotting santri ke bus & penunjukan panitia.</p></div>
                            </div>
                            <div class="flow-step d-flex align-items-center">
                                <div class="step-icon"><i class="fas fa-mobile-alt"></i></div>
                                <div><h6 class="font-weight-bold mb-1 text-sm">Portal Khusus Panitia (Staff)</h6><p class="text-xs text-muted mb-0">Panitia akses dashboard mobile & kontak koordinator.</p></div>
                            </div>
                            <div class="flow-step d-flex align-items-center">
                                <div class="step-icon"><i class="fas fa-clipboard-list"></i></div>
                                <div><h6 class="font-weight-bold mb-1 text-sm">Real-time Checklist (Staff)</h6><p class="text-xs text-muted mb-0">Verifikasi keberangkatan (Bus) & kedatangan (Terminal).</p></div>
                            </div>
                            <div class="flow-step d-flex align-items-center">
                                <div class="step-icon"><i class="fas fa-chart-line"></i></div>
                                <div><h6 class="font-weight-bold mb-1 text-sm">Monitoring & Pelaporan (Admin)</h6><p class="text-xs text-muted mb-0">Pusat memantau laporan real-time.</p></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: PANDUAN ORANG TUA -->
                <div class="tab-pane fade" id="parent" role="tabpanel">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <h4 class="font-weight-bold text-orange mb-4">Panduan Penggunaan bagi Wali / Orang Tua</h4>
                            <div class="mb-4">
                                <h6 class="font-weight-bold"><i class="fas fa-key text-muted mr-2"></i> 1. Akses & Login</h6>
                                <p class="text-muted text-sm">Wali login menggunakan Username & Password yang telah dibagikan. Pastikan menggunakan portal resmi untuk keamanan data.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="font-weight-bold"><i class="fas fa-ticket-alt text-muted mr-2"></i> 2. Registrasi Tiket / Flight</h6>
                                <p class="text-muted text-sm">Buka menu <strong>"Registrasi Tiket"</strong>. Masukkan detail penerbangan (Maskapai, Jam, No Penerbangan) untuk mempermudah admin membagi rombongan bus yang sesuai.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="font-weight-bold"><i class="fas fa-info-circle text-muted mr-2"></i> 3. Pantau Status Mobilitas</h6>
                                <p class="text-muted text-sm">Gunakan Dashboard Orang Tua untuk melihat apakah putra/putri Anda sudah naik bus, sudah tiba di terminal, atau sudah dalam pemantauan petugas di bandara/terminal tujuan.</p>
                            </div>
                        </div>
                        <div class="col-md-5 text-center px-lg-5">
                            <div class="p-4 bg-light rounded border border-warning">
                                <i class="fas fa-mobile-alt fa-4x text-warning mb-3"></i>
                                <h5 class="font-weight-bold">Akses Mandiri</h5>
                                <p class="text-sm text-muted">Orang tua mendapatkan kepastian lokasi anak secara real-time tanpa perlu bertanya berulang kali via WhatsApp.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: PANDUAN PANITIA -->
                <div class="tab-pane fade" id="staff" role="tabpanel">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <h4 class="font-weight-bold text-primary mb-4">Manual Operasional Petugas Lapangan</h4>
                            <div class="mb-4">
                                <h6 class="font-weight-bold"><i class="fas fa-tachometer-alt text-muted mr-2"></i> 1. Dashboard Ringkas (Mobile)</h6>
                                <p class="text-muted text-sm">Portal khusus panitia dirancang tanpa sidebar untuk akses cepat di HP. Fokus utama adalah statistik santri yang ditugaskan kepada Anda.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="font-weight-bold"><i class="fas fa-clipboard-check text-muted mr-2"></i> 2. Prosedur Checklist</h6>
                                <p class="text-muted text-sm">Klik <strong>"Kelola Checklist"</strong> di dashboard. Klik ikon ☑️ untuk mencentang santri yang sudah hadir. Data akan otomatis terkirim ke pusat secara real-time.</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="font-weight-bold"><i class="fas fa-phone-alt text-muted mr-2"></i> 3. Koordinasi Cepat</h6>
                                <p class="text-muted text-sm">Gunakan daftar <strong>"Kontak Koordinator"</strong> untuk menghubungi rekan setuagas atau pimpinan koordinasi via WhatsApp hanya dengan sekali klik.</p>
                            </div>
                        </div>
                        <div class="col-md-5 text-center px-lg-5">
                            <div class="p-4 bg-light rounded border border-primary">
                                <i class="fas fa-user-check fa-4x text-primary mb-3"></i>
                                <h5 class="font-weight-bold">Fokus Operasional</h5>
                                <p class="text-sm text-muted">Panitia lapangan tidak perlu pusing dengan navigasi Admin yang rumit, cukup fokus pada santri yang dipandu.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
