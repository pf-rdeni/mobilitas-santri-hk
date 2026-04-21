<?= $this->extend('frontend/template/template'); ?>

<?= $this->section('content'); ?>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Sukses!</h5>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Error!</h5>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<!-- Breadcrumb -->
<div class="mb-3 d-flex justify-content-between align-items-center">
    <div>
        <a href="<?= base_url('orangtua') ?>" class="text-muted"><i class="fas fa-home"></i> Dashboard</a>
        <span class="text-muted mx-1">/</span>
        <span class="text-dark">Data Santri</span>
    </div>
    <a href="<?= base_url('orangtua/santri/create') ?>" class="btn btn-primary btn-sm shadow-sm">
        <i class="fas fa-plus"></i> Tambah Santri
    </a>
</div>

<!-- Card Tabel -->
<div class="card card-outline card-warning">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-users-cog mr-2 text-warning"></i> Daftar Anak (Santri) Anda</h3>
    </div>
    <div class="card-body">
        <?php if (empty($santri)): ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle mr-1"></i> Anda belum memiliki data anak/santri. Silakan klik tombol <strong>"Tambah Santri"</strong>.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($santri as $s): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                        <div class="card-body text-center pt-4 pb-3">
                            <div class="mb-3">
                                <?php if(isset($s->foto) && $s->foto && file_exists(FCPATH . 'uploads/santri/thumb_' . $s->foto)): ?>
                                    <img src="<?= base_url('uploads/santri/thumb_' . $s->foto) ?>" alt="Foto" class="rounded-circle shadow-sm" style="width: 75px; height: 75px; object-fit: cover; border: 3px solid #f8f9fa;">
                                <?php elseif(isset($s->foto) && $s->foto && file_exists(FCPATH . 'uploads/santri/' . $s->foto)): ?>
                                    <img src="<?= base_url('uploads/santri/' . $s->foto) ?>" alt="Foto" class="rounded-circle shadow-sm" style="width: 75px; height: 75px; object-fit: cover; border: 3px solid #f8f9fa;">
                                <?php else: ?>
                                    <?php
                                        $namaParts = explode(' ', trim($s->nama));
                                        $initials = strtoupper(substr($namaParts[0], 0, 1));
                                        if (count($namaParts) > 1) {
                                            $initials .= strtoupper(substr(end($namaParts), 0, 1));
                                        }
                                        // Generate random background color based on name
                                        $colors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning text-dark', 'bg-danger', 'bg-secondary'];
                                        $colorClass = $colors[strlen($s->nama) % count($colors)];
                                    ?>
                                    <div class="<?= $colorClass ?> text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 75px; height: 75px; font-size: 2rem; font-weight: bold; border: 3px solid #f8f9fa;">
                                        <?= $initials ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <h5 class="font-weight-bold mb-1 text-dark" style="line-height: 1.3;"><?= esc($s->nama) ?></h5>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-map-marker-alt text-danger mr-1"></i> <?= esc($s->daerah_asal) ?: 'Belum diatur' ?>
                            </p>
                            <?php if (isset($s->riwayat) && $s->riwayat): ?>
                                <div class="bg-light rounded p-2 text-left small shadow-sm border">
                                    <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                        <span class="text-muted"><i class="fas fa-graduation-cap text-primary mr-1"></i> Kelas:</span>
                                        <strong><?= esc($s->riwayat->kelas) ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                        <span class="text-muted"><i class="fas fa-building text-info mr-1"></i> Asrama:</span>
                                        <strong><?= esc($s->riwayat->asrama) ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted"><i class="fas fa-calendar-alt text-secondary mr-1"></i> Tahun:</span>
                                        <strong><?= esc($s->riwayat->tahun_ajaran) ?></strong>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="bg-light rounded p-2 small text-muted border text-center font-italic">
                                    Riwayat akademik belum diatur.
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer bg-light border-top-0 d-flex justify-content-between p-3">
                            <a href="<?= base_url('orangtua/santri/edit/' . $s->id) ?>" class="btn btn-warning btn-sm flex-fill mr-2 font-weight-bold shadow-sm" style="border-radius: 8px;">
                                <i class="fas fa-edit mr-1"></i> Buka Profil & Riwayat
                            </a>
                            <form action="<?= base_url('orangtua/santri/delete/' . $s->id) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data santri ini?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-outline-danger btn-sm shadow-sm px-3" style="border-radius: 8px;" title="Hapus Data">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection(); ?>
