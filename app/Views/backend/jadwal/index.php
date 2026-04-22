<?= $this->extend('backend/template/template'); ?>

<?= $this->section('content'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-calendar-alt text-primary mr-2"></i> <?= $title ?></h3>
                <a href="<?= base_url('admin-jadwal/create') ?>" class="btn btn-primary btn-sm ml-auto shadow-sm"><i class="fas fa-plus"></i> Tambah Jadwal</a>
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

                <div class="alert alert-info bg-light border-info">
                    <h5><i class="icon fas fa-info-circle text-info"></i> Informasi Penting</h5>
                    Sistem hanya mendukung **satu jadwal aktif** pada satu waktu. Jadwal yang berstatus <b>Aktif</b> akan digunakan sebagai acuan tanggal pelaksanaan bagi wali santri dan sistem plotting bus.
                </div>

                <div class="table-responsive mt-4">
                    <table class="table table-hover table-striped table-bordered text-sm" id="jadwalTable">
                        <thead class="bg-light text-center">
                            <tr>
                                <th width="5%">No</th>
                                <th>Jenis Mobilitas</th>
                                <th>Tanggal Pelaksanaan</th>
                                <th>Status</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($jadwals)): ?>
                                <?php $no = 1; foreach ($jadwals as $row): ?>
                                <tr>
                                    <td class="text-center align-middle"><?= $no++ ?></td>
                                    <td class="align-middle text-center">
                                        <span class="badge <?= $row->jenis == 'kedatangan' ? 'badge-success' : 'badge-warning' ?> px-3 py-2" style="font-size: 0.85rem;">
                                            <i class="fas <?= $row->jenis == 'kedatangan' ? 'fa-plane-arrival' : 'fa-plane-departure' ?> mr-1"></i>
                                            <?= strtoupper($row->jenis) ?>
                                        </span>
                                    </td>
                                    <td class="text-center align-middle font-weight-bold">
                                        <div class="text-primary" style="font-size: 1rem;">
                                            <?= date('d F Y', strtotime($row->tanggal_pelaksanaan)) ?>
                                        </div>
                                        <small class="text-muted"><?= helper_hari_indo(date('N', strtotime($row->tanggal_pelaksanaan))) ?></small>
                                    </td>
                                    <td class="text-center align-middle">
                                        <?php if($row->status == 'aktif'): ?>
                                            <span class="badge badge-primary shadow-sm px-3 py-2"><i class="fas fa-check-circle mr-1"></i> AKTIF</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary px-3 py-2">SELESAI / NON-AKTIF</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="btn-group">
                                            <?php if($row->status != 'aktif'): ?>
                                                <form action="<?= base_url('admin-jadwal/set-aktif/' . $row->id) ?>" method="POST" class="d-inline mr-1">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-primary shadow-sm" title="Jadikan Jadwal Aktif"><i class="fas fa-power-off"></i></button>
                                                </form>
                                            <?php endif; ?>
                                            <a href="<?= base_url('admin-jadwal/edit/' . $row->id) ?>" class="btn btn-sm btn-info shadow-sm mr-1" title="Edit"><i class="fas fa-edit"></i></a>
                                            <button type="button" class="btn btn-sm btn-danger shadow-sm btn-delete" 
                                                data-id="<?= $row->id ?>" 
                                                data-name="<?= strtoupper($row->jenis) . ' (' . date('d/m/Y', strtotime($row->tanggal_pelaksanaan)) . ')' ?>" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data jadwal.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    $('#jadwalTable').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": false,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "search": "Cari Jadwal:",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
        }
    });

    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        // Show loading state
        Swal.fire({
            title: 'Memuat Data...',
            text: 'Sedang menghitung data yang terdampak...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Fetch dependency summary
        $.ajax({
            url: '<?= base_url('admin-jadwal/get-summary') ?>/' + id,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const data = response.data;
                    
                    if (data.is_aktif && data.santri > 0) {
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Jadwal AKTIF yang sudah memiliki data santri tidak dapat dihapus.',
                            icon: 'error'
                        });
                        return;
                    }

                    let summaryHtml = `<div class="text-left mt-3 p-3 bg-light border rounded">
                        <p class="mb-1"><i class="fas fa-users text-primary mr-2"></i> Santri Terdaftar: <b>${data.santri}</b></p>
                        <p class="mb-1"><i class="fas fa-bus text-warning mr-2"></i> Armada Bus: <b>${data.bus}</b></p>
                        <p class="mb-0"><i class="fas fa-user-shield text-info mr-2"></i> Penugasan Petugas: <b>${data.penugasan}</b></p>
                    </div>
                    <div class="alert alert-danger mt-3 mb-0 py-2 text-sm text-left">
                        <i class="fas fa-exclamation-triangle mr-1"></i> <b>PERHATIAN:</b> Menghapus jadwal ini akan menghapus <b>SEMUA</b> data di atas secara permanen, termasuk <b>file tiket & bukti transfer</b> di server untuk menghemat ruang penyimpanan.
                    </div>`;

                    Swal.fire({
                        title: 'Hapus Jadwal?',
                        html: `Yakin ingin menghapus jadwal <b>${name}</b>?<br>${summaryHtml}`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus Permanen!',
                        cancelButtonText: 'Batal',
                        width: '550px'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '<?= base_url('admin-jadwal/delete') ?>/' + id;
                        }
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Gagal mengambil informasi data.', 'error');
            }
        });
    });
});
</script>
<?php
function helper_hari_indo($dayNum) {
    $days = [
        1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis',
        5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'
    ];
    return $days[$dayNum] ?? '';
}
?>
<?= $this->endSection(); ?>
