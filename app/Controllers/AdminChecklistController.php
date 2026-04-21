<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ArmadaBusModel;
use App\Models\JadwalMobilitasModel;
use App\Models\RegistrasiTiketModel;
use App\Models\PenugasanTerminalPanitiaModel;
use Myth\Auth\Models\UserModel;

class AdminChecklistController extends BaseController
{
    protected $busModel;
    protected $jadwalModel;
    protected $tiketModel;
    protected $terminalPanitiaModel;
    protected $userModel;

    public function __construct()
    {
        $this->busModel = new ArmadaBusModel();
        $this->jadwalModel = new JadwalMobilitasModel();
        $this->tiketModel = new RegistrasiTiketModel();
        $this->terminalPanitiaModel = new PenugasanTerminalPanitiaModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $jadwalAktif = $this->jadwalModel->where('status', 'aktif')->first();
        if (!$jadwalAktif) {
            $jadwalAktif = $this->jadwalModel->first();
        }

        $rombonganData = [];
        if ($jadwalAktif) {
            $userId = user_id();
            $isAdmin = in_groups(['admin', 'superadmin']);
            $isPanitiaOnly = in_groups('panitia') && !$isAdmin;
            
            // 1. Cek Assignment Terminal
            $myTerminalAssignments = $this->terminalPanitiaModel->where('id_jadwal', $jadwalAktif->id)
                                                                 ->where('id_panitia', $userId)
                                                                 ->findAll();

            // 2. Cek Assignment Bus
            $allBusesRaw = $this->busModel->where('id_jadwal', $jadwalAktif->id)->findAll();
            $myBusIds = [];
            foreach($allBusesRaw as $b) {
                $ids = explode(',', $b->id_pendamping_bus ?? '');
                if (in_array($userId, $ids)) $myBusIds[] = $b->id;
            }

            // MODE DETERMINATION
            $requestedMode = $this->request->getGet('mode'); // 'bus' atau 'terminal'

            // Jika dia pndamping terminal, utamakan view Terminal (kecuali diminta explicitly bus)
            if ($isPanitiaOnly && !empty($myTerminalAssignments) && $requestedMode !== 'bus') {
                // --- MODE TERMINAL ---
                $terminals = [];
                foreach($myTerminalAssignments as $ta) $terminals[] = $ta->terminal_bandara;
                $terminals = array_unique($terminals);

                foreach($terminals as $t) {
                    $santriList = $this->tiketModel->select('registrasi_tiket.*, santri.nama, santri.foto, armada_bus.nama_rombongan as nama_bus')
                                                   ->join('santri', 'santri.id = registrasi_tiket.id_santri')
                                                   ->join('armada_bus', 'armada_bus.id = registrasi_tiket.id_bus', 'left')
                                                   ->where('terminal_bandara', $t)
                                                   ->where('registrasi_tiket.id_jadwal', $jadwalAktif->id)
                                                   ->orderBy('waktu_penerbangan', 'ASC')
                                                   ->findAll();
                    
                    $checkedInCount = 0;
                    foreach ($santriList as $s) {
                        if ($s->status_checkin == 'sudah') $checkedInCount++;
                    }

                    $rombonganData[] = [
                        'type'          => 'terminal',
                        'title'         => "Terminal " . $t,
                        'subtitle'      => "Daftar santri lintas bus yang tiba/berangkat di Terminal " . $t,
                        'santri'        => $santriList,
                        'total_santri'  => count($santriList),
                        'total_checkin' => $checkedInCount,
                        'id_group'      => $t
                    ];
                }
            } else {
                // --- MODE BUS (Default / Admin) ---
                $busQuery = $this->busModel->where('id_jadwal', $jadwalAktif->id);
                if ($isPanitiaOnly) {
                    if (empty($myBusIds)) $busQuery->where('id', 0);
                    else $busQuery->whereIn('id', $myBusIds);
                }
                
                $buses = $busQuery->orderBy('nama_rombongan', 'ASC')->findAll();

                // mapping panitia
                $panitiasShared = $this->userModel->select('id, fullname, username')->findAll();
                $panitiaMap = [];
                foreach($panitiasShared as $p) $panitiaMap[$p->id] = !empty($p->fullname) ? $p->fullname : $p->username;

                foreach ($buses as $bus) {
                    $santriList = $this->tiketModel->select('registrasi_tiket.*, santri.nama, santri.foto')
                                                   ->join('santri', 'santri.id = registrasi_tiket.id_santri')
                                                   ->where('id_bus', $bus->id)
                                                   ->where('id_jadwal', $jadwalAktif->id)
                                                   ->orderBy('waktu_penerbangan', 'ASC')
                                                   ->findAll();

                    $checkedInCount = 0;
                    foreach ($santriList as $s) {
                        if ($s->status_checkin == 'sudah') $checkedInCount++;
                    }

                    $termAssigns = $this->terminalPanitiaModel->where('id_bus', $bus->id)->findAll();
                    $namesTerm = [];
                    foreach($termAssigns as $ta) {
                        if(isset($panitiaMap[$ta->id_panitia])) {
                            $namesTerm[] = $panitiaMap[$ta->id_panitia] . " (T-{$ta->terminal_bandara})";
                        }
                    }
                    $bus->nama_pendamping_terminal = implode(', ', $namesTerm);

                    $rombonganData[] = [
                        'type'          => 'bus',
                        'title'         => $bus->nama_rombongan,
                        'subtitle'      => $bus->no_polisi . " | Koord: " . $bus->koordinator_bus,
                        'bus'           => $bus,
                        'santri'        => $santriList,
                        'total_santri'  => count($santriList),
                        'total_checkin' => $checkedInCount,
                        'id_group'      => $bus->id
                    ];
                }
            }
        }

        // Konfigurasi Dinamis berdasarkan Jenis Jadwal
        $isKedatangan = ($jadwalAktif && $jadwalAktif->jenis == 'kedatangan');
        $config = [
            'pageTitle' => $isKedatangan ? 'Checklist Kembali ke Pondok' : 'Checklist Pulang ke Rumah',
            'stages'    => [
                'checkin' => [
                    'label' => $isKedatangan ? 'Tiba di Terminal' : 'Sampai di Terminal',
                    'icon'  => $isKedatangan ? 'fas fa-plane-arrival' : 'fas fa-plane-departure',
                    'color' => 'text-primary'
                ],
                'bus' => [
                    'label' => $isKedatangan ? 'Naik Bus ke Pondok' : 'Naik Bus dari Pondok',
                    'icon'  => 'fas fa-bus',
                    'color' => 'text-success'
                ],
                'istirahat' => [
                    'label' => 'Istirahat / Rest Area',
                    'icon'  => 'fas fa-coffee',
                    'color' => 'text-warning'
                ]
            ]
        ];

        $data = [
            'layout'     => (in_groups('panitia') && !in_groups(['admin', 'superadmin'])) ? 'backend/template/layout_panitia' : 'backend/template/template',
            'title'      => $config['pageTitle'],
            'pageTitle'  => $config['pageTitle'],
            'jadwal'     => $jadwalAktif,
            'rombongan'  => $rombonganData,
            'config'     => $config,
            'breadcrumb' => [
                ['title' => 'Home', 'url' => (in_groups('panitia') && !in_groups(['admin', 'superadmin'])) ? 'panitia' : 'dashboard'],
                ['title' => 'Checklist'],
            ],
        ];

        return view('backend/checklist/index', $data);
    }

    public function toggleStage()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $id_tiket = $this->request->getPost('id_tiket');
        $stage    = $this->request->getPost('stage'); // 'checkin', 'bus', 'istirahat'
        $status   = $this->request->getPost('status'); // 'belum' atau 'sudah'

        $columnMap = [
            'checkin'   => 'status_checkin',
            'bus'       => 'status_bus',
            'istirahat' => 'status_istirahat'
        ];

        if (!isset($columnMap[$stage])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid stage.']);
        }

        if ($this->tiketModel->update($id_tiket, [$columnMap[$stage] => $status])) {
            return $this->response->setJSON([
                'status' => 'success',
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error', 
            'message' => 'Gagal mengupdate status.',
            'csrf_hash' => csrf_hash()
        ]);
    }
}
