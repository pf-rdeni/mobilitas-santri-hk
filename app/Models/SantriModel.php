<?php

namespace App\Models;

use CodeIgniter\Model;

class SantriModel extends Model
{
    protected $table            = 'santri';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = \App\Entities\Santri::class;
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'nama', 'daerah_asal', 'id_orang_tua',
        'provinsi_id', 'provinsi_nama', 
        'kabupaten_id', 'kabupaten_nama', 
        'kecamatan_id', 'kecamatan_nama', 
        'kelurahan_id', 'kelurahan_nama', 'foto',
        'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 
        'nama_bapak', 'nama_ibu', 'no_hp_bapak', 'no_hp_ibu', 
        'alamat_rumah', 'asrama'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    protected $validationRules = [
        'nama'        => 'required|min_length[3]|max_length[100]',
        'daerah_asal' => 'required|max_length[100]',
    ];

    protected $validationMessages = [
        'nama' => [
            'required'   => 'Nama santri wajib diisi.',
            'min_length' => 'Nama minimal 3 karakter.',
        ],
    ];

    /**
     * Ambil semua santri milik orang tua tertentu (by user id).
     */
    public function getSantriByOrangTua(int $userId): array
    {
        return $this->where('id_orang_tua', $userId)->findAll();
    }

    /**
     * Santri dengan data orang tua (join users).
     */
    public function getSantriWithOrangTua(): array
    {
        return $this->select('santri.*, users.username as no_hp_ortu')
                    ->join('users', 'users.id = santri.id_orang_tua', 'left')
                    ->findAll();
    }
}
