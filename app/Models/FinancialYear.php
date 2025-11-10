<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Hasher;
use PDO;

class FinancialYear extends Model
{
    public function allForOrganization(int $organizationId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM financial_years WHERE organization_id = :organization_id ORDER BY date_from DESC');
        $stmt->execute(['organization_id' => $organizationId]);
        $years = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function ($year) {
            $year['hash_id'] = Hasher::encode((int) $year['id']);
            return $year;
        }, $years);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO financial_years (organization_id, label, date_from, date_to, is_current, created_at, updated_at) VALUES (:organization_id, :label, :date_from, :date_to, :is_current, NOW(), NOW())');
        $stmt->execute([
            'organization_id' => $data['organization_id'],
            'label' => $data['label'],
            'date_from' => $data['date_from'],
            'date_to' => $data['date_to'],
            'is_current' => $data['is_current'] ?? 0,
        ]);
        return (int) $this->db->lastInsertId();
    }
}
