<?= $this->extend('backend/template/template'); ?>

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

<?= $this->section('content'); ?>

<!-- Filter Jadwal -->
<form action="" method="get" class="form-inline mb-3">
    <label class="mr-2">Pilih Jadwal Kegiatan:</label>
    <select name="id_jadwal" class="form-control mr-2" onchange="this.form.submit()">
        <option value="">-- Silakan Pilih --</option>
        <?php foreach($semuaJadwal as $j): ?>
            <option value="<?= $j->id ?>" <?= $idJadwal == $j->id ? 'selected' : '' ?>>
                <?= ucfirst($j->jenis) ?> (<?= date('d M Y', strtotime($j->tanggal_pelaksanaan)) ?>)
                <?= $j->status == 'aktif' ? '[AKTIF]' : '' ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php if($idJadwal): ?>
        <?php 
            $selectedJadwal = array_filter($semuaJadwal, function($j) use ($idJadwal) { return $j->id == $idJadwal; });
            $sj = reset($selectedJadwal);
            
            // Logika Terminologi Dinamis
            $isKepulangan = ($sj && $sj->jenis == 'kepulangan');
            $termJam      = $isKepulangan ? 'Take-off' : 'Landing';
            $termKegiatan = $isKepulangan ? 'Keberangkatan' : 'Kedatangan';
            $termGrup     = $isKepulangan ? 'Pemberangkatan' : 'Penjemputan';
        ?>
        <?php if($sj && $sj->status == 'aktif'): ?>
            <span class="badge badge-success shadow-sm p-2"><i class="fas fa-check-circle"></i> Menampilkan Data Jadwal AKTIF</span>
        <?php else: ?>
            <span class="badge badge-secondary shadow-sm p-2"><i class="fas fa-history"></i> Menampilkan Data Arsip (Selesai)</span>
        <?php endif; ?>
    <?php else: ?>
        <?php 
            $termJam      = 'Penerbangan';
            $termKegiatan = 'Kegiatan';
            $termGrup     = 'Peserta';
        ?>
    <?php endif; ?>
</form>

<!-- 1.1. Status Aktivasi Wali (Hanya muncul jika ada yang menunggu) -->
<?php if(isset($statPendingAktivasi) && $statPendingAktivasi > 0): ?>
<div class="row mt-2">
    <div class="col-12">
        <div class="small-box bg-maroon shadow-sm border-0">
            <div class="inner">
                <h3><?= $statPendingAktivasi ?></h3>
                <p class="font-weight-bold">Wali Santri Menunggu Aktivasi Akun Mandiri</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
            <a href="<?= base_url('orangtua-manage') ?>" class="small-box-footer" style="background: rgba(0,0,0,0.1);">
                Klik untuk Aktivasi Akun Wali <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- 1. Small Boxes / Summary -->
<div class="row mt-3">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $statBerangkat ?></h3>
                <p>Total Keberangkatan</p>
            </div>
            <div class="icon"><i class="fas fa-plane-departure"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $statDatang ?></h3>
                <p>Total Kedatangan</p>
            </div>
            <div class="icon"><i class="fas fa-plane-arrival"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $kebutuhanBus ?> Bus</h3>
                <p>Estimasi Kebutuhan armada</p>
            </div>
            <div class="icon"><i class="fas fa-bus"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $belumDialokasi ?></h3>
                <p>Belum Teralokasi</p>
            </div>
            <div class="icon"><i class="fas fa-user-times"></i></div>
        </div>
    </div>
</div>

<!-- 1.2. Time Range Groups -->
<div class="row mt-4">
    <div class="col-12 d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="fas fa-clock"></i> Grouping Berdasarkan Jam <?= $termJam ?></h4>
        <button type="button" class="btn btn-sm btn-outline-primary shadow-sm" data-toggle="modal" data-target="#modalSettingsRange">
            <i class="fas fa-cog"></i> Atur Rentang Waktu
        </button>
    </div>
    <?php 
    $colors = ['primary', 'success', 'warning', 'danger'];
    $i = 0;
    foreach($timeGroups as $key => $group): 
        $color = $colors[$i % 4];
        $i++;
    ?>
    <div class="col-md-3">
        <div class="info-box shadow-sm border-left-<?= $color ?>" style="border-left: 5px solid;">
            <span class="info-box-icon bg-<?= $color ?>-light text-<?= $color ?>"><i class="far fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-uppercase font-weight-bold" style="font-size: 0.8rem; letter-spacing: 1px;"><?= $group['nama'] ?></span>
                <span class="info-box-number h3 mb-0"><?= $group['count'] ?> <small class="text-muted" style="font-size: 1rem;">Santri</small></span>
                
                <div class="row mt-2 no-gutters border-top pt-2">
                    <div class="col-4 text-center border-right">
                        <div class="text-xs text-muted text-uppercase" style="font-size: 0.65rem;">Term 1</div>
                        <div class="font-weight-bold h5 mb-0"><?= $group['terminal'][1] ?></div>
                    </div>
                    <div class="col-4 text-center border-right">
                        <div class="text-xs text-muted text-uppercase" style="font-size: 0.65rem;">Term 2</div>
                        <div class="font-weight-bold h5 mb-0"><?= $group['terminal'][2] ?></div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="text-xs text-muted text-uppercase" style="font-size: 0.65rem;">Term 3</div>
                        <div class="font-weight-bold h5 mb-0"><?= $group['terminal'][3] ?></div>
                    </div>
                </div>

                <div class="progress mt-2" style="height: 2px;">
                    <div class="progress-bar bg-<?= $color ?>" style="width: 100%"></div>
                </div>
                <span class="progress-description text-muted mt-1" style="font-size: 0.75rem;">
                    Range <?= $termJam ?>: <strong><?= $group['range'] ?></strong>
                </span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Modal Settings Range -->
<div class="modal fade" id="modalSettingsRange" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="<?= base_url('dashboard/update-settings') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-cog mr-2"></i> Pengaturan Rentang <?= $termJam ?> Dashboard</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-4">Anda dapat menambah, mengubah, atau menghapus grup jam. Pastikan rentang waktu tidak tumpang tindih untuk akurasi data.</p>
                    
                    <table class="table table-bordered table-sm" id="tableGrupJam">
                        <thead>
                            <tr class="bg-light">
                                <th>Nama Grup</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th style="width: 50px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($grupRaw as $g): ?>
                            <tr>
                                <td>
                                    <input type="hidden" name="id[]" value="<?= $g->id ?>">
                                    <input type="text" name="nama_grup[]" class="form-control form-control-sm" value="<?= esc($g->nama_grup) ?>" required>
                                </td>
                                <td><input type="time" name="jam_mulai[]" class="form-control form-control-sm" value="<?= $g->jam_mulai ?>" required></td>
                                <td><input type="time" name="jam_selesai[]" class="form-control form-control-sm" value="<?= $g->jam_selesai ?>" required></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-xs btn-danger btn-remove-row"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-sm btn-success shadow-sm" id="btn-add-row"><i class="fas fa-plus"></i> Tambah Grup</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php if($idJadwal): ?>

<!-- 1.5. Widget Transparansi Armada -->
<h4 class="mt-4 mb-3"><i class="fas fa-bus-alt"></i> Distribusi Armada Bus & Petugas</h4>
<?= view('components/widget_transparansi', ['buses' => $buses]) ?>

<!-- 2. Terminal Management (Grid) -->
<h4 class="mt-4 mb-3"><i class="fas fa-th"></i> Grouping Santri per Terminal (<?= $termGrup ?>)</h4>
<div class="row">
    <?php 
    $terminals = ['1', '2', '3'];
    foreach($terminals as $term): 
        $santriDiTerm = isset($groupedData[$term]) ? $groupedData[$term] : [];
    ?>
    <div class="col-md-4">
        <div class="card card-outline card-primary">
            <div class="card-header bg-dark text-white">
                <h3 class="card-title">Terminal <?= $term ?> (<?= count($santriDiTerm) ?> Santri)</h3>
            </div>
            <div class="card-body">
                
                <div class="mb-3">
                    <label class="small text-muted mb-2 d-block"><i class="fas fa-user-shield"></i> Petugas Pendamping Terminal</label>
                    <div class="d-flex flex-wrap" style="gap: 5px;">
                        <?php if(empty($penugasanData[$term])): ?>
                            <span class="badge badge-light border text-muted py-2 px-3 w-100 text-center"><i class="fas fa-user-times mr-1"></i> Belum Ditugaskan</span>
                        <?php else: ?>
                            <?php foreach($penugasanData[$term] as $petugas): ?>
                                <div class="bg-light border rounded p-2 mb-1 w-100 d-flex justify-content-between align-items-center">
                                    <div class="small font-weight-bold">
                                        <i class="fas fa-user text-primary mr-1"></i> <?= esc($petugas->fullname ?: $petugas->username) ?>
                                    </div>
                                    <span class="badge badge-info text-xs"><i class="fas fa-bus"></i> <?= esc($petugas->nama_rombongan ?: 'Bus (-)') ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="mt-3 text-center">
                        <a href="<?= base_url('admin-bus') ?>" class="btn btn-xs btn-outline-secondary w-100"><i class="fas fa-cog"></i> Kelola Penugasan</a>
                    </div>
                </div>

                <hr>

                <ul class="list-group list-group-flush align-items-stretch" style="max-height: 250px; overflow-y: auto;">
                    <?php if(empty($santriDiTerm)): ?>
                        <li class="list-group-item text-center text-muted small">Belum ada data</li>
                    <?php else: ?>
                        <?php foreach($santriDiTerm as $sd): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                                <div>
                                    <strong><?= esc($sd->nama) ?></strong><br>
                                    <small class="text-muted">
                                        <?= esc($sd->maskapai) ?> 
                                        <span class="text-primary font-weight-bold ml-1">(<?= esc($sd->kode_booking) ?: 'N/A' ?>)</span>
                                        <?php 
                                            $cekUrlTerm = getAirlineLink($sd->maskapai, $airlineUrls); 
                                            if ($sd->kode_booking && $cekUrlTerm != '#'): 
                                        ?>
                                            <a href="<?= $cekUrlTerm ?>" target="_blank" class="ml-1 text-info" title="Cek status web maskapai"><i class="fas fa-external-link-alt"></i></a>
                                        <?php endif; ?>
                                    </small>
                                </div>
                                <span class="badge badge-info badge-pill"><i class="far fa-clock"></i> <?= date('H:i', strtotime($sd->waktu_penerbangan)) ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>

            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- 3. Tabel Rekap -->
<h4 class="mt-4 mb-3"><i class="fas fa-table"></i> Tabel Detail Registrasi Tiket</h4>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabelRekap" class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Santri</th>
                    <th>Kelas</th>
                    <th>Asrama</th>
                    <th>Jenis Kegiatan</th>
                    <th>Bus & Lampiran</th>
                    <th>Terminal</th>
                    <th>Maskapai & PNR</th>
                    <th>Jam <?= $termJam ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; foreach($tiketList as $t): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($t->nama) ?> <br><small class="text-muted">(<?= esc($t->daerah_asal) ?>)</small></td>
                    <td class="text-center">
                        <?php if(isset($t->riwayat) && $t->riwayat): ?>
                            <span class="badge badge-light border border-info text-info"><i class="fas fa-graduation-cap"></i> <?= esc($t->riwayat->kelas) ?></span>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php if(isset($t->riwayat) && $t->riwayat): ?>
                            <small class="text-muted font-weight-bold"><i class="fas fa-building"></i> <?= esc($t->riwayat->asrama) ?></small>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge <?= $t->jenis == 'kepulangan' ? 'badge-primary' : 'badge-success' ?>">
                            <?= ucfirst($t->jenis) ?>
                        </span><br>
                        <small><?= date('d M Y', strtotime($t->tanggal_pelaksanaan)) ?></small>
                    </td>
                    <td>
                        <div class="mb-1">
                        <?php if(isset($t->status_transfer) && $t->status_transfer == 'diverifikasi'): ?>
                            <span class="badge badge-success"><i class="fas fa-check-double"></i> Terverifikasi (Lunas)</span>
                            <button class="btn btn-xs btn-link text-danger btn-verify" data-id="<?= $t->id ?>" data-status="belum" title="Batalkan Verifikasi"><i class="fas fa-undo"></i></button>
                        <?php elseif(isset($t->status_transfer) && $t->status_transfer == 'sudah' && isset($t->bukti_transfer) && $t->bukti_transfer): ?>
                            <span class="badge badge-warning mb-1"><i class="fas fa-hourglass-half"></i> Menunggu Verifikasi</span>
                            <div class="btn-group btn-group-xs">
                                <a href="<?= base_url('uploads/transfer/' . $t->bukti_transfer) ?>" target="_blank" class="btn btn-xs btn-info" title="Lihat Bukti Transfer"><i class="fas fa-eye"></i></a>
                                <button class="btn btn-xs btn-success btn-verify" data-id="<?= $t->id ?>" data-status="diverifikasi" title="Verifikasi Pembayaran"><i class="fas fa-check"></i></button>
                            </div>
                        <?php else: ?>
                            <span class="badge badge-secondary mb-1"><i class="fas fa-times-circle"></i> Belum Bayar Bus</span>
                        <?php endif; ?>
                        </div>

                        <div class="mt-1">
                        <?php if(isset($t->bukti_tiket) && $t->bukti_tiket): ?>
                            <a href="<?= base_url('uploads/tiket/' . $t->bukti_tiket) ?>" target="_blank" class="badge badge-primary"><i class="fas fa-ticket-alt"></i> E-Ticket</a>
                        <?php else: ?>
                            <span class="badge badge-danger"><i class="fas fa-times"></i> Tiket (-)</span>
                        <?php endif; ?>
                        </div>
                    </td>
                    <td class="text-center font-weight-bold">T-<?= esc($t->terminal_bandara) ?></td>
                    <td>
                        <?php if($t->bandara_asal && $t->bandara_tujuan): ?>
                            <div class="mb-1" style="font-size: 0.85rem;">
                                <i class="fas fa-plane-departure text-primary" title="<?= esc($t->bandara_asal) ?>"></i> <strong><?= esc(explode(' - ', $t->bandara_asal)[0] ?? '') ?></strong><br>
                                <i class="fas fa-plane-arrival text-success" title="<?= esc($t->bandara_tujuan) ?>"></i> <strong><?= esc(explode(' - ', $t->bandara_tujuan)[0] ?? '') ?></strong>
                            </div>
                        <?php endif; ?>
                        <?= esc($t->maskapai) ?><br>
                        <span class="badge badge-info shadow-sm pt-1 pb-1 px-2 border border-info"><i class="fas fa-barcode"></i> <?= esc($t->kode_booking) ?: '-' ?></span>
                        <?php 
                            $cekUrl = getAirlineLink($t->maskapai, $airlineUrls); 
                            if ($t->kode_booking && $cekUrl != '#'): 
                        ?>
                            <a href="<?= $cekUrl ?>" target="_blank" class="badge badge-light border border-secondary" title="Cek status web maskapai"><i class="fas fa-external-link-alt text-primary"></i></a>
                        <?php endif; ?>
                    </td>
                    <td><?= date('H:i', strtotime($t->waktu_penerbangan)) ?> WIB</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        </div>
    </div>
</div>

<?php else: ?>
    <div class="alert alert-info">Pilih jadwal kegiatan terlebih dahulu untuk melihat dashboard.</div>
<?php endif; ?>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    var table = $('#tabelRekap').DataTable({
        "buttons": [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn-success btn-sm'
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn-danger btn-sm'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn-info btn-sm'
            }
        ],
        "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
               "<'row'<'col-sm-12 mb-2'B>>" +
               "<'row'<'col-sm-12'tr>>" +
               "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    });

    // Add Row
    $('#btn-add-row').on('click', function() {
        var row = `<tr>
            <td>
                <input type="hidden" name="id[]" value="">
                <input type="text" name="nama_grup[]" class="form-control form-control-sm" placeholder="Nama Grup" required>
            </td>
            <td><input type="time" name="jam_mulai[]" class="form-control form-control-sm" required></td>
            <td><input type="time" name="jam_selesai[]" class="form-control form-control-sm" required></td>
            <td class="text-center">
                <button type="button" class="btn btn-xs btn-danger btn-remove-row"><i class="fas fa-trash"></i></button>
            </td>
        </tr>`;
        $('#tableGrupJam tbody').append(row);
    });

    // Verify Payment Action
    $(document).on('click', '.btn-verify', function() {
        const id = $(this).data('id');
        const status = $(this).data('status');
        const title = status === 'diverifikasi' ? 'Verifikasi Pembayaran?' : 'Batalkan Verifikasi?';
        const text = status === 'diverifikasi' ? 'Pastikan bukti transfer sudah sesuai dengan dana masuk di rekening.' : 'Status akan dikembalikan menjadi belum lunas.';
        
        Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: status === 'diverifikasi' ? '#28a745' : '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Lanjutkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('dashboard/verify-payment') ?>/' + id,
                    type: 'POST',
                    data: {
                        status: status,
                        <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
                    }
                });
            }
        });
    });
});
</script>
<?= $this->endSection(); ?>
