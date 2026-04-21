<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Myth\Auth\Exceptions\PermissionException;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authenticate = service('authentication');
        $authorize    = service('authorization');

        // If no user is logged in then send them to the login form.
        if (! $authenticate->check()) {
            session()->set('redirect_url', current_url());
            return redirect()->to(site_url('/login'));
        }

        if (empty($arguments)) {
            return;
        }

        // Check each requested permission
        foreach ($arguments as $group) {
            if ($authorize->inGroup($group, $authenticate->id())) {
                return;
            }
        }

        // If denied, we redirect them to their appropriate dashboard to prevent redirect loops.
        $userId = $authenticate->id();
        $groupModel = new \Myth\Auth\Models\GroupModel();
        $userGroups = $groupModel->getGroupsForUser($userId);
        $groupNames = array_column($userGroups, 'name');

        if (in_array('orangtua', $groupNames)) {
            $redirectURL = site_url('/orangtua');
        } else {
            $redirectURL = site_url('/dashboard');
        }

        // Make sure we don't redirect to the same URL to prevent infinite loops!
        if (current_url() == $redirectURL) {
            // Throw exception if we are stuck on the same page
            throw new PermissionException('Akses ditolak: Anda tidak memiliki izin untuk halaman ini.');
        }

        return redirect()->to($redirectURL)->with('error', 'Akses ditolak: Anda tidak memiliki izin untuk halaman tersebut. Anda telah diarahkan ke dashboard Anda.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
