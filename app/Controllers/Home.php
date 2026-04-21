<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        if (logged_in()) {
            if (in_groups('orangtua')) {
                return redirect()->to('/orangtua');
            }
            if (in_groups(['superadmin', 'admin', 'panitia'])) {
                return redirect()->to('/dashboard');
            }
            // Default fallback if role is unknown but logged in
            return redirect()->to('/orangtua');
        }
        return redirect()->to('/login');
    }
}
