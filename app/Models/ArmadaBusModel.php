<?php

namespace App\Models;

use CodeIgniter\Model;

class ArmadaBusModel extends Model
{
    protected $table         = 'armada_bus';
    protected $primaryKey    = 'id';
    // Gunakan array atau object return type, karena entity lama mungkin belum diperbarui
    protected $returnType    = 'object'; 
    protected $allowedFields = [
        'id_jadwal', 
        'nama_rombongan', 
        'no_polisi', 
        'perusahaan_bus', 
        'koordinator_bus', 
        'no_kontak', 
        'kapasitas', 
        'tanggal_digunakan', 
        'waktu_keberangkatan', 
        'pemesan_bus', 
        'id_pendamping_bus', 
        'id_pendamping_terminal'
    ];
    protected $useTimestamps = true;

    protected $validationRules = [
        'id_jadwal'           => 'required|integer',
        'nama_rombongan'      => 'required|max_length[100]',
        'no_polisi'           => 'required|max_length[20]',
        'kapasitas'           => 'required|integer|greater_than[0]',
        'tanggal_digunakan'   => 'required|valid_date',
        'waktu_keberangkatan' => 'required',
    ];

    public function getBusByJadwal(int $idJadwal): array
    {
        return $this->where('id_jadwal', $idJadwal)
                    ->orderBy('waktu_keberangkatan', 'ASC')
                    ->findAll();
    }
}
