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
        $photoData = trim($_POST['photo_data'] ?? '');

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

        if ($photoData !== '') {
            $storedPhoto = $this->storeCroppedPhoto($photoData, (int) $freshUser['id'], $freshUser['photo_path'] ?? null);
            if ($storedPhoto === null) {
                $errors[] = 'We could not process the uploaded photo. Please try again with a different image.';
            } else {
                $updateData['photo_path'] = $storedPhoto;
            }
        }

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
                    'photo_path' => $freshUser['photo_path'] ?? null,
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

    private function storeCroppedPhoto(string $photoData, int $userId, ?string $currentPath): ?string
    {
        if (!str_starts_with($photoData, 'data:image/')) {
            return null;
        }

        $commaPosition = strpos($photoData, ',');
        if ($commaPosition === false) {
            return null;
        }

        $metadata = substr($photoData, 0, $commaPosition + 1);
        if (!preg_match('/^data:image\/(png|jpe?g);base64,$/i', $metadata)) {
            return null;
        }

        $base64 = substr($photoData, $commaPosition + 1);
        $binary = base64_decode($base64, true);
        if ($binary === false || strlen($binary) > 5 * 1024 * 1024) {
            return null;
        }

        $image = imagecreatefromstring($binary);
        if ($image === false) {
            return null;
        }

        $width = imagesx($image);
        $height = imagesy($image);
        if ($width === 0 || $height === 0) {
            imagedestroy($image);
            return null;
        }

        $targetSize = 400;
        $canvas = imagecreatetruecolor($targetSize, $targetSize);
        if ($canvas === false) {
            imagedestroy($image);
            return null;
        }

        $background = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $background);

        $resampled = imagecopyresampled($canvas, $image, 0, 0, 0, 0, $targetSize, $targetSize, $width, $height);
        if (!$resampled) {
            imagedestroy($image);
            imagedestroy($canvas);
            return null;
        }

        $directory = __DIR__ . '/../../public/uploads/profile';
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            imagedestroy($image);
            imagedestroy($canvas);
            return null;
        }

        $filename = sprintf('user_%d_%s.jpg', $userId, uniqid('', true));
        $path = $directory . '/' . $filename;

        $saved = imagejpeg($canvas, $path, 90);
        imagedestroy($image);
        imagedestroy($canvas);

        if (!$saved) {
            return null;
        }

        if ($currentPath) {
            $absoluteCurrent = __DIR__ . '/../../public/' . ltrim($currentPath, '/');
            if (is_file($absoluteCurrent)) {
                @unlink($absoluteCurrent);
            }
        }

        return 'uploads/profile/' . $filename;
    }
}
