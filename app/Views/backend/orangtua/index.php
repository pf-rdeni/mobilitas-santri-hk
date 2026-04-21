<?= $this->extend('backend/template/template'); ?>

<?= $this->section('content'); ?>

<div class="card card-outline card-info shadow-sm">
    <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-user-shield mr-2 text-info"></i> <?= $title ?></h3>
            <a href="<?= base_url('orangtua-manage/create') ?>" class="btn btn-primary btn-sm shadow-sm rounded-pill px-3">
                <i class="fas fa-plus mr-1"></i> Tambah Akun Wali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabelOrangtua" class="table table-bordered table-striped table-hover mt-2">
                <thead class="bg-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th>Info Akun (Wali)</th>
                        <th>Email</th>
                        <th class="text-center">Status</th>
                        <th>Terdaftar Pada</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($users as $user): ?>
                    <tr>
                        <td class="text-center align-middle"><?= $no++ ?></td>
                        <td class="align-middle">
                            <strong class="text-dark d-block"><?= esc($user->fullname ?? 'Tanpa Nama') ?></strong>
                            <small class="text-muted"><i class="fas fa-phone-alt mr-1"></i><?= esc($user->username) ?></small>
                        </td>
                        <td class="align-middle"><?= esc($user->email) ?></td>
                        <td class="text-center align-middle">
                            <?php if($user->active): ?>
                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle mr-1"></i> Non-Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="align-middle small text-muted">
                            <?= date('d/m/Y H:i', strtotime($user->created_at)) ?>
                        </td>
                        <td class="text-center align-middle">
                            <div class="btn-group">
                                <a href="<?= base_url('orangtua-manage/edit/' . $user->id) ?>" class="btn btn-info btn-sm shadow-sm" title="Edit Akun">
                                    <i class="fas fa-key"></i>
                                </a>
                                <form action="<?= base_url('orangtua-manage/delete/' . $user->id) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini? Segala data santri yang terhubung dengan akun ini mungkin terpengaruh.');">
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
    <div class="card-footer bg-light small text-muted">
        <i class="fas fa-info-circle mr-1 text-primary"></i> Gunakan "No HP" wali sebagai username untuk memudahkan orang tua mengingat akun mereka.
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    $('#tabelOrangtua').DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});
</script>
<?= $this->endSection(); ?>
