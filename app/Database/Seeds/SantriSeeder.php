<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SantriSeeder extends Seeder
{
    public function run()
    {
        // Ambil beberapa user orang tua dari tabel users
        $users = $this->db->table('users')->limit(3)->get()->getResultArray();

        if (empty($users)) {
            echo "⚠️  Tidak ada user. Jalankan UserSeeder Myth:Auth terlebih dahulu.\n";
            return;
        }

        $daerah = ['Kuningan', 'Cirebon', 'Majalengka', 'Brebes', 'Garut', 'Tasikmalaya'];
        $namaList = [
            'Ahmad Fauzi', 'Siti Nur Aisyah', 'Muhammad Rizky', 'Fatimah Azzahra',
            'Abdullah Hakim', 'Khadijah Nur', 'Umar Faruk', 'Zainab Salma',
            'Yusuf Al-Amin', 'Maryam Sholehah', 'Ibrahim Khalil', 'Asma Ulfa',
        ];

        $data = [];
        foreach ($namaList as $i => $nama) {
            $user = $users[$i % count($users)];
            $data[] = [
                'nama'         => $nama,
                'daerah_asal'  => $daerah[array_rand($daerah)],
                'id_orang_tua' => $user['id'],
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ];
        }

        $this->db->table('santri')->insertBatch($data);
        echo "✅ SantriSeeder: " . count($data) . " santri berhasil ditambahkan.\n";
    }
}
