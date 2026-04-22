<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JadwalMobilitasModel;
use App\Models\ArmadaBusModel;
use App\Models\RegistrasiTiketModel;
use App\Models\PenugasanTerminalModel;
use App\Models\PenugasanTerminalPanitiaModel;

class AdminJadwalController extends BaseController
{
    protected $jadwalModel;
    protected $busModel;

    public function __construct()
    {
        $this->jadwalModel = new JadwalMobilitasModel();
        $this->busModel = new ArmadaBusModel();
    }

    public function index()
    {
        $data = [
            'title'      => 'Pengaturan Jadwal Mobilitas',
            'pageTitle'  => 'Manajemen Jadwal & Status Aktif',
            'jadwals'    => $this->jadwalModel->orderBy("status = 'aktif'", 'DESC')->orderBy('tanggal_pelaksanaan', 'DESC')->findAll(),
            'breadcrumb' => [
                ['title' => 'Home', 'url' => 'dashboard'],
                ['title' => 'Pengaturan Jadwal'],
            ],
        ];

        return view('backend/jadwal/index', $data);
    }

    public function create()
    {
        $data = [
            'title'      => 'Tambah Jadwal Mobilitas',
            'pageTitle'  => 'Tambah Data Jadwal',
            'breadcrumb' => [
                ['title' => 'Home', 'url' => 'dashboard'],
                ['title' => 'Pengaturan Jadwal', 'url' => 'admin-jadwal'],
                ['title' => 'Tambah'],
            ],
        ];

        return view('backend/jadwal/form', $data);
    }

    public function store()
    {
        $rules = [
            'jenis'               => 'required|in_list[kedatangan,kepulangan]',
            'tanggal_pelaksanaan' => 'required|valid_date',
            'status'              => 'required|in_list[aktif,selesai]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'jenis'               => $this->request->getPost('jenis'),
            'tanggal_pelaksanaan' => $this->request->getPost('tanggal_pelaksanaan'),
            'status'              => $this->request->getPost('status'),
        ];

        // Jika status yang diinput adalah 'aktif', nonaktifkan jadwal lain
        if ($data['status'] === 'aktif') {
            $this->jadwalModel->where('status', 'aktif')->set(['status' => 'selesai'])->update();
        }

        if ($this->jadwalModel->insert($data)) {
            return redirect()->to('/admin-jadwal')->with('success', 'Jadwal berhasil ditambahkan.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan jadwal.');
    }

    public function edit($id)
    {
        $jadwal = $this->jadwalModel->find($id);
        if (!$jadwal) {
            return redirect()->to('/admin-jadwal')->with('error', 'Data jadwal tidak ditemukan.');
        }

        $data = [
            'title'      => 'Edit Jadwal Mobilitas',
            'pageTitle'  => 'Edit Data Jadwal',
            'jadwal'     => $jadwal,
            'breadcrumb' => [
                ['title' => 'Home', 'url' => 'dashboard'],
                ['title' => 'Pengaturan Jadwal', 'url' => 'admin-jadwal'],
                ['title' => 'Edit'],
            ],
        ];

        return view('backend/jadwal/form', $data);
    }

    public function update($id)
    {
        $jadwal = $this->jadwalModel->find($id);
        if (!$jadwal) {
            return redirect()->to('/admin-jadwal')->with('error', 'Data jadwal tidak ditemukan.');
        }

        $rules = [
            'jenis'               => 'required|in_list[kedatangan,kepulangan]',
            'tanggal_pelaksanaan' => 'required|valid_date',
            'status'              => 'required|in_list[aktif,selesai]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'jenis'               => $this->request->getPost('jenis'),
            'tanggal_pelaksanaan' => $this->request->getPost('tanggal_pelaksanaan'),
            'status'              => $this->request->getPost('status'),
        ];

        // Jika diubah menjadi 'aktif', nonaktifkan jadwal lain
        if ($data['status'] === 'aktif') {
            $this->jadwalModel->where('status', 'aktif')->where('id !=', $id)->set(['status' => 'selesai'])->update();
        }

        if ($this->jadwalModel->update($id, $data)) {
            return redirect()->to('/admin-jadwal')->with('success', 'Jadwal berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui jadwal.');
    }

    public function getDependencySummary($id)
    {
        $jadwal = $this->jadwalModel->find($id);
        if (!$jadwal) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Jadwal tidak ditemukan.']);
        }

        $tiketModel = new RegistrasiTiketModel();
        $penugasanModel = new PenugasanTerminalModel();
        $panitiaModel = new PenugasanTerminalPanitiaModel();

        $summary = [
            'santri'    => $tiketModel->where('id_jadwal', $id)->countAllResults(),
            'bus'       => $this->busModel->where('id_jadwal', $id)->countAllResults(),
            'penugasan' => $penugasanModel->where('id_jadwal', $id)->countAllResults() + 
                           $panitiaModel->where('id_jadwal', $id)->countAllResults(),
            'is_aktif'  => $jadwal->status === 'aktif'
        ];

        return $this->response->setJSON(['status' => 'success', 'data' => $summary]);
    }

    public function delete($id)
    {
        $jadwal = $this->jadwalModel->find($id);
        if (!$jadwal) {
            return redirect()->to('/admin-jadwal')->with('error', 'Data jadwal tidak ditemukan.');
        }

        $tiketModel = new RegistrasiTiketModel();
        $tiketCount = $tiketModel->withDeleted()->where('id_jadwal', $id)->countAllResults();

        // Jadwal aktif hanya boleh dihapus jika masih kosong (belum ada santri/tiket)
        if ($jadwal->status === 'aktif' && $tiketCount > 0) {
            return redirect()->to('/admin-jadwal')->with('error', 'Gagal menghapus. Jadwal AKTIF yang sudah memiliki data santri tidak boleh dihapus demi stabilitas sistem. Silakan set jadwal lain menjadi aktif terlebih dahulu.');
        }

        try {
            // 1. Cleanup File Fisik (Tiket & Transfer) - jika ada (biasanya 0 jika aktif & kosong)
            $allTikets = $tiketModel->withDeleted()->where('id_jadwal', $id)->findAll();
            
            foreach ($allTikets as $t) {
                if ($t->bukti_tiket) {
                    $pathTiket = FCPATH . 'uploads/tiket/' . $t->bukti_tiket;
                    $thumbTiket = FCPATH . 'uploads/tiket/thumb_' . $t->bukti_tiket;
                    if (file_exists($pathTiket)) @unlink($pathTiket);
                    if (file_exists($thumbTiket)) @unlink($thumbTiket);
                }

                if ($t->bukti_transfer) {
                    $pathTransfer = FCPATH . 'uploads/transfer/' . $t->bukti_transfer;
                    $thumbTransfer = FCPATH . 'uploads/transfer/thumb_' . $t->bukti_transfer;
                    if (file_exists($pathTransfer)) @unlink($pathTransfer);
                    if (file_exists($thumbTransfer)) @unlink($thumbTransfer);
                }
            }

            // 2. Hapus dari Database
            if ($this->jadwalModel->delete($id)) {
                return redirect()->to('/admin-jadwal')->with('success', 'Jadwal berhasil dihapus permanen.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin-jadwal')->with('error', 'Gagal menghapus jadwal: ' . $e->getMessage());
        }

        return redirect()->to('/admin-jadwal')->with('error', 'Gagal menghapus jadwal.');
    }

    public function setAktif($id)
    {
        // Nonaktifkan semua yang sedang aktif
        $this->jadwalModel->where('status', 'aktif')->set(['status' => 'selesai'])->update();
        
        // Aktifkan yang dipilih
        if ($this->jadwalModel->skipValidation(true)->update($id, ['status' => 'aktif'])) {
            return redirect()->to('/admin-jadwal')->with('success', 'Jadwal terpilih sekarang menjadi Jadwal Aktif.');
        }

        return redirect()->to('/admin-jadwal')->with('error', 'Gagal menetapkan jadwal aktif.');
    }
}
