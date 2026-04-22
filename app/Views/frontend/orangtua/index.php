<?= $this->extend('frontend/template/template'); ?>

<?php
$airlineUrls = [
    'Garuda Indonesia' => 'https://www.garuda-indonesia.com/id/id/index',
    'Batik Air' => 'https://www.batikair.com/id/Manage',
    'Citilink' => 'https://book.citilink.co.id/ManageBooking.aspx',
    'Lion Air' => 'https://www.lionair.co.id/kelola-pesanan/rincian-pemesanan',
    'Super Air Jet' => 'https://www.superairjet.com/id/manage.php',
    'Sriwijaya Air' => 'https://www.sriwijayaair.co.id/',
    'AirAsia' => 'https://www.airasia.com/member/search',
];
function getAirlineLink($maskapai, $urls) {
    return isset($urls[$maskapai]) ? $urls[$maskapai] : '#';
}
?>

<?= $this->section('styles'); ?>
    <!-- DataTables CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<!-- Welcome -->
<div class="mb-4">
    <h4><i class="fas fa-user-circle text-primary"></i> Assalamu'alaikum, <?= esc(user()->fullname) ?></h4>
    <p class="text-muted">Selamat datang di Sistem Informasi Mobilitas Santri</p>
</div>

<?php if ($totalSantri > 1): ?>
<!-- Card Summary (Desktop) -->
<div class="row d-none d-md-flex">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner"><h3><?= $totalSantri ?></h3><p>Jumlah Santri</p></div>
            <div class="icon"><i class="fas fa-user-graduate"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner"><h3><?= $totalTiket ?></h3><p>Tiket Terdaftar</p></div>
            <div class="icon"><i class="fas fa-ticket-alt"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner"><h3><?= $totalBerangkat ?></h3><p>Keberangkatan</p></div>
            <div class="icon"><i class="fas fa-plane-departure"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner"><h3><?= $totalDatang ?></h3><p>Kedatangan</p></div>
            <div class="icon"><i class="fas fa-plane-arrival"></i></div>
        </div>
    </div>
</div>

<!-- Quick Stats (Mobile Only) -->
<div class="row d-md-none mb-3 text-center">
    <div class="col-3">
        <div class="p-2 rounded bg-white shadow-sm border-bottom border-info">
            <div class="text-info font-weight-bold h5 mb-0"><?= $totalSantri ?></div>
            <div class="text-muted small" style="font-size: 0.6rem;">Santri</div>
        </div>
    </div>
    <div class="col-3">
        <div class="p-2 rounded bg-white shadow-sm border-bottom border-success">
            <div class="text-success font-weight-bold h5 mb-0"><?= $totalTiket ?></div>
            <div class="text-muted small" style="font-size: 0.6rem;">Tiket</div>
        </div>
    </div>
    <div class="col-3">
        <div class="p-2 rounded bg-white shadow-sm border-bottom border-primary">
            <div class="text-primary font-weight-bold h5 mb-0"><?= $totalBerangkat ?></div>
            <div class="text-muted small" style="font-size: 0.6rem;">Pergi</div>
        </div>
    </div>
    <div class="col-3">
        <div class="p-2 rounded bg-white shadow-sm border-bottom border-warning">
            <div class="text-warning font-weight-bold h5 mb-0"><?= $totalDatang ?></div>
            <div class="text-muted small" style="font-size: 0.6rem;">Pulang</div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Action Buttons (Simplified) -->
<div class="row mb-4">
    <div class="col-6">
        <?php if ($totalSantri >= 1): ?>
            <a href="<?= base_url('registrasi-tiket') ?>" class="btn btn-primary btn-block py-2 shadow-sm d-flex align-items-center justify-content-center" style="border-radius: 12px; font-size: 0.9rem;">
                <i class="fas fa-ticket-alt mr-2"></i> <span>Registrasi Tiket</span>
            </a>
        <?php else: ?>
            <button class="btn btn-secondary btn-block py-2 shadow-sm d-flex align-items-center justify-content-center disabled" style="border-radius: 12px; font-size: 0.9rem; cursor: not-allowed;" title="Daftarkan santri terlebih dahulu">
                <i class="fas fa-ticket-alt mr-2"></i> <span>Registrasi Tiket</span>
            </button>
        <?php endif; ?>
    </div>
    <div class="col-6">
        <a href="<?= base_url('orangtua/santri') ?>" class="btn btn-warning btn-block py-2 shadow-sm d-flex align-items-center justify-content-center text-dark" style="border-radius: 12px; font-size: 0.9rem;">
            <i class="fas fa-users-cog mr-2"></i> <span>Kelola Data</span>
        </a>
    </div>
</div>

<!-- Status Perjalanan Santri (Grouped) -->
<?php if (!empty($groupedTickets)): ?>
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0"><i class="fas fa-route text-success"></i> Monitor Perjalanan Anak Anda</h4>
            <span class="badge badge-pill badge-light border text-muted">Berdasarkan Bus & Terminal</span>
        </div>
        <div class="row">
            <?php 
                $busColors = ['bg-primary', 'bg-info', 'bg-purple', 'bg-indigo', 'bg-navy', 'bg-maroon', 'bg-teal'];
                $termColors = ['bg-orange', 'bg-warning', 'bg-olive', 'bg-fuchsia', 'bg-lime', 'bg-cyan'];
                
                foreach ($groupedTickets as $key => $g): 
                    // Pick a color based on ID for variation
                    $idBus = $g['bus_info']->id ?? 0;
                    $idTerm = intval($g['terminal']) ?: 0;
                    $bCol = $busColors[$idBus % count($busColors)];
                    $tCol = $termColors[$idTerm % count($termColors)];
            ?>
                <div class="col-md-6 mb-3">
                    <div class="card card-outline card-success shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h3 class="card-title text-success font-weight-bold">
                                <i class="fas fa-users mr-1"></i>
                                <?php 
                                    $names = array_column($g['members'], 'nama');
                                    echo implode(', ', $names);
                                ?>
                            </h3>
                            <div class="card-tools">
                                <span class="badge <?= $bCol ?> shadow-sm"><i class="fas fa-bus mr-1"></i> Bus: <?= esc($g['bus_info']->nama_rombongan ?? '-') ?></span>
                                <span class="badge <?= $tCol ?> shadow-sm"><i class="fas fa-map-marker-alt mr-1"></i> Terminal <?= esc($g['terminal']) ?></span>
                            </div>
                        </div>
                        <div class="card-body py-3">
                            <!-- Progress Status (Grouped) -->
                            <div class="d-flex justify-content-between mb-4 text-center">
                                <div class="flex-fill">
                                    <i class="fas fa-map-marker-alt fa-2x mb-2 <?= array_search('belum', array_column($g['members'], 'status_checkin')) === false ? 'text-success' : 'text-muted' ?>"></i>
                                    <p class="small mb-1 font-weight-bold border-bottom">Terminal</p>
                                    <?php foreach($g['members'] as $m): ?>
                                        <div class="d-flex justify-content-between align-items-center px-2 py-1 mb-1 bg-light rounded-sm" style="font-size: 0.65rem;">
                                            <span class="text-truncate mr-1" style="max-width: 60px;"><?= explode(' ', $m['nama'])[0] ?></span>
                                            <span class="badge badge-pill <?= $m['status_checkin'] == 'sudah' ? 'badge-success' : 'badge-secondary' ?>" style="font-size: 0.5rem;">
                                                <?= $m['status_checkin'] == 'sudah' ? 'Ready' : '...' ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="flex-fill border-left border-right mx-2">
                                    <i class="fas fa-bus fa-2x mb-2 <?= array_search('belum', array_column($g['members'], 'status_bus')) === false ? 'text-success' : 'text-muted' ?>"></i>
                                    <p class="small mb-1 font-weight-bold border-bottom">Naik Bus</p>
                                    <?php foreach($g['members'] as $m): ?>
                                        <div class="d-flex justify-content-between align-items-center px-2 py-1 mb-1 bg-light rounded-sm" style="font-size: 0.65rem;">
                                            <span class="text-truncate mr-1" style="max-width: 60px;"><?= explode(' ', $m['nama'])[0] ?></span>
                                            <span class="badge badge-pill <?= $m['status_bus'] == 'sudah' ? 'badge-success' : 'badge-secondary' ?>" style="font-size: 0.5rem;">
                                                <?= $m['status_bus'] == 'sudah' ? 'On' : 'Hold' ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="flex-fill">
                                    <i class="fas fa-coffee fa-2x mb-2 <?= array_search('belum', array_column($g['members'], 'status_istirahat')) === false ? 'text-success' : 'text-muted' ?>"></i>
                                    <p class="small mb-1 font-weight-bold border-bottom">Rest Area</p>
                                    <?php foreach($g['members'] as $m): ?>
                                        <div class="d-flex justify-content-between align-items-center px-2 py-1 mb-1 bg-light rounded-sm" style="font-size: 0.65rem;">
                                            <span class="text-truncate mr-1" style="max-width: 60px;"><?= explode(' ', $m['nama'])[0] ?></span>
                                            <span class="badge badge-pill <?= $m['status_istirahat'] == 'sudah' ? 'badge-success' : 'badge-secondary' ?>" style="font-size: 0.5rem;">
                                                <?= $m['status_istirahat'] == 'sudah' ? 'OK' : '-' ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Koordinasi -->
                            <div class="row pt-2 border-top bg-light rounded p-2">
                                <div class="col-6">
                                    <p class="small text-muted mb-1 font-weight-bold"><i class="fas fa-user-shield mr-1"></i> Koord. Bus</p>
                                    <?php if(!empty($g['coord_bus'])): foreach($g['coord_bus'] as $cb): ?>
                                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $cb['wa']) ?>" target="_blank" class="btn btn-xs btn-outline-success btn-block mb-1 text-left px-2">
                                            <i class="fab fa-whatsapp"></i> <?= esc($cb['nama']) ?>
                                        </a>
                                    <?php endforeach; else: ?>
                                        <span class="text-xs text-muted">Belum ada</span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-6 border-left border-white">
                                    <p class="small text-muted mb-1 font-weight-bold"><i class="fas fa-building mr-1"></i> Koord. Terminal</p>
                                    <?php if(!empty($g['coord_term'])): foreach($g['coord_term'] as $ct): ?>
                                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $ct['wa']) ?>" target="_blank" class="btn btn-xs btn-outline-info btn-block mb-1 text-left px-2">
                                            <i class="fab fa-whatsapp"></i> <?= esc($ct['nama']) ?>
                                        </a>
                                    <?php endforeach; else: ?>
                                        <span class="text-xs text-muted">Belum ada</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Widget Transparansi Bus -->
<?php if(isset($jadwalAktif) && $jadwalAktif): ?>
    <div class="mb-4">
        <h4><i class="fas fa-info-circle text-muted"></i> Informasi Operasional Lintas Armada</h4>
        <p class="text-muted small">Transparansi seluruh armada (<?= date('d M Y', strtotime($jadwalAktif->tanggal_pelaksanaan)) ?>)</p>
        <div class="collapsed-card card card-outline card-secondary shadow-none border-top-0 mb-0">
            <div class="card-header p-2">
                <button type="button" class="btn btn-tool btn-xs" data-card-widget="collapse">
                    <i class="fas fa-plus"></i> Lihat Seluruh Bus & Kontak
                </button>
            </div>
            <div class="card-body p-0">
                <?= view('components/widget_transparansi', ['buses' => $buses]) ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Daftar Tiket Santri (Tabbed) -->
<div class="card card-outline card-success shadow-sm">
    <div class="card-header p-2">
        <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link active" href="#active" data-toggle="tab"><i class="fas fa-ticket-alt mr-1"></i> Tiket Aktif</a></li>
            <li class="nav-item"><a class="nav-link" href="#all-active" data-toggle="tab"><i class="fas fa-globe mr-1"></i> Seluruh Peserta</a></li>
            <li class="nav-item"><a class="nav-link" href="#archive" data-toggle="tab"><i class="fas fa-history mr-1"></i> Arsip Perjalanan</a></li>
        </ul>
    </div>
    <div class="card-body p-0">
        <div class="tab-content">
            <!-- Tab 1: Tiket Aktif Personal -->
            <div class="active tab-pane" id="active">
                <?php if(isset($jadwalAktif) && $jadwalAktif): ?>
                <div class="px-3 py-2 bg-light border-bottom">
                    <p class="mb-0 text-sm font-weight-bold"><i class="fas fa-calendar-check text-success mr-1"></i> Menampilkan Tiket Aktif untuk: <u><?= esc($jadwalAktif->nama_kegiatan) ?></u> (<?= date('d M Y', strtotime($jadwalAktif->tanggal_pelaksanaan)) ?>)</p>
                </div>
                <?php endif; ?>
                <?php if (empty($activePersonal)): ?>
                    <div class="alert alert-info m-3 text-center">
                        <i class="fas fa-info-circle mr-1"></i> Tidak ada tiket aktif saat ini.
                    </div>
                <?php else: ?>
                    <div class="table-responsive p-2">
                        <table id="table-active" class="table table-bordered table-striped table-hover mb-0 w-100">
                            <thead class="bg-success text-white">
                                <tr>
                                    <th width="120" class="text-center">Aksi</th>
                                    <th>Nama Santri</th>
                                    <th>Jenis / Tgl / Jam</th>
                                    <th>Maskapai, PNR & Terminal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($activePersonal as $t): ?>
                                <tr>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <a href="<?= base_url('registrasi-tiket/edit/' . $t->id) ?>" class="btn btn-warning btn-sm btn-block shadow-sm font-weight-bold mb-2 py-2">
                                            <i class="fas fa-edit mr-1"></i> EDIT DATA
                                        </a>
                                        <form action="<?= base_url('registrasi-tiket/delete/' . $t->id) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan/menghapus tiket penerbangan ini?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-xs btn-link text-danger p-0" title="Hapus Tiket">
                                                <i class="fas fa-trash"></i> Batal/Hapus
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <strong><?= esc($t->nama) ?></strong><br>
                                        <small class="text-muted d-block mb-1"><?= esc($t->daerah_asal) ?></small>
                                        <div class="d-flex flex-wrap" style="gap: 2px;">
                                            <?php if(isset($t->status_transfer) && $t->status_transfer == 'diverifikasi'): ?>
                                                <span class="badge badge-success" style="font-size: 0.65rem;"><i class="fas fa-check-double"></i> Lunas Bus</span>
                                            <?php elseif(isset($t->status_transfer) && $t->status_transfer == 'sudah' && isset($t->bukti_transfer) && $t->bukti_transfer): ?>
                                                <span class="badge badge-warning text-white" style="font-size: 0.65rem;"><i class="fas fa-hourglass-half"></i> Tunggu Verifikasi</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary" style="font-size: 0.65rem;"><i class="fas fa-times-circle"></i> Belum Bayar Bus</span>
                                            <?php endif; ?>
                                            
                                            <?php if(isset($t->bukti_tiket) && $t->bukti_tiket): ?>
                                                <a href="<?= base_url('uploads/tiket/' . $t->bukti_tiket) ?>" target="_blank" class="badge badge-primary" style="font-size: 0.65rem;"><i class="fas fa-ticket-alt"></i> E-Ticket</a>
                                            <?php else: ?>
                                                <span class="badge badge-danger" style="font-size: 0.65rem;"><i class="fas fa-times"></i> Tiket (-)</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($t->jenis === 'kepulangan'): ?>
                                            <span class="badge badge-primary"><i class="fas fa-plane-departure mr-1"></i> PULANG</span>
                                        <?php else: ?>
                                            <span class="badge badge-success"><i class="fas fa-plane-arrival mr-1"></i> DATANG</span>
                                        <?php endif; ?>
                                        <br><small class="text-muted font-weight-bold"><?= date('d M Y', strtotime($t->tanggal_pelaksanaan)) ?></small>
                                        <div class="mt-1"><span class="badge badge-light border"><i class="far fa-clock"></i> <?= date('H:i', strtotime($t->waktu_penerbangan)) ?> WIB</span></div>
                                    </td>
                                    <td>
                                        <?php if($t->bandara_asal && $t->bandara_tujuan): ?>
                                            <div class="mb-1" style="font-size: 0.85rem;">
                                                <i class="fas fa-plane-departure text-primary"></i> <strong><?= esc(explode(' - ', $t->bandara_asal)[0] ?? '') ?></strong><br>
                                                <i class="fas fa-plane-arrival text-success"></i> <strong><?= esc(explode(' - ', $t->bandara_tujuan)[0] ?? '') ?></strong>
                                            </div>
                                        <?php endif; ?>
                                        <span><?= esc($t->maskapai) ?></span><br>
                                        <span class="badge badge-info"><i class="fas fa-barcode mr-1"></i> <?= esc($t->kode_booking) ?: '-' ?></span>
                                        <span class="badge badge-dark"><i class="fas fa-building mr-1"></i> T-<?= esc($t->terminal_bandara) ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Tab 2: Seluruh Peserta (View Only) -->
            <div class="tab-pane" id="all-active">
                <?php if(isset($jadwalAktif) && $jadwalAktif): ?>
                <div class="px-3 py-2 bg-light border-bottom">
                    <p class="mb-0 text-sm font-weight-bold"><i class="fas fa-globe text-primary mr-1"></i> Daftar Seluruh Santri Pelaksanaan: <u><?= esc($jadwalAktif->nama_kegiatan) ?></u> (<?= date('d M Y', strtotime($jadwalAktif->tanggal_pelaksanaan)) ?>)</p>
                </div>
                <?php endif; ?>
                <div class="table-responsive p-2">
                    <table id="table-all-active" class="table table-sm table-bordered table-hover mb-0 w-100">
                        <thead class="bg-light text-xs text-uppercase">
                            <tr>
                                <th>Nama Santri</th>
                                <th>Rombongan (Bus)</th>
                                <th class="text-center">Terminal</th>
                                <th>Daerah Asal</th>
                                <th>Jam Terbang</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($allActiveTickets)): ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada peserta terdaftar.</td></tr>
                            <?php else: foreach ($allActiveTickets as $at): ?>
                                <tr <?= in_array($at->id_santri, array_column($santriList, 'id')) ? 'class="bg-light-yellow"' : '' ?>>
                                    <td class="text-sm font-weight-bold">
                                        <?= esc($at->nama) ?>
                                        <?php if(in_array($at->id_santri, array_column($santriList, 'id'))): ?>
                                            <span class="badge badge-pill badge-primary ml-1" style="font-size: 0.6rem;">Anak Anda</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><span class="badge badge-light border text-xs"><?= esc($at->nama_rombongan ?? 'Belum Plotting') ?></span></td>
                                    <td class="font-weight-bold text-sm text-center">T-<?= esc($at->terminal_bandara) ?></td>
                                    <td class="text-xs text-muted"><?= esc($at->daerah_asal) ?></td>
                                    <td class="text-sm font-italic"><?= date('H:i', strtotime($at->waktu_penerbangan)) ?> WIB</td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-2 bg-light border-top">
                    <small class="text-muted"><i class="fas fa-info-circle mr-1"></i> Data seluruh santri yang berangkat pada jadwal aktif (Transparansi Operasional).</small>
                </div>
            </div>

            <!-- Tab 3: Arsip Tiket Personal -->
            <div class="tab-pane" id="archive">
                <?php if (empty($archivePersonal)): ?>
                    <div class="alert alert-light m-3 text-center border">
                        <i class="fas fa-info-circle mr-1"></i> Belum ada riwayat tiket lama.
                    </div>
                <?php else: ?>
                    <div class="table-responsive p-2">
                        <table id="table-archive" class="table table-bordered table-striped table-hover mb-0 w-100">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Nama Santri</th>
                                    <th>Kegiatan Lama</th>
                                    <th>Lampiran</th>
                                    <th>Terminal</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($archivePersonal as $t): ?>
                                <tr>
                                    <td class="text-muted">
                                        <strong><?= esc($t->nama) ?></strong><br>
                                        <small><?= esc($t->daerah_asal) ?></small>
                                    </td>
                                    <td>
                                        <div class="small"><?= date('d M Y', strtotime($t->tanggal_pelaksanaan)) ?></div>
                                        <small class="font-italic"><?= ucfirst($t->jenis) ?></small>
                                    </td>
                                    <td>
                                        <?php if(isset($t->bukti_tiket) && $t->bukti_tiket): ?>
                                            <a href="<?= base_url('uploads/tiket/' . $t->bukti_tiket) ?>" target="_blank" class="badge badge-light border"><i class="fas fa-ticket-alt"></i> E-Ticket</a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center text-muted">T-<?= esc($t->terminal_bandara) ?></td>
                                    <td class="text-center">
                                        <span class="badge badge-secondary py-1 px-3 shadow-none opacity-50"><i class="fas fa-archive mr-1"></i> Archive</span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<!-- DataTables JS CDN -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    // Inisialisasi DataTables untuk ketiga tabel
    var tableOptions = {
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "search": "Cari Santri:",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
            "infoEmpty": "Menampilkan 0 data",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "paginate": {
                "first": "Awal",
                "last": "Akhir",
                "next": "Lanjut",
                "previous": "Balik"
            }
        }
    };

    $('#table-active').DataTable(tableOptions);
    $('#table-all-active').DataTable(tableOptions);
    $('#table-archive').DataTable(tableOptions);

    // Perbaiki alignment kolom saat berpindah tab
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $($.fn.dataTable.tables(true)).DataTable()
           .columns.adjust()
           .responsive.recalc();
    });
});
</script>
<?= $this->endSection(); ?>
