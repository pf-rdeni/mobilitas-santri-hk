<?php

namespace App\Models;

use CodeIgniter\Model;

class BusPendampingTerminalModel extends Model
{
    protected $table            = 'bus_pendamping_terminal';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['id_bus', 'id_panitia'];
    protected $useTimestamps    = true;

    /**
     * Get all panitia IDs assigned to a bus for terminal duty.
     */
    public function getAssigneesByBus($idBus)
    {
        return $this->where('id_bus', $idBus)->findAll();
    }
}
