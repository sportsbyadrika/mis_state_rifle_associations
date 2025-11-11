<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Hasher;
use PDO;

class Organization extends Model
{
    public const TYPE_STATE = 'ksra';
    public const TYPE_DISTRICT = 'dra';
    public const TYPE_INSTITUTION = 'ai';
    public const TYPE_CLUB = 'club';

    public function allByType(string $type, ?int $parentId = null): array
    {
        $sql = 'SELECT * FROM organizations WHERE type = :type';
        $params = ['type' => $type];

        if ($parentId !== null) {
            $sql .= ' AND parent_id = :parent_id';
            $params['parent_id'] = $parentId;
        }

        $sql .= ' ORDER BY name';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $organizations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function ($org) {
            $org['hash_id'] = Hasher::encode((int) $org['id']);
            return $org;
        }, $organizations);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO organizations (name, type, status, parent_id, address, phone, email, created_at, updated_at) VALUES (:name, :type, :status, :parent_id, :address, :phone, :email, NOW(), NOW())');
        $stmt->execute([
            'name' => $data['name'],
            'type' => $data['type'],
            'status' => $data['status'] ?? 'active',
            'parent_id' => $data['parent_id'] ?? null,
            'address' => $data['address'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM organizations WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $organization = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($organization) {
            $organization['hash_id'] = Hasher::encode((int) $organization['id']);
        }
        return $organization ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = ['id' => $id];

        foreach (['name', 'email', 'phone', 'address', 'parent_id', 'status'] as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = sprintf('%s = :%s', $field, $field);
                $params[$field] = $data[$field];
            }
        }

        if (empty($fields)) {
            return false;
        }

        $fields[] = 'updated_at = NOW()';
        $sql = 'UPDATE organizations SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function setStatus(int $id, string $status): bool
    {
        return $this->update($id, ['status' => $status]);
    }
}
