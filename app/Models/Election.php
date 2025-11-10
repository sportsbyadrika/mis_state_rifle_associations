<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Hasher;
use PDO;

class Election extends Model
{
    public function allPublished(): array
    {
        $stmt = $this->db->query('SELECT e.*, o.name AS organization_name FROM elections e JOIN organizations o ON o.id = e.organization_id WHERE e.is_published = 1 ORDER BY e.held_on DESC');
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(function ($row) {
            $row['hash_id'] = Hasher::encode((int) $row['id']);
            return $row;
        }, $records);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO elections (organization_id, title, held_on, description, photo_path, is_published, created_at, updated_at) VALUES (:organization_id, :title, :held_on, :description, :photo_path, :is_published, NOW(), NOW())');
        $stmt->execute([
            'organization_id' => $data['organization_id'],
            'title' => $data['title'],
            'held_on' => $data['held_on'],
            'description' => $data['description'],
            'photo_path' => $data['photo_path'] ?? null,
            'is_published' => $data['is_published'] ?? 0,
        ]);
        return (int) $this->db->lastInsertId();
    }
}
