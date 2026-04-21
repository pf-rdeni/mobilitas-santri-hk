<?php

namespace App\Controllers;

use Myth\Auth\Controllers\AuthController as MythAuthController;

class AuthPanitiaController extends MythAuthController
{
    /**
     * Displays the login form, or redirects the user
     * to their dashboard/home if they are already logged in.
     */
    public function login()
    {
        // No need to show a login form if the user is already logged in.
        if ($this->auth->check()) {
            $redirectURL = session('redirect_url') ?? site_url('/');
            unset($_SESSION['redirect_url']);

            return redirect()->to($redirectURL);
        }

        // Set a cookie to remember that they used the panitia login (optional, but good for UX)
        // For now, just render the simple view.
        
        return $this->_render('App\Views\Auth\login_panitia', ['config' => $this->config]);
    }
}
