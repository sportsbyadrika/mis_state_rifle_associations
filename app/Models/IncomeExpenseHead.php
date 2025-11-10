<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Hasher;
use PDO;

class IncomeExpenseHead extends Model
{
    public function allForOrganization(int $organizationId, string $type): array
    {
        $stmt = $this->db->prepare('SELECT * FROM income_expense_heads WHERE organization_id = :organization_id AND type = :type ORDER BY name');
        $stmt->execute(['organization_id' => $organizationId, 'type' => $type]);
        $heads = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function ($head) {
            $head['hash_id'] = Hasher::encode((int) $head['id']);
            return $head;
        }, $heads);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO income_expense_heads (organization_id, type, name, description, created_at, updated_at) VALUES (:organization_id, :type, :name, :description, NOW(), NOW())');
        $stmt->execute([
            'organization_id' => $data['organization_id'],
            'type' => $data['type'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }
}
