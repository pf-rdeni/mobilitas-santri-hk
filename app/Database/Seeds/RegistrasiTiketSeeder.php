<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RegistrasiTiketSeeder extends Seeder
{
    public function run()
    {
        $santriList = $this->db->table('santri')->get()->getResultArray();
        $jadwalList = $this->db->table('jadwal_mobilitas')->get()->getResultArray();

        if (empty($santriList) || empty($jadwalList)) {
            echo "⚠️  Jalankan SantriSeeder dan JadwalSeeder terlebih dahulu.\n";
            return;
        }

        $maskapai  = ['Garuda Indonesia', 'Lion Air', 'Batik Air', 'Citilink', 'Sriwijaya Air'];
        $terminal  = ['1', '2', '3'];
        $waktu     = ['06:30', '08:00', '09:45', '11:20', '13:00', '15:30', '17:00', '19:15'];

        $data = [];
        foreach ($santriList as $i => $santri) {
            // Setiap santri didaftarkan ke salah satu jadwal
            $jadwal = $jadwalList[$i % count($jadwalList)];
            $data[] = [
                'id_santri'          => $santri['id'],
                'id_jadwal'          => $jadwal['id'],
                'maskapai'           => $maskapai[array_rand($maskapai)],
                'terminal_bandara'   => $terminal[array_rand($terminal)],
                'waktu_penerbangan'  => $waktu[array_rand($waktu)],
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ];
        }

        $this->db->table('registrasi_tiket')->insertBatch($data);
        echo "✅ RegistrasiTiketSeeder: " . count($data) . " tiket berhasil ditambahkan.\n";
    }
}
