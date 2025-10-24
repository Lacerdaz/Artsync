<?php

namespace App\Controllers;

use App\Models\User;
use App\Repositories\PDO\PdoUserRepository;

class AuthController {
    
    protected ?PdoUserRepository $userRepository;

    public function __construct() {
        // Inicia a sessão em todas as páginas
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->userRepository = new PdoUserRepository();
    }

    /**
     * Helper para checar se o usuário está logado.
     * Os controllers filhos (Dashboard, Schedule, etc.) vão chamar esta função.
     */
    protected function checkAuth(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    /**
     * Helper para carregar uma View, passando dados para ela.
     * @param string $path O caminho para o arquivo da view (ex: 'auth/login')
     * @param array $data Os dados para extrair na view (ex: ['error' => 'msg'])
     */
    protected function view(string $path, array $data = []): void {
        // Transforma as chaves do array em variáveis
        // Ex: ['error' => 'msg'] vira $error = 'msg'
        extract($data); 
        
        // Inclui o arquivo da view
        require __DIR__ . "/../../views/{$path}.php";
    }

    // --- MÉTODOS PÚBLICOS (Rotas de Autenticação) ---

    /**
     * Ação: Exibe a página de login.
     */
    public function showLogin(): void {
        $this->view('auth/login');
    }

    /**
     * Ação: Exibe a página de registro.
     */
    public function showRegister(): void {
        $this->view('auth/register');
    }

    /**
     * Ação: Processa a tentativa de login do formulário.
     */
    public function handleLogin(): void {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userRepository->findByEmail($email);

        if ($user && password_verify($password, $user->password)) {
            // Login bem-sucedido! Armazena os dados na sessão.
            $_SESSION['user_id'] = $user->id;
            $_SESSION['artist_name'] = $user->artistName;
            $_SESSION['is_admin'] = $user->isAdmin;
            header("Location: /dashboard");
            exit;
        }

        // Se falhar, volta para a página de login com uma mensagem de erro
        $this->view('auth/login', ['error' => 'Email ou senha inválidos.']);
    }

    /**
     * Ação: Processa o formulário de registro.
     */
    public function handleRegister(): void {
        $name = $_POST['artist_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        // Validações (simplificadas)
        if ($password !== $confirm) {
            $this->view('auth/register', ['error' => 'As senhas não coincidem.']);
            return;
        }
        if (strlen($password) < 6) {
             $this->view('auth/register', ['error' => 'A senha deve ter pelo menos 6 caracteres.']);
            return;
        }
        if ($this->userRepository->findByEmail($email)) {
            $this->view('auth/register', ['error' => 'Este email já está cadastrado.']);
            return;
        }

        // Cria o hash da senha
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Cria o objeto Modelo
        $user = new User(
            null,
            $name,
            $email,
            $hashed_password,
            false // isAdmin (padrão é false)
        );

        // Salva no banco
        if ($this->userRepository->save($user)) {
            // Sucesso! Redireciona para o login
            header("Location: /login");
            exit;
        } else {
            $this->view('auth/register', ['error' => 'Ocorreu um erro ao criar a conta.']);
        }
    }

    /**
     * Ação: Desloga o usuário.
     */
    public function logout(): void {
        session_destroy();
        header("Location: /login");
        exit;
    }
}