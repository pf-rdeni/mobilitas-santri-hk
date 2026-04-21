<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ArmadaBusModel;
use App\Models\JadwalMobilitasModel;
use App\Models\RegistrasiTiketModel;
use Myth\Auth\Models\UserModel;

class AdminBusController extends BaseController
{
    protected $busModel;
    protected $jadwalModel;
    protected $tiketModel;
    protected $userModel;
    protected $terminalPanitiaModel;
    protected $terminalModel;

    public function __construct()
    {
        $this->busModel = new ArmadaBusModel();
        $this->jadwalModel = new JadwalMobilitasModel();
        $this->tiketModel = new RegistrasiTiketModel();
        $this->userModel = new UserModel();
        $this->terminalPanitiaModel = new \App\Models\PenugasanTerminalPanitiaModel();
        $this->terminalModel = new \App\Models\BusPendampingTerminalModel();
    }

    private function getPanitia()
    {
        return $this->userModel->select('users.id, users.fullname, users.username')
                               ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
                               ->where('auth_groups_users.group_id', 3) // Group Panitia (ID: 3)
                               ->findAll();
    }

    public function index()
    {
        // Ambil jadwal aktif
        $jadwalAktif = $this->jadwalModel->where('status', 'aktif')->first();
        if (!$jadwalAktif) {
            $jadwalAktif = $this->jadwalModel->first(); // fallback jika tak ada yg aktif
        }

        $buses = [];
        $panitias = $this->getPanitia();
        // Create an associative map of id => fullname for easy lookup
        $panitiaMap = [];
        foreach($panitias as $p) {
            $panitiaMap[$p->id] = !empty($p->fullname) ? $p->fullname : $p->username;
        }

        if ($jadwalAktif) {
            $buses = $this->busModel->getBusByJadwal($jadwalAktif->id);
            
            // --- SMART CALCULATION TERISI ---
            foreach ($buses as &$bus) {
                // 1. Santri
                $santriCount = $this->tiketModel->where('id_bus', $bus->id)->countAllResults();
                
                // 2. Pendamping Bus (Primary attendants)
                $idsBusAttendants = array_filter(explode(',', $bus->id_pendamping_bus ?? ''));
                
                // 3. Other Panitias who sit in this bus (assigned to any terminal but assigned to this bus)
                // Filter out those who are already primary attendants to avoid double counting
                $otherPanitiasInBus = $this->terminalPanitiaModel->where('id_bus', $bus->id);
                if (!empty($idsBusAttendants)) {
                    $otherPanitiasInBus->whereNotIn('id_panitia', $idsBusAttendants);
                }
                $otherPanitiaCount = $otherPanitiasInBus->countAllResults();

                $bus->terisi = $santriCount + count($idsBusAttendants) + $otherPanitiaCount;

                // Nama pendamping bus untuk tampilan
                $namesBus = [];
                foreach($idsBusAttendants as $id) {
                    if(isset($panitiaMap[$id])) $namesBus[] = $panitiaMap[$id];
                }
                $bus->nama_pendamping_bus = implode(', ', $namesBus);
            }

            // --- GROUP DATA PER TERMINAL ---
            $terminalAssignments = [
                '1' => $this->terminalPanitiaModel->getByTerminal($jadwalAktif->id, '1'),
                '2' => $this->terminalPanitiaModel->getByTerminal($jadwalAktif->id, '2'),
                '3' => $this->terminalPanitiaModel->getByTerminal($jadwalAktif->id, '3'),
            ];

            // List of all panitias who are already ALREADY assigned as Pendamping Bus in ANY bus
            $allBusAttendants = [];
            foreach($buses as $b) {
                $ids = array_filter(explode(',', $b->id_pendamping_bus ?? ''));
                foreach($ids as $id) {
                    $allBusAttendants[$id] = $b->id; // Mapping PanitiaID => BusID
                }
            }
        }

        $data = [
            'title'               => 'Armada & Penugasan',
            'pageTitle'           => 'Manajemen Armada & Penugasan',
            'jadwal'              => $jadwalAktif,
            'buses'               => $buses,
            'panitias'            => $panitias,
            'terminalAssignments' => $terminalAssignments ?? [],
            'allBusAttendants'    => $allBusAttendants ?? [],
            'panitiaMap'          => $panitiaMap,
            'breadcrumb' => [
                ['title' => 'Home', 'url' => 'dashboard'],
                ['title' => 'Armada & Penugasan'],
            ],
        ];

        return view('backend/bus/index', $data);
    }

    public function updatePendamping()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $id_bus = $this->request->getPost('id_bus');
        $type = $this->request->getPost('type'); // remains 'bus' only now
        $ids = $this->request->getPost('ids'); // array of IDs

        if (empty($id_bus) || $type !== 'bus') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak valid.']);
        }

        $idsString = !empty($ids) ? implode(',', $ids) : null;

        // Validation for Bus Attendant (exclusive)
        if (!empty($ids)) {
            $currentBus = $this->busModel->find($id_bus);
            $allBusesInJadwal = $this->busModel->where('id_jadwal', $currentBus->id_jadwal)->findAll();
            
            foreach ($allBusesInJadwal as $b) {
                if ($b->id == $id_bus) continue;
                $takenBus = array_filter(explode(',', $b->id_pendamping_bus ?? ''));
                foreach ($ids as $submittedId) {
                    if (in_array($submittedId, $takenBus)) {
                        $pUser = $this->userModel->find($submittedId);
                        $pName = ($pUser->fullname ?? $pUser->username);
                        return $this->response->setJSON([
                            'status' => 'error', 
                            'message' => "Panitia '$pName' sudah ditugaskan sebagai Pendamping Bus di {$b->nama_rombongan}."
                        ]);
                    }
                }
            }
        }

        if ($this->busModel->update($id_bus, ['id_pendamping_bus' => $idsString])) {
            // Update terminal assignments for these panitias: if they are now bus attendants, their terminal-bus seat should sync
            if (!empty($ids)) {
                $this->terminalPanitiaModel->whereIn('id_panitia', $ids)->set(['id_bus' => $id_bus])->update();
            }
            return $this->response->setJSON(['status' => 'success', 'message' => 'Penugasan Bus berhasil diperbarui!']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui penugasan.']);
    }

    public function assignTerminalPanitia()
    {
        if (! $this->request->isAJAX()) return $this->response->setStatusCode(403);

        $id_jadwal = $this->request->getPost('id_jadwal');
        $terminal  = $this->request->getPost('terminal');
        $id_panitia = $this->request->getPost('id_panitia');
        $id_bus    = $this->request->getPost('id_bus');

        // Check if already assigned to this terminal
        $exists = $this->terminalPanitiaModel->where('id_jadwal', $id_jadwal)
                                             ->where('terminal_bandara', $terminal)
                                             ->where('id_panitia', $id_panitia)
                                             ->first();
        if ($exists) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Panitia ini sudah terdaftar di terminal ini.']);
        }

        // Capacity Check if they need a new seat (if id_bus is provided and they aren't already a primary bus attendant)
        if ($id_bus) {
            $usage = $this->getBusUsage($id_bus);
            $bus = $this->busModel->find($id_bus);
            if ($usage >= $bus->kapasitas) {
                return $this->response->setJSON(['status' => 'error', 'message' => "Gagal. Bus {$bus->nama_rombongan} sudah penuh ({$usage}/{$bus->kapasitas})."]);
            }
        }

        $data = [
            'id_jadwal'        => $id_jadwal,
            'terminal_bandara' => $terminal,
            'id_panitia'       => $id_panitia,
            'id_bus'           => $id_bus ?: null
        ];

        if ($this->terminalPanitiaModel->insert($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Panitia berhasil ditugaskan ke terminal.']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan penugasan.']);
    }

    public function removeTerminalPanitia()
    {
        if (! $this->request->isAJAX()) return $this->response->setStatusCode(403);
        $id = $this->request->getPost('id');
        if ($this->terminalPanitiaModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Penugasan berhasil dihapus.']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus penugasan.']);
    }

    private function getBusUsage($idBus)
    {
        $santriCount = $this->tiketModel->where('id_bus', $idBus)->countAllResults();
        $bus = $this->busModel->find($idBus);
        $attendantsBus = array_filter(explode(',', $bus->id_pendamping_bus ?? ''));
        
        $otherPanitias = $this->terminalPanitiaModel->where('id_bus', $idBus);
        if (!empty($attendantsBus)) {
            $otherPanitias->whereNotIn('id_panitia', $attendantsBus);
        }
        $otherCount = $otherPanitias->countAllResults();

        return $santriCount + count($attendantsBus) + $otherCount;
    }

    public function create()
    {
        $jadwalAktif = $this->jadwalModel->where('status', 'aktif')->first();
        
        if (!$jadwalAktif) {
            return redirect()->to('/admin-jadwal')->with('error', 'Tidak ada Jadwal Mobilitas yang sedang AKTIF. Silakan tentukan atau aktifkan jadwal terlebih dahulu sebelum menambah data bus.');
        }

        $data = [
            'title'      => 'Tambah Armada & Penugasan',
            'pageTitle'  => 'Tambah Data Armada & Penugasan',
            'jadwal'     => $jadwalAktif,
            'panitias'   => $this->getPanitia(),
            'breadcrumb' => [
                ['title' => 'Home', 'url' => 'dashboard'],
                ['title' => 'Armada & Penugasan', 'url' => 'admin-bus'],
                ['title' => 'Tambah'],
            ],
        ];

        return view('backend/bus/form', $data);
    }

    public function store()
    {
        $rules = [
            'nama_rombongan'      => 'required|max_length[100]',
            'no_polisi'           => 'required|max_length[20]',
            'perusahaan_bus'      => 'required|max_length[100]',
            'koordinator_bus'     => 'required|max_length[100]',
            'no_kontak'           => 'required|max_length[20]',
            'kapasitas'           => 'required|integer|greater_than[0]',
            'tanggal_digunakan'   => 'required|valid_date',
            'waktu_keberangkatan' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Pastikan tanggal mengikuti jadwal jika ini create
        $jadwal = $this->jadwalModel->find($this->request->getPost('id_jadwal'));
        $tanggalFix = $jadwal->tanggal_pelaksanaan;

        $data = [
            'id_jadwal'              => $this->request->getPost('id_jadwal'),
            'nama_rombongan'         => $this->request->getPost('nama_rombongan'),
            'no_polisi'              => $this->request->getPost('no_polisi'),
            'perusahaan_bus'         => $this->request->getPost('perusahaan_bus'),
            'koordinator_bus'        => $this->request->getPost('koordinator_bus'),
            'no_kontak'              => $this->request->getPost('no_kontak'),
            'kapasitas'              => $this->request->getPost('kapasitas'),
            'tanggal_digunakan'      => $tanggalFix, // Di-lock sesuai jadwal
            'waktu_keberangkatan'    => $this->request->getPost('waktu_keberangkatan'),
            'pemesan_bus'            => user()->fullname ?? user()->username,
        ];

        if ($this->busModel->insert($data)) {
            return redirect()->to('/admin-bus')->with('success', 'Data Bus berhasil ditambahkan.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data bus.');
    }

    public function edit($id)
    {
        $bus = $this->busModel->find($id);
        if (!$bus) {
            return redirect()->to('/admin-bus')->with('error', 'Data bus tidak ditemukan.');
        }

        $data = [
            'title'      => 'Edit Armada & Penugasan',
            'pageTitle'  => 'Edit Data Armada & Penugasan',
            'bus'        => $bus,
            'jadwal'     => $this->jadwalModel->find($bus->id_jadwal) ?: $this->jadwalModel->first(),
            'panitias'   => $this->getPanitia(),
            'breadcrumb' => [
                ['title' => 'Home', 'url' => 'dashboard'],
                ['title' => 'Armada & Penugasan', 'url' => 'admin-bus'],
                ['title' => 'Edit'],
            ],
        ];

        return view('backend/bus/form', $data);
    }

    public function update($id)
    {
        $bus = $this->busModel->find($id);
        if (!$bus) {
            return redirect()->to('/admin-bus')->with('error', 'Data bus tidak ditemukan.');
        }

        $rules = [
            'nama_rombongan'      => 'required|max_length[100]',
            'no_polisi'           => 'required|max_length[20]',
            'perusahaan_bus'      => 'required|max_length[100]',
            'koordinator_bus'     => 'required|max_length[100]',
            'no_kontak'           => 'required|max_length[20]',
            'kapasitas'           => 'required|integer|greater_than[0]',
            'tanggal_digunakan'   => 'required|valid_date',
            'waktu_keberangkatan' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id_jadwal'              => $this->request->getPost('id_jadwal'),
            'nama_rombongan'         => $this->request->getPost('nama_rombongan'),
            'no_polisi'              => $this->request->getPost('no_polisi'),
            'perusahaan_bus'         => $this->request->getPost('perusahaan_bus'),
            'koordinator_bus'        => $this->request->getPost('koordinator_bus'),
            'no_kontak'              => $this->request->getPost('no_kontak'),
            'kapasitas'              => $this->request->getPost('kapasitas'),
            'tanggal_digunakan'      => $this->request->getPost('tanggal_digunakan'),
            'waktu_keberangkatan'    => $this->request->getPost('waktu_keberangkatan'),
        ];

        if ($this->busModel->update($id, $data)) {
            return redirect()->to('/admin-bus')->with('success', 'Data Bus berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data bus.');
    }

    public function delete($id)
    {
        // Cek apakah ada santri yang sudah di-assign ke bus ini
        $santriCount = $this->tiketModel->where('id_bus', $id)->countAllResults();
        if ($santriCount > 0) {
            return redirect()->to('/admin-bus')->with('error', "Gagal menghapus. Bus ini masih memuat $santriCount santri yang sudah di-plotting. Un-assign santri terlebih dahulu.");
        }

        if ($this->busModel->delete($id)) {
            return redirect()->to('/admin-bus')->with('success', 'Data Bus berhasil dihapus.');
        }

        return redirect()->to('/admin-bus')->with('error', 'Gagal menghapus data bus.');
    }
}
