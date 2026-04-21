<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SantriModel;
use App\Models\RegistrasiTiketModel;
use App\Models\JadwalMobilitasModel;
use App\Models\PenugasanTerminalPanitiaModel;

class OrangtuaDashboardController extends BaseController
{
    protected $santriModel;
    protected $tiketModel;
    protected $jadwalModel;

    public function __construct()
    {
        $this->santriModel = new SantriModel();
        $this->tiketModel  = new RegistrasiTiketModel();
        $this->jadwalModel    = new JadwalMobilitasModel();
        $this->penugasanModel = new PenugasanTerminalPanitiaModel();
    }

    public function index()
    {
        if (!logged_in()) {
            return redirect()->to('/login');
        }

        $userId = user_id();

        // Ambil santri milik orangtua yang login
        $santriList = $this->santriModel->getSantriByOrangTua($userId);

        // Ambil semua ID santri
        $santriIds = array_map(function ($s) {
            return $s->id;
        }, $santriList);

        // Ambil tiket registrasi untuk semua santri milik orangtua ini
        $tiketList = [];
        if (!empty($santriIds)) {
            $tiketList = $this->tiketModel
                ->select('registrasi_tiket.*, santri.nama, santri.daerah_asal, jadwal_mobilitas.tanggal_pelaksanaan, jadwal_mobilitas.jenis')
                ->join('santri', 'santri.id = registrasi_tiket.id_santri')
                ->join('jadwal_mobilitas', 'jadwal_mobilitas.id = registrasi_tiket.id_jadwal')
                ->whereIn('registrasi_tiket.id_santri', $santriIds)
                ->orderBy('jadwal_mobilitas.tanggal_pelaksanaan', 'DESC')
                ->findAll();
        }

        // Fetch user mapping for coordinates
        $userModel = new \Myth\Auth\Models\UserModel();
        $panitias = $userModel->select('id, fullname, username')->findAll();
        $panitiaMap = [];
        foreach($panitias as $p) {
            $panitiaMap[$p->id] = [
                'nama' => !empty($p->fullname) ? $p->fullname : $p->username,
                'wa'   => $p->username // username stores phone number
            ];
        }

        $busModel = new \App\Models\ArmadaBusModel();
        
        $jadwalAktif = $this->jadwalModel->where('status', 'aktif')->first();
        if (!$jadwalAktif) {
            $jadwalAktif = $this->jadwalModel->first();
        }

        $groupedTickets = [];
        $activePersonal = [];
        $archivePersonal = [];

        foreach ($tiketList as $tiket) {
            // Partition for Tabs
            if ($tiket->id_jadwal == $jadwalAktif->id) {
                $activePersonal[] = $tiket;
            } else {
                $archivePersonal[] = $tiket;
            }

            // Grouping for Journey Cards (only for active schedule)
            if (!$tiket->id_bus || $tiket->id_jadwal != $jadwalAktif->id) continue;

            $groupKey = $tiket->id_bus . '-' . $tiket->terminal_bandara;
            
            if (!isset($groupedTickets[$groupKey])) {
                $tmpBus = [];
                $tmpTerm = [];
                $busInfo = null;

                $bus = $busModel->find($tiket->id_bus);
                if ($bus) {
                    $busInfo = $bus;
                    
                    // Bus Coordinator (from id_pendamping_bus)
                    $idsBus = array_filter(explode(',', $bus->id_pendamping_bus ?? ''));
                    foreach($idsBus as $pid) {
                        if (isset($panitiaMap[$pid])) {
                            $tmpBus[] = $panitiaMap[$pid];
                        }
                    }

                    // Terminal Coordinator (fetch ALL for this terminal in active schedule)
                    $termAssigns = $this->penugasanModel
                        ->where('id_jadwal', $jadwalAktif->id)
                        ->where('terminal_bandara', $tiket->terminal_bandara)
                        ->findAll();
                    
                    foreach ($termAssigns as $ta) {
                        if (isset($panitiaMap[$ta->id_panitia])) {
                            $tmpTerm[] = $panitiaMap[$ta->id_panitia];
                        }
                    }
                }

                $groupedTickets[$groupKey] = [
                    'bus_info'   => $busInfo,
                    'coord_bus'  => $tmpBus,
                    'coord_term' => $tmpTerm,
                    'terminal'   => $tiket->terminal_bandara,
                    'members'    => []
                ];
            }

            $groupedTickets[$groupKey]['members'][] = [
                'nama'            => $tiket->nama,
                'status_checkin'  => $tiket->status_checkin,
                'status_bus'      => $tiket->status_bus,
                'status_istirahat' => $tiket->status_istirahat
            ];
        }

        // Fetch ALL Active Tickets (Global for Tab 2)
        $allActiveTickets = [];
        if ($jadwalAktif) {
            $allActiveTickets = $this->tiketModel
                ->select('registrasi_tiket.*, santri.nama, santri.daerah_asal, armada_bus.nama_rombongan')
                ->join('santri', 'santri.id = registrasi_tiket.id_santri')
                ->join('armada_bus', 'armada_bus.id = registrasi_tiket.id_bus', 'left')
                ->where('registrasi_tiket.id_jadwal', $jadwalAktif->id)
                ->orderBy('nama_rombongan', 'ASC')
                ->orderBy('santri.nama', 'ASC')
                ->findAll();
        }

        // Hitung statistik
        $totalSantri  = count($santriList);
        $totalTiket   = count($tiketList);
        $totalBerangkat = 0;
        $totalDatang   = 0;
        foreach ($tiketList as $t) {
            if ($t->jenis === 'kepulangan') $totalBerangkat++;
            if ($t->jenis === 'kedatangan') $totalDatang++;
        }


        // Keep general transparency widget data
        $buses = [];
        if ($jadwalAktif) {
            $buses = $busModel->getBusByJadwal($jadwalAktif->id);
            foreach($buses as &$bus) {
                $idsBus = array_filter(explode(',', $bus->id_pendamping_bus ?? ''));
                $namesBus = [];
                foreach($idsBus as $id) if(isset($panitiaMap[$id])) $namesBus[] = $panitiaMap[$id]['nama'];
                $bus->nama_pendamping_bus = implode(', ', $namesBus);

                $termAssigns = $this->penugasanModel->where('id_bus', $bus->id)->findAll();
                $namesTerm = [];
                foreach($termAssigns as $ta) {
                    if(isset($panitiaMap[$ta->id_panitia])) {
                        $namesTerm[] = $panitiaMap[$ta->id_panitia]['nama'] . " (T-{$ta->terminal_bandara})";
                    }
                }
                $bus->nama_pendamping_terminal = implode(', ', $namesTerm);

                $santriCount = $this->tiketModel->where('id_bus', $bus->id)->countAllResults();
                $bus->terisi = $santriCount + count($idsBus) + count($termAssigns);
            }
        }

        $data = [
            'title'            => 'Dashboard Orang Tua',
            'santriList'       => $santriList,
            'tiketList'        => $tiketList,
            'activePersonal'   => $activePersonal,
            'archivePersonal'  => $archivePersonal,
            'allActiveTickets' => $allActiveTickets,
            'totalSantri'      => $totalSantri,
            'totalTiket'       => $totalTiket,
            'totalBerangkat'   => $totalBerangkat,
            'totalDatang'      => $totalDatang,
            'jadwalAktif'      => $jadwalAktif,
            'buses'            => $buses,
            'groupedTickets'   => $groupedTickets,
        ];

        return view('frontend/orangtua/index', $data);
    }
}
