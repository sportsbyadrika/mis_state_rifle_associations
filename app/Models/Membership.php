<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Hasher;
use PDO;

class Membership extends Model
{
    public function allForUser(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT m.*, o.name AS organization_name, mt.name AS membership_type FROM memberships m JOIN organizations o ON o.id = m.organization_id JOIN membership_types mt ON mt.id = m.membership_type_id WHERE m.user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        $memberships = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function ($membership) {
            $membership['hash_id'] = Hasher::encode((int) $membership['id']);
            return $membership;
        }, $memberships);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO memberships (user_id, organization_id, membership_type_id, status, requested_on, approved_on, created_at, updated_at) VALUES (:user_id, :organization_id, :membership_type_id, :status, :requested_on, :approved_on, NOW(), NOW())');
        $stmt->execute([
            'user_id' => $data['user_id'],
            'organization_id' => $data['organization_id'],
            'membership_type_id' => $data['membership_type_id'],
            'status' => $data['status'] ?? 'pending',
            'requested_on' => $data['requested_on'] ?? date('Y-m-d'),
            'approved_on' => $data['approved_on'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }
}
