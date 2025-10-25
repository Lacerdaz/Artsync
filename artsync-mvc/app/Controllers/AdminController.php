<?php

namespace App\Controllers;

use App\Repositories\PDO\PdoUserRepository; // Precisa saber como buscar usuários

class AdminController extends AuthController { // Estende AuthController para usar checkAuth e view

    private PdoUserRepository $userRepository;

    public function __construct() {
        parent::__construct(); // Chama o construtor do AuthController (inicia sessão)
        $this->checkAuth();    // Garante que o usuário está logado

        // **PASSO CRUCIAL DE SEGURANÇA:** Verifica se o usuário logado é um admin
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            // Se não for admin, define uma mensagem de erro na sessão
            $_SESSION['feedback'] = ['type' => 'error', 'message' => 'Acesso negado. Você não tem permissão para acessar esta área.'];
            // E redireciona para o dashboard comum
            header('Location: /dashboard');
            exit; // Interrompe a execução para garantir o redirecionamento
        }
        
        // Se chegou até aqui, o usuário é admin. Instancia o repositório de usuários.
        $this->userRepository = new PdoUserRepository();
    }

    /**
     * Ação: Exibe a página principal do admin (lista de usuários).
     */
    public function index(): void {
        // Busca todos os usuários usando o repositório
        $users = $this->userRepository->getAll();
        
        // Chama a view de admin e passa a lista de usuários para ela
        $this->view('admin/index', [
            'pageTitle' => 'Admin - Gerenciar Usuários',
            'currentPage' => 'admin', // Para o menu lateral
            'users' => $users,
            'feedback' => $_SESSION['feedback'] ?? null // Pega qualquer mensagem de feedback da sessão
        ]);
        unset($_SESSION['feedback']); // Limpa a mensagem da sessão após exibi-la
    }

    /**
     * Ação: Processa a exclusão de um usuário.
     */
    public function deleteUser(): void {
        // Pega o ID do usuário a ser deletado da URL (ex: /admin/delete?id=5)
        $id = $_GET['id'] ?? null;
        
        // Validação: Não permite que um admin exclua a si mesmo
        if ($id && (int)$id === $_SESSION['user_id']) {
            $_SESSION['feedback'] = ['type' => 'error', 'message' => 'Você não pode excluir sua própria conta de administrador.'];
        } 
        // Se um ID válido foi passado e não é o próprio admin
        elseif ($id) {
            // Chama o repositório para deletar o usuário
            $this->userRepository->delete((int)$id);
            $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Usuário excluído com sucesso.'];
        }
        
        // Redireciona de volta para a página principal do admin
        header('Location: /admin');
        exit;
    }
}