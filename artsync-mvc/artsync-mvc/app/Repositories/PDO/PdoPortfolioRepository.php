<?php

namespace App\Repositories\PDO;

use App\Models\PortfolioItem;
use App\Repositories\Contracts\PortfolioRepositoryInterface;
use Config\Database;
use PDO;

class PdoPortfolioRepository implements PortfolioRepositoryInterface {
    
    private PDO $db;

    public function __construct() {
        // Pega a instância Singleton (única) da conexão com o banco
        $this->db = Database::getInstance();
    }

    public function getByUserId(int $userId): array {
        $stmt = $this->db->prepare("SELECT * FROM portfolio WHERE user_id = :uid ORDER BY uploaded_at DESC");
        $stmt->execute(['uid' => $userId]);
        
        $items = [];
        while ($row = $stmt->fetch()) {
            // Converte o array do banco em um objeto PortfolioItem
            $items[] = new PortfolioItem(
                $row['id'],
                $row['user_id'],
                $row['title'],
                $row['file_path'],
                $row['description']
            );
        }
        return $items;
    }

    public function find(int $id, int $userId): ?PortfolioItem {
        $stmt = $this->db->prepare("SELECT * FROM portfolio WHERE id = :id AND user_id = :uid");
        $stmt->execute(['id' => $id, 'uid' => $userId]);
        $row = $stmt->fetch();
        
        if ($row) {
            // Converte o array do banco em um objeto PortfolioItem
            return new PortfolioItem(
                $row['id'],
                $row['user_id'],
                $row['title'],
                $row['file_path'],
                $row['description']
            );
        }
        return null;
    }

    public function save(PortfolioItem $item): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO portfolio (user_id, title, description, file_path, media_type) 
             VALUES (:uid, :title, :desc, :path, 'image')" // 'image' como padrão
        );
        return $stmt->execute([
            'uid' => $item->userId,
            'title' => $item->title,
            'desc' => $item->description,
            'path' => $item->filePath // Salva o caminho web (/uploads/...)
        ]);
    }

    public function delete(int $id, int $userId): bool {
        // Deleta apenas se o ID do item E o ID do usuário baterem
        $stmt = $this->db->prepare("DELETE FROM portfolio WHERE id = :id AND user_id = :uid");
        return $stmt->execute(['id' => $id, 'uid' => $userId]);
    }
}