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
        $stmt = $this->db->prepare('INSERT INTO users (name, email, phone, password, role, status, organization_id, photo_path, created_at, updated_at) VALUES (:name, :email, :phone, :password, :role, :status, :organization_id, :photo_path, NOW(), NOW())');
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'role' => $data['role'],
            'status' => $data['status'] ?? 'active',
            'organization_id' => $data['organization_id'] ?? null,
            'photo_path' => $data['photo_path'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $user['hash_id'] = Hasher::encode((int) $user['id']);
        }
        return $user ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = ['id' => $id];

        foreach (['name', 'email', 'phone', 'password', 'role', 'status', 'organization_id', 'photo_path'] as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = sprintf('%s = :%s', $field, $field);
                $params[$field] = $data[$field];
            }
        }

        if (empty($fields)) {
            return false;
        }

        $fields[] = 'updated_at = NOW()';
        $stmt = $this->db->prepare('UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id');
        return $stmt->execute($params);
    }

    public function setStatus(int $id, string $status): bool
    {
        return $this->update($id, ['status' => $status]);
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM users WHERE email = :email';
        $params = ['email' => $email];
        if ($excludeId !== null) {
            $sql .= ' AND id != :exclude';
            $params['exclude'] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetchColumn();
    }
}
