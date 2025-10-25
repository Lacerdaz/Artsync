<?php

namespace App\Controllers;

class DashboardController extends AuthController {

    public function __construct() {
        parent::__construct(); // Pega o construtor do AuthController (inicia sessão)
        $this->checkAuth();    // Checa se o usuário está logado
    }

    /**
     * Ação: Exibe a página de dashboard.
     */
    public function index(): void {
        
        // Prepara os dados para a View
        $data = [
            'pageTitle' => 'Dashboard',
            'currentPage' => 'dashboard'
            // 'artist_name' já está na $_SESSION
        ];

        // Chama a View
        $this->view('dashboard/index', $data);
    }
}