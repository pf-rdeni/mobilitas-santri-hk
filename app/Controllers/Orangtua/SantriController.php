<?php

namespace App\Controllers\Orangtua;

use App\Controllers\BaseController;
use App\Models\SantriModel;
use App\Models\RiwayatSantriModel;

class SantriController extends BaseController
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
        $santri = $this->santriModel->where('id_orang_tua', user_id())->findAll();
        
        // Ambil riwayat akademik terbaru untuk masing-masing santri
        foreach ($santri as $s) {
            $s->riwayat = $this->riwayatModel->where('id_santri', $s->id)
                                             ->orderBy('tahun_ajaran', 'desc')
                                             ->orderBy('id', 'desc')
                                             ->first();
        }

        $data = [
            'title'  => 'Daftar Santri Anda',
            'santri' => $santri
        ];
        return view('frontend/orangtua/santri/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Data Santri'
        ];
        return view('frontend/orangtua/santri/create', $data);
    }

    public function store()
    {
        // Validasi input
        $rules = [
            'nama'           => 'required',
            'provinsi_id'    => 'required',
            'provinsi_nama'  => 'required',
            'kabupaten_id'   => 'required',
            'kabupaten_nama' => 'required',
            'kecamatan_id'   => 'required',
            'kecamatan_nama' => 'required',
            'kelurahan_id'   => 'required',
            'kelurahan_nama' => 'required',
            'jenis_kelamin'  => 'required|in_list[L,P]',
            'tempat_lahir'   => 'required',
            'tanggal_lahir'  => 'required|valid_date[Y-m-d]',
            'nama_bapak'     => 'required',
            'nama_ibu'       => 'required',
            'no_hp_bapak'    => 'required',
            'no_hp_ibu'      => 'required',
            'alamat_rumah'   => 'required',
            'asrama'         => 'required',
            'awal_tahun_ajaran' => 'required',
            'awal_kelas'     => 'required',
            'awal_asrama'    => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Susun daerah_asal untuk legacy/display sederhana
        $daerahAsal = $this->request->getPost('kelurahan_nama') . ', ' . $this->request->getPost('kabupaten_nama');

        // Proses Foto (Base64 dari Cropper)
        $croppedImage = $this->request->getPost('cropped_image');
        $namaFoto = $this->_processBase64Image($croppedImage, FCPATH . 'uploads/santri');

        $data = [
            'id_orang_tua'   => user_id(),
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
        
        if ($this->santriModel->insert($data)) {
            $santriId = $this->santriModel->insertID();
            
            // Simpan Riwayat Akademik Awal
            $this->riwayatModel->insert([
                'id_santri'    => $santriId,
                'tahun_ajaran' => $this->request->getPost('awal_tahun_ajaran'),
                'kelas'        => $this->request->getPost('awal_kelas'),
                'asrama'       => $this->request->getPost('awal_asrama'),
            ]);

            return redirect()->to('/orangtua/santri')->with('success', 'Data santri berhasil ditambahkan.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data anak.');
    }

    public function edit($id)
    {
        $santri = $this->santriModel->find($id);

        if (!$santri || $santri->id_orang_tua != user_id()) {
            return redirect()->to('/orangtua/santri')->with('error', 'Data santri tidak ditemukan atau Anda tidak berhak mengaksesnya.');
        }

        $data = [
            'title'   => 'Edit Data Santri',
            'santri'  => $santri,
            'riwayat' => $this->riwayatModel->getRiwayatBySantri($id)
        ];

        return view('frontend/orangtua/santri/edit', $data);
    }

    public function update($id)
    {
        $santri = $this->santriModel->find($id);
        if (!$santri || $santri->id_orang_tua != user_id()) {
            return redirect()->to('/orangtua/santri')->with('error', 'Akses ditolak.');
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
            'riwayat_akademik' => $this->request->getPost('riwayat_akademik'),
        ];

        if ($this->santriModel->update($id, $data)) {
            return redirect()->to('/orangtua/santri')->with('success', 'Data santri berhasil diubah.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mengubah data.');
    }

    public function delete($id)
    {
        $santri = $this->santriModel->find($id);
        if ($santri && $santri->id_orang_tua == user_id()) {
            $this->santriModel->delete($id);
            return redirect()->to('/orangtua/santri')->with('success', 'Data santri berhasil dihapus.');
        }
        return redirect()->to('/orangtua/santri')->with('error', 'Akses ditolak.');
    }

    // --- RIWAYAT LOGIC ---

    public function storeRiwayat()
    {
        $idSantri = $this->request->getPost('id_santri');
        $santri = $this->santriModel->find($idSantri);

        // Security check
        if (!$santri || $santri->id_orang_tua != user_id()) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $rules = [
            'id_santri'    => 'required',
            'tahun_ajaran' => 'required',
            'kelas'        => 'required',
            'asrama'       => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id_santri'    => $idSantri,
            'tahun_ajaran' => $this->request->getPost('tahun_ajaran'),
            'kelas'        => $this->request->getPost('kelas'),
            'asrama'       => $this->request->getPost('asrama'),
        ];

        if ($this->riwayatModel->insert($data)) {
            return redirect()->back()->with('success', 'Riwayat akademik berhasil ditambahkan.');
        }

        return redirect()->back()->with('error', 'Gagal menyimpan riwayat akademik.');
    }

    public function deleteRiwayat($id)
    {
        $riwayat = $this->riwayatModel->find($id);
        if ($riwayat) {
            // Check ownership via santri
            $santri = $this->santriModel->find($riwayat->id_santri);
            if ($santri && $santri->id_orang_tua == user_id()) {
                $this->riwayatModel->delete($id);
                return redirect()->back()->with('success', 'Riwayat akademik berhasil dihapus.');
            }
        }
        return redirect()->back()->with('error', 'Akses ditolak.');
    }

    /**
     * Helper Function: Memproses gambar base64, menyimpan versi asli (resized) & thumbnail
     */
    private function _processBase64Image($base64Data, $path)
    {
        if (empty($base64Data)) return null;

        // Ensure directory exists
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        // Parse base64
        $imageParts = explode(";base64,", $base64Data);
        if (count($imageParts) != 2) return null;
        
        $imageTypeAux = explode("image/", $imageParts[0]);
        $imageType = $imageTypeAux[1] ?? 'png';
        $imageBase64 = base64_decode($imageParts[1]);

        $fileName = 'santri_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $imageType;
        $fileFullPath = $path . '/' . $fileName;

        // Save original file
        if (file_put_contents($fileFullPath, $imageBase64)) {
            // Buat Thumbnail (contoh: ukuran 150x150)
            $imageSrv = \Config\Services::image();
            $imageSrv->withFile($fileFullPath)
                     ->fit(150, 150, 'center')
                     ->save($path . '/thumb_' . $fileName);
            
            return $fileName;
        }

        return null;
    }
}
