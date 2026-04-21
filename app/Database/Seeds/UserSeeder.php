<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Models\GroupModel;

class UserSeeder extends Seeder
{
    public function run()
    {
        $userModel  = new UserModel();
        $groupModel = new GroupModel();

        // ============================
        // 1. Data User
        // ============================
        $users = [
            // Superadmin
            [
                'username' => 'superadmin',
                'email'    => 'superadmin@mobilitas.test',
                'password' => 'password123',
                'active'   => 1,
                'group'    => 'superadmin',
            ],
            // Admin
            [
                'username' => 'admin',
                'email'    => 'admin@mobilitas.test',
                'password' => 'password123',
                'active'   => 1,
                'group'    => 'admin',
            ],
            // Panitia
            [
                'username' => 'panitia01',
                'email'    => 'panitia01@mobilitas.test',
                'password' => 'password123',
                'active'   => 1,
                'group'    => 'panitia',
            ],
            // Orang Tua
            [
                'username' => '081234567890',
                'email'    => 'ortu1@mobilitas.test',
                'password' => 'password123',
                'active'   => 1,
                'group'    => 'orangtua',
            ],
            [
                'username' => '082345678901',
                'email'    => 'ortu2@mobilitas.test',
                'password' => 'password123',
                'active'   => 1,
                'group'    => 'orangtua',
            ],
            [
                'username' => '083456789012',
                'email'    => 'ortu3@mobilitas.test',
                'password' => 'password123',
                'active'   => 1,
                'group'    => 'orangtua',
            ],
        ];

        // ============================
        // 2. Insert / Update Users
        // ============================
        foreach ($users as $u) {
            $groupName = $u['group'];
            unset($u['group']); // Remove non-user field

            $existing = $userModel->where('username', $u['username'])->first();
            if ($existing) {
                // Update password
                $existing->password = $u['password'];
                $userModel->save($existing);
                $userId = $existing->id;
            } else {
                $user = new User($u);
                $userModel->save($user);
                $userId = $userModel->getInsertID();
            }

            // Assign group
            $existingGroups = $groupModel->getGroupsForUser($userId);
            foreach ($existingGroups as $eg) {
                $groupModel->removeUserFromGroup($userId, $eg['group_id']);
            }
            
            // Fetch group ID securely using DB builder
            $groupRow = $this->db->table('auth_groups')->where('name', $groupName)->get()->getRow();
            if ($groupRow) {
                $groupModel->addUserToGroup($userId, $groupRow->id);
            }
        }

        echo "✅ UserSeeder: " . count($users) . " user berhasil di-seed dengan group assignment.\n";
        echo "   superadmin  → superadmin / password123\n";
        echo "   admin       → admin / password123\n";
        echo "   panitia     → panitia01 / password123\n";
        echo "   orangtua    → 081234567890 / 082345678901 / 083456789012 / password123\n";
    }
}
