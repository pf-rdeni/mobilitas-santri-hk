<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AdminDocController extends BaseController
{
    public function index()
    {
        $data = [
            'title'      => 'Dokumentasi Sistem Mobilitas',
            'pageTitle'  => 'Alur Kerja & Panduan Sistem (IPO)',
            'breadcrumb' => [
                ['title' => 'Home', 'url' => 'dashboard'],
                ['title' => 'Dokumentasi Sistem'],
            ],
        ];

        return view('backend/doc/index', $data);
    }
}
