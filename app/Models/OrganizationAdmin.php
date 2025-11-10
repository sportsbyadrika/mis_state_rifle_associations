<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Hasher;
use PDO;

class OrganizationAdmin extends Model
{
    public function getByOrganization(int $organizationId): array
    {
        $stmt = $this->db->prepare(
            'SELECT oa.id as pivot_id, oa.assigned_role, u.* FROM organization_admins oa '
            . 'JOIN users u ON u.id = oa.user_id WHERE oa.organization_id = :organization ORDER BY u.name'
        );
        $stmt->execute(['organization' => $organizationId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function (array $row) {
            $row['hash_id'] = Hasher::encode((int) $row['id']);
            return $row;
        }, $rows);
    }

    public function assign(int $organizationId, int $userId, string $role): void
    {
        $stmt = $this->db->prepare('SELECT id FROM organization_admins WHERE organization_id = :organization AND user_id = :user LIMIT 1');
        $stmt->execute(['organization' => $organizationId, 'user' => $userId]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $update = $this->db->prepare('UPDATE organization_admins SET assigned_role = :role WHERE id = :id');
            $update->execute(['role' => $role, 'id' => $existing['id']]);
            return;
        }

        $insert = $this->db->prepare('INSERT INTO organization_admins (organization_id, user_id, assigned_role, created_at) VALUES (:organization, :user, :role, NOW())');
        $insert->execute([
            'organization' => $organizationId,
            'user' => $userId,
            'role' => $role,
        ]);
    }

    public function belongsToOrganization(int $organizationId, int $userId): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM organization_admins WHERE organization_id = :organization AND user_id = :user');
        $stmt->execute(['organization' => $organizationId, 'user' => $userId]);
        return (bool) $stmt->fetchColumn();
    }
}
