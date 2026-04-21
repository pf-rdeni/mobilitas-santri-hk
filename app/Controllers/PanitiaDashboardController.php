<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ArmadaBusModel;
use App\Models\RegistrasiTiketModel;
use App\Models\PenugasanTerminalPanitiaModel;
use App\Models\JadwalMobilitasModel;

class PanitiaDashboardController extends BaseController
{
    protected $busModel;
    protected $tiketModel;
    protected $penugasanModel;
    protected $jadwalModel;

    public function __construct()
    {
        $this->busModel       = new ArmadaBusModel();
        $this->tiketModel      = new RegistrasiTiketModel();
        $this->penugasanModel = new PenugasanTerminalPanitiaModel();
        $this->jadwalModel     = new JadwalMobilitasModel();
    }

    public function index()
    {
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        $user = user();
        $userId = $user->id;

        // Ambil Jadwal Aktif
        $jadwalAktif = $this->jadwalModel->where('status', 'aktif')->first();
        if (!$jadwalAktif) {
            $jadwalAktif = $this->jadwalModel->orderBy('tanggal_pelaksanaan', 'DESC')->first();
        }

        // 1. Cek Penugasan BUS
        // Kita cari bus yang di mana user ini ada di id_pendamping_bus (string search)
        $myBus = null;
        if ($jadwalAktif) {
            $allBuses = $this->busModel->where('id_jadwal', $jadwalAktif->id)->findAll();
            foreach ($allBuses as $bus) {
                $ids = explode(',', $bus->id_pendamping_bus ?? '');
                if (in_array($userId, $ids)) {
                    $myBus = $bus;
                    break;
                }
            }
        }

        // 2. Cek Penugasan TERMINAL
        $myTerminal = null;
        if ($jadwalAktif) {
            $myTerminal = $this->penugasanModel->where('id_jadwal', $jadwalAktif->id)
                                               ->where('id_panitia', $userId)
                                               ->first();
        }

        // Logika Terminologi Dinamis
        $isKepulangan = ($jadwalAktif && $jadwalAktif->jenis == 'kepulangan');
        $labels = [
            'type'        => $isKepulangan ? 'kepulangan' : 'kedatangan',
            'termKegiatan' => $isKepulangan ? 'Keberangkatan' : 'Kedatangan',
            'termTugas'    => $isKepulangan ? 'Pantau Keberangkatan' : 'Pantau Kedatangan',
            'iconTerminal' => $isKepulangan ? 'fas fa-plane-departure' : 'fas fa-plane-arrival',
            'descTerminal' => $isKepulangan ? 'Santri yang akan berangkat pulang' : 'Santri yang tiba kembali ke pondok',
            'termBus'      => $isKepulangan ? 'Pemberangkatan Bus' : 'Penjemputan Bus',
            'descBus'      => $isKepulangan ? 'Daftar santri yang akan naik bus' : 'Daftar santri yang menumpang bus'
        ];

        $data = [
            'title'        => 'Dashboard Panitia',
            'user'         => $user,
            'jadwal'       => $jadwalAktif,
            'myBus'        => $myBus,
            'myTerminal'   => $myTerminal,
            'labels'       => $labels,
            'breadcrumb'   => [
                ['title' => 'Home', 'url' => 'panitia'],
                ['title' => 'Dashboard'],
            ],
        ];

        if ($myBus) {
            // Hitung terisi (Santri + Pendamping)
            $santriCount = $this->tiketModel->where('id_bus', $myBus->id)->countAllResults();
            $attendantsBus = array_filter(explode(',', $myBus->id_pendamping_bus ?? ''));
            
            $otherPanitias = $this->penugasanModel->where('id_bus', $myBus->id);
            if (!empty($attendantsBus)) {
                $otherPanitias->whereNotIn('id_panitia', $attendantsBus);
            }
            $otherCount = $otherPanitias->countAllResults();

            $myBus->terisi = $santriCount + count($attendantsBus) + $otherCount;

            $data['santriBus'] = $this->tiketModel->select('registrasi_tiket.*, santri.nama, santri.foto')
                                                  ->join('santri', 'santri.id = registrasi_tiket.id_santri')
                                                  ->where('id_bus', $myBus->id)
                                                  ->findAll();
        }

        // Jika dia ditugaskan di terminal, ambil statistik terminal tsb
        if ($myTerminal) {
            $data['santriTerminal'] = $this->tiketModel->select('registrasi_tiket.*, santri.nama, santri.foto')
                                                       ->join('santri', 'santri.id = registrasi_tiket.id_santri')
                                                       ->where('id_jadwal', $jadwalAktif->id)
                                                       ->where('terminal_bandara', $myTerminal->terminal_bandara)
                                                       ->findAll();
        }

        // --- INFORMASI UMUM: KOORDINATOR ---
        $listBusCoord = [];
        $listTermCoord = [];
        if ($jadwalAktif) {
            // Bus Coords
            $listBusCoord = $this->busModel->where('id_jadwal', $jadwalAktif->id)
                                            ->select('koordinator_bus, no_kontak, nama_rombongan')
                                            ->findAll();

            // Terminal Coords
            $listTermCoord = $this->penugasanModel->where('penugasan_terminal_panitia.id_jadwal', $jadwalAktif->id)
                                                   ->select('penugasan_terminal_panitia.terminal_bandara, users.fullname, users.username as phone')
                                                   ->join('users', 'users.id = penugasan_terminal_panitia.id_panitia')
                                                   ->findAll();
        }

        $data['listBusCoord'] = $listBusCoord;
        $data['listTermCoord'] = $listTermCoord;

        // --- GLOBAL CROSS-CHECK DATA ---
        $allStudents = [];
        if ($jadwalAktif) {
            $allStudents = $this->tiketModel->select('registrasi_tiket.*, santri.nama, santri.foto, armada_bus.nama_rombongan, users.username as no_hp')
                                            ->join('santri', 'santri.id = registrasi_tiket.id_santri')
                                            ->join('armada_bus', 'armada_bus.id = registrasi_tiket.id_bus', 'left')
                                            ->join('users', 'users.id = santri.id_orang_tua', 'left')
                                            ->where('registrasi_tiket.id_jadwal', $jadwalAktif->id)
                                            ->orderBy('santri.nama', 'ASC')
                                            ->findAll();
        }
        $data['allStudents'] = $allStudents;

        // --- STATISTIK GLOBAL UNTUK DASHBOARD ---
        $statsGlobal = [
            'totalSantri' => 0,
            'totalBus'    => 0,
            'perTerminal' => [],
            'perBus'      => []
        ];

        if ($jadwalAktif) {
            $statsGlobal['totalSantri'] = $this->tiketModel->where('id_jadwal', $jadwalAktif->id)->countAllResults();
            $statsGlobal['totalBus']    = $this->busModel->where('id_jadwal', $jadwalAktif->id)->countAllResults();
            
            // Per Terminal
            $termData = $this->tiketModel->select('terminal_bandara, COUNT(*) as jumlah')
                                         ->where('id_jadwal', $jadwalAktif->id)
                                         ->groupBy('terminal_bandara')
                                         ->findAll();
            foreach ($termData as $td) {
                $statsGlobal['perTerminal'][$td->terminal_bandara] = $td->jumlah;
            }

            // Per Bus
            $busData = $this->tiketModel->select('armada_bus.nama_rombongan, COUNT(*) as jumlah')
                                        ->join('armada_bus', 'armada_bus.id = registrasi_tiket.id_bus')
                                        ->where('registrasi_tiket.id_jadwal', $jadwalAktif->id)
                                        ->groupBy('armada_bus.id')
                                        ->findAll();
            foreach ($busData as $bd) {
                $statsGlobal['perBus'][$bd->nama_rombongan] = $bd->jumlah;
            }
        }
        $data['statsGlobal'] = $statsGlobal;

        return view('backend/panitia/dashboard', $data);
    }
}
