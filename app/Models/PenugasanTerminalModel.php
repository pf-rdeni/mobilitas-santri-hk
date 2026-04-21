<?php

namespace App\Models;

use CodeIgniter\Model;

class PenugasanTerminalModel extends Model
{
    protected $table         = 'penugasan_terminal';
    protected $primaryKey    = 'id';
    protected $returnType    = \App\Entities\PenugasanTerminal::class;
    protected $allowedFields = ['id_jadwal', 'terminal_bandara', 'id_petugas'];
    protected $useTimestamps = true;

    protected $validationRules = [
        'id_jadwal'        => 'required|integer',
        'terminal_bandara' => 'required|in_list[1,2,3]',
    ];

    /**
     * Ambil penugasan terminal dengan nama petugas untuk suatu jadwal.
     */
    public function getPenugasanByJadwal(int $idJadwal): array
    {
        return $this->select('penugasan_terminal.*, users.username as nama_petugas')
                    ->join('users', 'users.id = penugasan_terminal.id_petugas', 'left')
                    ->where('id_jadwal', $idJadwal)
                    ->findAll();
    }

    /**
     * Simpan atau update penugasan (upsert by jadwal + terminal).
     */
    public function saveOrUpdate(int $idJadwal, string $terminal, ?int $idPetugas): void
    {
        $existing = $this->where('id_jadwal', $idJadwal)
                         ->where('terminal_bandara', $terminal)
                         ->first();

        if ($existing) {
            $this->update($existing->id, ['id_petugas' => $idPetugas]);
        } else {
            $this->insert([
                'id_jadwal'        => $idJadwal,
                'terminal_bandara' => $terminal,
                'id_petugas'       => $idPetugas,
            ]);
        }
    }
}
