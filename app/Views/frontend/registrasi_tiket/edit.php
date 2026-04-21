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
    <span class="text-dark">Edit Tiket Penerbangan</span>
</div>

<!-- Form Card -->
<div class="card card-warning card-outline">
    <div class="card-header bg-warning">
        <h3 class="card-title text-dark"><i class="fas fa-edit"></i> Edit Data Penerbangan Santri</h3>
    </div>
    <form action="<?= base_url('registrasi-tiket/update/' . $tiket->id) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="card-body">

            <!-- Section 1: Data Santri -->
            <div class="form-section-title"><i class="fas fa-user-graduate"></i> 1. Data Santri</div>
            <div class="form-group">
                <label>Pilih Anak (Santri)</label>
                <select name="id_santri" class="form-control" required>
                    <option value="">-- Pilih Santri --</option>
                    <?php foreach ($santri as $s) : ?>
                        <option value="<?= $s->id ?>" <?= (old('id_santri', $tiket->id_santri) == $s->id) ? 'selected' : '' ?>>
                            <?= esc($s->nama) ?> (<?= esc($s->daerah_asal) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Section 2: Jadwal Perjalanan -->
            <div class="form-section-title"><i class="fas fa-calendar-alt"></i> 2. Jadwal Registrasi</div>
            <div class="form-group bg-light p-3 border rounded">
                <label class="mb-0 text-primary"><i class="fas fa-calendar-check mr-1"></i> Jadwal Saat Ini (Tiket Anda):</label>
                <h5 class="font-weight-bold mt-1 mb-0"><?= esc($jadwalTiket->nama_kegiatan) ?> (<?= ucfirst($jadwalTiket->jenis) ?>)</h5>
                <small class="text-muted">Jadwal tanggal: <span class="badge badge-info"><?= date('d M Y', strtotime($jadwalTiket->tanggal_pelaksanaan)) ?></span></small>
                
                <input type="hidden" name="id_jadwal" id="id_jadwal" value="<?= $jadwalTiket->id ?>">
                <input type="hidden" name="jenis_perjalanan" id="jenis_perjalanan" value="<?= $jadwalTiket->jenis ?>">
            </div>

            <!-- Section 3: Detail Penerbangan -->
            <div class="form-section-title"><i class="fas fa-plane"></i> 3. Detail Penerbangan</div>

            <div class="form-group row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label>Bandara Keberangkatan (Asal)</label>
                    <select name="bandara_asal" id="bandara_asal" class="form-control select2bs4" style="width: 100%;" required>
                        <option value="">-- Ketik Nama atau Kota Bandara --</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Bandara Tujuan</label>
                    <select name="bandara_tujuan" id="bandara_tujuan" class="form-control select2bs4" style="width: 100%;" required>
                        <option value="">-- Ketik Nama atau Kota Bandara --</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Maskapai Penerbangan</label>
                <select name="maskapai" class="form-control" required>
                    <option value="">-- Pilih Maskapai --</option>
                    <?php 
                        $maskapaiList = ['Garuda Indonesia', 'Batik Air', 'Citilink', 'Lion Air', 'Super Air Jet', 'Sriwijaya Air', 'AirAsia', 'Lainnya'];
                        foreach($maskapaiList as $m) :
                    ?>
                        <option value="<?= $m ?>" <?= (old('maskapai', $tiket->maskapai) == $m) ? 'selected' : '' ?>><?= $m ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Kode Booking Pesawat PNR (6 Karakter)</label>
                <input type="text" name="kode_booking" class="form-control" value="<?= old('kode_booking', $tiket->kode_booking) ?>" placeholder="Contoh: AB12CD" maxlength="10" required style="text-transform: uppercase;">
            </div>

            <div class="form-group">
                <label>Terminal Bandara (Soekarno-Hatta)</label>
                <div>
                    <?php $ter = old('terminal_bandara', $tiket->terminal_bandara); ?>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="term1" name="terminal_bandara" class="custom-control-input" value="1" <?= $ter == '1' ? 'checked' : '' ?> required>
                        <label class="custom-control-label" for="term1">Terminal 1</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="term2" name="terminal_bandara" class="custom-control-input" value="2" <?= $ter == '2' ? 'checked' : '' ?> required>
                        <label class="custom-control-label" for="term2">Terminal 2</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="term3" name="terminal_bandara" class="custom-control-input" value="3" <?= $ter == '3' ? 'checked' : '' ?> required>
                        <label class="custom-control-label" for="term3">Terminal 3</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Tanggal Penerbangan</label>
                    <?php 
                        $currentDate = !empty($tiket->waktu_penerbangan) ? date('Y-m-d', strtotime($tiket->waktu_penerbangan)) : $jadwalTiket->tanggal_pelaksanaan;
                    ?>
                    <?php if ($jadwalTiket->jenis == 'kepulangan') : ?>
                        <?php 
                            $date0 = $jadwalTiket->tanggal_pelaksanaan;
                            $date1 = date('Y-m-d', strtotime($date0 . ' +1 day'));
                        ?>
                        <div class="d-flex mt-1">
                            <div class="custom-control custom-radio mr-4">
                                <input type="radio" id="date0" name="tanggal_penerbangan" class="custom-control-input" value="<?= $date0 ?>" <?= old('tanggal_penerbangan', $currentDate) == $date0 ? 'checked' : '' ?> required>
                                <label class="custom-control-label font-weight-normal" for="date0">
                                    <?= date('d M Y', strtotime($date0)) ?> <small class="text-muted">(Hari H)</small>
                                </label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="date1" name="tanggal_penerbangan" class="custom-control-input" value="<?= $date1 ?>" <?= old('tanggal_penerbangan', $currentDate) == $date1 ? 'checked' : '' ?>>
                                <label class="custom-control-label font-weight-normal" for="date1">
                                    <?= date('d M Y', strtotime($date1)) ?> <small class="text-muted">(Besoknya)</small>
                                </label>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            </div>
                            <input type="text" class="form-control" value="<?= date('d M Y', strtotime($jadwalTiket->tanggal_pelaksanaan)) ?>" readonly>
                            <input type="hidden" name="tanggal_penerbangan" value="<?= $jadwalTiket->tanggal_pelaksanaan ?>">
                        </div>
                        <small class="text-muted">Penjemputan dilakukan tepat pada tanggal kedatangan pesawat.</small>
                    <?php endif; ?>
                </div>

                <div class="col-md-6 mb-3">
                    <label id="label_waktu_penerbangan">Waktu Penerbangan (Jam Take-off / Landing)</label>
                    <?php 
                        $timeOnly = '';
                        if(!empty($tiket->waktu_penerbangan)) {
                            $timeOnly = date('H:i', strtotime($tiket->waktu_penerbangan));
                        }
                    ?>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                        </div>
                        <input type="time" name="waktu_penerbangan" class="form-control" value="<?= old('waktu_penerbangan', $timeOnly) ?>" required>
                    </div>
                </div>
            </div>

            <!-- Section 4: Lampiran Berkas & Pembayaran -->
            <div class="form-section-title mt-4"><i class="fas fa-file-invoice-dollar"></i> 4. Lampiran & Pembayaran Bus</div>

            <div class="form-group row bg-light p-3 border rounded mx-0">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label>Status Transfer Biaya Bus Panitia</label>
                    <div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="tf_belum" name="status_transfer" class="custom-control-input" value="belum" <?= old('status_transfer', $tiket->status_transfer ?? 'belum') == 'belum' ? 'checked' : '' ?> required>
                            <label class="custom-control-label" for="tf_belum">Belum Transfer</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="tf_sudah" name="status_transfer" class="custom-control-input" value="sudah" <?= old('status_transfer', $tiket->status_transfer ?? '') == 'sudah' ? 'checked' : '' ?> required>
                            <label class="custom-control-label text-success" for="tf_sudah">Sudah Transfer</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Lampiran Bukti Transfer <span id="label_bukti_transfer" class="text-muted"><small>(Bila ada, Max 2MB)</small></span></label>
                    <?php if(!empty($tiket->bukti_transfer)): ?>
                        <div class="mb-2" id="preview_bukti_transfer_wrapper">
                            <a href="<?= base_url('uploads/transfer/' . $tiket->bukti_transfer) ?>" target="_blank" id="existing_bukti_transfer">
                                <img src="<?= base_url('uploads/transfer/thumb_' . $tiket->bukti_transfer) ?>" alt="Bukti Transfer" class="img-thumbnail" style="height: 80px" onerror="this.onerror=null; this.src='<?= base_url('uploads/transfer/' . $tiket->bukti_transfer) ?>';">
                            </a>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="bukti_transfer_input" name="bukti_transfer" class="form-control-file" accept=".jpg,.jpeg,.png,.pdf">
                    <small class="text-info">*Pilih file baru jika ingin mengganti lampiran lama.</small>
                </div>
            </div>

            <div class="form-group mt-3">
                <label>Lampiran Bukti Tiket Penerbangan (E-Ticket / Boarding Pass)</label>
                <?php if(!empty($tiket->bukti_tiket)): ?>
                    <div class="mb-2">
                        <a href="<?= base_url('uploads/tiket/' . $tiket->bukti_tiket) ?>" target="_blank">
                            <img src="<?= base_url('uploads/tiket/thumb_' . $tiket->bukti_tiket) ?>" alt="Bukti Tiket" class="img-thumbnail" style="height: 100px" onerror="this.onerror=null; this.src='<?= base_url('uploads/tiket/' . $tiket->bukti_tiket) ?>';">
                        </a>
                    </div>
                <?php endif; ?>
                <div class="alert alert-info py-2 px-3 mb-2 small"><i class="fas fa-info-circle"></i> Wajib dilampirkan agar panitia bisa melakukan pengecekan validasi tiket terkait keberangkatan/kepulangan. (Format: JPG/PNG/PDF, Max 2 MB).</div>
                <input type="file" name="bukti_tiket" class="form-control-file border p-2 rounded" accept=".jpg,.jpeg,.png,.pdf">
                <small class="text-info">*Biarkan kosong jika tidak ingin mengubah file tiket lama.</small>
            </div>

        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="<?= base_url('orangtua') ?>" class="btn btn-default"><i class="fas fa-arrow-left"></i> Batal</a>
            <button type="submit" class="btn btn-warning font-weight-bold text-dark"><i class="fas fa-save"></i> Perbarui Data Tiket</button>
        </div>
    </form>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<style>
    .form-section-title { font-weight: bold; margin-top: 20px; margin-bottom: 10px; border-bottom: 2px solid #dee2e6; padding-bottom: 5px; color: #495057; }
</style>
<script>
$(document).ready(function() {
    function updateLabel() {
        var jenis = $('#jenis_perjalanan').val();
        
        if (jenis === 'kepulangan') {
            $('#label_waktu_penerbangan').html('<i class="fas fa-plane-departure text-primary"></i> Jam Take-off (Keberangkatan)');
        } else if (jenis === 'kedatangan') {
            $('#label_waktu_penerbangan').html('<i class="fas fa-plane-arrival text-success"></i> Jam Landing (Kedatangan)');
        } else {
            $('#label_waktu_penerbangan').html('Waktu Penerbangan (Jam Take-off / Landing)');
        }
    }

    // Call once on load for pre-selected schedule
    updateLabel();

    // Validasi dynamis Bukti Transfer
    function toggleBuktiTransfer() {
        var isSudah = $('#tf_sudah').is(':checked');
        var hasExisting = $('#existing_bukti_transfer').length > 0;
        
        if (isSudah) {
            if (!hasExisting) {
                $('#bukti_transfer_input').prop('required', true);
                $('#label_bukti_transfer').html('<span class="text-danger">* Wajib Diupload</span> <small class="text-muted">(Max 2MB)</small>');
            } else {
                $('#bukti_transfer_input').prop('required', false);
                $('#label_bukti_transfer').html('<span class="text-success"><i class="fas fa-check-circle"></i> Sudah Dilampirkan</span> <small class="text-muted">(Max 2MB)</small>');
            }
        } else {
            $('#bukti_transfer_input').prop('required', false);
            $('#label_bukti_transfer').html('<small class="text-muted">(Pilih file jika sudah transfer, Max 2MB)</small>');
        }
    }

    var previousStatus = $('input[name="status_transfer"]:checked').val();

    $('input[name="status_transfer"]').change(function() {
        var newStatus = $(this).val();
        
        // Cek jika pindah dari 'sudah' ke 'belum'
        if (newStatus === 'belum' && previousStatus === 'sudah') {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Jika diubah ke Belum Transfer, bukti transfer sebelumnya (jika ada) akan dihapus secara otomatis saat Anda menyimpan form ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Ubah Status!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#bukti_transfer_input').val(''); // Clear file input
                    $('#preview_bukti_transfer_wrapper').hide(); // Sembunyikan div wrapper keseluruhan
                    $('#existing_bukti_transfer').remove(); // Hapus a-tag-nya agar JS validasi menangkap isExisting = false
                    previousStatus = 'belum';
                    toggleBuktiTransfer();
                } else {
                    // Revert kembali ke "sudah"
                    $('#tf_sudah').prop('checked', true);
                    previousStatus = 'sudah';
                    toggleBuktiTransfer();
                }
            });
        } else {
            previousStatus = newStatus;
            toggleBuktiTransfer();
        }
    });

    // Inisialisasi Select2 & Fetch Data Bandara
    $('.select2bs4').select2({
        theme: 'bootstrap4',
        placeholder: '-- Ketik Nama atau Kota Bandara --',
        allowClear: true
    });

    $.getJSON('<?= base_url('assets/data/bandara.json') ?>', function(data) {
        var options = '<option value=""></option>';
        $.each(data, function(index, airport) {
            var text = airport.name + ' (' + airport.iata + ') - ' + airport.city;
            options += '<option value="' + text + '">' + text + '</option>';
        });
        
        $('#bandara_asal').append(options);
        $('#bandara_tujuan').append(options);
        
        // Restore existing values
        var oldAsal = '<?= old('bandara_asal', $tiket->bandara_asal) ?>';
        if (oldAsal) {
            $('#bandara_asal').val(oldAsal).trigger('change');
        }
        var oldTujuan = '<?= old('bandara_tujuan', $tiket->bandara_tujuan) ?>';
        if (oldTujuan) {
            $('#bandara_tujuan').val(oldTujuan).trigger('change');
        }
    });

    // Run on load
    toggleBuktiTransfer();
});
</script>
<?= $this->endSection(); ?>
