<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Myth\Auth\Models\UserModel;

class ApiController extends BaseController
{
    public function checkUsername()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Hanya menerima request AJAX.']);
        }

        $username = $this->request->getPost('username');
        $ignoreId = $this->request->getPost('ignore_id'); // Untuk kasus mode Edit

        if (empty($username)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Username/No HP kosong.']);
        }

        $userModel = new UserModel();
        
        $builder = $userModel->where('username', $username);
        if (!empty($ignoreId)) {
            $builder = $builder->where('id !=', $ignoreId);
        }

        $user = $builder->first();

        if ($user) {
            return $this->response->setJSON([
                'status' => 'exists',
                'fullname' => $user->fullname ?? 'Tanpa Nama'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'available'
            ]);
        }
    }
}
