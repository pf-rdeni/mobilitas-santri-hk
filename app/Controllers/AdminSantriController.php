<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SantriModel;
use App\Models\RiwayatSantriModel;

class AdminSantriController extends BaseController
{
    protected $santriModel;
    protected $riwayatModel;

    public function __construct()
    {
        $this->santriModel = new SantriModel();
        $this->riwayatModel = new RiwayatSantriModel();
    }

    public function index()
    {
        if (! logged_in()) {
            return redirect()->to('/login');
        }

        $santri = $this->santriModel->select('santri.*, users.username as phone_ortu, users.email as email_ortu')
                                   ->join('users', 'users.id = santri.id_orang_tua', 'left')
                                   ->findAll();
        
        // Ambil riwayat akademik terbaru untuk masing-masing santri
        foreach ($santri as $s) {
            $s->riwayat = $this->riwayatModel->where('id_santri', $s->id)
                                             ->orderBy('tahun_ajaran', 'desc')
                                             ->orderBy('id', 'desc')
                                             ->first();
        }

        $data = [
            'title'      => 'Daftar Seluruh Santri',
            'pageTitle'  => 'Manajemen Data Santri',
            'santri'     => $santri,
            'breadcrumb' => [
                ['title' => 'Home', 'url' => 'dashboard'],
                ['title' => 'Daftar Santri'],
            ],
        ];

        return view('backend/santri/index', $data);
    }

    public function edit($id)
    {
        $santri = $this->santriModel->find($id);
        if (!$santri) {
            return redirect()->to('/santri')->with('error', 'Data santri tidak ditemukan.');
        }

        $data = [
            'title'      => 'Edit Data Santri (Admin)',
            'pageTitle'  => 'Edit Profil Santri',
            'santri'     => $santri,
            'riwayat'    => $this->riwayatModel->getRiwayatBySantri($id),
            'breadcrumb' => [
                ['title' => 'Home', 'url' => 'dashboard'],
                ['title' => 'Daftar Santri', 'url' => 'santri'],
                ['title' => 'Edit Santri'],
            ],
        ];

        return view('backend/santri/edit', $data);
    }

    public function update($id)
    {
        $santri = $this->santriModel->find($id);
        if (!$santri) {
            return redirect()->to('/santri')->with('error', 'Data tidak ditemukan.');
        }

        $rules = [
            'nama'           => 'required',
            'provinsi_id'    => 'required',
            'kabupaten_id'   => 'required',
            'kecamatan_id'   => 'required',
            'kelurahan_id'   => 'required',
            'jenis_kelamin'  => 'required|in_list[L,P]',
            'tempat_lahir'   => 'required',
            'tanggal_lahir'  => 'required|valid_date[Y-m-d]',
            'nama_bapak'     => 'required',
            'nama_ibu'       => 'required',
            'no_hp_bapak'    => 'required',
            'no_hp_ibu'      => 'required',
            'alamat_rumah'   => 'required',
            'asrama'         => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $daerahAsal = $this->request->getPost('kelurahan_nama') . ', ' . $this->request->getPost('kabupaten_nama');

        // Proses Foto (Base64 dari Cropper)
        $croppedImage = $this->request->getPost('cropped_image');
        $namaFoto = $santri->foto;
        
        if (!empty($croppedImage)) {
            $namaBaru = $this->_processBase64Image($croppedImage, FCPATH . 'uploads/santri');
            if ($namaBaru) {
                // Hapus foto lama
                if ($santri->foto && file_exists(FCPATH . 'uploads/santri/' . $santri->foto)) {
                    @unlink(FCPATH . 'uploads/santri/' . $santri->foto);
                    if (file_exists(FCPATH . 'uploads/santri/thumb_' . $santri->foto)) {
                        @unlink(FCPATH . 'uploads/santri/thumb_' . $santri->foto);
                    }
                }
                $namaFoto = $namaBaru;
            }
        }

        $data = [
            'nama'           => $this->request->getPost('nama'),
            'foto'           => $namaFoto,
            'daerah_asal'    => $daerahAsal,
            'provinsi_id'    => $this->request->getPost('provinsi_id'),
            'provinsi_nama'  => $this->request->getPost('provinsi_nama'),
            'kabupaten_id'   => $this->request->getPost('kabupaten_id'),
            'kabupaten_nama' => $this->request->getPost('kabupaten_nama'),
            'kecamatan_id'   => $this->request->getPost('kecamatan_id'),
            'kecamatan_nama' => $this->request->getPost('kecamatan_nama'),
            'kelurahan_id'   => $this->request->getPost('kelurahan_id'),
            'kelurahan_nama' => $this->request->getPost('kelurahan_nama'),
            'jenis_kelamin'  => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir'   => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'  => $this->request->getPost('tanggal_lahir'),
            'nama_bapak'     => $this->request->getPost('nama_bapak'),
            'nama_ibu'       => $this->request->getPost('nama_ibu'),
            'no_hp_bapak'    => $this->request->getPost('no_hp_bapak'),
            'no_hp_ibu'      => $this->request->getPost('no_hp_ibu'),
            'alamat_rumah'   => $this->request->getPost('alamat_rumah'),
            'asrama'         => $this->request->getPost('asrama'),
        ];

        if ($this->santriModel->update($id, $data)) {
            return redirect()->to('/santri')->with('success', 'Data santri #' . $id . ' berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data.');
    }

    public function delete($id)
    {
        $santri = $this->santriModel->find($id);
        if ($santri) {
            // Hapus foto jika ada
            if ($santri->foto) {
                @unlink(FCPATH . 'uploads/santri/' . $santri->foto);
                @unlink(FCPATH . 'uploads/santri/thumb_' . $santri->foto);
            }
            $this->santriModel->delete($id);
            return redirect()->to('/santri')->with('success', 'Data santri #' . $id . ' berhasil dihapus.');
        }
        return redirect()->to('/santri')->with('error', 'Gagal menghapus data.');
    }

    /**
     * Helper Function: Memproses gambar base64, menyimpan versi asli (resized) & thumbnail
     */
    private function _processBase64Image($base64Data, $path)
    {
        if (empty($base64Data)) return null;

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $imageParts = explode(";base64,", $base64Data);
        if (count($imageParts) != 2) return null;
        
        $imageTypeAux = explode("image/", $imageParts[0]);
        $imageType = $imageTypeAux[1] ?? 'png';
        $imageBase64 = base64_decode($imageParts[1]);

        $fileName = 'santri_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $imageType;
        $fileFullPath = $path . '/' . $fileName;

        if (file_put_contents($fileFullPath, $imageBase64)) {
            $imageSrv = \Config\Services::image();
            $imageSrv->withFile($fileFullPath)
                     ->fit(150, 150, 'center')
                     ->save($path . '/thumb_' . $fileName);
            
            return $fileName;
        }

        return null;
    }
}
