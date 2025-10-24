<?php

namespace App\Controllers;

use App\Models\PortfolioItem;
use App\Repositories\PDO\PdoPortfolioRepository;

class PortfolioController extends AuthController {
    
    private PdoPortfolioRepository $repository;
    // Define o caminho físico real no servidor para onde os arquivos vão
    private string $uploadDir = __DIR__ . '/../../public/uploads/';

    public function __construct() {
        parent::__construct();
        $this->checkAuth();
        $this->repository = new PdoPortfolioRepository();
    }

    /**
     * Ação: Exibe a página do portfólio com os itens do usuário.
     */
    public function index(): void {
        $items = $this->repository->getByUserId($_SESSION['user_id']);
        
        $this->view('portfolio/index', [
            'pageTitle' => 'Meu Portfólio',
            'currentPage' => 'portfolio',
            'items' => $items,
            'feedback' => $_SESSION['feedback'] ?? null
        ]);
        unset($_SESSION['feedback']); // Limpa a mensagem
    }

    /**
     * Ação: Processa o upload de uma nova mídia.
     */
    public function upload(): void {
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $file = $_FILES['media_file'] ?? null;

        if (empty($title) || $file === null || $file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['feedback'] = ['type' => 'error', 'message' => 'Título e arquivo são obrigatórios.'];
            header('Location: /portfolio');
            exit;
        }
        
        // Lógica de upload segura
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $unique_file_name = uniqid('media_') . '.' . $file_extension;
        $target_file = $this->uploadDir . $unique_file_name;
        
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            // Salva no banco o caminho ACESSÍVEL PELA WEB (ex: /uploads/media_123.jpg)
            $webPath = '/uploads/' . $unique_file_name;
            
            $item = new PortfolioItem(
                null,
                $_SESSION['user_id'],
                $title,
                $webPath,
                $description
            );
            
            $this->repository->save($item);
            $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Mídia adicionada com sucesso!'];
        } else {
            $_SESSION['feedback'] = ['type' => 'error', 'message' => 'Erro ao mover o arquivo.'];
        }
        
        header('Location: /portfolio');
        exit;
    }

    /**
     * Ação: Processa a exclusão de uma mídia.
     */
    public function delete(): void {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /portfolio');
            exit;
        }

        // 1. Pega o item no banco para saber o caminho do arquivo
        $item = $this->repository->find((int)$id, $_SESSION['user_id']);

        if ($item) {
            // 2. Deleta o arquivo físico do servidor
            // Constrói o caminho físico completo (ex: C:/xampp/htdocs/artsync-mvc/public/uploads/media_123.jpg)
            $filePath = __DIR__ . '/../../public' . $item->filePath; 
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // 3. Deleta o registro do banco
            $this->repository->delete((int)$id, $_SESSION['user_id']);
            $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Mídia excluída.'];
        }

        header('Location: /portfolio');
        exit;
    }
}