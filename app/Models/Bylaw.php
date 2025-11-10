<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Bylaw extends Model
{
    public function allPublished(): array
    {
        $stmt = $this->db->query('SELECT * FROM bylaws ORDER BY published_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
