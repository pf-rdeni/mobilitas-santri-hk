<?php

namespace App\Models;

use CodeIgniter\Model;

class PenugasanTerminalPanitiaModel extends Model
{
    protected $table            = 'penugasan_terminal_panitia';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['id_jadwal', 'terminal_bandara', 'id_panitia', 'id_bus'];
    protected $useTimestamps    = true;

    /**
     * Get assignments by schedule and terminal
     */
    public function getByTerminal($idJadwal, $terminal)
    {
        return $this->select('penugasan_terminal_panitia.*, users.fullname, users.username, armada_bus.nama_rombongan')
                    ->join('users', 'users.id = penugasan_terminal_panitia.id_panitia')
                    ->join('armada_bus', 'armada_bus.id = penugasan_terminal_panitia.id_bus', 'left')
                    ->where('penugasan_terminal_panitia.id_jadwal', $idJadwal)
                    ->where('penugasan_terminal_panitia.terminal_bandara', $terminal)
                    ->findAll();
    }
}
