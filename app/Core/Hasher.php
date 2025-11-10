<?php

namespace App\Core;

class Hasher
{
    public static function encode(int $id): string
    {
        $config = require __DIR__ . '/../../bootstrap.php';
        $salt = $config['app']['hash_salt'];
        $hash = hash_hmac('sha256', (string) $id, $salt);
        return rtrim(strtr(base64_encode($hash . ':' . $id), '+/', '-_'), '=');
    }

    public static function decode(string $hash): ?int
    {
        $config = require __DIR__ . '/../../bootstrap.php';
        $salt = $config['app']['hash_salt'];
        $decoded = base64_decode(strtr($hash, '-_', '+/'), true);
        if (!$decoded) {
            return null;
        }
        [$storedHash, $id] = explode(':', $decoded, 2) + [null, null];
        if (!$storedHash || !$id) {
            return null;
        }
        $expected = hash_hmac('sha256', (string) $id, $salt);
        return hash_equals($storedHash, $expected) ? (int) $id : null;
    }
}
