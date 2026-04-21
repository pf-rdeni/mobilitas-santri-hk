<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Santri extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id'           => 'integer',
        'id_orang_tua' => 'integer',
    ];
}
