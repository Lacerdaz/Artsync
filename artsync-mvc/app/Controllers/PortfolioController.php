<?php

namespace App\Controllers;

use App\Models\PortfolioItem;
use App\Repositories\PDO\PdoPortfolioRepository;
use PDO;
use PDOException;

class PortfolioController extends AuthController
{
    private PdoPortfolioRepository $repository;
    private PDO $pdo;
    private string $uploadDir = __DIR__ . '/../../public/uploads/';

    public function __construct()
    {
        parent::__construct();
        $this->checkAuth();

        try {
            // Configuração do banco
            $host = 'localhost';
            $dbname = 'artsync_db';
            $username = 'root';
            $password = ''; // coloque sua senha do MySQL se tiver

            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->pdo = $pdo;
            $this->repository = new PdoPortfolioRepository($pdo);

            // Garante que a tabela exista
            $this->createTableIfNotExists();

        } catch (PDOException $e) {
            die("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }

    /**
     * Cria a tabela portfolio_items se não existir
     */
    private function createTableIfNotExists(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS portfolio_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                file_path VARCHAR(255) NOT NULL,
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ";
        $this->pdo->exec($sql);
    }

    /**
     * Exibe a página do portfólio com os itens do usuário.
     */
    public function index(): void
    {
        $items = $this->repository->getByUserId((int)$_SESSION['user_id']);

        $this->view('portfolio/index', [
            'pageTitle' => 'Meu Portfólio',
            'currentPage' => 'portfolio',
            'items' => $items,
            'feedback' => $_SESSION['feedback'] ?? null
        ]);
        unset($_SESSION['feedback']);
    }

    /**
     * Faz o upload de uma nova mídia
     */
    public function upload(): void
    {
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $file = $_FILES['media_file'] ?? null;

        if (empty($title) || $file === null || $file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['feedback'] = ['type' => 'error', 'message' => 'Título e arquivo são obrigatórios.'];
            header('Location: /portfolio');
            exit;
        }

        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $unique_file_name = uniqid('media_') . '.' . $file_extension;
        $target_file = $this->uploadDir . $unique_file_name;

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            $webPath = '/uploads/' . $unique_file_name;

            try {
                $this->repository->save(
                    (int)$_SESSION['user_id'],
                    $title,
                    $webPath,
                    $description ?: null
                );

                $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Mídia adicionada com sucesso!'];
            } catch (PDOException $e) {
                $_SESSION['feedback'] = ['type' => 'error', 'message' => 'Erro ao salvar no banco: ' . $e->getMessage()];
            }
        } else {
            $_SESSION['feedback'] = ['type' => 'error', 'message' => 'Erro ao mover o arquivo.'];
        }

        header('Location: /portfolio');
        exit;
    }

    /**
     * Exclui uma mídia existente
     */
    public function delete(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            header('Location: /portfolio');
            exit;
        }

        // Busca o item (objeto)
        $item = $this->repository->find($id, (int)$_SESSION['user_id']);
        if (!$item) {
            $_SESSION['feedback'] = ['type' => 'error', 'message' => 'Item não encontrado.'];
            header('Location: /portfolio');
            exit;
        }

        // Caminho físico completo do arquivo
        $filePath = __DIR__ . '/../../public' . $item->filePath;
        if (is_file($filePath)) {
            @unlink($filePath);
        }

        // Exclui do banco
        $this->repository->delete($item->id, (int)$_SESSION['user_id']);

        $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Mídia excluída com sucesso!'];
        header('Location: /portfolio');
        exit;
    }
}
