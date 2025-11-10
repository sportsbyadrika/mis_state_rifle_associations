<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Otp extends Model
{
    public function create(string $email, string $code, int $expiresInMinutes = 10): void
    {
        $expiresAt = date('Y-m-d H:i:s', time() + ($expiresInMinutes * 60));
        $stmt = $this->db->prepare('INSERT INTO otps (email, code, expires_at, created_at) VALUES (:email, :code, :expires_at, NOW())');
        $stmt->execute([
            'email' => $email,
            'code' => password_hash($code, PASSWORD_DEFAULT),
            'expires_at' => $expiresAt,
        ]);
    }

    public function validate(string $email, string $code): bool
    {
        $stmt = $this->db->prepare('SELECT * FROM otps WHERE email = :email ORDER BY created_at DESC LIMIT 1');
        $stmt->execute(['email' => $email]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$record) {
            return false;
        }

        $isExpired = strtotime($record['expires_at']) < time();
        if ($isExpired) {
            return false;
        }

        return password_verify($code, $record['code']);
    }
}
