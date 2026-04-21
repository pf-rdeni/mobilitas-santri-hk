<?= $this->extend('backend/template/template'); ?>

<?= $this->section('content'); ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                <h3 class="card-title font-weight-bold text-dark">
                    <i class="fas <?= isset($jadwal) ? 'fa-edit' : 'fa-plus-circle' ?> text-primary mr-2"></i> 
                    <?= isset($jadwal) ? 'Ubah Data Jadwal' : 'Tambah Jadwal Baru' ?>
                </h3>
            </div>
            <div class="card-body">
                
                <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger shadow-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i> <?= session()->getFlashdata('error') ?>
                </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger shadow-sm">
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form action="<?= isset($jadwal) ? base_url('admin-jadwal/update/' . $jadwal->id) : base_url('admin-jadwal/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Jenis Mobilitas <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-md-6 text-center">
                                <label class="w-100 p-3 border rounded <?= (old('jenis', $jadwal->jenis ?? '') == 'kedatangan') ? 'border-success bg-light' : '' ?>" style="cursor: pointer;">
                                    <input type="radio" name="jenis" value="kedatangan" <?= (old('jenis', $jadwal->jenis ?? '') == 'kedatangan') ? 'checked' : '' ?> required>
                                    <div class="mt-2">
                                        <i class="fas fa-plane-arrival fa-2x text-success mb-2"></i><br>
                                        <span class="font-weight-bold">Kedatangan</span><br>
                                        <small class="text-muted">(Santri arah kembali ke HK)</small>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6 text-center">
                                <label class="w-100 p-3 border rounded <?= (old('jenis', $jadwal->jenis ?? '') == 'kepulangan') ? 'border-warning bg-light' : '' ?>" style="cursor: pointer;">
                                    <input type="radio" name="jenis" value="kepulangan" <?= (old('jenis', $jadwal->jenis ?? '') == 'kepulangan') ? 'checked' : '' ?> required>
                                    <div class="mt-2">
                                        <i class="fas fa-plane-departure fa-2x text-warning mb-2"></i><br>
                                        <span class="font-weight-bold">Kepulangan</span><br>
                                        <small class="text-muted">(Santri libur dari HK)</small>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="tanggal_pelaksanaan" class="font-weight-bold">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white"><i class="fas fa-calendar-day"></i></span>
                            </div>
                            <input type="date" class="form-control form-control-lg" id="tanggal_pelaksanaan" name="tanggal_pelaksanaan" 
                                value="<?= old('tanggal_pelaksanaan', $jadwal->tanggal_pelaksanaan ?? '') ?>" required>
                        </div>
                        <small class="text-muted mt-1 d-block">Tanggal ini akan muncul di form pendaftaran wali santri sebagai satu-satunya opsi tanggal pelaksanaan.</small>
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Status Awal <span class="text-danger">*</span></label>
                        <select name="status" class="form-control form-control-lg" required>
                            <option value="aktif" <?= (old('status', $jadwal->status ?? '') == 'aktif') ? 'selected' : '' ?>>Aktif (Digunakan Sekarang)</option>
                            <option value="selesai" <?= (old('status', $jadwal->status ?? '') == 'selesai') ? 'selected' : '' ?>>Non-Aktif / Selesai</option>
                        </select>
                        <p class="text-warning small mt-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Catatan: Jika memilih status <b>Aktif</b>, sistem akan menonaktifkan jadwal lain secara otomatis.
                        </p>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('admin-jadwal') ?>" class="btn btn-light px-4 border">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary px-5 font-weight-bold shadow">
                            <i class="fas fa-save mr-1"></i> Simpan Data Jadwal
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    $('input[name="jenis"]').on('change', function() {
        $('.border-success, .border-warning').removeClass('border-success border-warning bg-light');
        if($(this).val() == 'kedatangan') {
            $(this).parent().addClass('border-success bg-light');
        } else {
            $(this).parent().addClass('border-warning bg-light');
        }
    });
});
</script>
<?= $this->endSection(); ?>
