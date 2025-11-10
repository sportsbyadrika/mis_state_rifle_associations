<?php

namespace App\Core;

class Csrf
{
    public static function token(): string
    {
        Auth::user(); // ensure session started
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    public static function validate(string $token): bool
    {
        Auth::user();
        return hash_equals($_SESSION['_csrf_token'] ?? '', $token);
    }
}
