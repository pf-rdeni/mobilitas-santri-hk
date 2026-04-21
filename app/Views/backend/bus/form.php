<?= $this->extend('backend/template/template'); ?>

<?= $this->section('content'); ?>
<?php $isEdit = isset($bus); ?>

<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card card-outline card-primary shadow-sm" style="border-radius: 10px;">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                <h3 class="card-title font-weight-bold text-dark"><i class="fas <?= $isEdit ? 'fa-edit' : 'fa-plus' ?> mr-2 text-primary"></i> <?= $title ?></h3>
            </div>
            
            <form action="<?= base_url('admin-bus/' . ($isEdit ? 'update/'.$bus->id : 'store')) ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id_jadwal" value="<?= $jadwal->id ?>">
                
                <div class="card-body">
                    <!-- Info Jadwal -->
                    <div class="alert alert-info d-flex align-items-center mb-4 shadow-sm" style="border-radius: 8px;">
                        <i class="fas fa-calendar-check fa-2x mr-3"></i>
                        <div>
                            <h6 class="mb-0 font-weight-bold">Penambahan Bus untuk Jadwal: <?= strtoupper($jadwal->jenis) ?></h6>
                            <small>Tanggal: <?= date('d M Y', strtotime($jadwal->tanggal_pelaksanaan)) ?></small>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Kolom Info Rombongan -->
                        <div class="col-md-6 border-right">
                            <h6 class="text-primary font-weight-bold mb-3"><i class="fas fa-bus mr-1"></i> Data Rombongan & Waktu</h6>
                            
                            <div class="form-group mb-3">
                                <label for="nama_rombongan">Nama Rombongan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_rombongan" id="nama_rombongan" class="form-control <?= session('errors.nama_rombongan') ? 'is-invalid' : '' ?>" value="<?= old('nama_rombongan', $isEdit ? $bus->nama_rombongan : '') ?>" placeholder="Misal: Rombongan 1 / Bus Pagi" required>
                                <?php if(session('errors.nama_rombongan')): ?><div class="invalid-feedback"><?= session('errors.nama_rombongan') ?></div><?php endif; ?>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="tanggal_digunakan_display">Tgl Diberangkatkan</label>
                                        <input type="text" id="tanggal_digunakan_display" class="form-control bg-light" value="<?= date('d/m/Y', strtotime($jadwal->tanggal_pelaksanaan)) ?>" readonly>
                                        <input type="hidden" name="tanggal_digunakan" value="<?= $jadwal->tanggal_pelaksanaan ?>">
                                        <small class="text-muted">Mengikuti tanggal jadwal aktif.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="waktu_keberangkatan">Jam Berangkat <span class="text-danger">*</span></label>
                                        <input type="time" name="waktu_keberangkatan" id="waktu_keberangkatan" class="form-control <?= session('errors.waktu_keberangkatan') ? 'is-invalid' : '' ?>" value="<?= old('waktu_keberangkatan', $isEdit ? $bus->waktu_keberangkatan : '') ?>" required>
                                        <?php if(session('errors.waktu_keberangkatan')): ?><div class="invalid-feedback"><?= session('errors.waktu_keberangkatan') ?></div><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="kapasitas">Kapasitas Kursi (Jumlah Penumpang) <span class="text-danger">*</span></label>
                                <input type="number" name="kapasitas" id="kapasitas" class="form-control <?= session('errors.kapasitas') ? 'is-invalid' : '' ?>" value="<?= old('kapasitas', $isEdit ? $bus->kapasitas : '40') ?>" min="1" required>
                                <?php if(session('errors.kapasitas')): ?><div class="invalid-feedback"><?= session('errors.kapasitas') ?></div><?php endif; ?>
                            </div>
                        </div>

                        <!-- Kolom Info Armada Vendor -->
                        <div class="col-md-6">
                            <h6 class="text-primary font-weight-bold mb-3"><i class="fas fa-industry mr-1"></i> Data Armada & Vendor</h6>

                            <div class="form-group mb-3">
                                <label for="perusahaan_bus">Nama Perusahaan Bus / PO <span class="text-danger">*</span></label>
                                <input type="text" name="perusahaan_bus" id="perusahaan_bus" class="form-control <?= session('errors.perusahaan_bus') ? 'is-invalid' : '' ?>" value="<?= old('perusahaan_bus', $isEdit ? $bus->perusahaan_bus : '') ?>" placeholder="Sinar Jaya, Rosalia Indah, dsb." required>
                                <?php if(session('errors.perusahaan_bus')): ?><div class="invalid-feedback"><?= session('errors.perusahaan_bus') ?></div><?php endif; ?>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="no_polisi">Plat Nomor (No. Polisi) <span class="text-danger">*</span></label>
                                        <input type="text" name="no_polisi" id="no_polisi" class="form-control text-uppercase <?= session('errors.no_polisi') ? 'is-invalid' : '' ?>" value="<?= old('no_polisi', $isEdit ? $bus->no_polisi : '') ?>" placeholder="B 1234 CD" required>
                                        <?php if(session('errors.no_polisi')): ?><div class="invalid-feedback"><?= session('errors.no_polisi') ?></div><?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="koordinator_bus">Supir / Koordinator <span class="text-danger">*</span></label>
                                        <input type="text" name="koordinator_bus" id="koordinator_bus" class="form-control <?= session('errors.koordinator_bus') ? 'is-invalid' : '' ?>" value="<?= old('koordinator_bus', $isEdit ? $bus->koordinator_bus : '') ?>" placeholder="Nama Pak Supir" required>
                                        <?php if(session('errors.koordinator_bus')): ?><div class="invalid-feedback"><?= session('errors.koordinator_bus') ?></div><?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="no_kontak">No HP Supir / Kontak Bus <span class="text-danger">*</span></label>
                                <input type="text" name="no_kontak" id="no_kontak" class="form-control <?= session('errors.no_kontak') ? 'is-invalid' : '' ?>" value="<?= old('no_kontak', $isEdit ? $bus->no_kontak : '') ?>" placeholder="081xxxxxxxxx" required>
                                <?php if(session('errors.no_kontak')): ?><div class="invalid-feedback"><?= session('errors.no_kontak') ?></div><?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
                
                <div class="card-footer bg-light text-right" style="border-radius: 0 0 10px 10px;">
                    <a href="<?= base_url('admin-bus') ?>" class="btn btn-default shadow-sm mr-2"><i class="fas fa-times mr-1"></i> Batal</a>
                    <button type="submit" class="btn btn-primary shadow-sm font-weight-bold px-4"><i class="fas <?= $isEdit ? 'fa-save' : 'fa-plus-circle' ?> mr-1"></i> <?= $isEdit ? 'Simpan Perjalanan' : 'Buat Rombongan' ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    // Basic init if needed
});
</script>
<?= $this->endSection(); ?>
