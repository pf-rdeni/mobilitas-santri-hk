<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalMobilitasModel extends Model
{
    protected $table            = 'jadwal_mobilitas';
    protected $primaryKey       = 'id';
    protected $returnType       = \App\Entities\JadwalMobilitas::class;
    protected $allowedFields    = ['jenis', 'tanggal_pelaksanaan', 'status'];
    protected $useTimestamps    = true;

    protected $validationRules = [
        'jenis'               => 'required|in_list[kedatangan,kepulangan]',
        'tanggal_pelaksanaan' => 'required|valid_date',
        'status'              => 'required|in_list[aktif,selesai]',
    ];

    /**
     * Ambil jadwal yang masih aktif.
     */
    public function getJadwalAktif(): array
    {
        return $this->where('status', 'aktif')->orderBy('tanggal_pelaksanaan', 'DESC')->findAll();
    }
}
