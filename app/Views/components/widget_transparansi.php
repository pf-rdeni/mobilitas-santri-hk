<?php if(empty($buses)): ?>
    <div class="alert alert-light border-info text-info shadow-sm">
        <i class="fas fa-info-circle mr-1"></i> Data pembagian rombongan armada bus belum tersedia/belum diperbarui oleh panitia.
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach($buses as $bus): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100" style="border-radius: 10px; border-top: 4px solid #28a745;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-bus text-success mr-2"></i> <?= esc($bus->nama_rombongan) ?></h6>
                            <span class="badge badge-light border text-muted"><i class="far fa-clock"></i> <?= substr($bus->waktu_keberangkatan, 0, 5) ?></span>
                        </div>

                        <!-- Progress Bar Kapasitas -->
                        <?php 
                            $persen = $bus->kapasitas > 0 ? round(($bus->terisi / $bus->kapasitas) * 100) : 0;
                            $barColor = 'bg-success';
                            if($persen > 70) $barColor = 'bg-warning';
                            if($persen >= 100) $barColor = 'bg-danger';
                        ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1 small">
                                <span class="text-muted font-weight-bold"><?= $persen ?>% Terisi</span>
                                <span class="font-weight-bold <?= $persen >= 100 ? 'text-danger' : 'text-success' ?>">
                                    <?= $bus->terisi ?> / <?= $bus->kapasitas ?> Kursi
                                </span>
                            </div>
                            <div class="progress shadow-sm" style="height: 10px; border-radius: 5px; background-color: #f0f0f0;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated <?= $barColor ?>" role="progressbar" style="width: <?= $persen ?>%" aria-valuenow="<?= $persen ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <?php if($persen >= 100): ?>
                                <small class="text-danger mt-1 d-block font-weight-bold text-center" style="font-size: 0.7rem;"><i class="fas fa-exclamation-triangle"></i> Terisi Penuh</small>
                            <?php else: ?>
                                <small class="text-success mt-1 d-block font-weight-bold text-center" style="font-size: 0.7rem;"><i class="fas fa-check-circle"></i> Tersedia <?= $bus->kapasitas - $bus->terisi ?> Kursi</small>
                            <?php endif; ?>
                        </div>
                        <ul class="list-unstyled mb-0" style="font-size: 0.9rem;">
                            <li class="mb-2 pb-2 border-bottom">
                                <span class="text-muted d-block"><i class="fas fa-id-card"></i> Kendaraan:</span>
                                <b><?= esc($bus->no_polisi) ?></b> (<?= esc($bus->perusahaan_bus) ?>)
                            </li>
                            <li class="mb-2 pb-2 border-bottom">
                                <span class="text-muted d-block"><i class="fas fa-user-shield"></i> Pendamping Bus:</span>
                                <?php if($bus->nama_pendamping_bus): ?>
                                    <b><?= esc($bus->nama_pendamping_bus) ?></b>
                                <?php else: ?>
                                    <span class="text-danger"><i>Belum Diatur</i></span>
                                <?php endif; ?>
                            </li>
                            <li class="mb-2 pb-2 border-bottom">
                                <span class="text-muted d-block"><i class="fas fa-map-marker-alt"></i> Pendamping Terminal:</span>
                                <?php if($bus->nama_pendamping_terminal): ?>
                                    <b><?= esc($bus->nama_pendamping_terminal) ?></b>
                                <?php else: ?>
                                    <span class="text-muted" style="font-style: italic;">Tidak ada pendamping terminal di bus ini</span>
                                <?php endif; ?>
                            </li>
                            <li>
                                <span class="text-muted d-block"><i class="fas fa-phone-alt"></i> Kontak Darurat (Kendaraan):</span>
                                <b><?= esc($bus->koordinator_bus) ?> - <?= esc($bus->no_kontak) ?></b>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
