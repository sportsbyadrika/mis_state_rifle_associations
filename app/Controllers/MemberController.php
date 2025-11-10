<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Csrf;
use App\Models\Membership;
use App\Core\Hasher;

class MemberController extends Controller
{
    public function index(): void
    {
        $user = Auth::user();
        if (!$user) {
            header('Location: ' . \url_to('login'));
            exit;
        }

        $membershipModel = new Membership();
        $memberships = $membershipModel->allForUser((int) $user['id']);
        $this->view('dashboard/memberships', [
            'user' => $user,
            'memberships' => $memberships,
            'csrf' => Csrf::token(),
        ]);
    }

    public function apply(): void
    {
        $user = Auth::user();
        if (!$user) {
            header('Location: ' . \url_to('login'));
            exit;
        }

        $this->view('dashboard/membership_apply', [
            'user' => $user,
            'csrf' => Csrf::token(),
        ]);
    }

    public function store(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? '')) {
            http_response_code(422);
            echo 'Invalid CSRF token';
            return;
        }

        $user = Auth::user();
        if (!$user) {
            header('Location: ' . \url_to('login'));
            exit;
        }

        $organizationHash = $_POST['organization_id'] ?? '';
        $membershipTypeHash = $_POST['membership_type_id'] ?? '';

        $data = [
            'user_id' => (int) $user['id'],
            'organization_id' => Hasher::decode($organizationHash) ?? 0,
            'membership_type_id' => Hasher::decode($membershipTypeHash) ?? 0,
            'status' => 'pending',
        ];

        if ($data['organization_id'] <= 0 || $data['membership_type_id'] <= 0) {
            header('Location: ' . \url_to('memberships'));
            exit;
        }

        $membershipModel = new Membership();
        $membershipModel->create($data);
        header('Location: ' . \url_to('memberships'));
        exit;
    }
}
