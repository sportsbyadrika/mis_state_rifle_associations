<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Validator;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit(): void
    {
        $user = Auth::user();
        if (!$user) {
            header('Location: ' . \url_to('login'));
            exit;
        }

        $this->view('profile/edit', [
            'user' => $user,
            'csrf' => Csrf::token(),
        ]);
    }

    public function update(): void
    {
        if (!Csrf::validate($_POST['_token'] ?? '')) {
            http_response_code(422);
            echo 'Invalid CSRF token';
            return;
        }

        $authUser = Auth::user();
        if (!$authUser) {
            header('Location: ' . \url_to('login'));
            exit;
        }

        $userModel = new User();
        $freshUser = $userModel->find((int) $authUser['id']);
        if (!$freshUser) {
            header('Location: ' . \url_to('logout'));
            exit;
        }

        $name = Validator::sanitize($_POST['name'] ?? '');
        $email = Validator::sanitize($_POST['email'] ?? '');
        $phone = Validator::sanitize($_POST['phone'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['password'] ?? '';
        $confirmPassword = $_POST['password_confirmation'] ?? '';

        $errors = [];
        if (!Validator::required($name)) {
            $errors[] = 'Name is required.';
        }

        if (!Validator::email($email)) {
            $errors[] = 'Please provide a valid email address.';
        } elseif ($userModel->emailExists($email, (int) $freshUser['id'])) {
            $errors[] = 'Another account already uses this email address.';
        }

        $updateData = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone !== '' ? $phone : null,
        ];

        if ($newPassword !== '' || $confirmPassword !== '' || $currentPassword !== '') {
            if (!password_verify($currentPassword, $freshUser['password'])) {
                $errors[] = 'Your current password does not match our records.';
            }

            if (strlen($newPassword) < 8) {
                $errors[] = 'New passwords must be at least 8 characters.';
            }

            if ($newPassword !== $confirmPassword) {
                $errors[] = 'New password confirmation does not match.';
            }

            if (empty($errors)) {
                $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
            }
        }

        if (!empty($errors)) {
            $this->view('profile/edit', [
                'user' => array_merge($authUser, [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                ]),
                'csrf' => Csrf::token(),
                'errors' => $errors,
            ]);
            return;
        }

        $userModel->update((int) $freshUser['id'], $updateData);
        $updated = $userModel->find((int) $freshUser['id']);
        if (isset($updated['password'])) {
            unset($updated['password']);
        }

        Auth::login($updated);

        $this->view('profile/edit', [
            'user' => $updated,
            'csrf' => Csrf::token(),
            'status' => 'Profile updated successfully.',
        ]);
    }
}
