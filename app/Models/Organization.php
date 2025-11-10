<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Hasher;

class Organization extends Model
{
    public const TYPE_STATE = 'ksra';
    public const TYPE_DISTRICT = 'dra';
    public const TYPE_INSTITUTION = 'ai';
    public const TYPE_CLUB = 'club';

    public function allByType(string $type): array
    {
        $stmt = $this->db->prepare('SELECT * FROM organizations WHERE type = :type ORDER BY name');
        $stmt->execute(['type' => $type]);
        $organizations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function ($org) {
            $org['hash_id'] = Hasher::encode((int) $org['id']);
            return $org;
        }, $organizations);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO organizations (name, type, parent_id, address, phone, email, created_at, updated_at) VALUES (:name, :type, :parent_id, :address, :phone, :email, NOW(), NOW())');
        $stmt->execute([
            'name' => $data['name'],
            'type' => $data['type'],
            'parent_id' => $data['parent_id'] ?? null,
            'address' => $data['address'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }
}
