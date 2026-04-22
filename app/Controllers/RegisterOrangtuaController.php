<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\GroupModel;

/**
 * Custom registrasi mandiri untuk wali santri.
 * Akun dibuat dengan active = 0, menunggu aktivasi admin.
 */
class RegisterOrangtuaController extends BaseController
{
    protected $userModel;
    protected $groupModel;

    public function __construct()
    {
        $this->userModel  = new UserModel();
        $this->groupModel = new GroupModel();
    }

    public function index()
    {
        // Jika sudah login, redirect sesuai role
        if (logged_in()) {
            return redirect()->to('/orangtua');
        }

        return view('Auth/register_orangtua');
    }

    public function store()
    {
        if (logged_in()) {
            return redirect()->to('/orangtua');
        }

        $rules = [
            'fullname'     => 'required|max_length[100]',
            'username'     => 'required|min_length[10]|max_length[20]|is_unique[users.username]',
            'password'     => 'required|min_length[6]',
            'pass_confirm' => 'required|matches[password]',
        ];

        $messages = [
            'username' => [
                'min_length'  => 'No HP minimal 10 digit.',
                'max_length'  => 'No HP maksimal 20 digit.',
                'is_unique'   => 'No HP ini sudah terdaftar. Silakan hubungi admin jika lupa password.',
            ],
            'pass_confirm' => [
                'matches' => 'Konfirmasi password tidak cocok.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Gunakan User entity myth/auth agar format hash password konsisten
        // dengan yang digunakan sistem login (setPassword() di User entity)
        $user = new User([
            'fullname' => $this->request->getPost('fullname'),
            'username' => $this->request->getPost('username'),
            'email'    => '',
            'password' => $this->request->getPost('password'), // User entity auto-hash via setPassword()
            'active'   => 0, // Menunggu aktivasi admin
        ]);

        // skipValidation(true) agar validasi 'email required' dari UserModel dilewati
        if (! $this->userModel->skipValidation(true)->save($user)) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data. Silakan coba lagi.');
        }

        $userId = $this->userModel->getInsertID();

        // Masukkan ke grup orangtua (ID 4)
        $this->groupModel->addUserToGroup($userId, 4);

        return redirect()->to('/login')->with('message',
            'Registrasi berhasil! Akun Anda sedang menunggu verifikasi admin. Silakan tunggu konfirmasi sebelum login.'
        );
    }

    /**
     * AJAX endpoint: cek apakah No HP sudah terdaftar
     */
    public function checkNohp()
    {
        $nohp = $this->request->getPost('username');
        if (empty($nohp)) {
            return $this->response->setJSON(['exists' => false]);
        }
        $exists = $this->userModel->where('username', $nohp)->countAllResults() > 0;
        return $this->response->setJSON(['exists' => $exists]);
    }
}
