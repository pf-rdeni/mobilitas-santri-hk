<?= $this->extend('backend/template/template'); ?>

<?= $this->section('content'); ?>

<div class="card card-outline card-primary shadow-sm">
    <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
        <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-users-cog mr-2 text-primary"></i> <?= $title ?></h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabelSantri" class="table table-bordered table-striped table-hover mt-2">
                <thead class="bg-light">
                    <tr>
                        <th width="5%" class="text-center">Profil</th>
                        <th>Nama Santri</th>
                        <th>Wali / Orang Tua</th>
                        <th>Asal Wilayah</th>
                        <th class="text-center">Kelas</th>
                        <th class="text-center">Asrama</th>
                        <th width="12%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($santri as $s): ?>
                    <tr>
                        <td class="text-center align-middle">
                            <?php if($s->foto && file_exists(FCPATH . 'uploads/santri/thumb_' . $s->foto)): ?>
                                <img src="<?= base_url('uploads/santri/thumb_' . $s->foto) ?>" alt="Foto" class="img-circle elevation-1" style="width: 45px; height: 45px; object-fit: cover;">
                            <?php else: ?>
                                <?php 
                                    $names = explode(' ', trim($s->nama));
                                    $initials = strtoupper(substr($names[0], 0, 1));
                                    if (count($names) > 1) $initials .= strtoupper(substr(end($names), 0, 1));
                                    $bgColors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger', 'bg-indigo', 'bg-purple', 'bg-orange', 'bg-teal'];
                                    $bgClass = $bgColors[abs(crc32($s->nama)) % count($bgColors)];
                                ?>
                                <div class="img-circle elevation-1 <?= $bgClass ?> d-inline-flex align-items-center justify-content-center text-white font-weight-bold" style="width: 45px; height: 45px; font-size: 1rem; letter-spacing: 1px;">
                                    <?= $initials ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="align-middle">
                            <span class="font-weight-bold d-block text-dark"><?= esc($s->nama) ?></span>
                            <small class="text-muted"><i class="fas fa-tag mr-1"></i> ID: #<?= $s->id ?></small>
                        </td>
                        <td class="align-middle">
                            <span class="d-block"><i class="fas fa-user-circle text-muted mr-1"></i> <?= esc($s->phone_ortu) ?></span>
                            <small class="text-muted"><i class="fas fa-envelope mr-1"></i> <?= esc($s->email_ortu) ?></small><br>
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $s->phone_ortu) ?>" target="_blank" class="btn btn-xs btn-outline-success mt-1 rounded-pill">
                                <i class="fab fa-whatsapp"></i> Hubungi WA
                            </a>
                        </td>
                        <td class="align-middle small">
                            <strong class="text-dark d-block"><?= esc($s->provinsi_nama) ?></strong>
                            <span class="text-muted"><?= esc($s->kabupaten_nama) ?></span><br>
                            <span class="text-muted font-italic"><?= esc($s->kelurahan_nama) ?></span>
                        </td>
                        <td class="text-center align-middle">
                            <?php if($s->riwayat): ?>
                                <span class="badge badge-info px-2"><?= esc($s->riwayat->kelas) ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center align-middle">
                            <?php if($s->riwayat): ?>
                                <small class="font-weight-bold"><?= esc($s->riwayat->asrama) ?></small>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center align-middle">
                            <div class="btn-group">
                                <a href="<?= base_url('santri/edit/' . $s->id) ?>" class="btn btn-warning btn-sm shadow-sm" title="Edit Data">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?= base_url('santri/delete/' . $s->id) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data santri ini? Seluruh riwayatnya juga akan hilang!');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-danger btn-sm shadow-sm" title="Hapus Data">
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
        <i class="fas fa-info-circle mr-1"></i> Gunakan kotak pencarian di sebelah kanan atas tabel untuk menyaring data secara instan.
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    $('#tabelSantri').DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "order": [[1, "asc"]] // Order by name by default
    });
});
</script>
<?= $this->endSection(); ?>
