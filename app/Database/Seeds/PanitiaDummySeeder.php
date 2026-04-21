<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Myth\Auth\Entities\User;
use App\Models\UserModel;
use Myth\Auth\Models\GroupModel;

class PanitiaDummySeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();
        $groupModel = new GroupModel();

        // Data 10 orang Panitia
        $panitias = [
            [
                'fullname' => 'Ust. Budi Rahmat, S.Pd',
                'username' => '081211110001',
                'email'    => 'budi.panitia@example.com',
            ],
            [
                'fullname' => 'Ustz. Siti Aisyah',
                'username' => '081211110002',
                'email'    => 'siti.panitia@example.com',
            ],
            [
                'fullname' => 'Ust. Ahmad Zaki, Lc',
                'username' => '081211110003',
                'email'    => 'ahmad.panitia@example.com',
            ],
            [
                'fullname' => 'Ustz. Nuril Fajmi',
                'username' => '081211110004',
                'email'    => 'nuril.panitia@example.com',
            ],
            [
                'fullname' => 'Ust. Fahmi Husaeni',
                'username' => '081211110005',
                'email'    => 'fahmi.panitia@example.com',
            ],
            [
                'fullname' => 'Ustz. Ratna Sari, M.Pd',
                'username' => '081211110006',
                'email'    => 'ratna.panitia@example.com',
            ],
            [
                'fullname' => 'Ust. Fajar Sidiq',
                'username' => '081211110007',
                'email'    => 'fajar.panitia@example.com',
            ],
            [
                'fullname' => 'Ust. Hasyim Asyari',
                'username' => '081211110008',
                'email'    => 'hasyim.panitia@example.com',
            ],
            [
                'fullname' => 'Ustz. Dinda Aulia',
                'username' => '081211110009',
                'email'    => 'dinda.panitia@example.com',
            ],
            [
                'fullname' => 'Ust. Reza Aditya',
                'username' => '081211110010',
                'email'    => 'reza.panitia@example.com',
            ]
        ];

        $defaultPassword = 'passwordpanitia123';
        $insertedCount = 0;

        foreach ($panitias as $p) {
            // Cek apakah username/no hp ini sudah ada
            $existing = $userModel->where('username', $p['username'])
                                  ->orWhere('email', $p['email'])
                                  ->first();

            if (!$existing) {
                // Buat instance Entitas Myth\Auth
                $user = new User([
                    'fullname' => $p['fullname'],
                    'username' => $p['username'], // Kita jadikan No HP sebagai Username
                    'email'    => $p['email'],
                    'password' => $defaultPassword,
                    'active'   => 1,
                ]);

                // Insert User ke DB
                if ($userModel->save($user)) {
                    $userId = $userModel->getInsertID();
                    
                    // Assign User ke group Panitia (group_id = 3)
                    $groupModel->addUserToGroup($userId, 3);
                    
                    $insertedCount++;
                }
            } else {
                // Update fullname jika sudah ada
                $userModel->update($existing->id, ['fullname' => $p['fullname']]);
                $insertedCount++;
            }
        }

        echo "Berhasil membuat $insertedCount data Panitia Dummy!\n";
    }
}
