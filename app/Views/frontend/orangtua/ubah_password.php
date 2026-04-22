<?= $this->extend('frontend/template/template') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header border-bottom-0 pt-3 pb-0">
                <h4 class="card-title font-weight-bold text-dark"><i class="fas fa-key mr-2 text-primary"></i> Ubah Password</h4>
            </div>
            <div class="card-body">
                
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success border-0 shadow-sm"><i class="fas fa-check-circle mr-1"></i> <?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger border-0 shadow-sm"><i class="fas fa-exclamation-triangle mr-1"></i> <?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger border-0 shadow-sm">
                        <ul class="mb-0 pl-3">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('orangtua/ubah-password/update') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="form-group mb-3">
                        <label for="password_lama">Password Saat Ini</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-unlock-alt"></i></span>
                            </div>
                            <input type="password" class="form-control" name="password_lama" id="password_lama" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="password_baru">Password Baru</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" name="password_baru" id="password_baru" required minlength="6" placeholder="Minimal 6 karakter">
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="konfirmasi_password">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                            </div>
                            <input type="password" class="form-control" name="konfirmasi_password" id="konfirmasi_password" required>
                        </div>
                    </div>

                    <div class="text-right d-flex justify-content-between align-items-center">
                        <a href="<?= base_url('orangtua') ?>" class="btn btn-link text-muted p-0"><i class="fas fa-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm"><i class="fas fa-save mr-1"></i> Simpan Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
