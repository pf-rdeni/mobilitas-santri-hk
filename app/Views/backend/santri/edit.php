<?= $this->extend('backend/template/template'); ?>

<?= $this->section('content'); ?>

<!-- Breadcrumb is handled via $breadcrumb in controller/template if using standard template -->

<div class="row">
    <!-- COL 1: Data Pokok -->
    <div class="col-lg-7">
        <div class="card card-warning card-outline mb-4 shadow-sm">
            <div class="card-header bg-warning">
                <h3 class="card-title text-dark font-weight-bold"><i class="fas fa-user-edit mr-2"></i> Edit Data Pokok Santri (ID: #<?= $santri->id ?>)</h3>
            </div>
            <form action="<?= base_url('santri/update/' . $santri->id) ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    
                    <div class="row mb-4">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <label class="d-block text-muted small text-dark">Foto Profil</label>
                            <div id="image-preview-container" class="mx-auto bg-light border rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 140px; height: 140px; overflow: hidden; cursor: pointer; border: 4px solid #ffc107 !important;">
                                <?php if($santri->foto && file_exists(FCPATH . 'uploads/santri/thumb_' . $santri->foto)): ?>
                                    <img id="image-preview" src="<?= base_url('uploads/santri/thumb_' . $santri->foto) ?>" alt="Preview" class="w-100 h-100" style="object-fit: cover;">
                                <?php else: ?>
                                    <i class="fas fa-camera fa-2x text-muted" id="placeholder-icon"></i>
                                    <img id="image-preview" src="#" alt="Preview" class="d-none w-100 h-100" style="object-fit: cover;">
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn btn-outline-warning btn-xs mt-2 text-dark px-3" onclick="document.getElementById('foto_input').click()">
                                <i class="fas fa-upload mr-1"></i> Perbarui Foto
                            </button>
                            <input type="file" id="foto_input" accept="image/*" class="d-none">
                            <input type="hidden" name="cropped_image" id="cropped_image">
                        </div>
                        <div class="col-md-8 pt-md-2">
                            <div class="form-group mb-0">
                                <label>Nama Lengkap Santri <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control form-control-lg font-weight-bold" value="<?= old('nama', $santri->nama) ?>" required>
                                <small class="text-muted mt-2 d-block bg-light p-2 rounded border"><i class="fas fa-info-circle mr-1 text-info"></i> Gunakan lingkaran di samping atau tombol kuning untuk mengubah foto. Perubahan foto baru akan tersimpan setelah klik "Update Data" di bawah.</small>
                            </div>
                        </div>
                    </div>

                    <h5 class="border-bottom pb-2 mb-3 mt-4 text-warning"><i class="fas fa-info-circle"></i> Detail Personal Santri</h5>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Jenis Kelamin <span class="text-danger">*</span></label>
                            <div class="d-flex mt-1">
                                <div class="custom-control custom-radio mr-3">
                                    <input type="radio" id="jk_l" name="jenis_kelamin" value="L" class="custom-control-input" <?= old('jenis_kelamin', $santri->jenis_kelamin) == 'L' ? 'checked' : '' ?> required>
                                    <label class="custom-control-label font-weight-normal text-dark" for="jk_l">Laki-laki</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="jk_p" name="jenis_kelamin" value="P" class="custom-control-input" <?= old('jenis_kelamin', $santri->jenis_kelamin) == 'P' ? 'checked' : '' ?> required>
                                    <label class="custom-control-label font-weight-normal text-dark" for="jk_p">Perempuan</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" name="tempat_lahir" class="form-control" value="<?= old('tempat_lahir', $santri->tempat_lahir) ?>" required placeholder="Contoh: Jakarta">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_lahir" class="form-control" value="<?= old('tanggal_lahir', $santri->tanggal_lahir) ?>" required>
                        </div>
                    </div>

                    <h5 class="border-bottom pb-2 mb-3 mt-3 text-warning"><i class="fas fa-users"></i> Data Orang Tua</h5>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Nama Bapak <span class="text-danger">*</span></label>
                            <input type="text" name="nama_bapak" class="form-control" value="<?= old('nama_bapak', $santri->nama_bapak) ?>" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>No. HP Bapak <span class="text-danger">*</span></label>
                            <input type="text" name="no_hp_bapak" class="form-control" value="<?= old('no_hp_bapak', $santri->no_hp_bapak) ?>" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Nama Ibu <span class="text-danger">*</span></label>
                            <input type="text" name="nama_ibu" class="form-control" value="<?= old('nama_ibu', $santri->nama_ibu) ?>" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>No. HP Ibu <span class="text-danger">*</span></label>
                            <input type="text" name="no_hp_ibu" class="form-control" value="<?= old('no_hp_ibu', $santri->no_hp_ibu) ?>" required>
                        </div>
                    </div>

                    <h5 class="border-bottom pb-2 mb-3 mt-3 text-warning"><i class="fas fa-home"></i> Alamat & Asrama</h5>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label>Asrama <span class="text-danger">*</span></label>
                            <select name="asrama" class="form-control" required>
                                <option value="">-- Pilih Asrama --</option>
                                <?php 
                                    $listAsrama = ['Ali Bin Abi Thalib', 'Utsman Bin Affan', 'Umar Bin Khattab', 'Abu Bakar Siddiq', 'Fathimah', 'Hafshoh', 'Ummu Kultsum', 'Asma', 'Khodijah', 'Aisyah'];
                                    foreach($listAsrama as $as):
                                ?>
                                    <option value="<?= $as ?>" <?= old('asrama', $santri->asrama) == $as ? 'selected' : '' ?>><?= $as ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Provinsi <span class="text-danger">*</span></label>
                            <select name="provinsi_id" id="provinsi_id" class="form-control select2" style="width: 100%;" required>
                                <option value="">Pilih Provinsi</option>
                            </select>
                            <input type="hidden" name="provinsi_nama" id="provinsi_nama" value="<?= old('provinsi_nama', $santri->provinsi_nama) ?>">
                        </div>

                        <div class="col-md-6 form-group">
                            <label>Kabupaten / Kota <span class="text-danger">*</span></label>
                            <select name="kabupaten_id" id="kabupaten_id" class="form-control select2" style="width: 100%;" required>
                                <option value="">Pilih Kabupaten</option>
                            </select>
                            <input type="hidden" name="kabupaten_nama" id="kabupaten_nama" value="<?= old('kabupaten_nama', $santri->kabupaten_nama) ?>">
                        </div>

                        <div class="col-md-6 form-group">
                            <label>Kecamatan <span class="text-danger">*</span></label>
                            <select name="kecamatan_id" id="kecamatan_id" class="form-control select2" style="width: 100%;" required>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                            <input type="hidden" name="kecamatan_nama" id="kecamatan_nama" value="<?= old('kecamatan_nama', $santri->kecamatan_nama) ?>">
                        </div>

                        <div class="col-md-6 form-group">
                            <label>Kelurahan / Desa <span class="text-danger">*</span></label>
                            <select name="kelurahan_id" id="kelurahan_id" class="form-control select2" style="width: 100%;" required>
                                <option value="">Pilih Kelurahan</option>
                            </select>
                            <input type="hidden" name="kelurahan_nama" id="kelurahan_nama" value="<?= old('kelurahan_nama', $santri->kelurahan_nama) ?>">
                        </div>

                        <div class="col-md-12 form-group">
                            <label>Alamat Rumah Lengkap <span class="text-danger">*</span></label>
                            <textarea name="alamat_rumah" class="form-control" rows="2" required placeholder="Jalan, No Rumah, RT/RW..."><?= old('alamat_rumah', $santri->alamat_rumah) ?></textarea>
                        </div>
                    </div>


                </div>
                <div class="card-footer text-right">
                    <a href="<?= base_url('santri') ?>" class="btn btn-default mr-1"><i class="fas fa-times"></i> Batal</a>
                    <button type="submit" class="btn btn-warning text-dark font-weight-bold"><i class="fas fa-save"></i> Perbarui Data Santri</button>
                </div>
            </form>
        </div>
    </div>

    <!-- COL 2: Riwayat Akademik (Read Only / Delete allowed if needed, using existing riwayat modal path or similar) -->
    <div class="col-lg-5">
        <div class="card card-info card-outline shadow-sm">
            <div class="card-header bg-info text-white">
                <h3 class="card-title font-weight-bold"><i class="fas fa-history mr-2"></i> Riwayat Akademik</h3>
            </div>
            <div class="card-body p-0">
                <?php if (empty($riwayat)): ?>
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-info-circle mb-2" style="font-size: 2rem;"></i>
                        <p>Belum ada riwayat akademik untuk santri ini.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered m-0">
                            <thead class="bg-light small">
                                <tr>
                                    <th>Thn Ajaran</th>
                                    <th>Kelas</th>
                                    <th>Asrama</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($riwayat as $r): ?>
                                <tr>
                                    <td class="align-middle"><strong><?= esc($r->tahun_ajaran) ?></strong></td>
                                    <td class="align-middle badge badge-light border mt-2 ml-2"><?= esc($r->kelas) ?></td>
                                    <td class="align-middle small"><?= esc($r->asrama) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-light text-muted small border-top">
                <i class="fas fa-exclamation-triangle mr-1 text-warning"></i> Admin hanya dapat merubah data pokok. Perubahan riwayat akademik disarankan dilakukan oleh orang tua melalui dashboard mereka agar akurat.
            </div>
        </div>
    </div>
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

<style>
    .img-container {
        min-height: 300px;
        max-height: 500px;
        width: 100%;
        text-align: center;
    }
    #image-preview-container:hover {
        background-color: #f8f9fa !important;
        border-color: #ffc107 !important;
        opacity: 0.8;
    }
</style>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<!-- Select2 & Cropper -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
$(document).ready(function() {
    $('.select2').select2({ theme: 'bootstrap4' });

    // Initial Data from DB
    var initProvId = "<?= old('provinsi_id', $santri->provinsi_id) ?>";
    var initKabId  = "<?= old('kabupaten_id', $santri->kabupaten_id) ?>";
    var initKecId  = "<?= old('kecamatan_id', $santri->kecamatan_id) ?>";
    var initKelId  = "<?= old('kelurahan_id', $santri->kelurahan_id) ?>";

    // 1. Load Provinces
    $.ajax({
        url: 'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json',
        type: 'GET',
        dataType: 'json',
        success: function(result) {
            var options = '<option value="">Pilih Provinsi</option>';
            $.each(result, function(key, val) {
                var selected = (val.id == initProvId) ? 'selected' : '';
                options += '<option value="' + val.id + '" data-nama="' + val.name + '" ' + selected + '>' + val.name + '</option>';
            });
            $('#provinsi_id').html(options);
            if(initProvId) loadKabupaten(initProvId, initKabId);
        }
    });

    function loadKabupaten(provId, selectedKabId = null) {
        if (!provId) return;
        $('#kabupaten_id').html('<option value="">Loading...</option>').prop('disabled', true);
        $.ajax({
            url: 'https://www.emsifa.com/api-wilayah-indonesia/api/regencies/' + provId + '.json',
            type: 'GET',
            dataType: 'json',
            success: function(result) {
                var options = '<option value="">Pilih Kabupaten</option>';
                $.each(result, function(key, val) {
                    var selected = (val.id == selectedKabId) ? 'selected' : '';
                    options += '<option value="' + val.id + '" data-nama="' + val.name + '" ' + selected + '>' + val.name + '</option>';
                });
                $('#kabupaten_id').html(options).prop('disabled', false);
                if(selectedKabId) loadKecamatan(selectedKabId, initKecId);
            }
        });
    }

    function loadKecamatan(kabId, selectedKecId = null) {
        if (!kabId) return;
        $('#kecamatan_id').html('<option value="">Loading...</option>').prop('disabled', true);
        $.ajax({
            url: 'https://www.emsifa.com/api-wilayah-indonesia/api/districts/' + kabId + '.json',
            type: 'GET',
            dataType: 'json',
            success: function(result) {
                var options = '<option value="">Pilih Kecamatan</option>';
                $.each(result, function(key, val) {
                    var selected = (val.id == selectedKecId) ? 'selected' : '';
                    options += '<option value="' + val.id + '" data-nama="' + val.name + '" ' + selected + '>' + val.name + '</option>';
                });
                $('#kecamatan_id').html(options).prop('disabled', false);
                if(selectedKecId) loadKelurahan(selectedKecId, initKelId);
            }
        });
    }

    function loadKelurahan(kecId, selectedKelId = null) {
        if (!kecId) return;
        $('#kelurahan_id').html('<option value="">Loading...</option>').prop('disabled', true);
        $.ajax({
            url: 'https://www.emsifa.com/api-wilayah-indonesia/api/villages/' + kecId + '.json',
            type: 'GET',
            dataType: 'json',
            success: function(result) {
                var options = '<option value="">Pilih Kelurahan</option>';
                $.each(result, function(key, val) {
                    var selected = (val.id == selectedKelId) ? 'selected' : '';
                    options += '<option value="' + val.id + '" data-nama="' + val.name + '" ' + selected + '>' + val.name + '</option>';
                });
                $('#kelurahan_id').html(options).prop('disabled', false);
            }
        });
    }

    // ON CHANGE EVENTS
    $('#provinsi_id').change(function() {
        $('#provinsi_nama').val($(this).find(':selected').data('nama'));
        $('#kabupaten_id').html('<option value="">Pilih Kabupaten</option>').prop('disabled', true);
        $('#kecamatan_id').html('<option value="">Pilih Kecamatan</option>').prop('disabled', true);
        $('#kelurahan_id').html('<option value="">Pilih Kelurahan</option>').prop('disabled', true);
        loadKabupaten($(this).val());
    });
    $('#kabupaten_id').change(function() {
        $('#kabupaten_nama').val($(this).find(':selected').data('nama'));
        $('#kecamatan_id').html('<option value="">Pilih Kecamatan</option>').prop('disabled', true);
        $('#kelurahan_id').html('<option value="">Pilih Kelurahan</option>').prop('disabled', true);
        loadKecamatan($(this).val());
    });
    $('#kecamatan_id').change(function() {
        $('#kecamatan_nama').val($(this).find(':selected').data('nama'));
        $('#kelurahan_id').html('<option value="">Pilih Kelurahan</option>').prop('disabled', true);
        loadKelurahan($(this).val());
    });
    $('#kelurahan_id').change(function() {
        $('#kelurahan_nama').val($(this).find(':selected').data('nama'));
    });

    // --- LOGIKA CROPPER ---
    var $modal = $('#modalCropper');
    var $image = $('#image-to-crop');
    var cropper;

    $('#image-preview-container').on('click', function() { $('#foto_input').click(); });

    $('#foto_input').on('change', function(e) {
        var files = e.target.files;
        var done = function(url) { $image.attr('src', url); $modal.modal('show'); };
        if (files && files.length > 0) {
            var reader = new FileReader();
            reader.onload = function(e) { done(reader.result); };
            reader.readAsDataURL(files[0]);
        }
    });

    $modal.on('shown.bs.modal', function() {
        cropper = new Cropper($image[0], { aspectRatio: 1, viewMode: 2, autoCropArea: 1 });
    }).on('hidden.bs.modal', function() {
        cropper.destroy(); cropper = null;
        $('#foto_input').val(''); 
    });

    $('#rotate-left').click(function() { cropper.rotate(-90); });
    $('#rotate-right').click(function() { cropper.rotate(90); });

    $('#crop-save').click(function() {
        var canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
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
        }, 'image/jpeg', 0.85); 
    });
});
</script>
<?= $this->endSection(); ?>
