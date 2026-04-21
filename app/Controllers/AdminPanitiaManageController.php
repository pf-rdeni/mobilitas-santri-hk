<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Entities\User;
use Myth\Auth\Models\GroupModel;

class AdminPanitiaManageController extends BaseController
{
    protected $userModel;
    protected $groupModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->groupModel = new GroupModel();
    }

    public function index()
    {
        // Ambil semua user yang berada di grup 'panitia' (ID 3)
        $users = $this->userModel->select('users.*')
                                ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
                                ->where('auth_groups_users.group_id', 3)
                                ->findAll();

        $data = [
            'title'      => 'Manajemen Akun Panitia',
            'pageTitle'  => 'Daftar Akun Panitia Operasional',
            'users'      => $users,
            'breadcrumb' => [
                ['title' => 'Home', 'url' => 'dashboard'],
                ['title' => 'Manajemen Panitia'],
            ],
        ];

        return view('backend/panitia/index', $data);
    }

    public function create()
    {
        $data = [
            'title'      => 'Tambah Akun Panitia',
            'pageTitle'  => 'Buat Akun Panitia Baru',
            'breadcrumb' => [
                ['title' => 'Home', 'url' => 'dashboard'],
                ['title' => 'Manajemen Panitia', 'url' => 'panitia-manage'],
                ['title' => 'Tambah Akun'],
            ],
        ];

        return view('backend/panitia/create', $data);
    }

    public function store()
    {
        $rules = [
            'fullname' => 'required|max_length[100]',
            'username' => 'required|is_unique[users.username]|min_length[10]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = new User([
            'fullname' => $this->request->getPost('fullname'),
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'active'   => 1,
        ]);

        if (! $this->userModel->save($user)) {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }

        $userId = $this->userModel->getInsertID();

        // Tambahkan ke grup 'panitia' (ID 3)
        $this->groupModel->addUserToGroup($userId, 3);

        return redirect()->to('/panitia-manage')->with('success', 'Akun panitia berhasil dibuat.');
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to('/panitia-manage')->with('error', 'User tidak ditemukan.');
        }

        $data = [
            'title'      => 'Edit Akun Panitia',
            'pageTitle'  => 'Edit Akun Panitia',
            'user'       => $user,
            'breadcrumb' => [
                ['title' => 'Home', 'url' => 'dashboard'],
                ['title' => 'Manajemen Panitia', 'url' => 'panitia-manage'],
                ['title' => 'Edit Akun'],
            ],
        ];

        return view('backend/panitia/edit', $data);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to('/panitia-manage')->with('error', 'User tidak ditemukan.');
        }

        $rules = [
            'fullname' => 'required|max_length[100]',
            'username' => "required|min_length[10]|is_unique[users.username,id,{$id}]",
            'email'    => "required|valid_email|is_unique[users.email,id,{$id}]",
        ];

        $password = $this->request->getPost('password');
        if (! empty($password)) {
            $rules['password'] = 'required|min_length[8]';
            $rules['pass_confirm'] = 'required|matches[password]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user->fullname = $this->request->getPost('fullname');
        $user->username = $this->request->getPost('username');
        $user->email = $this->request->getPost('email');

        if (! empty($password)) {
            $user->password = $password;
        }

        if ($this->userModel->save($user)) {
            return redirect()->to('/panitia-manage')->with('success', 'Akun panitia berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui akun.');
    }

    public function delete($id)
    {
        // Prevent deleting oneself
        if ($id == user_id()) {
            return redirect()->to('/panitia-manage')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('/panitia-manage')->with('success', 'Akun panitia berhasil dihapus.');
        }
        return redirect()->to('/panitia-manage')->with('error', 'Gagal menghapus akun.');
    }
}
