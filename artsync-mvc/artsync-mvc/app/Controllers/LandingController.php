<?php

namespace App\Controllers;

// LandingController também estende AuthController para usar o helper 'view'
class LandingController extends AuthController {

    /**
     * Ação: Exibe a página inicial (landing page).
     */
    public function index(): void {
        
        // Verifica se o usuário já está logado
        // Se estiver, redireciona para o dashboard
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }

        // Se não estiver logado, mostra a landing page
        $this->view('landing/index', [
            'pageTitle' => 'Art Sync - Sua Carreira na Era Digital',
            'currentPage' => 'landing' // Identificador para esta página
            // Não precisa carregar o header/footer padrão do dashboard
        ]);
    }
}