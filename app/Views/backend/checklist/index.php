<?= $this->extend($layout); ?>

<?= $this->section('content'); ?>
<div class="row">
    <div class="col-12">
        <?php if (!$jadwal): ?>
            <div class="alert alert-warning border-0 shadow-sm"><i class="fas fa-exclamation-triangle"></i> Tidak ada jadwal aktif.</div>
        <?php elseif (empty($rombongan)): ?>
            <div class="alert alert-info border-0 shadow-sm"><i class="fas fa-info-circle"></i> Belum ada armada bus (rombongan) yang didaftarkan.</div>
        <?php else: ?>
            <div class="card card-outline card-success shadow-sm">
                <div class="card-header bg-white pt-3 pb-2 border-bottom-0">
                    <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-clipboard-check text-success mr-2"></i> <?= $title ?></h3>
                    <div class="card-tools">
                        <span class="badge badge-success px-3 py-2 text-sm">
                            <i class="far fa-calendar-alt"></i> <?= date('d M Y', strtotime($jadwal->tanggal_pelaksanaan)) ?>
                        </span>
                    </div>
                </div>

                <div class="card-body bg-light">
                    <div class="row pt-3">
                        <?php foreach($rombongan as $r): $santris = $r['santri']; ?>
                            <div class="col-12 mb-4">
                                <div class="card shadow-sm h-100 border-0" style="border-radius: 10px;">
                                    <?php 
                                        $allDone = ($r['total_santri'] > 0 && $r['total_checkin'] == $r['total_santri']);
                                        $headerBg = $allDone ? 'bg-success' : ($r['type'] == 'terminal' ? 'bg-info' : 'bg-primary'); 
                                        $icon = $r['type'] == 'terminal' ? 'fas fa-plane' : 'fas fa-bus-alt';
                                    ?>
                                    <div class="card-header <?= $headerBg ?> text-white" style="border-radius: 10px 10px 0 0;">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h5 class="font-weight-bold mb-0 text-white"><i class="<?= $icon ?> mr-1"></i> <?= esc($r['title']) ?></h5>
                                            <?php if($allDone): ?>
                                                <span class="badge badge-light text-success"><i class="fas fa-check-double"></i> SELESAI</span>
                                            <?php else: ?>
                                                <span class="badge badge-light text-white bg-transparent border border-white"><?= $r['total_checkin'] ?>/<?= $r['total_santri'] ?> Siap</span>
                                            <?php endif; ?>
                                        </div>
                                        <small class="opacity-80"><i class="fas fa-info-circle mr-1"></i> <?= esc($r['subtitle']) ?></small>
                                    </div>

                                    <div class="card-body p-0">
                                        <ul class="list-group list-group-flush" id="checklist_<?= $r['id_group'] ?>">
                                            <?php if(empty($santris)): ?>
                                                <li class="list-group-item text-center text-muted py-4">Belum ada santri yang terdata di grup ini.</li>
                                            <?php else: ?>
                                                <?php foreach($santris as $s): ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-3 <?= ($s->status_checkin == 'sudah' && $s->status_bus == 'sudah' && $s->status_istirahat == 'sudah') ? 'bg-light' : '' ?>" style="border-left: 5px solid transparent; transition: 0.3s; border-bottom: 1px solid rgba(0,0,0,0.05);">
                                                        <div class="d-flex align-items-center flex-grow-1" style="min-width: 0;">
                                                            <?php if($s->foto && file_exists(FCPATH . 'uploads/santri/thumb_' . $s->foto)): ?>
                                                                <img src="<?= base_url('uploads/santri/thumb_' . $s->foto) ?>" class="img-circle mr-3 shadow-sm" style="width: 45px; height: 45px; object-fit: cover; flex-shrink: 0; border: 2px solid #fff;">
                                                            <?php else: ?>
                                                                <?php 
                                                                    $names = explode(' ', trim($s->nama));
                                                                    $initials = strtoupper(substr($names[0], 0, 1));
                                                                    if (count($names) > 1) $initials .= strtoupper(substr(end($names), 0, 1));
                                                                    $bgColors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger', 'bg-indigo', 'bg-purple', 'bg-orange', 'bg-teal'];
                                                                    $bgClass = $bgColors[abs(crc32($s->nama)) % count($bgColors)];
                                                                ?>
                                                                <div class="img-circle mr-3 shadow-sm <?= $bgClass ?> d-inline-flex align-items-center justify-content-center text-white font-weight-bold flex-shrink-0" style="width: 45px; height: 45px; font-size: 1rem; border: 2px solid #fff;">
                                                                    <?= $initials ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            <div class="text-truncate">
                                                                <h6 class="mb-0 font-weight-bold text-lg" title="<?= esc($s->nama) ?>"><?= esc($s->nama) ?></h6>
                                                                <div class="text-muted small d-flex align-items-center mt-1">
                                                                    <?php if($r['type'] == 'terminal'): ?>
                                                                        <span class="badge badge-light border mr-2"><i class="fas fa-bus mr-1"></i> <?= esc($s->nama_bus ?: '-') ?></span>
                                                                    <?php endif; ?>
                                                                    <span class="mr-2"><i class="fas fa-plane mr-1"></i> <?= esc($s->maskapai) ?> (<?= esc($s->no_penerbangan) ?>)</span>
                                                                    <span class="badge border"><i class="far fa-clock mr-1 text-info"></i> <?= date('H:i', strtotime($s->waktu_penerbangan)) ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="d-flex" style="flex-shrink: 0;">
                                                            <?php foreach($config['stages'] as $stageKey => $cfg): ?>
                                                                <?php 
                                                                    $valField = 'status_' . $stageKey;
                                                                    $isChecked = $s->$valField == 'sudah';
                                                                ?>
                                                                <div class="text-center mx-1 d-flex flex-column align-items-center" style="width: 45px;">
                                                                    <i class="<?= $cfg['icon'] ?> <?= $cfg['color'] ?> mb-1" style="font-size: 0.8rem;" title="<?= $cfg['label'] ?>" data-toggle="tooltip"></i>
                                                                    <label class="checklist-switch mb-0">
                                                                        <input type="checkbox" class="chk-status" 
                                                                               data-tiket-id="<?= $s->id ?>" 
                                                                               data-stage="<?= $stageKey ?>" 
                                                                               <?= $isChecked ? 'checked' : '' ?>>
                                                                        <span class="slider"></span>
                                                                    </label>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </li>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Custom Minimalist Switch */
.checklist-switch {
    position: relative;
    display: inline-block;
    width: 28px;
    height: 15px;
}
.checklist-switch input { 
    opacity: 0; 
    width: 0; 
    height: 0; 
    position: absolute;
}
.slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #dee2e6;
    transition: .3s;
    border-radius: 15px;
}
.slider:before {
    position: absolute;
    content: "";
    height: 11px;
    width: 11px;
    left: 2px;
    bottom: 2px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}
input:checked + .slider {
    background-color: #007bff;
}
input:checked + .slider:before {
    transform: translateX(13px);
}

/* Specific Stage Colors when checked */
input[data-stage="bus"]:checked + .slider { background-color: #28a745; }
input[data-stage="istirahat"]:checked + .slider { background-color: #ffc107; }

.tooltip { font-size: 0.7rem; }
.text-xs { font-size: 0.8rem; }
</style>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    $('[title]').tooltip(); // Initialize Tooltips

    let csrfName = '<?= csrf_token() ?>';
    let csrfHash = '<?= csrf_hash() ?>';

    $('.chk-status').on('change', function() {
        let isChecked = $(this).is(':checked');
        let tiketId = $(this).data('tiket-id');
        let stage = $(this).data('stage');
        let status = isChecked ? 'sudah' : 'belum';
        
        let chk = $(this);
        let data = {
            id_tiket: tiketId,
            stage: stage,
            status: status
        };
        data[csrfName] = csrfHash;

        $.ajax({
            url: '<?= base_url('admin-checklist/toggle') ?>',
            type: 'POST',
            data: data,
            success: function(res) {
                if(res.csrf_hash) csrfHash = res.csrf_hash; // ROTATE CSRF

                if(res.status === 'success') {
                    let listItem = chk.closest('.list-group-item');
                } else {
                    Swal.fire('Error', res.message, 'error');
                    chk.prop('checked', !isChecked); // revert
                }
            },
            error: function(xhr) {
                if(xhr.responseJSON && xhr.responseJSON.csrf_hash) {
                    csrfHash = xhr.responseJSON.csrf_hash;
                }
                Swal.fire('Error', 'Gagal terhubung ke server.', 'error');
                chk.prop('checked', !isChecked); // revert
            }
        });
    });
});
</script>
<?= $this->endSection(); ?>
