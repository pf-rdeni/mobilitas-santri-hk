<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SantriModel;
use App\Models\JadwalMobilitasModel;
use App\Models\RegistrasiTiketModel;

class RegistrasiTiketController extends BaseController
{
    protected $santriModel;
    protected $jadwalModel;
    protected $tiketModel;

    public function __construct()
    {
        $this->santriModel = new SantriModel();
        $this->jadwalModel = new JadwalMobilitasModel();
        $this->tiketModel  = new RegistrasiTiketModel();
    }

    public function create()
    {
        // Pastikan user sudah login
        if (! logged_in()) {
            return redirect()->to('/login');
        }

        $jadwalAktif = $this->jadwalModel->getJadwalAktif();

        if (empty($jadwalAktif)) {
            return redirect()->to('/orangtua')->with('error', 'Maaf, saat ini tidak ada jadwal registrasi yang diaktifkan oleh panitia.');
        }

        $userId = user_id();

        $data = [
            'title'        => 'Form Registrasi Tiket Santri',
            'santri'       => $this->santriModel->getSantriByOrangTua($userId),
            'jadwalAktif'  => $jadwalAktif[0], // Ambil jadwal pertama yang aktif
        ];

        return view('frontend/registrasi_tiket/create', $data);
    }

    public function store()
    {
        if (! logged_in()) {
            return redirect()->to('/login');
        }

        // Validasi input
        $rules = [
            'id_santri'         => 'required|integer',
            'id_jadwal'         => 'required|integer',
            'jenis_perjalanan'  => 'required|in_list[kepulangan,kedatangan]',
            'maskapai'          => 'required',
            'kode_booking'      => 'required|alpha_numeric|exact_length[6]',
            'bandara_asal'      => 'required',
            'bandara_tujuan'    => 'required',
            'terminal_bandara'  => 'required',
            'tanggal_penerbangan' => 'required|valid_date[Y-m-d]',
            'waktu_penerbangan' => 'required', // Validasi format jam dilakukan manual atau via regex
            'status_transfer'   => 'required|in_list[belum,sudah,diverifikasi]',
            'bukti_tiket'       => 'max_size[bukti_tiket,2048]|ext_in[bukti_tiket,png,jpg,jpeg,pdf]',
            'bukti_transfer'    => 'max_size[bukti_transfer,2048]|ext_in[bukti_transfer,png,jpg,jpeg,pdf]',
        ];

        if ($this->request->getPost('status_transfer') == 'sudah') {
            $rules['bukti_transfer'] = 'uploaded[bukti_transfer]|' . $rules['bukti_transfer'];
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil Tanggal dari Jadwal
        $jadwal = $this->jadwalModel->find($this->request->getPost('id_jadwal'));
        if (!$jadwal) return redirect()->back()->with('error', 'Jadwal tidak valid.');

        $timeInput = $this->request->getPost('waktu_penerbangan');
        $dateInput = $this->request->getPost('tanggal_penerbangan');

        // Validasi Range Tanggal
        $tglH0 = $jadwal->tanggal_pelaksanaan;
        if ($jadwal->jenis == 'kepulangan') {
            $tglH1 = date('Y-m-d', strtotime($tglH0 . ' +1 day'));
            if ($dateInput !== $tglH0 && $dateInput !== $tglH1) {
                return redirect()->back()->withInput()->with('error', 'Tanggal penerbangan tidak sesuai dengan jadwal kepulangan (Harus H+0 atau H+1).');
            }
        } else {
            if ($dateInput !== $tglH0) {
                return redirect()->back()->withInput()->with('error', 'Tanggal penerbangan tidak sesuai dengan jadwal kedatangan (Harus Hari H).');
            }
        }

        $fullDateTime = $dateInput . ' ' . $timeInput . ':00';

        $ikutBus = 'ya';
        $statusTransfer = $this->request->getPost('status_transfer');

        // Proses File Upload
        $fileTiket = $this->request->getFile('bukti_tiket');
        $namaTiket = $this->_uploadAndResize($fileTiket, FCPATH . 'uploads/tiket');

        $fileTransfer = $this->request->getFile('bukti_transfer');
        $namaTransfer = $this->_uploadAndResize($fileTransfer, FCPATH . 'uploads/transfer');

        // Proses simpan
        $dataSimpan = [
            'id_santri'         => $this->request->getPost('id_santri'),
            'id_jadwal'         => $this->request->getPost('id_jadwal'),
            'maskapai'          => $this->request->getPost('maskapai'),
            'kode_booking'      => strtoupper($this->request->getPost('kode_booking')),
            'bandara_asal'      => $this->request->getPost('bandara_asal'),
            'bandara_tujuan'    => $this->request->getPost('bandara_tujuan'),
            'terminal_bandara'  => $this->request->getPost('terminal_bandara'),
            'waktu_penerbangan' => $fullDateTime,
            'ikut_bus'          => $ikutBus,
            'status_transfer'   => $statusTransfer,
            'bukti_tiket'       => $namaTiket,
            'bukti_transfer'    => $namaTransfer,
        ];

        if ($this->tiketModel->insert($dataSimpan)) {
            return redirect()->to('/registrasi-tiket')->with('success', 'Data registrasi tiket berhasil disimpan!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data.');
        }
    }

    public function edit($id)
    {
        if (! logged_in()) return redirect()->to('/login');

        $tiket = $this->tiketModel->find($id);
        if (!$tiket) {
            return redirect()->to('/orangtua')->with('error', 'Tiket tidak ditemukan.');
        }

        // Pastikan tiket ini milik santri dari parent yang login
        $santriIds = array_column($this->santriModel->getSantriByOrangTua(user_id()), 'id');
        if (!in_array($tiket->id_santri, $santriIds)) {
            return redirect()->to('/orangtua')->with('error', 'Akses ditolak.');
        }

        $jadwalFilter = $this->jadwalModel->find($tiket->id_jadwal);
        if ($jadwalFilter) {
            $tiket->jenis_perjalanan = $jadwalFilter->jenis;
        }

        $data = [
            'title'        => 'Edit Registrasi Tiket Santri',
            'santri'       => $this->santriModel->whereIn('id', $santriIds)->findAll(), // Hanya tampilkan anak sendiri
            'jadwalTiket'  => $jadwalFilter, // Jadwal spesifik milik tiket ini
            'tiket'        => $tiket
        ];

        return view('frontend/registrasi_tiket/edit', $data);
    }

    public function update($id)
    {
        if (! logged_in()) return redirect()->to('/login');

        $tiket = $this->tiketModel->find($id);
        if (!$tiket) return redirect()->to('/orangtua')->with('error', 'Tiket tidak ditemukan.');

        $santriIds = array_column($this->santriModel->getSantriByOrangTua(user_id()), 'id');
        if (!in_array($tiket->id_santri, $santriIds)) {
            return redirect()->to('/orangtua')->with('error', 'Akses ditolak.');
        }

        $rules = [
            'id_santri'         => 'required|integer',
            'id_jadwal'         => 'required|integer',
            'maskapai'          => 'required',
            'kode_booking'      => 'required',
            'terminal_bandara'  => 'required|in_list[1,2,3]',
            'tanggal_penerbangan' => 'required|valid_date[Y-m-d]',
            'waktu_penerbangan' => 'required',
            'status_transfer'   => 'required|in_list[belum,sudah]',
            'bukti_tiket'       => 'max_size[bukti_tiket,2048]|ext_in[bukti_tiket,png,jpg,jpeg,pdf]',
            'bukti_transfer'    => 'max_size[bukti_transfer,2048]|ext_in[bukti_transfer,png,jpg,jpeg,pdf]',
        ];

        if ($this->request->getPost('status_transfer') == 'sudah' && empty($tiket->bukti_transfer)) {
            $rules['bukti_transfer'] = 'uploaded[bukti_transfer]|' . $rules['bukti_transfer'];
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil Tanggal dari Jadwal
        $jadwal = $this->jadwalModel->find($this->request->getPost('id_jadwal'));
        $timeInput = $this->request->getPost('waktu_penerbangan');
        $dateInput = $this->request->getPost('tanggal_penerbangan');

        // Validasi Range Tanggal
        $tglH0 = $jadwal->tanggal_pelaksanaan;
        if ($jadwal->jenis == 'kepulangan') {
            $tglH1 = date('Y-m-d', strtotime($tglH0 . ' +1 day'));
            if ($dateInput !== $tglH0 && $dateInput !== $tglH1) {
                return redirect()->back()->withInput()->with('error', 'Tanggal penerbangan tidak sesuai dengan jadwal kepulangan (Harus H+0 atau H+1).');
            }
        } else {
            if ($dateInput !== $tglH0) {
                return redirect()->back()->withInput()->with('error', 'Tanggal penerbangan tidak sesuai dengan jadwal kedatangan (Harus Hari H).');
            }
        }

        $fullDateTime = $dateInput . ' ' . $timeInput . (strlen($timeInput) == 5 ? ':00' : '');

        $ikutBus = 'ya';
        $statusTransfer = $this->request->getPost('status_transfer');

        // Proses File Upload (Gantikan jika ada file baru)
        $fileTiket = $this->request->getFile('bukti_tiket');
        $namaTiket = $this->_uploadAndResize($fileTiket, FCPATH . 'uploads/tiket', $tiket->bukti_tiket);

        if ($statusTransfer == 'sudah' || $statusTransfer == 'diverifikasi') {
            $fileTransfer = $this->request->getFile('bukti_transfer');
            $namaTransfer = $this->_uploadAndResize($fileTransfer, FCPATH . 'uploads/transfer', $tiket->bukti_transfer);
        } else {
            if ($tiket->bukti_transfer && file_exists(FCPATH . 'uploads/transfer/' . $tiket->bukti_transfer)) {
                @unlink(FCPATH . 'uploads/transfer/' . $tiket->bukti_transfer);
                if (file_exists(FCPATH . 'uploads/transfer/thumb_' . $tiket->bukti_transfer)) {
                    @unlink(FCPATH . 'uploads/transfer/thumb_' . $tiket->bukti_transfer);
                }
            }
            $namaTransfer = null;
        }

        $dataUpdate = [
            'id_santri'         => $this->request->getPost('id_santri'),
            'id_jadwal'         => $this->request->getPost('id_jadwal'),
            'maskapai'          => $this->request->getPost('maskapai'),
            'kode_booking'      => strtoupper($this->request->getPost('kode_booking')),
            'bandara_asal'      => $this->request->getPost('bandara_asal'),
            'bandara_tujuan'    => $this->request->getPost('bandara_tujuan'),
            'terminal_bandara'  => $this->request->getPost('terminal_bandara'),
            'waktu_penerbangan' => $fullDateTime,
            'ikut_bus'          => $ikutBus,
            'status_transfer'   => $statusTransfer,
            'bukti_tiket'       => $namaTiket,
            'bukti_transfer'    => $namaTransfer,
        ];

        if ($this->tiketModel->update($id, $dataUpdate)) {
            return redirect()->to('/orangtua')->with('success', 'Data registrasi tiket berhasil diubah!');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mengubah data tiket.');
    }

    public function delete($id)
    {
        if (! logged_in()) return redirect()->to('/login');

        $tiket = $this->tiketModel->find($id);
        if ($tiket) {
            $santriIds = array_column($this->santriModel->getSantriByOrangTua(user_id()), 'id');
            if (in_array($tiket->id_santri, $santriIds)) {
                $this->tiketModel->delete($id);
                return redirect()->to('/orangtua')->with('success', 'Tiket penerbangan berhasil dihapus.');
            }
        }
        return redirect()->to('/orangtua')->with('error', 'Akses ditolak atau tiket tidak ditemukan.');
    }

    private function _uploadAndResize($file, $path, $oldFile = null)
    {
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $newName = $file->getRandomName();
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $file->move($path, $newName);

            // Jika file gambar, buat thumbnail dan resize original
            $mime = mime_content_type($path . '/' . $newName);
            if (strpos($mime, 'image/') === 0) {
                try {
                    $image = \Config\Services::image();
                    
                    // Resize ke max 1000px untuk menghemat space
                    $image->withFile($path . '/' . $newName)
                          ->resize(1000, 1000, true, 'auto')
                          ->save($path . '/' . $newName, 70);

                    // Buat thumbnail 150px
                    $image->withFile($path . '/' . $newName)
                          ->fit(150, 150, 'center')
                          ->save($path . '/thumb_' . $newName, 60);
                } catch (\CodeIgniter\Images\Exceptions\ImageException $e) {
                    // Ignore error jika gagal resize
                }
            }

            // Hapus file lama beserta thumbnailnya jika ada upload baru yang sukses
            if ($oldFile && file_exists($path . '/' . $oldFile)) {
                unlink($path . '/' . $oldFile);
                if (file_exists($path . '/thumb_' . $oldFile)) {
                    unlink($path . '/thumb_' . $oldFile);
                }
            }

            return $newName;
        }

        return $oldFile; // return old file if no new upload
    }
}
