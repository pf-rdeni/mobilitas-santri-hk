<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatSantriModel extends Model
{
    protected $table            = 'riwayat_santri';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = ['id_santri', 'tahun_ajaran', 'kelas', 'asrama'];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    // Validation rules
    protected $validationRules      = [
        'id_santri'    => 'required|integer',
        'tahun_ajaran' => 'required|max_length[20]',
        'kelas'        => 'required|max_length[50]',
        'asrama'       => 'required|max_length[100]',
    ];

    public function getRiwayatBySantri(int $idSantri): array
    {
        return $this->where('id_santri', $idSantri)
                    ->orderBy('tahun_ajaran', 'DESC')
                    ->findAll();
    }
}
