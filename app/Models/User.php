<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Hasher;
use PDO;

class User extends Model
{
    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_STATE_ADMIN = 'state_admin';
    public const ROLE_DISTRICT_ADMIN = 'district_admin';
    public const ROLE_INSTITUTION_ADMIN = 'institution_admin';
    public const ROLE_CLUB_ADMIN = 'club_admin';
    public const ROLE_MEMBER = 'member';

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $user['hash_id'] = Hasher::encode((int) $user['id']);
        }
        return $user ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO users (name, email, phone, password, role, status, created_at, updated_at) VALUES (:name, :email, :phone, :password, :role, :status, NOW(), NOW())');
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'role' => $data['role'],
            'status' => $data['status'] ?? 'active',
        ]);
        return (int) $this->db->lastInsertId();
    }
}
