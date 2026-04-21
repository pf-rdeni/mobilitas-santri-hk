<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class PenugasanTerminal extends Entity
{
    protected $dates = ['created_at', 'updated_at'];
    protected $casts = [
        'id'          => 'integer',
        'id_jadwal'   => 'integer',
        'id_petugas'  => 'integer',
    ];
}
