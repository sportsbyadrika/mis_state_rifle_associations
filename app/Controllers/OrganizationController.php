<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Validator;
use App\Models\Organization;
use App\Core\Auth;

class OrganizationController extends Controller
{
    private array $allowedTypes = [
        Organization::TYPE_DISTRICT,
        Organization::TYPE_INSTITUTION,
        Organization::TYPE_CLUB,
    ];

    public function index(string $type): void
    {
        $user = Auth::user();
        if (!$user) {
            header('Location: ' . \url_to('login'));
            exit;
        }

        $type = in_array($type, $this->allowedTypes, true) ? $type : Organization::TYPE_DISTRICT;
        $orgModel = new Organization();
        $organizations = $orgModel->allByType($type);

        $this->view('dashboard/organizations', [
            'user' => $user,
            'organizations' => $organizations,
            'type' => $type,
            'csrf' => Csrf::token(),
        ]);
    }

    public function store(string $type): void
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

        $type = in_array($type, $this->allowedTypes, true) ? $type : Organization::TYPE_DISTRICT;
        $name = Validator::sanitize($_POST['name'] ?? '');
        $email = Validator::sanitize($_POST['email'] ?? '');
        $phone = Validator::sanitize($_POST['phone'] ?? '');
        $address = Validator::sanitize($_POST['address'] ?? '');
        $parent = $_POST['parent_id'] ?? null;

        if (!Validator::required($name)) {
            header('Location: ' . \url_to('organizations/' . rawurlencode($type)));
            exit;
        }

        $orgModel = new Organization();
        $orgModel->create([
            'name' => $name,
            'type' => $type,
            'parent_id' => $parent ? (int) $parent : null,
            'address' => $address,
            'email' => $email,
            'phone' => $phone,
        ]);

        header('Location: ' . \url_to('organizations/' . rawurlencode($type)));
        exit;
    }
}
