<?= $this->extend('backend/template/template'); ?>

<?= $this->section('content'); ?>

<style>
/* Styling for Drag & Drop and Select UI */
.santri-card {
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    padding: 10px;
    margin-bottom: 8px;
    background: #fff;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    transition: all 0.2s ease;
    cursor: grab;
}
.santri-card:hover {
    border-color: #007bff;
    box-shadow: 0 2px 5px rgba(0,123,255,0.2);
}
.santri-card.selected {
    background-color: #e8f4ff;
    border-color: #007bff;
}
.bus-container {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    border: 2px dashed #ddd;
    margin-bottom: 20px;
}
.bus-container.drag-over {
    background: #e9ecef;
    border-color: #28a745;
}
.avatar-sm {
    width: 32px;
    height: 32px;
    object-fit: cover;
    border-radius: 50%;
}
.scrollbar-custom::-webkit-scrollbar { width: 6px; }
.scrollbar-custom::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
.scrollbar-custom::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
.scrollbar-custom::-webkit-scrollbar-thumb:hover { background: #aaa; }
</style>

<div class="callout callout-primary bg-white shadow-sm mb-4">
    <div class="row align-items-center">
        <div class="col-8">
            <h5 class="mb-1 font-weight-bold text-primary"><i class="fas fa-calendar-alt mr-2"></i> Jadwal Aktif Saat Ini</h5>
            <p class="mb-0 text-muted">
                Anda sedang melakukan pemetaan untuk kegiatan: 
                <span class="badge badge-info mx-1"><?= strtoupper($jadwal->jenis) ?></span> 
                pada tanggal <span class="font-weight-bold text-dark"><?= date('d F Y', strtotime($jadwal->tanggal_pelaksanaan)) ?></span>
            </p>
        </div>
        <div class="col-4 text-right">
            <a href="<?= base_url('admin-bus') ?>" class="btn btn-sm btn-outline-primary shadow-sm"><i class="fas fa-bus mr-1"></i> Kelola Armada</a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Unassigned List (Santri Belum Plotting) -->
    <div class="col-md-4">
        <div class="card card-outline card-warning shadow-sm sticky-top" style="top: 20px; max-height: 85vh;">
            <div class="card-header bg-white pt-3 pb-2 border-bottom-0">
                <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-users-slash text-warning mr-2"></i> Belum Dipetakan</h3>
                <div class="card-tools">
                    <span class="badge badge-warning"><?= count($unassigned) ?> Santri</span>
                </div>
            </div>
            
            <div class="card-body p-2 px-3 pb-3" style="overflow-y: auto;" id="unassigned-container">
                <!-- Checkbox Select All Tool -->
                <?php if(!empty($unassigned)): ?>
                <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="selectAllUnassigned">
                        <label class="custom-control-label text-muted" for="selectAllUnassigned"><small>Pilih Semua</small></label>
                    </div>
                    <div>
                        <button type="button" class="btn btn-xs btn-outline-primary" id="btnAssignSelected" disabled><i class="fas fa-arrow-right"></i> Assign ke Bus</button>
                    </div>
                </div>
                
                <div class="list-unassigned pb-2">
                    <?php foreach($unassigned as $tiket): ?>
                        <div class="santri-card d-flex align-items-center justify-content-between unassigned-item" data-id="<?= $tiket->id ?>">
                            <div class="d-flex align-items-center">
                                <div class="custom-control custom-checkbox mr-2">
                                    <input type="checkbox" class="custom-control-input chk-unassigned" id="chk_<?= $tiket->id ?>" value="<?= $tiket->id ?>">
                                    <label class="custom-control-label" for="chk_<?= $tiket->id ?>"></label>
                                </div>
                                <img src="<?= $tiket->foto ? base_url('uploads/santri/thumb_' . $tiket->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($tiket->nama) . '&background=random&color=fff' ?>" alt="Foto" class="avatar-sm mr-2" onerror="this.src='https://ui-avatars.com/api/?name=S&background=ddd&color=666'">
                                <div>
                                    <p class="mb-0 font-weight-bold text-sm text-truncate" style="max-width: 140px;"><?= esc($tiket->nama) ?></p>
                                    <small class="text-muted"><i class="fas fa-plane-departure text-info"></i> <?= substr($tiket->waktu_penerbangan, 0, 5) ?> WIB</small>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="badge badge-light border"><?= esc($tiket->maskapai) ?></span><br>
                                <span class="badge badge-secondary p-1 mt-1">T<?= $tiket->terminal_bandara ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-check-circle fa-3x text-success mb-3 opacity-50"></i>
                        <h6>Semua santri (ikut bus) sudah dipetakan!</h6>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bus Containers (Daftar Rombongan) -->
    <div class="col-md-8">
        <?php if (!$jadwal): ?>
            <div class="alert alert-warning">Tidak ada Jadwal Aktif.</div>
        <?php elseif (empty($buses)): ?>
            <div class="alert alert-info">
                <h5><i class="icon fas fa-info"></i> Armada Belum Ditambahkan</h5>
                Tidak ada data armada bus untuk jadwal ini. Silakan <a href="<?= base_url('admin-bus/create') ?>">Tambah Armada Bus</a> terlebih dahulu.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($buses as $bus): ?>
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 mb-4 bus-card" data-bus-id="<?= $bus->id ?>">
                            <?php 
                                $percent = ($bus->terisi / $bus->kapasitas) * 100;
                                $headerBg = 'bg-white';
                                $titleColor = 'text-primary';
                                if($percent >= 100) { $headerBg = 'bg-danger'; $titleColor = 'text-white'; }
                                elseif($percent > 80) { $headerBg = 'bg-warning'; }
                            ?>
                            <div class="card-header <?= $headerBg ?> pt-3 pb-2 border-bottom-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h5 class="card-title font-weight-bold <?= $titleColor ?> mb-0">
                                        <i class="fas fa-bus-alt mr-1"></i> <?= esc($bus->nama_rombongan) ?>
                                    </h5>
                                    <div>
                                        <span class="badge <?= $percent >= 100 ? 'badge-light text-danger' : 'badge-primary' ?> px-2 py-1">
                                            Sisa: <span class="bus-sisa-kursi"><?= $bus->sisa_kursi ?></span> Kursi
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2" style="font-size: 0.85rem;">
                                    <span class="mr-2"><i class="far fa-clock text-info"></i> <?= substr($bus->waktu_keberangkatan, 0, 5) ?> WIB</span>
                                    <span class="text-muted"><i class="fas fa-id-card"></i> <?= esc($bus->no_polisi) ?></span>
                                </div>
                                
                                <!-- Bar Indikator -->
                                <div class="progress progress-xs mt-2" style="height: 4px;">
                                    <div class="progress-bar <?= $percent >= 100 ? 'bg-white' : ($percent > 75 ? 'bg-danger' : 'bg-success') ?>" style="width: <?= $percent ?>%"></div>
                                </div>
                            </div>
                            
                            <!-- Area Drop / Daftar Assigned Santri -->
                            <div class="card-body p-2 bus-container scrollbar-custom" style="min-height: 150px; max-height: 250px; overflow-y: auto;">
                                <?php if(isset($assigned[$bus->id]) && !empty($assigned[$bus->id])): ?>
                                    <?php foreach($assigned[$bus->id] as $tiket): ?>
                                        <div class="santri-card d-flex align-items-center justify-content-between p-2 mb-2 assigned-item" data-id="<?= $tiket->id ?>">
                                            <div class="d-flex align-items-center">
                                                <div class="custom-control custom-checkbox mr-2">
                                                    <input type="checkbox" class="custom-control-input chk-assigned" id="chk_assigned_<?= $tiket->id ?>" value="<?= $tiket->id ?>">
                                                    <label class="custom-control-label" for="chk_assigned_<?= $tiket->id ?>"></label>
                                                </div>
                                                <img src="<?= $tiket->foto ? base_url('uploads/santri/thumb_' . $tiket->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($tiket->nama) . '&background=random&color=fff' ?>" class="avatar-sm mr-2" onerror="this.src='https://ui-avatars.com/api/?name=S&background=ddd&color=666'">
                                                <div>
                                                    <p class="mb-0 font-weight-bold text-sm"><?= esc($tiket->nama) ?></p>
                                                    <small class="text-muted"><i class="fas fa-plane-departure"></i> <?= substr($tiket->waktu_penerbangan, 0, 5) ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center text-muted p-4 empty-bus-msg">
                                        <i class="fas fa-box-open fa-2x mb-2 opacity-50"></i><br>
                                        <small>Belum ada santri</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Footer untuk aksi massal Un-assign -->
                            <div class="card-footer bg-white p-2 d-flex justify-content-between align-items-center">
                                <div class="custom-control custom-checkbox ml-1">
                                    <input type="checkbox" class="custom-control-input cb-select-all-bus" id="sa_bus_<?= $bus->id ?>" data-bus="<?= $bus->id ?>">
                                    <label class="custom-control-label text-muted" for="sa_bus_<?= $bus->id ?>"><small>Pilih di bus ini</small></label>
                                </div>
                                <button type="button" class="btn btn-xs btn-outline-danger btnUnassign" data-bus="<?= $bus->id ?>" disabled><i class="fas fa-sign-out-alt"></i> Keluarkan</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Pilih Bus untuk Assign Massal -->
<div class="modal fade" id="modalSelectBus" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary border-0">
                <h5 class="modal-title font-weight-bold text-white"><i class="fas fa-bus"></i> Pilih Rombongan Bus</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Silakan pilih armada bus untuk menampung <strong id="lbl-jml-santri">0</strong> santri yang dipilih:</p>
                <?php foreach($buses as $bus): ?>
                    <button class="btn btn-outline-primary btn-block text-left py-3 mb-2 aksi-pilih-bus" data-id="<?= $bus->id ?>" <?= $bus->sisa_kursi <= 0 ? 'disabled' : '' ?>>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <b><?= esc($bus->nama_rombongan) ?></b><br>
                                <small>Berangkat: <?= substr($bus->waktu_keberangkatan, 0, 5) ?> WIB</small>
                            </div>
                            <span class="badge <?= $bus->sisa_kursi <= 0 ? 'badge-danger' : 'badge-info' ?> p-2">Sisa: <?= $bus->sisa_kursi ?> Kursi</span>
                        </div>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    
    // UI Interactions for Unassigned
    $('#selectAllUnassigned').on('change', function() {
        $('.chk-unassigned').prop('checked', $(this).is(':checked'));
        toggleAssignBtn();
    });

    $('.chk-unassigned').on('change', function() {
        toggleAssignBtn();
        if(!$(this).is(':checked')) $('#selectAllUnassigned').prop('checked', false);
    });

    function toggleAssignBtn() {
        let count = $('.chk-unassigned:checked').length;
        $('#btnAssignSelected').prop('disabled', count === 0);
        if(count > 0) {
            $('#btnAssignSelected').html(`<i class="fas fa-arrow-right"></i> Assign (${count}) Santri`);
        } else {
            $('#btnAssignSelected').html(`<i class="fas fa-arrow-right"></i> Assign ke Bus`);
        }
    }

    // Modal Assign Click
    $('#btnAssignSelected').on('click', function() {
        let count = $('.chk-unassigned:checked').length;
        $('#lbl-jml-santri').text(count);
        $('#modalSelectBus').modal('show');
    });

    // Proses Assign ke server
    $('.aksi-pilih-bus').on('click', function() {
        let id_bus = $(this).data('id');
        let selectedTikets = [];
        $('.chk-unassigned:checked').each(function() {
            selectedTikets.push($(this).val());
        });

        $('#modalSelectBus').modal('hide');
        Swal.fire({ title: 'Memproses Mapping...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

        $.ajax({
            url: '<?= base_url('admin-plotting/assign') ?>',
            type: 'POST',
            data: { id_bus: id_bus, id_tikets: selectedTikets, <?= csrf_token() ?>: '<?= csrf_hash() ?>' },
            success: function(res) {
                if(res.status === 'success') {
                    Swal.fire('Berhasil!', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
            }
        });
    });


    // UI Interactions for Assigned (Un-Assign process)
    $('.cb-select-all-bus').on('change', function() {
        let busId = $(this).data('bus');
        let card = $('.bus-card[data-bus-id="'+busId+'"]');
        card.find('.chk-assigned').prop('checked', $(this).is(':checked'));
        toggleUnassignBtn(busId);
    });

    $('.chk-assigned').on('change', function() {
        let busId = $(this).closest('.bus-card').data('bus-id');
        toggleUnassignBtn(busId);
        if(!$(this).is(':checked')) $('#sa_bus_'+busId).prop('checked', false);
    });

    function toggleUnassignBtn(busId) {
        let card = $('.bus-card[data-bus-id="'+busId+'"]');
        let count = card.find('.chk-assigned:checked').length;
        card.find('.btnUnassign').prop('disabled', count === 0);
    }

    // Proses Unassign ke server
    $('.btnUnassign').on('click', function() {
        let busId = $(this).data('bus');
        let card = $('.bus-card[data-bus-id="'+busId+'"]');
        let selectedTikets = [];
        card.find('.chk-assigned:checked').each(function() {
            selectedTikets.push($(this).val());
        });

        Swal.fire({
            title: 'Keluarkan Santri?',
            text: `Yakin ingin mengeluarkan ${selectedTikets.length} santri dari bus ini?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Keluarkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
                $.ajax({
                    url: '<?= base_url('admin-plotting/unassign') ?>',
                    type: 'POST',
                    data: { id_tikets: selectedTikets, <?= csrf_token() ?>: '<?= csrf_hash() ?>' },
                    success: function(res) {
                        if(res.status === 'success') {
                            Swal.fire('Berhasil!', res.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Gagal', res.message, 'error');
                        }
                    },
                    error: function() { Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error'); }
                });
            }
        });
    });

    // Note: To implement full Drag & Drop, we can use SortableJS in the future. 
    // Right now, Checkbox + Modal is extremely robust and mobile-friendly for assignments.
});
</script>
<?= $this->endSection(); ?>
