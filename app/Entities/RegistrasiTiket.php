<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class RegistrasiTiket extends Entity
{
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'id'         => 'integer',
        'id_santri'  => 'integer',
        'id_jadwal'  => 'integer',
    ];
}
