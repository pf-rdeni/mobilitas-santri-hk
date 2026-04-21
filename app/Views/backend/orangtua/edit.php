<?= $this->extend('backend/template/template'); ?>

<?= $this->section('content'); ?>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card card-outline card-info shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-user-edit mr-2 text-info"></i> <?= $title ?></h3>
            </div>
            <form action="<?= base_url('orangtua-manage/update/' . $user->id) ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    
                    <div class="alert alert-light border small shadow-sm mb-4">
                        <i class="fas fa-info-circle mr-2 text-info"></i> <strong>Catatan:</strong> Kosongkan field <strong>Password</strong> jika tidak ingin mengubah password akun wali ini. Username biasanya menggunakan nomor HP.
                    </div>

                    <div class="form-group mb-3">
                        <label for="fullname">Nama Lengkap Wali <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            </div>
                            <input type="text" name="fullname" id="fullname" class="form-control <?= session('errors.fullname') ? 'is-invalid' : '' ?>" value="<?= old('fullname', $user->fullname) ?>" placeholder="Nama lengkap sesuai KTP/KK" required>
                            <?php if (session('errors.fullname')) : ?>
                                <div class="invalid-feedback"><?= session('errors.fullname') ?></div>
                            <?php endif ?>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="username">No HP (Username) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                            </div>
                            <input type="text" name="username" id="username" class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>" value="<?= old('username', $user->username) ?>" placeholder="Contoh: 081234567890" required>
                            <?php if (session('errors.username')) : ?>
                                <div class="invalid-feedback"><?= session('errors.username') ?></div>
                            <?php endif ?>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email">Alamat Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="email" name="email" id="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" value="<?= old('email', $user->email) ?>" placeholder="email@contoh.com" required>
                            <?php if (session('errors.email')) : ?>
                                <div class="invalid-feedback"><?= session('errors.email') ?></div>
                            <?php endif ?>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="text-muted font-weight-bold mb-3"><i class="fas fa-key mr-1"></i> Ganti Password (Opsional)</h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="password">Password Baru</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="password" id="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" placeholder="Minimal 6 karakter" autocomplete="new-password">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-info" type="button" id="btn-regenerate" title="Buat password acak baru">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                    <?php if (session('errors.password')) : ?>
                                        <div class="invalid-feedback"><?= session('errors.password') ?></div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="pass_confirm">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                                    </div>
                                    <input type="password" name="pass_confirm" id="pass_confirm" class="form-control <?= session('errors.pass_confirm') ? 'is-invalid' : '' ?>" placeholder="Ulangi password">
                                    <?php if (session('errors.pass_confirm')) : ?>
                                        <div class="invalid-feedback"><?= session('errors.pass_confirm') ?></div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer bg-light text-right border-top-0">
                    <a href="<?= base_url('orangtua-manage') ?>" class="btn btn-default mr-1"><i class="fas fa-times"></i> Batal</a>
                    <button type="submit" class="btn btn-info px-4 shadow-sm font-weight-bold"><i class="fas fa-save mr-1"></i> Perbarui Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    let currentUserId = '<?= $user->id ?>';
    $('#username').on('blur', function() {
        let username = $(this).val();
        if (username.length > 5) {
            $.ajax({
                url: '<?= base_url('api/check-username') ?>',
                type: 'POST',
                data: {
                    username: username,
                    ignore_id: currentUserId,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                success: function(response) {
                    if (response.status === 'exists') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Nomor Sudah Terdaftar!',
                            text: 'Nomor HP ini sudah digunakan oleh: ' + response.fullname,
                            confirmButtonText: 'Mengerti'
                        });
                        $('#username').val('');
                    }
                }
            });
        }
    });

    // Make password visible when generated
    $('#password').attr('type', 'text');
    $('#pass_confirm').attr('type', 'text');

    // Regenerate password button logic
    $('#btn-regenerate').on('click', function() {
        const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#*';
        let pass = '';
        for (let i = 0; i < 6; i++) {
            pass += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        $('#password').val(pass);
        $('#pass_confirm').val(pass);
        
        let icon = $(this).find('i');
        icon.addClass('fa-spin');
        setTimeout(function() {
            icon.removeClass('fa-spin');
        }, 500);
    });
});
</script>
<?= $this->endSection(); ?>
