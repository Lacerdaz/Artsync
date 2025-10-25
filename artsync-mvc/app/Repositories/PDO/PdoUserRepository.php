<?php

namespace App\Repositories\PDO;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Config\Database;
use PDO;

/**
 * Implementação do UserRepositoryInterface que usa PDO.
 */
class PdoUserRepository implements UserRepositoryInterface {
    
    private PDO $db;

    public function __construct() {
        // Pega a instância Singleton (única) da conexão com o banco
        $this->db = Database::getInstance();
    }

    public function findByEmail(string $email): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch();

        if ($data) {
            // Converte o array do banco em um objeto User
            return new User(
                $data['id'],
                $data['artist_name'],
                $data['email'],
                $data['password'],
                (bool)$data['is_admin']
            );
        }
        return null;
    }
    
    public function findById(int $id): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();
        if ($data) {
             // Converte o array do banco em um objeto User
             return new User(
                $data['id'],
                $data['artist_name'],
                $data['email'],
                $data['password'],
                (bool)$data['is_admin']
            );
        }
        return null;
    }

    public function save(User $user): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO users (artist_name, email, password, is_admin) 
             VALUES (:name, :email, :pass, :admin)"
        );
        return $stmt->execute([
            'name' => $user->artistName,
            'email' => $user->email,
            'pass' => $user->password, // A senha já deve vir com hash do AuthController
            'admin' => $user->isAdmin ? 1 : 0
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT id, artist_name, email, is_admin, created_at FROM users ORDER BY artist_name ASC");
        // Para o admin, retornamos apenas o array de dados, não objetos User completos.
        return $stmt->fetchAll(); 
    }
}