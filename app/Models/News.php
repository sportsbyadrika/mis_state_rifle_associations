<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class News extends Model
{
    public function latestPublic(int $limit = 10): array
    {
        $stmt = $this->db->prepare('SELECT * FROM news_items WHERE is_public = 1 ORDER BY published_at DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
