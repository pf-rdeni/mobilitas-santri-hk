<?php

namespace App\Models;

use CodeIgniter\Model;

class GrupJamModel extends Model
{
    protected $table            = 'grup_jam_dashboard';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['nama_grup', 'jam_mulai', 'jam_selesai'];
    protected $useTimestamps    = true;

    /**
     * Get all groups ordered by start time.
     */
    public function getAllOrdered()
    {
        return $this->orderBy('jam_mulai', 'ASC')->findAll();
    }
}
