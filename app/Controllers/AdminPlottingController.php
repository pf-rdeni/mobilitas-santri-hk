<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ArmadaBusModel;
use App\Models\JadwalMobilitasModel;
use App\Models\RegistrasiTiketModel;

class AdminPlottingController extends BaseController
{
    protected $busModel;
    protected $jadwalModel;
    protected $tiketModel;

    public function __construct()
    {
        $this->busModel = new ArmadaBusModel();
        $this->jadwalModel = new JadwalMobilitasModel();
        $this->tiketModel = new RegistrasiTiketModel();
    }

    public function index()
    {
        $jadwalAktif = $this->jadwalModel->where('status', 'aktif')->first();
        if (!$jadwalAktif) {
            $jadwalAktif = $this->jadwalModel->first();
        }

        $buses = [];
        $unassigned = [];
        $assigned = [];

        if ($jadwalAktif) {
            // Ambil daftar Armada Bus
            $buses = $this->busModel->where('id_jadwal', $jadwalAktif->id)
                                    ->orderBy('waktu_keberangkatan', 'ASC')
                                    ->findAll();

            // Hitung smart capacity tiap bus
            foreach ($buses as &$bus) {
                $santriCount = $this->tiketModel->where('id_bus', $bus->id)->countAllResults();
                
                // Parse multiple IDs for smart capacity
                $idsBus = array_filter(explode(',', $bus->id_pendamping_bus ?? ''));
                $idsTerm = array_filter(explode(',', $bus->id_pendamping_terminal ?? ''));
                $allUniquePanitiaInvolved = array_unique(array_merge($idsBus, $idsTerm));
                $pendampingCount = count($allUniquePanitiaInvolved);
                
                $bus->terisi = $santriCount + $pendampingCount;
                $bus->sisa_kursi = $bus->kapasitas - $bus->terisi;
                
                // Ambil santri yang sudah ada di bus ini
                $assigned[$bus->id] = $this->tiketModel->select('registrasi_tiket.*, santri.nama, santri.foto')
                                       ->join('santri', 'santri.id = registrasi_tiket.id_santri')
                                       ->where('id_bus', $bus->id)
                                       ->where('id_jadwal', $jadwalAktif->id)
                                       ->orderBy('waktu_penerbangan', 'ASC')
                                       ->findAll();
            }

            // Ambil santri yang BELUM di assign (id_bus IS NULL) dan ikut_bus = 'ya'
            $unassigned = $this->tiketModel->select('registrasi_tiket.*, santri.nama, santri.foto')
                                           ->join('santri', 'santri.id = registrasi_tiket.id_santri')
                                           ->where('id_bus', null)
                                           ->where('ikut_bus', 'ya')
                                           ->where('id_jadwal', $jadwalAktif->id)
                                           ->orderBy('waktu_penerbangan', 'ASC')
                                           ->findAll();
        }

        $data = [
            'title'      => 'Plotting Rombongan Bus',
            'pageTitle'  => 'Pemetaan Keberangkatan Santri',
            'jadwal'     => $jadwalAktif,
            'buses'      => $buses,
            'unassigned' => $unassigned,
            'assigned'   => $assigned,
            'breadcrumb' => [
                ['title' => 'Home', 'url' => 'dashboard'],
                ['title' => 'Manajemen Bus', 'url' => 'admin-bus'],
                ['title' => 'Plotting Rombongan'],
            ],
        ];

        return view('backend/plotting/index', $data);
    }

    // API ENDPOINTS FOR AJAX
    public function assign()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $id_bus = $this->request->getPost('id_bus');
        $id_tikets = $this->request->getPost('id_tikets'); // array of tiket id

        if (empty($id_bus) || empty($id_tikets)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap.']);
        }

        // Cek kapasitas bus
        $bus = $this->busModel->find($id_bus);
        if (!$bus) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Armada Bus tidak ditemukan.']);
        }

        // Hitung yang sudah terisi
        $santriCount = $this->tiketModel->where('id_bus', $bus->id)->countAllResults();
        
        $idsBus = array_filter(explode(',', $bus->id_pendamping_bus ?? ''));
        $idsTerm = array_filter(explode(',', $bus->id_pendamping_terminal ?? ''));
        $allUniquePanitiaInvolved = array_unique(array_merge($idsBus, $idsTerm));
        $pendampingCount = count($allUniquePanitiaInvolved);

        $terisi_sekarang = $santriCount + $pendampingCount;
        $sisa_kursi = $bus->kapasitas - $terisi_sekarang;

        if (count($id_tikets) > $sisa_kursi) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Kapasitas Bus tidak mencukupi untuk jumlah santri yang dipilih! (Sisa: '.$sisa_kursi.' kursi)']);
        }

        // Lakukan update massal
        foreach ($id_tikets as $id_tiket) {
            $this->tiketModel->update($id_tiket, ['id_bus' => $id_bus]);
        }

        return $this->response->setJSON([
            'status' => 'success', 
            'message' => count($id_tikets) . ' Santri berhasil ditambahkan ke ' . $bus->nama_rombongan
        ]);
    }

    public function unassign()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $id_tikets = $this->request->getPost('id_tikets');

        if (empty($id_tikets)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada santri yang dipilih.']);
        }

        foreach ($id_tikets as $id_tiket) {
            $this->tiketModel->update($id_tiket, ['id_bus' => null]);
        }

        return $this->response->setJSON([
            'status' => 'success', 
            'message' => count($id_tikets) . ' Santri berhasil dikeluarkan dari rombongan.'
        ]);
    }
}
