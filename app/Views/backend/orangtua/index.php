<?= $this->extend('backend/template/template'); ?>

<?= $this->section('content'); ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-check-circle mr-2"></i><?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-exclamation-circle mr-2"></i><?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>



<?php $pendingCount = count($pending ?? []); ?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0 font-weight-bold">
        <i class="fas fa-user-shield mr-2 text-info"></i><?= $title ?>
        <?php if ($pendingCount > 0): ?>
            <span class="badge badge-danger ml-2 align-middle" style="font-size:0.75rem;">
                <?= $pendingCount ?> Menunggu Aktivasi
            </span>
        <?php endif; ?>
    </h4>
    <a href="<?= base_url('orangtua-manage/create') ?>" class="btn btn-primary btn-sm shadow-sm rounded-pill px-3">
        <i class="fas fa-plus mr-1"></i> Tambah Akun Wali
    </a>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs mb-0" id="orangtuaTabs">
    <?php if ($pendingCount > 0): ?>
    <li class="nav-item">
        <a class="nav-link <?= $pendingCount > 0 ? 'active font-weight-bold' : '' ?>" href="#tab-pending" data-toggle="tab">
            <i class="fas fa-hourglass-half text-warning mr-1"></i> Menunggu Aktivasi
            <span class="badge badge-warning ml-1"><?= $pendingCount ?></span>
        </a>
    </li>
    <?php endif; ?>
    <li class="nav-item">
        <a class="nav-link <?= $pendingCount == 0 ? 'active font-weight-bold' : '' ?>" href="#tab-aktif" data-toggle="tab">
            <i class="fas fa-check-circle text-success mr-1"></i> Akun Aktif
            <span class="badge badge-success ml-1"><?= count($aktif ?? []) ?></span>
        </a>
    </li>
</ul>

<div class="card card-outline card-info shadow-sm" style="border-top-left-radius:0;">
    <div class="card-body p-0">
        <div class="tab-content">

            <?php if ($pendingCount > 0): ?>
            <!-- TAB: Menunggu Aktivasi -->
            <div class="tab-pane fade show active" id="tab-pending">
                <div class="table-responsive">
                    <table id="tabelPending" class="table table-bordered table-hover mb-0">
                        <thead class="bg-warning-light">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th>Info Akun (Wali)</th>
                                <th>Mendaftar Pada</th>
                                <th width="18%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($pending as $user): ?>
                            <tr class="table-warning">
                                <td class="text-center align-middle"><?= $no++ ?></td>
                                <td class="align-middle">
                                    <strong class="text-dark d-block"><?= esc($user->fullname ?? 'Tanpa Nama') ?></strong>
                                    <small class="text-muted"><i class="fas fa-phone-alt mr-1"></i><?= esc($user->username) ?></small>
                                    <span class="badge badge-warning ml-1" style="font-size:0.65rem;"><i class="fas fa-history"></i> <?= esc($user->status_message ?? 'Mandiri') ?></span>
                                </td>
                                <td class="align-middle small text-muted">
                                    <?= date('d/m/Y H:i', strtotime($user->created_at)) ?>
                                </td>
                                <td class="text-center align-middle">
                                    <!-- Tombol Aktifkan -->
                                    <form action="<?= base_url('orangtua-manage/activate/' . $user->id) ?>" method="post" class="d-inline btn-activate-form">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-success btn-sm shadow-sm"
                                                data-nama="<?= esc($user->fullname) ?>"
                                                title="Aktifkan Akun">
                                            <i class="fas fa-check mr-1"></i> Aktifkan
                                        </button>
                                    </form>
                                    <!-- Edit -->
                                    <a href="<?= base_url('orangtua-manage/edit/' . $user->id) ?>" class="btn btn-info btn-sm shadow-sm" title="Edit Akun">
                                        <i class="fas fa-key"></i>
                                    </a>
                                    <!-- Hapus -->
                                    <form action="<?= base_url('orangtua-manage/delete/' . $user->id) ?>" method="post" class="d-inline btn-delete-form"
                                          data-nama="<?= esc($user->fullname) ?>">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-danger btn-sm shadow-sm" title="Tolak & Hapus">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>

            <!-- TAB: Akun Aktif -->
            <div class="tab-pane fade <?= $pendingCount == 0 ? 'show active' : '' ?>" id="tab-aktif">
                <div class="table-responsive">
                    <table id="tabelAktif" class="table table-bordered table-striped table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th>Info Akun (Wali)</th>
                                <th>Terdaftar Pada</th>
                                <th width="18%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($aktif as $user): ?>
                            <tr>
                                <td class="text-center align-middle"><?= $no++ ?></td>
                                <td class="align-middle">
                                    <strong class="text-dark d-block"><?= esc($user->fullname ?? 'Tanpa Nama') ?></strong>
                                    <small class="text-muted"><i class="fas fa-phone-alt mr-1"></i><?= esc($user->username) ?></small>
                                    <span class="badge badge-success ml-1" style="font-size:0.65rem;"><i class="fas fa-check-circle"></i> Aktif</span>
                                </td>
                                <td class="align-middle small text-muted">
                                    <?= date('d/m/Y H:i', strtotime($user->created_at)) ?>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group">
                                        <!-- Nonaktifkan -->
                                        <form action="<?= base_url('orangtua-manage/deactivate/' . $user->id) ?>" method="post" class="d-inline btn-deactivate-form"
                                              data-nama="<?= esc($user->fullname) ?>">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-warning btn-sm shadow-sm" title="Nonaktifkan">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                        <!-- Edit -->
                                        <a href="<?= base_url('orangtua-manage/edit/' . $user->id) ?>" class="btn btn-info btn-sm shadow-sm" title="Edit/Reset Password">
                                            <i class="fas fa-key"></i>
                                        </a>
                                        <!-- Hapus -->
                                        <form action="<?= base_url('orangtua-manage/delete/' . $user->id) ?>" method="post" class="d-inline btn-delete-form"
                                              data-nama="<?= esc($user->fullname) ?>">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-danger btn-sm shadow-sm" title="Hapus Akun">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <div class="card-footer bg-light small text-muted">
        <i class="fas fa-info-circle mr-1 text-primary"></i>
        Wali santri dapat mendaftar sendiri di halaman <strong>/register</strong>. Akun yang baru daftar perlu diaktifkan admin terlebih dahulu sebelum bisa login.
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {

    // Inisialisasi DataTable untuk tabel aktif
    $('#tabelAktif').DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });

    // Konfirmasi Aktifkan
    $(document).on('submit', '.btn-activate-form', function(e) {
        e.preventDefault();
        const form = this;
        const nama = $(form).find('button[data-nama]').data('nama') || 'wali ini';

        Swal.fire({
            title: 'Aktifkan Akun?',
            html: `Akun <strong>${nama}</strong> akan diaktifkan dan wali santri dapat langsung login.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-check"></i> Ya, Aktifkan!',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });

    // Konfirmasi Nonaktifkan
    $(document).on('submit', '.btn-deactivate-form', function(e) {
        e.preventDefault();
        const form = this;
        const nama = $(form).data('nama') || 'wali ini';

        Swal.fire({
            title: 'Nonaktifkan Akun?',
            html: `Akun <strong>${nama}</strong> akan dinonaktifkan dan tidak bisa login sampai diaktifkan kembali.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-ban"></i> Ya, Nonaktifkan',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });

    // Konfirmasi Hapus
    $(document).on('submit', '.btn-delete-form', function(e) {
        e.preventDefault();
        const form = this;
        const nama = $(form).data('nama') || 'akun ini';

        Swal.fire({
            title: 'Hapus Akun Permanen?',
            html: `Akun <strong>${nama}</strong> dan seluruh data santri terkait mungkin terpengaruh. Tindakan ini tidak bisa dibatalkan!`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash"></i> Hapus Permanen',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });

    // Jika ada pending, otomatis buka tab pending
    <?php if ($pendingCount > 0): ?>
    // Tab sudah defaultnya active (tab pending)
    <?php endif; ?>

    <?php if (session()->getFlashdata('wa_info')): ?>
    <?php 
        $wa = session()->getFlashdata('wa_info'); 
        $waText = "Assalamu'alaikum Bapak/Ibu *" . $wa['fullname'] . "*,\n\n";
        if ($wa['type'] == 'baru') {
            $waText .= "Berikut adalah informasi akun Anda untuk login ke Sistem Informasi Mobilitas Santri Husnul Khotimah:\n\n";
        } else {
            $waText .= "Password akun Anda di Sistem Informasi Mobilitas Santri Husnul Khotimah telah direset. Berikut informasi login Anda yang baru:\n\n";
        }
        $waText .= "*Username:* " . $wa['username'] . "\n";
        $waText .= "*Password:* " . $wa['password'] . "\n\n";
        $waText .= "Silakan login melalui link berikut:\n" . base_url('login') . "\n\n";
        $waText .= "Mohon jaga kerahasiaan password ini. Terima kasih.";
        
        $nohp = preg_replace('/[^0-9]/', '', $wa['username']);
        if (substr($nohp, 0, 1) == '0') {
            $nohp = '62' . substr($nohp, 1);
        }
        $waUrl = "https://wa.me/" . $nohp . "?text=" . urlencode($waText);
        $actionText = $wa['type'] == 'baru' ? 'dibuat' : 'direset password';
    ?>
    Swal.fire({
        title: 'Kirim Info via WhatsApp?',
        html: `Akun berhasil <strong><?= $actionText ?></strong>.<br><br>` +
              `<div style="text-align:left; background:#f8f9fa; padding:10px; border-radius:5px; font-size:14px; border:1px solid #ddd;">` +
              `<strong>Nama:</strong> <?= esc($wa['fullname']) ?><br>` +
              `<strong>Username:</strong> <?= esc($wa['username']) ?><br>` +
              `<strong>Password:</strong> <?= esc($wa['password']) ?>` +
              `</div><br>` +
              `Kirimkan detail login kepada wali santri?`,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#25D366',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fab fa-whatsapp"></i> Ya, Kirim WA',
        cancelButtonText: 'Tutup'
    }).then((result) => {
        if (result.isConfirmed) {
            window.open("<?= $waUrl ?>", "_blank");
        }
    });
    <?php endif; ?>
});
</script>
<?= $this->endSection(); ?>
