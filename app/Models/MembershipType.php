<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Hasher;
use PDO;

class MembershipType extends Model
{
    public function allForOrganization(int $organizationId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM membership_types WHERE organization_id = :organization_id ORDER BY name');
        $stmt->execute(['organization_id' => $organizationId]);
        $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function ($type) {
            $type['hash_id'] = Hasher::encode((int) $type['id']);
            return $type;
        }, $types);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO membership_types (organization_id, name, description, amount, duration_months, created_at, updated_at) VALUES (:organization_id, :name, :description, :amount, :duration_months, NOW(), NOW())');
        $stmt->execute([
            'organization_id' => $data['organization_id'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'amount' => $data['amount'],
            'duration_months' => $data['duration_months'],
        ]);
        return (int) $this->db->lastInsertId();
    }
}
