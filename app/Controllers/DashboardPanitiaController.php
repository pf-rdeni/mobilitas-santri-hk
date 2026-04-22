<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JadwalMobilitasModel;
use App\Models\RegistrasiTiketModel;
use App\Models\PenugasanTerminalPanitiaModel;
use Myth\Auth\Models\UserModel;

class DashboardPanitiaController extends BaseController
{
    protected $jadwalModel;
    protected $tiketModel;
    protected $userModel;
    protected $riwayatModel;
    protected $configModel;
    protected $grupJamModel;

    public function __construct()
    {
        $this->jadwalModel    = new JadwalMobilitasModel();
        $this->tiketModel     = new RegistrasiTiketModel();
        $this->penugasanModel = new PenugasanTerminalPanitiaModel();
        $this->userModel      = new UserModel();
        $this->riwayatModel   = new \App\Models\RiwayatSantriModel();
        $this->configModel    = new \App\Models\KonfigurasiDashboardModel();
        $this->grupJamModel   = new \App\Models\GrupJamModel();
    }

    public function index()
    {
        if (! logged_in()) {
            return redirect()->to('/login');
        }

        // Redirect Panitia ke dashboard khusus (tanpa sidebar)
        if (in_groups('panitia') && !in_groups(['admin', 'superadmin'])) {
            return redirect()->to('/panitia');
        }

        // Ambil ID jadwal yang dipilih, default ke jadwal terbaru jika tidak ada
        $idJadwal = $this->request->getGet('id_jadwal');
        $jadwalAktif = $this->jadwalModel->getJadwalAktif();
        if (!$idJadwal && !empty($jadwalAktif)) {
            $idJadwal = $jadwalAktif[0]->id;
        }

        // Statistik
        $semuaJadwal = $this->jadwalModel->findAll();
        
        // Total Kedatangan & Keberangkatan Santri untuk JADWAL YANG TERPILIH
        $statBerangkat = 0;
        $statDatang = 0;
        $db = \Config\Database::connect();

        if ($idJadwal) {
            $statBerangkat = $db->table('registrasi_tiket')
                ->where('id_jadwal', $idJadwal)
                ->join('jadwal_mobilitas', 'jadwal_mobilitas.id = registrasi_tiket.id_jadwal')
                ->where('jadwal_mobilitas.jenis', 'kepulangan')
                ->countAllResults();
                
            $statDatang = $db->table('registrasi_tiket')
                ->where('id_jadwal', $idJadwal)
                ->join('jadwal_mobilitas', 'jadwal_mobilitas.id = registrasi_tiket.id_jadwal')
                ->where('jadwal_mobilitas.jenis', 'kedatangan')
                ->countAllResults();
        }
        
        // Statistik Tambahan: Menunggu Aktivasi Wali Santri (Group 4)
        $statPendingAktivasi = $db->table('users')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
            ->where('auth_groups_users.group_id', 4)
            ->where('users.active', 0)
            ->where('users.deleted_at', null)
            ->countAllResults();

        // Rekap untuk jadwal yang terpilih
        $kebutuhanBus = 0;
        $belumDialokasi = 0; // opsional jika ada logic petugas atau bus belum komplit
        $groupedData = ['1' => [], '2' => [], '3' => []];
        $penugasanData = [];
        $petugasList = $this->userModel->findAll(); // sementara ambil semua user
        $tiketList = [];

        if ($idJadwal) {
            $statsJadwal = $this->tiketModel->getStatistikByJadwal($idJadwal);
            $kebutuhanBus = $statsJadwal['kebutuhan_bus'];
            
            // Data Santri Group By Terminal
            $groupedData = $this->tiketModel->getByJadwalGrouped($idJadwal);
            
            // Ambil petugas per terminal di jadwal ini (Multiple Panitias)
            $penugasanData = [
                '1' => $this->penugasanModel->getByTerminal($idJadwal, '1'),
                '2' => $this->penugasanModel->getByTerminal($idJadwal, '2'),
                '3' => $this->penugasanModel->getByTerminal($idJadwal, '3'),
            ];

            // Data untuk DataTables
            $tiketList = $this->tiketModel->select('registrasi_tiket.*, santri.nama, santri.daerah_asal, jadwal_mobilitas.tanggal_pelaksanaan, jadwal_mobilitas.jenis')
                                    ->join('santri', 'santri.id = registrasi_tiket.id_santri')
                                    ->join('jadwal_mobilitas', 'jadwal_mobilitas.id = registrasi_tiket.id_jadwal')
                                    ->where('id_jadwal', $idJadwal)
                                    ->findAll();

            // Ambil riwayat akademik terbaru untuk masing-masing santri dalam list
            foreach ($tiketList as $t) {
                $t->riwayat = $this->riwayatModel->where('id_santri', $t->id_santri)
                                                 ->orderBy('tahun_ajaran', 'desc')
                                                 ->orderBy('id', 'desc')
                                                 ->first();
            }
        }

        // --- Fitur Baru: Grouping Berdasarkan Jam (Dynamic) ---
        $dataGrup = $this->grupJamModel->getAllOrdered();
        $timeGroups = [];
        foreach ($dataGrup as $g) {
            $timeGroups[$g->id] = [
                'nama'     => $g->nama_grup,
                'count'    => 0,
                'terminal' => [1 => 0, 2 => 0, 3 => 0],
                'range'    => date('H:i', strtotime($g->jam_mulai)) . '-' . date('H:i', strtotime($g->jam_selesai))
            ];
        }

        if ($idJadwal && !empty($timeGroups)) {
            foreach ($tiketList as $t) {
                $jam = date('H:i', strtotime($t->waktu_penerbangan));
                $term = $t->terminal_bandara;
                foreach ($timeGroups as $id => &$group) {
                    list($start, $end) = explode('-', $group['range']);
                    if ($jam >= trim($start) && $jam <= trim($end)) {
                        $group['count']++;
                        if (isset($group['terminal'][$term])) {
                            $group['terminal'][$term]++;
                        }
                        break; 
                    }
                }
            }
        }

        $buses = [];
        if ($idJadwal) {
            $busModel = new \App\Models\ArmadaBusModel();
            $buses = $busModel->getBusByJadwal($idJadwal);
            
            // Map names for multiple panitias
            $panitias = $this->userModel->select('id, fullname, username')->findAll();
            $panitiaMap = [];
            foreach($panitias as $p) $panitiaMap[$p->id] = !empty($p->fullname) ? $p->fullname : $p->username;

            foreach($buses as &$bus) {
                $idsBus = array_filter(explode(',', $bus->id_pendamping_bus ?? ''));
                $namesBus = [];
                foreach($idsBus as $id) if(isset($panitiaMap[$id])) $namesBus[] = $panitiaMap[$id];
                $bus->nama_pendamping_bus = implode(', ', $namesBus);

                // Fetch terminal pendampings from the NEW relational table for this bus
                $termAssigns = $this->penugasanModel->where('id_bus', $bus->id)->findAll();
                $namesTerm = [];
                foreach($termAssigns as $ta) {
                    if(isset($panitiaMap[$ta->id_panitia])) {
                        $namesTerm[] = $panitiaMap[$ta->id_panitia] . " (T-{$ta->terminal_bandara})";
                    }
                }
                $bus->nama_pendamping_terminal = implode(', ', $namesTerm);

                // Calculate real terisi count (Santri + Bus Attendants + Terminal Panitias)
                $santriCount = $this->tiketModel->where('id_bus', $bus->id)->countAllResults();
                $attendantsBus = array_filter(explode(',', $bus->id_pendamping_bus ?? ''));
                
                $otherPanitias = $this->penugasanModel->where('id_bus', $bus->id);
                if (!empty($attendantsBus)) {
                    $otherPanitias->whereNotIn('id_panitia', $attendantsBus);
                }
                $otherCount = $otherPanitias->countAllResults();

                $bus->terisi = $santriCount + count($attendantsBus) + $otherCount;
            }
        }

        $data = [
            'title'          => 'Dashboard Panitia Mobilitas',
            'pageTitle'      => 'Dashboard Operasional Panitia',
            'breadcrumb'     => [
                ['title' => 'Home', 'url' => 'dashboard'],
                ['title' => 'Dashboard'],
            ],
            'semuaJadwal'    => $semuaJadwal,
            'idJadwal'       => $idJadwal,
            'statBerangkat'  => $statBerangkat,
            'statDatang'     => $statDatang,
            'kebutuhanBus'   => $kebutuhanBus,
            'belumDialokasi' => $belumDialokasi,
            'groupedData'    => $groupedData,
            'penugasanData'  => $penugasanData,
            'petugasList'    => $petugasList,
            'tiketList'      => $tiketList,
            'buses'          => $buses,
            'timeGroups'     => $timeGroups,
            'grupRaw'             => $dataGrup,
            'statPendingAktivasi' => $statPendingAktivasi,
        ];

        return view('backend/dashboard/index', $data);
    }

    public function updatePenugasan()
    {
        if (! logged_in()) return redirect()->to('/login');

        $idJadwal = $this->request->getPost('id_jadwal');
        $terminal = $this->request->getPost('terminal_bandara');
        $idPetugas = $this->request->getPost('id_petugas');
        
        $this->penugasanModel->saveOrUpdate($idJadwal, $terminal, $idPetugas ? $idPetugas : null);
        
        return redirect()->back()->with('success', 'Petugas Terminal ' . $terminal . ' berhasil diperbarui!');
    }
    public function verifyPayment($id)
    {
        if (! logged_in()) return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        if (! in_groups(['admin', 'superadmin', 'panitia'])) return $this->response->setJSON(['status' => 'error', 'message' => 'Forbidden']);

        $tiket = $this->tiketModel->find($id);
        if (!$tiket) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }

        $status = $this->request->getPost('status'); // 'diverifikasi' or 'belum' (to reject/reset)
        
        if ($this->tiketModel->skipValidation(true)->update($id, ['status_transfer' => $status])) {
            $msg = $status == 'diverifikasi' ? 'Pembayaran berhasil diverifikasi!' : 'Status pembayaran dibatalkan/dikembalikan.';
            return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui status']);
    }

    public function saveGroups()
    {
        if (! logged_in()) return redirect()->to('/login');

        $ids       = $this->request->getPost('id'); // array
        $namas     = $this->request->getPost('nama_grup');
        $mulais    = $this->request->getPost('jam_mulai');
        $selesais  = $this->request->getPost('jam_selesai');

        if ($namas) {
            // Kita gunakan strategi "Truncate/Delete All and Re-insert" agar simple buat "Flexible"
            // Atau "Sync" berdasarkan ID. Kita coba Delete yang tidak ada di IDS.
            $keepIds = array_filter($ids ?: []);
            if (!empty($keepIds)) {
                $this->grupJamModel->whereNotIn('id', $keepIds)->delete();
            } else {
                $this->grupJamModel->where('1=1')->delete();
            }

            foreach ($namas as $index => $nama) {
                $id = $ids[$index] ?? null;
                $data = [
                    'nama_grup'   => $nama,
                    'jam_mulai'   => $mulais[$index],
                    'jam_selesai' => $selesais[$index],
                ];

                if ($id) {
                    $this->grupJamModel->update($id, $data);
                } else {
                    $this->grupJamModel->insert($data);
                }
            }
            return redirect()->back()->with('success', 'Pengaturan grup jam dashboard berhasil diperbarui!');
        }
        return redirect()->back()->with('error', 'Gagal memperbarui pengaturan.');
    }
}
