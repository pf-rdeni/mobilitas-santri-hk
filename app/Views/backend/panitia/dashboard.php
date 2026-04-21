<?= $this->extend('backend/template/layout_panitia'); ?>

<?= $this->section('content'); ?>

<?php 
    if (!function_exists('formatWA')) {
        function formatWA($phone) {
            $phone = preg_replace('/[^0-9]/', '', (string)$phone);
            if (strpos($phone, '0') === 0) {
                $phone = '62' . substr($phone, 1);
            }
            return $phone;
        }
    }
?>

<div class="row">
    <div class="col-12 col-md-8 mx-auto">
        <!-- Welcome Card -->
        <div class="card card-outline card-primary shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px;">
                        <i class="fas fa-user-tie fa-lg"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">Halo, <?= esc($user->fullname ?? $user->username) ?>!</h4>
                        <p class="text-muted mb-0">Selamat bertugas hari ini.</p>
                    </div>
                </div>
                
                <?php if(!$jadwal): ?>
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Belum ada jadwal mobilitas yang aktif saat ini.
                    </div>
                <?php else: ?>
                    <div class="bg-light p-3 rounded">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted d-block uppercase font-weight-bold">Kegiatan</small>
                                <span><?= strtoupper($jadwal->jenis) ?></span>
                            </div>
                            <div class="col-6 text-right">
                                <small class="text-muted d-block uppercase font-weight-bold">Tanggal</small>
                                <span><?= date('d M Y', strtotime($jadwal->tanggal_pelaksanaan)) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if($jadwal): ?>
            <!-- STATISTIK GLOBAL (RINGKASAN OPERASIONAL) -->
            <div class="mb-4">
                <div class="row">
                    <div class="col-6">
                        <div class="info-box shadow-sm mb-3">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text text-muted text-xs font-weight-bold uppercase">Total Santri</span>
                                <span class="info-box-number h5 mb-0"><?= $statsGlobal['totalSantri'] ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box shadow-sm mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-bus"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text text-muted text-xs font-weight-bold uppercase">Total Bus</span>
                                <span class="info-box-number h5 mb-0"><?= $statsGlobal['totalBus'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-outline card-info shadow-sm">
                    <div class="card-header bg-white border-0 py-2">
                        <h3 class="card-title text-sm font-weight-bold uppercase text-info">Distribusi Terminal</h3>
                    </div>
                    <div class="card-body py-2">
                        <div class="row text-center border-top pt-2">
                            <div class="col-4 border-right">
                                <span class="badge badge-pill bg-indigo mb-1 px-2 py-1 text-xs uppercase" style="font-size: 0.65rem;">Term 1</span>
                                <div class="font-weight-bold h5 mb-0 text-indigo"><?= $statsGlobal['perTerminal'][1] ?? 0 ?></div>
                            </div>
                            <div class="col-4 border-right">
                                <span class="badge badge-pill bg-teal mb-1 px-2 py-1 text-xs uppercase" style="font-size: 0.65rem;">Term 2</span>
                                <div class="font-weight-bold h5 mb-0 text-teal"><?= $statsGlobal['perTerminal'][2] ?? 0 ?></div>
                            </div>
                            <div class="col-4">
                                <span class="badge badge-pill bg-orange mb-1 px-2 py-1 text-xs uppercase" style="font-size: 0.65rem;">Term 3</span>
                                <div class="font-weight-bold h5 mb-0 text-orange"><?= $statsGlobal['perTerminal'][3] ?? 0 ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-white p-0">
                        <div class="collapse" id="collapsePerBus">
                            <div class="p-3 border-top">
                                <h6 class="text-xs font-weight-bold uppercase text-muted mb-2">Detil Per Bus:</h6>
                                <div class="row">
                                    <?php foreach($statsGlobal['perBus'] as $busName => $jumlah): ?>
                                        <div class="col-6 mb-1">
                                            <div class="d-flex justify-content-between text-xs p-1 border-bottom">
                                                <span class="text-truncate mr-2"><?= esc($busName) ?></span>
                                                <span class="font-weight-bold"><?= $jumlah ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-default btn-block btn-xs text-muted border-0 py-2" type="button" data-toggle="collapse" data-target="#collapsePerBus">
                            <i class="fas fa-angle-down mr-1"></i> Lihat Detil Per Bus
                        </button>
                    </div>
                </div>
            </div>

            <!-- Assignment Section -->
            <h5 class="mb-3 font-weight-bold">Tugas Saya</h5>

            <?php if(!$myBus && !$myTerminal): ?>
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center py-5">
                        <img src="<?= base_url('assets/img/no_task.svg') ?>" class="mb-3" style="width: 100px; opacity: 0.5;">
                        <p class="text-muted mb-0">Anda belum memiliki penugasan khusus pada bus atau terminal untuk jadwal aktif ini.</p>
                        <hr>
                        <p class="small text-info">Silakan hubungi koordinator Admin untuk pengaturan tugas.</p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Bus Assignment Card -->
            <?php if($myBus): ?>
                <div class="card card-success card-outline shadow-sm mb-3">
                    <div class="card-header border-0 pb-0">
                        <h3 class="card-title font-weight-bold"><i class="fas fa-bus mr-2"></i> <?= $labels['termBus'] ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h3 class="font-weight-bold mb-0"><?= $myBus->nama_rombongan ?></h3>
                                <span class="badge badge-success"><?= $myBus->no_polisi ?></span>
                                <p class="text-muted small mb-0 mt-1"><?= $labels['descBus'] ?></p>
                            </div>
                            <div class="text-right">
                                <small class="text-muted d-block uppercase font-weight-bold" style="font-size: 0.65rem;">Okupansi Kursi</small>
                                <h4 class="mb-0 text-success font-weight-bold"><?= $myBus->terisi ?? '?' ?> / <?= $myBus->kapasitas ?></h4>
                            </div>
                        </div>
                        <a href="<?= base_url('admin-checklist?mode=bus') ?>" class="btn btn-block btn-success shadow-sm">
                            <i class="fas fa-tasks mr-2"></i> Kelola Checklist Santri
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Terminal Assignment Card -->
            <?php if($myTerminal): ?>
                <div class="card card-info card-outline shadow-sm mb-3">
                    <div class="card-header border-0 pb-0">
                        <h3 class="card-title font-weight-bold"><i class="<?= $labels['iconTerminal'] ?> mr-2"></i> Pendamping <?= $labels['termKegiatan'] ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h3 class="font-weight-bold mb-0">Terminal <?= $myTerminal->terminal_bandara ?></h3>
                                <span class="text-muted"><?= $labels['descTerminal'] ?></span>
                            </div>
                            <div class="text-right">
                                <small class="text-muted d-block">Total Santri</small>
                                <h4 class="mb-0 text-info font-weight-bold"><?= isset($santriTerminal) ? count($santriTerminal) : 0 ?></h4>
                            </div>
                        </div>
                        <a href="<?= base_url('admin-checklist?mode=terminal') ?>" class="btn btn-block btn-info shadow-sm">
                            <i class="fas fa-clipboard-check mr-2"></i> Kelola Checklist <?= $labels['termKegiatan'] ?> Terminal <?= $myTerminal->terminal_bandara ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            <!-- SEKSI KROSCEK GLOBAL (DATA SELURUH SANTRI) -->
            <div class="card card-outline card-secondary shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold" style="font-size: 1rem;"><i class="fas fa-search-plus mr-2 text-secondary"></i> Kroscek Seluruh Santri (Read-Only)</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" id="globalSearchInput" class="form-control border-left-0" placeholder="Cari nama, bus, atau terminal...">
                    </div>

                    <div id="globalStudentList" style="max-height: 400px; overflow-y: auto;" class="pr-1">
                        <?php if (empty($allStudents)): ?>
                            <div class="text-center py-4">
                                <p class="text-muted small">Tidak ada data santri.</p>
                            </div>
                        <?php else: ?>
                            <div class="list-group list-group-flush" id="studentContainer">
                                <?php foreach ($allStudents as $s): ?>
                                    <?php 
                                        $busName = $s->nama_rombongan ?: 'Belum di-plot';
                                        $terminal = $s->terminal_bandara ?: '-';
                                        $noHp = $s->no_hp ?: '';
                                        $maskapai = $s->maskapai ?: '';
                                        $waktu = $s->waktu_penerbangan ? date('H:i', strtotime($s->waktu_penerbangan)) : '';
                                        $searchData = strtolower(esc($s->nama . ' ' . $busName . ' ' . $terminal . ' ' . $noHp . ' ' . $maskapai . ' ' . $waktu));
                                    ?>
                                    <div class="list-group-item px-0 py-2 border-bottom student-item" data-search="<?= $searchData ?>">
                                        <div class="d-flex align-items-center">
                                            <?php if($s->foto && file_exists(FCPATH . 'uploads/santri/' . $s->foto)): ?>
                                                <img src="<?= base_url('uploads/santri/' . $s->foto) ?>" 
                                                     class="img-circle border" style="width: 38px; height: 38px; object-fit: cover;" 
                                                     alt="Foto">
                                            <?php else: ?>
                                                <?php 
                                                    $names = explode(' ', trim($s->nama));
                                                    $initials = strtoupper(substr($names[0], 0, 1));
                                                    if (count($names) > 1) $initials .= strtoupper(substr(end($names), 0, 1));
                                                    $bgColors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger', 'bg-indigo', 'bg-purple', 'bg-orange', 'bg-teal'];
                                                    $bgClass = $bgColors[abs(crc32($s->nama)) % count($bgColors)];
                                                ?>
                                                <div class="img-circle border shadow-sm <?= $bgClass ?> d-flex align-items-center justify-content-center text-white font-weight-bold" style="width: 38px; height: 38px; font-size: 0.8rem;">
                                                    <?= $initials ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="ml-3 flex-grow-1 overflow-hidden">
                                                <div class="font-weight-bold text-sm text-truncate"><?= esc($s->nama) ?></div>
                                                <div class="text-xs text-muted">
                                                    <span class="badge badge-light border text-dark">
                                                        <i class="fas fa-bus mr-1"></i> <?= $busName ?>
                                                    </span>
                                                    <span class="badge badge-light border text-dark">
                                                        <i class="fas fa-map-marker-alt mr-1"></i> Trm: <?= $terminal ?>
                                                    </span>
                                                </div>
                                                <div class="text-xs mt-1">
                                                    <span class="text-info mr-2">
                                                        <i class="fas fa-plane-departure fa-xs mr-1"></i> <?= $maskapai ?>
                                                    </span>
                                                    <span class="text-muted">
                                                        <i class="fas fa-clock fa-xs mr-1"></i> <?= $waktu ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <?php if ($noHp): ?>
                                                <a href="https://wa.me/<?= formatWA($noHp) ?>" target="_blank" class="btn btn-sm btn-outline-success border-0 ml-2" title="Hubungi Orang Tua">
                                                    <i class="fab fa-whatsapp" style="font-size: 1.2rem;"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div id="noMatchMessage" class="text-center py-4 d-none">
                                <p class="text-muted small">Data tidak ditemukan.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- Common Info Card -->
            <div class="card border-0 shadow-sm mt-4 pb-4">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush rounded shadow-sm overflow-hidden">
                        <button class="list-group-item list-group-item-action bg-light font-weight-bold d-flex justify-content-between align-items-center border-0" type="button" data-toggle="collapse" data-target="#collapseKontak">
                            <span><i class="fas fa-info-circle mr-2 text-primary"></i> Kontak Koordinator Lapangan</span>
                            <i class="fas fa-angle-down text-muted small"></i>
                        </button>
                        
                        <div class="collapse" id="collapseKontak">
                            <?php 
                                function getInitials($name) {
                                    $names = explode(' ', trim($name));
                                    $in = strtoupper(substr($names[0], 0, 1));
                                    if (count($names) > 1) $in .= strtoupper(substr(end($names), 0, 1));
                                    return $in;
                                }
                                function getBgColor($name) {
                                    $bgColors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger', 'bg-indigo', 'bg-purple', 'bg-orange', 'bg-teal'];
                                    return $bgColors[abs(crc32($name)) % count($bgColors)];
                                }
                            ?>

                            <!-- Terminal Coords -->
                            <?php foreach($listTermCoord as $tc): ?>
                                <a href="https://wa.me/<?= formatWA($tc->phone) ?>" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center py-3">
                                    <div class="img-circle border shadow-sm <?= getBgColor($tc->fullname) ?> d-flex align-items-center justify-content-center text-white font-weight-bold mr-3" style="width: 40px; height: 40px; font-size: 0.9rem; flex-shrink: 0;">
                                        <?= getInitials($tc->fullname) ?>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="font-weight-bold mb-0 text-info text-xs uppercase" style="letter-spacing: 0.5px;">
                                            <i class="fas fa-plane-arrival mr-1"></i> T-<?= $tc->terminal_bandara ?>
                                        </div>
                                        <div class="text-dark font-weight-bold text-truncate"><?= esc($tc->fullname) ?></div>
                                        <small class="text-muted"><i class="fab fa-whatsapp text-success"></i> <?= $tc->phone ?></small>
                                    </div>
                                    <i class="fab fa-whatsapp text-muted ml-2" style="font-size: 1.2rem; opacity: 0.3;"></i>
                                </a>
                            <?php endforeach; ?>

                            <!-- Bus Coords -->
                            <?php foreach($listBusCoord as $bc): ?>
                                <a href="https://wa.me/<?= formatWA($bc->no_kontak) ?>" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center py-3">
                                    <div class="img-circle border shadow-sm <?= getBgColor($bc->koordinator_bus) ?> d-flex align-items-center justify-content-center text-white font-weight-bold mr-3" style="width: 40px; height: 40px; font-size: 0.9rem; flex-shrink: 0;">
                                        <?= getInitials($bc->koordinator_bus) ?>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="font-weight-bold mb-0 text-success text-xs uppercase" style="letter-spacing: 0.5px;">
                                            <i class="fas fa-bus mr-1"></i> <?= esc($bc->nama_rombongan) ?>
                                        </div>
                                        <div class="text-dark font-weight-bold text-truncate"><?= esc($bc->koordinator_bus) ?></div>
                                        <small class="text-muted"><i class="fab fa-whatsapp text-success"></i> <?= $bc->no_kontak ?></small>
                                    </div>
                                    <i class="fab fa-whatsapp text-muted ml-2" style="font-size: 1.2rem; opacity: 0.3;"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('globalSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const filter = this.value.toLowerCase().trim();
            const items = document.querySelectorAll('.student-item');
            let matchCount = 0;

            items.forEach(function(item) {
                const searchStr = item.getAttribute('data-search');
                if (filter === '' || searchStr.includes(filter)) {
                    item.style.display = 'block';
                    matchCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            const noMatch = document.getElementById('noMatchMessage');
            if (matchCount === 0 && filter !== '') {
                noMatch.classList.remove('d-none');
            } else {
                noMatch.classList.add('d-none');
            }
        });
    }
});
</script>

<?= $this->endSection(); ?>
