<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Hasher;
use PDO;

class FinanceEntry extends Model
{
    public function allForYear(int $financialYearId): array
    {
        $stmt = $this->db->prepare('SELECT fe.*, h.name AS head_name FROM finance_entries fe JOIN income_expense_heads h ON h.id = fe.head_id WHERE fe.financial_year_id = :financial_year_id ORDER BY fe.recorded_on DESC');
        $stmt->execute(['financial_year_id' => $financialYearId]);
        $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function ($entry) {
            $entry['hash_id'] = Hasher::encode((int) $entry['id']);
            return $entry;
        }, $entries);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO finance_entries (financial_year_id, head_id, type, amount, recorded_on, notes, created_at, updated_at) VALUES (:financial_year_id, :head_id, :type, :amount, :recorded_on, :notes, NOW(), NOW())');
        $stmt->execute([
            'financial_year_id' => $data['financial_year_id'],
            'head_id' => $data['head_id'],
            'type' => $data['type'],
            'amount' => $data['amount'],
            'recorded_on' => $data['recorded_on'],
            'notes' => $data['notes'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }
}
