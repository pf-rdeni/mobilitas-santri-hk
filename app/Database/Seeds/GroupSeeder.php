<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run()
    {
        $groups = [
            ['name' => 'superadmin', 'description' => 'Super Administrator - akses penuh'],
            ['name' => 'admin',      'description' => 'Administrator - kelola sistem'],
            ['name' => 'panitia',    'description' => 'Panitia - kelola operasional mobilitas'],
            ['name' => 'orangtua',   'description' => 'Orang Tua - registrasi tiket santri'],
        ];

        foreach ($groups as $group) {
            $existing = $this->db->table('auth_groups')->where('name', $group['name'])->get()->getRow();
            if (!$existing) {
                $this->db->table('auth_groups')->insert($group);
            }
        }

        echo "✅ GroupSeeder: 4 grup berhasil ditambahkan (superadmin, admin, panitia, orangtua).\n";
    }
}
