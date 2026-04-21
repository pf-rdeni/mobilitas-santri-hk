<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistrasiTiketModel extends Model
{
    protected $table            = 'registrasi_tiket';
    protected $primaryKey       = 'id';
    protected $returnType       = \App\Entities\RegistrasiTiket::class;
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'id_santri', 'id_jadwal', 'maskapai', 'kode_booking', 'terminal_bandara', 
        'waktu_penerbangan', 'ikut_bus', 'id_bus', 'status_transfer', 'status_checkin', 'status_bus', 'status_istirahat', 'bukti_transfer', 'bukti_tiket', 
        'bandara_asal', 'bandara_tujuan'
    ];
    protected $useTimestamps    = true;
    protected $deletedField     = 'deleted_at';

    protected $validationRules = [
        'id_santri'         => 'required|integer',
        'id_jadwal'         => 'required|integer',
        'maskapai'          => 'required|max_length[100]',
        'kode_booking'      => 'required|max_length[50]',
        'terminal_bandara'  => 'required|in_list[1,2,3]',
        'waktu_penerbangan' => 'required',
    ];

    /**
     * Santri berdasarkan jadwal tertentu, dikelompokkan by terminal.
     */
    public function getByJadwalGrouped(int $idJadwal): array
    {
        $data = $this->select('registrasi_tiket.*, santri.nama, santri.daerah_asal')
                     ->join('santri', 'santri.id = registrasi_tiket.id_santri')
                     ->where('registrasi_tiket.id_jadwal', $idJadwal)
                     ->orderBy('terminal_bandara', 'ASC')
                     ->orderBy('waktu_penerbangan', 'ASC')
                     ->findAll();

        $grouped = ['1' => [], '2' => [], '3' => []];
        foreach ($data as $row) {
            $grouped[$row->terminal_bandara][] = $row;
        }
        return $grouped;
    }

    /**
     * Rekap statistik untuk dashboard panitia.
     */
    public function getStatistikByJadwal(int $idJadwal): array
    {
        $jadwalModel = new \App\Models\JadwalMobilitasModel();
        $jadwal      = $jadwalModel->find($idJadwal);

        $total = $this->where('id_jadwal', $idJadwal)->countAllResults();

        return [
            'total'           => $total,
            'jenis'           => $jadwal ? $jadwal->jenis : '-',
            'kebutuhan_bus'   => $total > 0 ? ceil($total / 40) : 0,
        ];
    }
}
