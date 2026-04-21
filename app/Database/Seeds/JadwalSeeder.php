<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class JadwalSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'jenis'               => 'kepulangan',
                'tanggal_pelaksanaan' => '2024-06-15',
                'status'              => 'aktif',
                'created_at'          => date('Y-m-d H:i:s'),
                'updated_at'          => date('Y-m-d H:i:s'),
            ],
            [
                'jenis'               => 'kedatangan',
                'tanggal_pelaksanaan' => '2024-07-10',
                'status'              => 'aktif',
                'created_at'          => date('Y-m-d H:i:s'),
                'updated_at'          => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('jadwal_mobilitas')->insertBatch($data);
        echo "✅ JadwalSeeder: " . count($data) . " jadwal berhasil ditambahkan.\n";
    }
}
