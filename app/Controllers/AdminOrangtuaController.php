<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\GroupModel;

class AdminOrangtuaController extends BaseController
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
        // Ambil semua user yang berada di grup 'orangtua' (ID 4)
        $users = $this->userModel->select('users.*')
                                ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
                                ->where('auth_groups_users.group_id', 4)
                                ->findAll();

        $data = [
            'title'      => 'Manajemen Akun Orang Tua',
            'pageTitle'  => 'Daftar Akun Wali Santri',
            'users'      => $users,
            'breadcrumb' => [
                ['title' => 'Home', 'url' => 'dashboard'],
                ['title' => 'Manajemen Wali'],
            ],
        ];

        return view('backend/orangtua/index', $data);
    }

    public function create()
    {
        // Generate random 6 character password for Orang Tua
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#*';
        $randomPass = substr(str_shuffle($chars), 0, 6);

        $data = [
            'title'      => 'Tambah Akun Orang Tua',
            'pageTitle'  => 'Buat Akun Baru',
            'randomPass' => $randomPass,
            'breadcrumb' => [
                ['title' => 'Home', 'url' => 'dashboard'],
                ['title' => 'Manajemen Wali', 'url' => 'orangtua-manage'],
                ['title' => 'Tambah Akun'],
            ],
        ];

        return view('backend/orangtua/create', $data);
    }

    public function store()
    {
        $rules = [
            'fullname' => 'required|max_length[100]',
            'username' => 'required|is_unique[users.username]|min_length[10]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Simpan User Baru
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

        // Dapatkan ID user yang baru disimpan
        $userId = $this->userModel->getInsertID();

        // Tambahkan ke grup 'orangtua' (ID 4)
        $this->groupModel->addUserToGroup($userId, 4);

        return redirect()->to('/orangtua-manage')->with('success', 'Akun orang tua berhasil dibuat.');
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to('/orangtua-manage')->with('error', 'User tidak ditemukan.');
        }

        $data = [
            'title'      => 'Edit Akun Orang Tua',
            'pageTitle'  => 'Edit Akun Wali',
            'user'       => $user,
            'breadcrumb' => [
                ['title' => 'Home', 'url' => 'dashboard'],
                ['title' => 'Manajemen Wali', 'url' => 'orangtua-manage'],
                ['title' => 'Edit Akun'],
            ],
        ];

        return view('backend/orangtua/edit', $data);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to('/orangtua-manage')->with('error', 'User tidak ditemukan.');
        }

        $rules = [
            'fullname' => 'required|max_length[100]',
            'username' => "required|min_length[10]|is_unique[users.username,id,{$id}]",
            'email'    => "required|valid_email|is_unique[users.email,id,{$id}]",
        ];

        // Jika password diisi, maka validasi password
        $password = $this->request->getPost('password');
        if (! empty($password)) {
            $rules['password'] = 'required|min_length[6]';
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
            return redirect()->to('/orangtua-manage')->with('success', 'Akun berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui akun.');
    }

    public function delete($id)
    {
        if ($this->userModel->delete($id)) {
            return redirect()->to('/orangtua-manage')->with('success', 'Akun berhasil dihapus.');
        }
        return redirect()->to('/orangtua-manage')->with('error', 'Gagal menghapus akun.');
    }
}
