<?php

namespace App\Core;

class Auth
{
    public static function user(): ?array
    {
        self::ensureSession();
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function login(array $user): void
    {
        self::ensureSession();
        session_regenerate_id(true);
        $_SESSION['user'] = $user;
    }

    public static function logout(): void
    {
        self::ensureSession();
        unset($_SESSION['user']);
        session_destroy();
    }

    private static function ensureSession(): void
    {
        $config = require __DIR__ . '/../../bootstrap.php';
        $sessionName = $config['app']['session_name'];
        if (session_status() === PHP_SESSION_NONE) {
            session_name($sessionName);
            session_start([
                'cookie_httponly' => true,
                'cookie_secure' => isset($_SERVER['HTTPS']),
                'cookie_samesite' => 'Strict',
            ]);
        }
    }
}
