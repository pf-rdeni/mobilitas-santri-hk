<?= $this->extend('backend/template/template'); ?>

<?= $this->section('content'); ?>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-user-plus mr-2 text-primary"></i> <?= $title ?></h3>
            </div>
            <form action="<?= base_url('panitia-manage/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    
                    <div class="text-center mb-4">
                        <div class="d-inline-block bg-light p-4 rounded-circle mb-3 shadow-sm border">
                            <i class="fas fa-user-tie fa-3x text-primary"></i>
                        </div>
                        <p class="text-muted">Lengkapi data di bawah ini untuk mendaftarkan akun panitia baru.</p>
                    </div>

                    <div class="form-group mb-3">
                        <label for="fullname">Nama Lengkap Panitia <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            </div>
                            <input type="text" name="fullname" id="fullname" class="form-control <?= session('errors.fullname') ? 'is-invalid' : '' ?>" value="<?= old('fullname') ?>" placeholder="Sesuai KTP/KK" required>
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
                            <input type="text" name="username" id="username" class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>" value="<?= old('username') ?>" placeholder="Contoh: 081234567890" required>
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
                            <input type="email" name="email" id="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" value="<?= old('email') ?>" placeholder="email@contoh.com" required>
                            <?php if (session('errors.email')) : ?>
                                <div class="invalid-feedback"><?= session('errors.email') ?></div>
                            <?php endif ?>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="useDefaultPassword" checked>
                            <label class="custom-control-label text-info" style="cursor: pointer;" for="useDefaultPassword">
                                Gunakan password default: <strong>passwordpanitia123</strong>
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="password">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" name="password" id="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" placeholder="Minimal 8 karakter" value="passwordpanitia123" readonly required autocomplete="new-password">
                                    <?php if (session('errors.password')) : ?>
                                        <div class="invalid-feedback"><?= session('errors.password') ?></div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="pass_confirm">Konfirmasi Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                                    </div>
                                    <input type="password" name="pass_confirm" id="pass_confirm" class="form-control <?= session('errors.pass_confirm') ? 'is-invalid' : '' ?>" placeholder="Ulangi password" value="passwordpanitia123" readonly required>
                                    <?php if (session('errors.pass_confirm')) : ?>
                                        <div class="invalid-feedback"><?= session('errors.pass_confirm') ?></div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer bg-light text-right border-top-0">
                    <a href="<?= base_url('panitia-manage') ?>" class="btn btn-default mr-1"><i class="fas fa-times"></i> Batal</a>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm font-weight-bold"><i class="fas fa-save mr-1"></i> Simpan Akun Panitia</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    $('#username').on('blur', function() {
        let username = $(this).val();
        if (username.length > 5) {
            $.ajax({
                url: '<?= base_url('api/check-username') ?>',
                type: 'POST',
                data: {
                    username: username,
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

    // Make default password visible initially or let admin see it
    $('#password').attr('type', 'text');
    $('#pass_confirm').attr('type', 'text');

    // Toggle default password logic
    $('#useDefaultPassword').on('change', function() {
        if ($(this).is(':checked')) {
            $('#password').val('passwordpanitia123').prop('readonly', true).attr('type', 'text');
            $('#pass_confirm').val('passwordpanitia123').prop('readonly', true).attr('type', 'text');
        } else {
            $('#password').val('').prop('readonly', false).attr('type', 'password').focus();
            $('#pass_confirm').val('').prop('readonly', false).attr('type', 'password');
        }
    });
});
</script>
<?= $this->endSection(); ?>
