<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Csrf;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(): void
    {
        $user = Auth::user();
        if (!$user) {
            header('Location: ' . \url_to('login'));
            exit;
        }

        $view = 'dashboard/member';
        switch ($user['role']) {
            case User::ROLE_SUPER_ADMIN:
                $view = 'dashboard/super_admin';
                break;
            case User::ROLE_STATE_ADMIN:
                $view = 'dashboard/state_admin';
                break;
            case User::ROLE_DISTRICT_ADMIN:
                $view = 'dashboard/district_admin';
                break;
            case User::ROLE_INSTITUTION_ADMIN:
                $view = 'dashboard/institution_admin';
                break;
            case User::ROLE_CLUB_ADMIN:
                $view = 'dashboard/club_admin';
                break;
        }

        $this->view($view, ['user' => $user, 'csrf' => Csrf::token()]);
    }
}
