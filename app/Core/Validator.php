<?php

namespace App\Core;

class Validator
{
    public static function sanitize(string $value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    public static function email(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function required($value): bool
    {
        if (is_array($value)) {
            return !empty($value);
        }
        return trim((string) $value) !== '';
    }
}
