<?= $this->extend('backend/template/template'); ?>

<?= $this->section('content'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-bus text-primary mr-2"></i> <?= $title ?></h3>
                <?php if ($jadwal): ?>
                  <a href="<?= base_url('admin-bus/create') ?>" class="btn btn-primary btn-sm ml-auto shadow-sm"><i class="fas fa-plus"></i> Tambah Armada / Rombongan</a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                
                <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle mr-2"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle mr-2"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php endif; ?>

                <?php if (!$jadwal): ?>
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Belum ada Jadwal Aktif!</h5>
                        Sistem tidak mendeteksi adanya <b>Jadwal Mobilitas</b> yang aktif. Anda harus membuat atau mengaktifkan jadwal di menu <a href="<?= base_url('admin/jadwal') ?>">Jadwal Keberangkatan</a> terlebih dahulu.
                    </div>
                <?php else: ?>
                    <div class="mb-3">
                        <span class="badge badge-info px-3 py-2" style="font-size: 14px;">
                            Jadwal Aktif: <?= date('d M Y', strtotime($jadwal->tanggal_pelaksanaan)) ?> 
                            (<?= strtoupper($jadwal->jenis) ?>)
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered text-sm" id="busTable">
                            <thead class="bg-light text-center">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Rombongan</th>
                                    <th>Keberangkatan</th>
                                    <th>Detail Armada</th>
                                    <th>Kapasitas</th>
                                    <th>Pendamping Bus</th>
                                    <th width="12%">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($buses)): ?>
                                    <?php $no = 1; foreach ($buses as $row): ?>
                                    <tr>
                                        <td class="text-center align-middle"><?= $no++ ?></td>
                                        <td class="align-middle font-weight-bold text-primary">
                                            <?= esc($row->nama_rombongan) ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-dark"><i class="far fa-clock mr-1"></i> <?= substr($row->waktu_keberangkatan, 0, 5) ?> WIB</span><br>
                                            <small class="text-muted"><?= date('d/m/Y', strtotime($row->tanggal_digunakan)) ?></small>
                                        </td>
                                        <td class="align-middle">
                                            <i class="fas fa-bus-alt text-secondary mr-1"></i> <b><?= esc($row->no_polisi) ?></b><br>
                                            <small><?= esc($row->perusahaan_bus) ?></small><br>
                                            <small class="text-muted"><i class="fas fa-user-tie"></i> <?= esc($row->koordinator_bus) ?> (<?= esc($row->no_kontak) ?>)</small>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php 
                                              $percent = ($row->terisi / $row->kapasitas) * 100;
                                              $barColor = 'bg-success';
                                              if($percent > 75) $barColor = 'bg-warning';
                                              if($percent >= 100) $barColor = 'bg-danger';
                                            ?>
                                            <b><?= $row->terisi ?></b> / <?= $row->kapasitas ?> Kursi
                                            <div class="progress progress-sm mt-1" style="height: 6px;">
                                                <div class="progress-bar <?= $barColor ?>" style="width: <?= $percent ?>%"></div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div id="text-bus-<?= $row->id ?>" class="small">
                                                    <?= $row->nama_pendamping_bus ? '<i class="fas fa-user-shield text-info mr-1"></i> ' . esc($row->nama_pendamping_bus) : '<span class="text-danger"><i>Belum Diatur</i></span>' ?>
                                                </div>
                                                <button type="button" class="btn btn-xs btn-outline-primary ml-2 btn-assign" 
                                                    data-id="<?= $row->id ?>" 
                                                    data-type="bus" 
                                                    data-selected="<?= $row->id_pendamping_bus ?>"
                                                    data-name="<?= esc($row->nama_rombongan) ?>"
                                                    title="Atur Pendamping Bus">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <a href="<?= base_url('admin-bus/edit/' . $row->id) ?>" class="btn btn-sm btn-info shadow-sm" title="Edit Logistik Bus"><i class="fas fa-edit"></i></a>
                                            <button type="button" class="btn btn-sm btn-danger shadow-sm btn-delete" data-id="<?= $row->id ?>" data-name="<?= esc($row->nama_rombongan) ?>" title="Hapus"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <div class="card card-outline card-info shadow-sm mt-4">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex justify-content-between">
                <div>
                    <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-map-marker-alt text-info mr-2"></i> Penugasan Pendamping Terminal (Check-in)</h3>
                    <p class="text-muted small mt-2"><i class="fas fa-info-circle mr-1"></i> Panitia di terminal otomatis mendapatkan kursi di bus jika berstatus <b>Pendamping Bus</b>. Jika tidak, Anda harus menentukan bus mana yang akan mereka tumpangi.</p>
                </div>
            </div>
            <div class="card-body">
                <?php if ($jadwal): ?>
                    <div class="row">
                        <?php foreach(['1', '2', '3'] as $term): ?>
                        <div class="col-md-4">
                            <div class="card card-light shadow-none border">
                                <div class="card-header py-2 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 font-weight-bold">Terminal <?= $term ?></h6>
                                    <button class="btn btn-xs btn-primary btn-add-terminal" data-term="<?= $term ?>"><i class="fas fa-plus"></i></button>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="list-group list-group-flush">
                                        <?php if(empty($terminalAssignments[$term])): ?>
                                            <li class="list-group-item text-center text-muted py-3 small">Belum ada penugasan</li>
                                        <?php else: ?>
                                            <?php foreach($terminalAssignments[$term] as $ta): ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                    <div>
                                                        <span class="d-block small font-weight-bold"><?= esc($ta->fullname ?: $ta->username) ?></span>
                                                        <span class="badge badge-light border text-xs" style="font-size: 10px;">
                                                            <i class="fas fa-bus mr-1"></i> <?= $ta->nama_rombongan ?: 'Belum Ada Bus' ?>
                                                        </span>
                                                    </div>
                                                    <button class="btn btn-xs btn-link text-danger btn-remove-terminal" data-id="<?= $ta->id ?>" title="Hapus"><i class="fas fa-times"></i></button>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Multiple Assignment -->
<div class="modal fade" id="modalAssign" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg" style="border-radius: 15px; border: none;">
            <div class="modal-header border-0 bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                <h5 class="modal-title font-weight-bold" id="modalTitle">Atur Penugasan Panitia</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <form id="formAssign">
                    <input type="hidden" id="assign_id_bus">
                    <input type="hidden" id="assign_type">
                    <div class="form-group">
                        <label class="font-weight-bold text-dark mb-2" id="labelAssign">Pilih Panitia:</label>
                        <select id="selectPanitia" class="form-control" multiple="multiple">
                            <?php foreach($panitias as $p): ?>
                                <option value="<?= $p->id ?>"><?= esc($p->fullname) ?> (<?= esc($p->username) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted mt-2 d-block">Anda dapat memilih satu atau lebih panitia untuk bertugas.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 p-3">
                <button type="button" class="btn btn-light px-4" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary px-4 font-weight-bold shadow-sm" id="btnSaveAssign"><i class="fas fa-save mr-1"></i> Simpan Penugasan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Terminal Assignment -->
<div class="modal fade" id="modalTerminalAssign" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg" style="border-radius: 15px; border: none;">
            <div class="modal-header border-0 bg-info text-white" style="border-radius: 15px 15px 0 0;">
                <h5 class="modal-title font-weight-bold">Tugaskan Panitia ke Terminal <span id="spanTerm"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <form id="formTerminalAssign">
                    <input type="hidden" id="term_id_jadwal" value="<?= $jadwal ? $jadwal->id : '' ?>">
                    <input type="hidden" id="term_name">
                    
                    <div class="form-group">
                        <label class="font-weight-bold text-dark mb-2">Pilih Panitia:</label>
                        <select id="selectTermPanitia" class="form-control" style="width: 100%;">
                            <option value="">-- Pilih Panitia --</option>
                            <?php foreach($panitias as $p): ?>
                                <option value="<?= $p->id ?>"><?= esc($p->fullname) ?> (<?= esc($p->username) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="busAllocationBox" class="mt-3" style="display:none;">
                        <div class="alert alert-light border p-2">
                            <p class="small mb-1 text-muted" id="panitiaStatusInfo"></p>
                            <label class="font-weight-bold text-dark mb-1 small">Alokasi Kursi Bus:</label>
                            <select id="selectTermBus" class="form-control form-control-sm">
                                <option value="">-- Pilih Bus (Wajib jika bukan pendamping bus) --</option>
                                <?php foreach($buses as $b): ?>
                                    <option value="<?= $b->id ?>"><?= esc($b->nama_rombongan) ?> [Sisa: <?= $b->kapasitas - $b->terisi ?>]</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 p-3">
                <button type="button" class="btn btn-light px-4" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-info px-4 font-weight-bold shadow-sm" id="btnSaveTermAssign"><i class="fas fa-save mr-1"></i> Simpan Penugasan</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<style>
    /* Sembunyikan panitia yang sudah bertugas di bus lain agar list lebih bersih */
    .select2-results__option[aria-disabled=true] {
        display: none !important;
    }
</style>
<script>
$(document).ready(function() {
    $('#busTable').DataTable({
        "paging": true, "lengthChange": true, "searching": true, "ordering": false, "info": true, "autoWidth": false, "responsive": true
    });

    $('#selectPanitia').select2({
        placeholder: "Pilih Panitia...", allowClear: true, width: '100%', dropdownParent: $('#modalAssign')
    });

    $('#selectTermPanitia').select2({
        placeholder: "Pilih Panitia...", allowClear: true, width: '100%', dropdownParent: $('#modalTerminalAssign')
    });

    let allBusAttendants = <?= json_encode($allBusAttendants) ?>; // PanitiaID => BusID

    // --- LOGIC ASSIGN PENDAMPING BUS ---
    $('.btn-assign').on('click', function() {
        if ($(this).data('type') === 'terminal') return; // Handled by new UI

        const idBus = $(this).data('id');
        const name = $(this).data('name');
        const selectedIds = $(this).data('selected');

        $('#assign_id_bus').val(idBus);
        $('#assign_type').val('bus');
        $('#modalTitle').text('Tugaskan Panitia Pendamping Bus');
        $('#labelAssign').text(`Pilih Panitia untuk ${name}:`);

        $('#selectPanitia').val(null).trigger('change');
        
        // Disable panitias already in other buses
        $('#selectPanitia option').each(function() {
            let pid = $(this).val();
            if (allBusAttendants[pid] && allBusAttendants[pid] != idBus) {
                $(this).prop('disabled', true);
            } else {
                $(this).prop('disabled', false);
            }
        });

        $('#selectPanitia').trigger('change');
        if (selectedIds) {
            const arrIds = String(selectedIds).split(',');
            $('#selectPanitia').val(arrIds).trigger('change');
        }
        $('#modalAssign').modal('show');
    });

    $('#btnSaveAssign').on('click', function() {
        const idBus = $('#assign_id_bus').val();
        const ids = $('#selectPanitia').val();
        const btn = $(this);

        btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...').prop('disabled', true);
        $.ajax({
            url: '<?= base_url('admin-bus/update-pendamping') ?>',
            type: 'POST',
            data: { id_bus: idBus, type: 'bus', ids: ids, <?= csrf_token() ?>: '<?= csrf_hash() ?>' },
            success: function(res) {
                if (res.status === 'success') {
                    location.reload(); // Reload needed to refresh capacity calculations and maps
                } else {
                    Swal.fire('Error', res.message, 'error');
                    btn.html('<i class="fas fa-save mr-1"></i> Simpan Penugasan').prop('disabled', false);
                }
            }
        });
    });

    // --- LOGIC TERMINAL ASSIGNMENT (NEW) ---
    $('.btn-add-terminal').on('click', function() {
        const term = $(this).data('term');
        $('#spanTerm').text(term);
        $('#term_name').val(term);
        $('#selectTermPanitia').val(null).trigger('change');
        $('#busAllocationBox').hide();
        $('#modalTerminalAssign').modal('show');
    });

    $('#selectTermPanitia').on('change', function() {
        const pid = $(this).val();
        if (!pid) {
            $('#busAllocationBox').hide();
            return;
        }

        $('#busAllocationBox').show();
        if (allBusAttendants[pid]) {
            // Already has a seat as primary attendant
            $('#panitiaStatusInfo').html(`<i class="fas fa-info-circle text-info"></i> Panitia ini sudah bertugas sebagai <b>Pendamping Bus</b>. Kursi otomatis dialokasikan di Bus tersebut.`);
            $('#selectTermBus').val(allBusAttendants[pid]).prop('disabled', true);
        } else {
            $('#panitiaStatusInfo').html(`<i class="fas fa-exclamation-triangle text-warning"></i> Panitia ini <b>belum memiliki kursi</b> bus. Silakan pilih alokasi bus yang tersedia.`);
            $('#selectTermBus').val('').prop('disabled', false);
        }
    });

    $('#btnSaveTermAssign').on('click', function() {
        const pid = $('#selectTermPanitia').val();
        const busId = $('#selectTermBus').val();
        const term = $('#term_name').val();
        const jid = $('#term_id_jadwal').val();

        if (!pid || (!busId && !$('#selectTermBus').is(':disabled'))) {
            Swal.fire('Info', 'Mohon lengkapi pilihan Panitia dan Alokasi Bus.', 'info');
            return;
        }

        const btn = $(this);
        btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...').prop('disabled', true);

        $.ajax({
            url: '<?= base_url('admin-bus/assign-terminal') ?>',
            type: 'POST',
            data: {
                id_jadwal: jid, terminal: term, id_panitia: pid, id_bus: busId,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            success: function(res) {
                if (res.status === 'success') {
                    location.reload();
                } else {
                    Swal.fire('Error', res.message, 'error');
                    btn.html('<i class="fas fa-save mr-1"></i> Simpan Penugasan').prop('disabled', false);
                }
            }
        });
    });

    // Remove Terminal Assign
    $(document).on('click', '.btn-remove-terminal', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus Penugasan?',
            text: "Panitia akan dihapus dari terminal ini.",
            icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('admin-bus/remove-terminal') ?>',
                    type: 'POST',
                    data: { id: id, <?= csrf_token() ?>: '<?= csrf_hash() ?>' },
                    success: function() { location.reload(); }
                });
            }
        });
    });

    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const name = $(this).data('name');
        Swal.fire({
            title: 'Hapus Rombongan Bus?',
            html: `Anda yakin ingin menghapus <b>${name}</b>?<br>Data yang dihapus tidak dapat dikembalikan.`,
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('admin-bus/delete') ?>/' + id;
            }
        });
    });
});
</script>
<?= $this->endSection(); ?>
