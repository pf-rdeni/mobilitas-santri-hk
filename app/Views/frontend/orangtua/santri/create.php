<?= $this->extend('frontend/template/template'); ?>

<?= $this->section('content'); ?>

<!-- Flash Errors -->
<?php if (session()->getFlashdata('errors')) : ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Breadcrumb -->
<div class="mb-3">
    <a href="<?= base_url('orangtua') ?>" class="text-muted"><i class="fas fa-home"></i> Dashboard</a>
    <span class="text-muted mx-1">/</span>
    <a href="<?= base_url('orangtua/santri') ?>" class="text-muted">Data Santri</a>
    <span class="text-muted mx-1">/</span>
    <span class="text-dark">Tambah</span>
</div>

<div class="card card-primary card-outline">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title"><i class="fas fa-user-plus mr-2"></i> Tambah Data Anak / Santri</h3>
    </div>
    <form action="<?= base_url('orangtua/santri/store') ?>" method="post">
        <?= csrf_field() ?>
        <div class="card-body">
            
            <div class="row mb-4">
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <label class="d-block text-muted small">Foto Profil</label>
                    <div id="image-preview-container" class="mx-auto bg-light border rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 120px; height: 120px; overflow: hidden; cursor: pointer; border: 3px solid #dee2e6 !important;">
                        <i class="fas fa-camera fa-2x text-muted" id="placeholder-icon"></i>
                        <img id="image-preview" src="#" alt="Preview" class="d-none w-100 h-100" style="object-fit: cover;">
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-xs mt-2" onclick="document.getElementById('foto_input').click()">
                        <i class="fas fa-upload mr-1"></i> Pilih Foto
                    </button>
                    <input type="file" id="foto_input" accept="image/*" class="d-none">
                    <input type="hidden" name="cropped_image" id="cropped_image">
                </div>
                <div class="col-md-9 pt-md-2">
                    <div class="form-group mb-0">
                        <label>Nama Lengkap Santri <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control form-control-lg" value="<?= old('nama') ?>" required placeholder="Masukkan nama lengkap santri">
                        <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle mr-1"></i> Klik area lingkaran atau tombol "Pilih Foto" untuk mengunggah foto profil.</small>
                    </div>
                </div>
            </div>

            <h5 class="border-bottom pb-2 mb-3 text-primary"><i class="fas fa-info-circle"></i> Detail Personal Santri</h5>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Jenis Kelamin <span class="text-danger">*</span></label>
                    <div class="d-flex mt-2">
                        <div class="custom-control custom-radio mr-3">
                            <input type="radio" id="jk_l" name="jenis_kelamin" value="L" class="custom-control-input" <?= old('jenis_kelamin') == 'L' ? 'checked' : '' ?> required>
                            <label class="custom-control-label font-weight-normal" for="jk_l">Laki-laki</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="jk_p" name="jenis_kelamin" value="P" class="custom-control-input" <?= old('jenis_kelamin') == 'P' ? 'checked' : '' ?> required>
                            <label class="custom-control-label font-weight-normal" for="jk_p">Perempuan</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 form-group">
                    <label>Tempat Lahir <span class="text-danger">*</span></label>
                    <input type="text" name="tempat_lahir" class="form-control" value="<?= old('tempat_lahir') ?>" required placeholder="Contoh: Jakarta">
                </div>
                <div class="col-md-4 form-group">
                    <label>Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="<?= old('tanggal_lahir') ?>" required>
                </div>
            </div>

            <h5 class="border-bottom pb-2 mb-3 mt-3 text-primary"><i class="fas fa-users"></i> Data Orang Tua</h5>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Nama Bapak <span class="text-danger">*</span></label>
                    <input type="text" name="nama_bapak" class="form-control" value="<?= old('nama_bapak') ?>" required placeholder="Masukkan nama Bapak">
                </div>
                <div class="col-md-6 form-group">
                    <label>No. HP / WA Bapak <span class="text-danger">*</span></label>
                    <input type="text" name="no_hp_bapak" class="form-control" value="<?= old('no_hp_bapak') ?>" required placeholder="628xxxx / 08xxxx">
                </div>
                <div class="col-md-6 form-group">
                    <label>Nama Ibu <span class="text-danger">*</span></label>
                    <input type="text" name="nama_ibu" class="form-control" value="<?= old('nama_ibu') ?>" required placeholder="Masukkan nama Ibu">
                </div>
                <div class="col-md-6 form-group">
                    <label>No. HP / WA Ibu <span class="text-danger">*</span></label>
                    <input type="text" name="no_hp_ibu" class="form-control" value="<?= old('no_hp_ibu') ?>" required placeholder="628xxxx / 08xxxx">
                </div>
            </div>

            <h5 class="border-bottom pb-2 mb-3 mt-3 text-primary"><i class="fas fa-home"></i> Alamat & Asrama</h5>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>Asrama <span class="text-danger">*</span></label>
                    <select name="asrama" class="form-control" required>
                        <option value="">-- Pilih Asrama --</option>
                        <?php 
                            $listAsrama = ['Ali Bin Abi Thalib', 'Utsman Bin Affan', 'Umar Bin Khattab', 'Abu Bakar Siddiq', 'Fathimah', 'Hafshoh', 'Ummu Kultsum', 'Asma', 'Khodijah', 'Aisyah'];
                            foreach($listAsrama as $as):
                        ?>
                            <option value="<?= $as ?>" <?= old('asrama') == $as ? 'selected' : '' ?>><?= $as ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>Provinsi <span class="text-danger">*</span></label>
                    <select name="provinsi_id" id="provinsi_id" class="form-control select2" style="width: 100%;" required>
                        <option value="">Pilih Provinsi</option>
                    </select>
                    <input type="hidden" name="provinsi_nama" id="provinsi_nama">
                </div>

                <div class="col-md-4 form-group">
                    <label>Kabupaten / Kota <span class="text-danger">*</span></label>
                    <select name="kabupaten_id" id="kabupaten_id" class="form-control select2" style="width: 100%;" required disabled>
                        <option value="">Pilih Kabupaten</option>
                    </select>
                    <input type="hidden" name="kabupaten_nama" id="kabupaten_nama">
                </div>

                <div class="col-md-4 form-group">
                    <label>Kecamatan <span class="text-danger">*</span></label>
                    <select name="kecamatan_id" id="kecamatan_id" class="form-control select2" style="width: 100%;" required disabled>
                        <option value="">Pilih Kecamatan</option>
                    </select>
                    <input type="hidden" name="kecamatan_nama" id="kecamatan_nama">
                </div>

                <div class="col-md-4 form-group">
                    <label>Kelurahan / Desa <span class="text-danger">*</span></label>
                    <select name="kelurahan_id" id="kelurahan_id" class="form-control select2" style="width: 100%;" required disabled>
                        <option value="">Pilih Kelurahan</option>
                    </select>
                    <input type="hidden" name="kelurahan_nama" id="kelurahan_nama">
                </div>
                
                <div class="col-md-12 form-group">
                    <label>Alamat Rumah Lengkap <span class="text-danger">*</span></label>
                    <textarea name="alamat_rumah" class="form-control" rows="2" required placeholder="Jalan, No Rumah, RT/RW..."><?= old('alamat_rumah') ?></textarea>
                </div>
            </div>

            <h5 class="border-bottom pb-2 mb-3 mt-4 text-secondary"><i class="fas fa-graduation-cap"></i> Riwayat Akademik Awal</h5>
            <div class="row">
                <div class="col-md-4 form-group text-dark">
                    <label>Tahun Ajaran <span class="text-danger">*</span></label>
                    <select name="awal_tahun_ajaran" class="form-control" required>
                        <?php 
                            $currentYear = date('Y');
                            $defaultThn = $currentYear . '/' . ($currentYear + 1);
                            for($i = $currentYear - 2; $i <= $currentYear + 1; $i++):
                                $thn = $i . '/' . ($i + 1);
                        ?>
                            <option value="<?= $thn ?>" <?= old('awal_tahun_ajaran', $defaultThn) == $thn ? 'selected' : '' ?>><?= $thn ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-4 form-group text-dark">
                    <label>Kelas <span class="text-danger">*</span></label>
                    <input type="text" name="awal_kelas" class="form-control" value="<?= old('awal_kelas') ?>" required placeholder="Contoh: 7A, 10-IPA-1">
                </div>
                <div class="col-md-4 form-group text-dark">
                    <label>Asrama <span class="text-danger">*</span></label>
                    <select name="awal_asrama" class="form-control" required>
                        <option value="">-- Pilih Asrama --</option>
                        <?php 
                            $listAsrama = ['Ali Bin Abi Thalib', 'Utsman Bin Affan', 'Umar Bin Khattab', 'Abu Bakar Siddiq', 'Fathimah', 'Hafshoh', 'Ummu Kultsum', 'Asma', 'Khodijah', 'Aisyah'];
                            foreach($listAsrama as $as):
                        ?>
                            <option value="<?= $as ?>" <?= old('awal_asrama') == $as ? 'selected' : '' ?>><?= $as ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group mt-4 text-right">
                <a href="<?= base_url('orangtua/santri') ?>" class="btn btn-default"><i class="fas fa-times"></i> Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Data Santri</button>
            </div>
        </div>
    </form>
</div>

<!-- Modal Cropper -->
<div class="modal fade" id="modalCropper" tabindex="-1" role="dialog" aria-labelledby="modalCropperLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title" id="modalCropperLabel"><i class="fas fa-crop-alt mr-2"></i> Sesuaikan Foto Profil</h5>
                <button type="button" class="close text-white outline-none" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0 bg-light" style="overflow: hidden;">
                <div class="img-container">
                    <img id="image-to-crop" src="" style="max-width: 100%;">
                </div>
            </div>
            <div class="modal-footer bg-white border-0 justify-content-between p-3">
                <button type="button" class="btn btn-light px-3" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Batal</button>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-secondary" id="rotate-left" title="Putar Kiri"><i class="fas fa-undo"></i></button>
                    <button type="button" class="btn btn-outline-secondary" id="rotate-right" title="Putar Kanan"><i class="fas fa-redo"></i></button>
                    <button type="button" class="btn btn-primary px-4 ml-2" id="crop-save"><i class="fas fa-check mr-1"></i> Terapkan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Cropper.js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<style>
    .img-container {
        min-height: 300px;
        max-height: 500px;
        width: 100%;
        text-align: center;
    }
    #image-preview-container:hover {
        background-color: #e9ecef !important;
        border-color: #007bff !important;
    }
</style>

<script>
$(document).ready(function() {
    // Inisialisasi Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // 1. Load Data Provinsi saat halaman dibuka
    $.ajax({
        url: 'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json',
        type: 'GET',
        dataType: 'json',
        success: function(result) {
            var options = '<option value="">Pilih Provinsi</option>';
            $.each(result, function(key, val) {
                options += '<option value="' + val.id + '" data-nama="' + val.name + '">' + val.name + '</option>';
            });
            $('#provinsi_id').html(options);
        }
    });

    // 2. Fetch Kabupaten saat Provinsi berubah
    $('#provinsi_id').change(function() {
        var provId = $(this).val();
        var provNama = $(this).find(':selected').data('nama');
        $('#provinsi_nama').val(provNama);
        
        // Reset dropdown bawahnya
        $('#kabupaten_id').html('<option value="">Loading...</option>').prop('disabled', true);
        $('#kecamatan_id').html('<option value="">Pilih Kecamatan</option>').prop('disabled', true);
        $('#kelurahan_id').html('<option value="">Pilih Kelurahan</option>').prop('disabled', true);
        
        if (provId) {
            $.ajax({
                url: 'https://www.emsifa.com/api-wilayah-indonesia/api/regencies/' + provId + '.json',
                type: 'GET',
                dataType: 'json',
                success: function(result) {
                    var options = '<option value="">Pilih Kabupaten/Kota</option>';
                    $.each(result, function(key, val) {
                        options += '<option value="' + val.id + '" data-nama="' + val.name + '">' + val.name + '</option>';
                    });
                    $('#kabupaten_id').html(options).prop('disabled', false);
                }
            });
        }
    });

    // 3. Fetch Kecamatan saat Kabupaten berubah
    $('#kabupaten_id').change(function() {
        var kabId = $(this).val();
        var kabNama = $(this).find(':selected').data('nama');
        $('#kabupaten_nama').val(kabNama);
        
        $('#kecamatan_id').html('<option value="">Loading...</option>').prop('disabled', true);
        $('#kelurahan_id').html('<option value="">Pilih Kelurahan</option>').prop('disabled', true);
        
        if (kabId) {
            $.ajax({
                url: 'https://www.emsifa.com/api-wilayah-indonesia/api/districts/' + kabId + '.json',
                type: 'GET',
                dataType: 'json',
                success: function(result) {
                    var options = '<option value="">Pilih Kecamatan</option>';
                    $.each(result, function(key, val) {
                        options += '<option value="' + val.id + '" data-nama="' + val.name + '">' + val.name + '</option>';
                    });
                    $('#kecamatan_id').html(options).prop('disabled', false);
                }
            });
        }
    });

    // 4. Fetch Kelurahan saat Kecamatan berubah
    $('#kecamatan_id').change(function() {
        var kecId = $(this).val();
        var kecNama = $(this).find(':selected').data('nama');
        $('#kecamatan_nama').val(kecNama);
        
        $('#kelurahan_id').html('<option value="">Loading...</option>').prop('disabled', true);
        
        if (kecId) {
            $.ajax({
                url: 'https://www.emsifa.com/api-wilayah-indonesia/api/villages/' + kecId + '.json',
                type: 'GET',
                dataType: 'json',
                success: function(result) {
                    var options = '<option value="">Pilih Kelurahan/Desa</option>';
                    $.each(result, function(key, val) {
                        options += '<option value="' + val.id + '" data-nama="' + val.name + '">' + val.name + '</option>';
                    });
                    $('#kelurahan_id').html(options).prop('disabled', false);
                }
            });
        }
    });

    // Set Kelurahan Nama on change
    $('#kelurahan_id').change(function() {
        var kelNama = $(this).find(':selected').data('nama');
        $('#kelurahan_nama').val(kelNama);
    });

    // --- LOGIKA CROPPER ---
    var $modal = $('#modalCropper');
    var $image = $('#image-to-crop');
    var cropper;

    $('#image-preview-container').on('click', function() {
        $('#foto_input').click();
    });

    $('#foto_input').on('change', function(e) {
        var files = e.target.files;
        var done = function(url) {
            $image.attr('src', url);
            $modal.modal('show');
        };

        if (files && files.length > 0) {
            var reader = new FileReader();
            reader.onload = function(e) {
                done(reader.result);
            };
            reader.readAsDataURL(files[0]);
        }
    });

    $modal.on('shown.bs.modal', function() {
        cropper = new Cropper($image[0], {
            aspectRatio: 1,
            viewMode: 2,
            autoCropArea: 1,
        });
    }).on('hidden.bs.modal', function() {
        cropper.destroy();
        cropper = null;
        $('#foto_input').val(''); // Reset input file
    });

    $('#rotate-left').click(function() { cropper.rotate(-90); });
    $('#rotate-right').click(function() { cropper.rotate(90); });

    $('#crop-save').click(function() {
        var canvas = cropper.getCroppedCanvas({
            width: 400,
            height: 400,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        canvas.toBlob(function(blob) {
            var reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function() {
                var base64data = reader.result;
                $('#cropped_image').val(base64data);
                $('#image-preview').attr('src', base64data).removeClass('d-none');
                $('#placeholder-icon').addClass('d-none');
                $modal.modal('hide');
            }
        }, 'image/jpeg', 0.85); // Compress fixed result to JPEG 85%
    });
});
</script>
<?= $this->endSection(); ?>
