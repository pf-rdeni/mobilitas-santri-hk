<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class ArmadaBus extends Entity
{
    protected $dates = ['created_at', 'updated_at'];
    protected $casts = [
        'id'                    => 'integer',
        'id_jadwal'             => 'integer',
        'kapasitas'             => 'integer',
        'id_petugas_pendamping' => 'integer',
    ];
}
