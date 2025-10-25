<?php

namespace App\Repositories\PDO;

use App\Models\ScheduleEvent;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Config\Database;
use PDO;

class PdoScheduleRepository implements ScheduleRepositoryInterface {
    
    private PDO $db;

    public function __construct() {
        // Pega a instância Singleton (única) da conexão com o banco
        $this->db = Database::getInstance();
    }

    public function getByUserId(int $userId): array {
        $stmt = $this->db->prepare("SELECT * FROM schedule WHERE user_id = :uid ORDER BY event_date ASC");
        $stmt->execute(['uid' => $userId]);
        
        $events = [];
        while ($row = $stmt->fetch()) {
            // Converte o array do banco em um objeto ScheduleEvent
            $events[] = new ScheduleEvent(
                $row['id'],
                $row['user_id'],
                $row['event_title'],
                $row['event_date'],
                $row['notes']
            );
        }
        return $events;
    }

    public function save(ScheduleEvent $event): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO schedule (user_id, event_title, event_date, notes) 
             VALUES (:uid, :title, :date, :notes)"
        );
        return $stmt->execute([
            'uid' => $event->userId,
            'title' => $event->title,
            'date' => $event->eventDate, // Formato YYYY-MM-DDTHH:MM
            'notes' => $event->notes
        ]);
    }

    public function delete(int $id, int $userId): bool {
        // Deleta apenas se o ID do evento E o ID do usuário baterem
        $stmt = $this->db->prepare("DELETE FROM schedule WHERE id = :id AND user_id = :uid");
        return $stmt->execute(['id' => $id, 'uid' => $userId]);
    }
}