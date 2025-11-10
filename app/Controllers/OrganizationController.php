<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Validator;
use App\Models\Organization;
use App\Models\OrganizationAdmin;
use App\Models\User;
use App\Core\Auth;
use App\Core\Hasher;

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

        if (!$this->authorized($user)) {
            http_response_code(403);
            echo 'Unauthorized access.';
            return;
        }

        $orgModel = new Organization();
        $organizations = $orgModel->allByType($type);

        $this->view('dashboard/organizations', [
            'user' => $user,
            'organizations' => $organizations,
            'type' => $type,
            'csrf' => Csrf::token(),
            'flash' => $this->popFlash(),
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

        if (!$this->authorized($user)) {
            http_response_code(403);
            echo 'Unauthorized access.';
            return;
        }

        $name = Validator::sanitize($_POST['name'] ?? '');
        $email = Validator::sanitize($_POST['email'] ?? '');
        $phone = Validator::sanitize($_POST['phone'] ?? '');
        $address = Validator::sanitize($_POST['address'] ?? '');
        $parent = $_POST['parent_id'] ?? null;

        if (!Validator::required($name)) {
            $this->flash('error', 'Name is required.');
            header('Location: ' . \url_to('organizations/' . rawurlencode($type)));
            exit;
        }

        $orgModel = new Organization();
        $orgModel->create([
            'name' => $name,
            'type' => $type,
            'parent_id' => $parent ? (int) $parent : null,
            'address' => $address ?: null,
            'email' => Validator::email($email) ? $email : null,
            'phone' => $phone ?: null,
        ]);

        $this->flash('success', 'Organization created successfully.');
        header('Location: ' . \url_to('organizations/' . rawurlencode($type)));
        exit;
    }

    public function show(string $type, string $hashId): void
    {
        $user = Auth::user();
        if (!$user) {
            header('Location: ' . \url_to('login'));
            exit;
        }

        $type = in_array($type, $this->allowedTypes, true) ? $type : Organization::TYPE_DISTRICT;

        if (!$this->authorized($user)) {
            http_response_code(403);
            echo 'Unauthorized access.';
            return;
        }

        $organization = $this->findOrganization($type, $hashId);
        if (!$organization) {
            $this->flash('error', 'Organization not found.');
            header('Location: ' . \url_to('organizations/' . rawurlencode($type)));
            exit;
        }

        $parent = null;
        if (!empty($organization['parent_id'])) {
            $orgModel = new Organization();
            $parent = $orgModel->find((int) $organization['parent_id']);
        }

        $adminModel = new OrganizationAdmin();
        $admins = $adminModel->getByOrganization((int) $organization['id']);

        $this->view('dashboard/organization_show', [
            'user' => $user,
            'organization' => $organization,
            'parent' => $parent,
            'type' => $type,
            'admins' => $admins,
            'csrf' => Csrf::token(),
            'flash' => $this->popFlash(),
            'adminRoleLabel' => $this->labelForType($type),
        ]);
    }

    public function update(string $type, string $hashId): void
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

        if (!$this->authorized($user)) {
            http_response_code(403);
            echo 'Unauthorized access.';
            return;
        }

        $organization = $this->findOrganization($type, $hashId);
        if (!$organization) {
            $this->flash('error', 'Organization not found.');
            header('Location: ' . \url_to('organizations/' . rawurlencode($type)));
            exit;
        }

        $name = Validator::sanitize($_POST['name'] ?? '');
        $email = Validator::sanitize($_POST['email'] ?? '');
        $phone = Validator::sanitize($_POST['phone'] ?? '');
        $address = Validator::sanitize($_POST['address'] ?? '');

        if (!Validator::required($name)) {
            $this->flash('error', 'Name is required.');
            header('Location: ' . \url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($hashId)));
            exit;
        }

        $orgModel = new Organization();
        $orgModel->update((int) $organization['id'], [
            'name' => $name,
            'email' => Validator::email($email) ? $email : null,
            'phone' => $phone ?: null,
            'address' => $address ?: null,
        ]);

        $this->flash('success', 'Organization details updated successfully.');
        header('Location: ' . \url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($hashId)));
        exit;
    }

    public function toggleStatus(string $type, string $hashId): void
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

        if (!$this->authorized($user)) {
            http_response_code(403);
            echo 'Unauthorized access.';
            return;
        }

        $organization = $this->findOrganization($type, $hashId);
        if (!$organization) {
            $this->flash('error', 'Organization not found.');
            header('Location: ' . \url_to('organizations/' . rawurlencode($type)));
            exit;
        }

        $newStatus = $organization['status'] === 'active' ? 'inactive' : 'active';
        $orgModel = new Organization();
        $orgModel->setStatus((int) $organization['id'], $newStatus);

        $this->flash('success', 'Organization status updated to ' . $newStatus . '.');
        header('Location: ' . \url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($hashId)));
        exit;
    }

    public function storeAdmin(string $type, string $hashId): void
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

        if (!$this->authorized($user)) {
            http_response_code(403);
            echo 'Unauthorized access.';
            return;
        }

        $organization = $this->findOrganization($type, $hashId);
        if (!$organization) {
            $this->flash('error', 'Organization not found.');
            header('Location: ' . \url_to('organizations/' . rawurlencode($type)));
            exit;
        }

        $name = Validator::sanitize($_POST['name'] ?? '');
        $email = Validator::sanitize($_POST['email'] ?? '');
        $phone = Validator::sanitize($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';

        $role = $this->mapTypeToRole($type);
        $userModel = new User();
        $existing = $userModel->findByEmail($email);
        $requiresPassword = !$existing;

        if (!Validator::required($name) || !Validator::email($email) || ($requiresPassword && strlen($password) < 8)) {
            $this->flash('error', 'Provide a name, valid email, and password with at least 8 characters.');
            header('Location: ' . \url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($hashId)));
            exit;
        }

        if ($existing && $existing['role'] !== $role) {
            $this->flash('error', 'Email already in use by a user with a different role.');
            header('Location: ' . \url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($hashId)));
            exit;
        }

        if ($existing) {
            $userId = (int) $existing['id'];
            $userModel->update($userId, [
                'name' => $name,
                'phone' => $phone ?: null,
                'organization_id' => (int) $organization['id'],
                'status' => 'active',
            ]);
        } else {
            $userId = $userModel->create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone ?: null,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => $role,
                'organization_id' => (int) $organization['id'],
                'status' => 'active',
            ]);
        }

        $adminModel = new OrganizationAdmin();
        $adminModel->assign((int) $organization['id'], $userId, $role);

        $this->flash('success', $this->labelForType($type) . ' assigned successfully.');
        header('Location: ' . \url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($hashId)));
        exit;
    }

    public function updateAdmin(string $type, string $hashId, string $adminHash): void
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

        if (!$this->authorized($user)) {
            http_response_code(403);
            echo 'Unauthorized access.';
            return;
        }

        $organization = $this->findOrganization($type, $hashId);
        if (!$organization) {
            $this->flash('error', 'Organization not found.');
            header('Location: ' . \url_to('organizations/' . rawurlencode($type)));
            exit;
        }

        $adminId = Hasher::decode($adminHash);
        if (!$adminId) {
            $this->flash('error', 'Invalid administrator selected.');
            header('Location: ' . \url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($hashId)));
            exit;
        }

        $adminModel = new OrganizationAdmin();
        if (!$adminModel->belongsToOrganization((int) $organization['id'], $adminId)) {
            $this->flash('error', 'Administrator does not belong to this organization.');
            header('Location: ' . \url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($hashId)));
            exit;
        }

        $name = Validator::sanitize($_POST['name'] ?? '');
        $email = Validator::sanitize($_POST['email'] ?? '');
        $phone = Validator::sanitize($_POST['phone'] ?? '');

        if (!Validator::required($name) || !Validator::email($email)) {
            $this->flash('error', 'Provide a valid name and email for the administrator.');
            header('Location: ' . \url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($hashId)));
            exit;
        }

        $userModel = new User();
        if ($userModel->emailExists($email, $adminId)) {
            $this->flash('error', 'Another user already uses this email address.');
            header('Location: ' . \url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($hashId)));
            exit;
        }

        $userModel->update($adminId, [
            'name' => $name,
            'email' => $email,
            'phone' => $phone ?: null,
        ]);

        $this->flash('success', 'Administrator details updated successfully.');
        header('Location: ' . \url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($hashId)));
        exit;
    }

    public function toggleAdminStatus(string $type, string $hashId, string $adminHash): void
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

        if (!$this->authorized($user)) {
            http_response_code(403);
            echo 'Unauthorized access.';
            return;
        }

        $organization = $this->findOrganization($type, $hashId);
        if (!$organization) {
            $this->flash('error', 'Organization not found.');
            header('Location: ' . \url_to('organizations/' . rawurlencode($type)));
            exit;
        }

        $adminId = Hasher::decode($adminHash);
        if (!$adminId) {
            $this->flash('error', 'Invalid administrator selected.');
            header('Location: ' . \url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($hashId)));
            exit;
        }

        $adminModel = new OrganizationAdmin();
        if (!$adminModel->belongsToOrganization((int) $organization['id'], $adminId)) {
            $this->flash('error', 'Administrator does not belong to this organization.');
            header('Location: ' . \url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($hashId)));
            exit;
        }

        $userModel = new User();
        $admin = $userModel->find($adminId);
        if (!$admin) {
            $this->flash('error', 'Administrator not found.');
            header('Location: ' . \url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($hashId)));
            exit;
        }

        $newStatus = $admin['status'] === 'active' ? 'inactive' : 'active';
        $userModel->setStatus($adminId, $newStatus);

        $this->flash('success', 'Administrator status updated to ' . $newStatus . '.');
        header('Location: ' . \url_to('organizations/' . rawurlencode($type) . '/' . rawurlencode($hashId)));
        exit;
    }

    private function authorized(array $user): bool
    {
        return in_array($user['role'] ?? '', [User::ROLE_SUPER_ADMIN, User::ROLE_STATE_ADMIN], true);
    }

    private function findOrganization(string $type, string $hashId): ?array
    {
        $decoded = Hasher::decode($hashId);
        if (!$decoded) {
            return null;
        }

        $orgModel = new Organization();
        $organization = $orgModel->find($decoded);

        if (!$organization || $organization['type'] !== $type) {
            return null;
        }

        return $organization;
    }

    private function flash(string $type, string $message): void
    {
        Auth::user();
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    private function popFlash(): ?array
    {
        Auth::user();
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }

    private function mapTypeToRole(string $type): string
    {
        return match ($type) {
            Organization::TYPE_DISTRICT => User::ROLE_DISTRICT_ADMIN,
            Organization::TYPE_INSTITUTION => User::ROLE_INSTITUTION_ADMIN,
            Organization::TYPE_CLUB => User::ROLE_CLUB_ADMIN,
            default => User::ROLE_DISTRICT_ADMIN,
        };
    }

    private function labelForType(string $type): string
    {
        return match ($type) {
            Organization::TYPE_DISTRICT => 'District Admin',
            Organization::TYPE_INSTITUTION => 'Institution Admin',
            Organization::TYPE_CLUB => 'Club Admin',
            default => 'Administrator',
        };
    }
}
