<?php

// Linha mais importante! Carrega o autoloader do Composer.
// Sem ele, as classes (Controllers, Models, Repositories, etc.) não são encontradas.
// Esta linha assume que a pasta 'vendor' está um nível ACIMA da pasta 'public'.
require __DIR__ . '/../vendor/autoload.php';

// Importa (use) todas as classes de Controller que o roteador vai precisar.
// Isso permite que usemos apenas 'AuthController' em vez de 'App\Controllers\AuthController' no código abaixo.
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ScheduleController;
use App\Controllers\PortfolioController;
use App\Controllers\AiController;
use App\Controllers\AdminController;
use App\Controllers\LandingController; // Controller da página inicial

// Pega a URL que o usuário está tentando acessar (ex: /dashboard, /portfolio/delete)
$request_uri = $_SERVER['REQUEST_URI'];
// Pega o método HTTP da requisição (GET para acessar, POST para enviar formulários)
$method = $_SERVER['REQUEST_METHOD'];

// Remove query strings da URL (ex: transforma /schedule/delete?id=123 em /schedule/delete)
// para que possamos encontrar a rota base no switch.
$route = strtok($request_uri, '?');

// Inicia a sessão PHP em todas as requisições.
// Isso permite que usemos $_SESSION para saber se o usuário está logado, etc.
session_start();

// --- O Roteador ---
// Este switch decide qual Controller e qual método (ação) chamar
// baseado na URL ($route) e, às vezes, no método HTTP ($method).
switch ($route) {

    // --- ROTA RAIZ (/) ---
    case '/':
    case '': // Rota para quando não há nada após o domínio (ex: http://artsync.local/)
        // Chama o LandingController para exibir a página inicial de apresentação.
        (new LandingController())->index();
        break;

    // --- Rotas de Autenticação ---
    case '/login':
        $controller = new AuthController();
        // Se a requisição for GET, mostra o formulário de login.
        // Se for POST (envio do formulário), processa a tentativa de login.
        if ($method === 'GET') {
            $controller->showLogin();
        } elseif ($method === 'POST') {
            $controller->handleLogin();
        }
        break;
    case '/register':
        $controller = new AuthController();
        // Se for GET, mostra o formulário de registro.
        // Se for POST, processa a tentativa de registro.
        if ($method === 'GET') {
            $controller->showRegister();
        } elseif ($method === 'POST') {
            $controller->handleRegister();
        }
        break;
    case '/logout':
        // Chama o método logout do AuthController para destruir a sessão.
        (new AuthController())->logout();
        break;

    // --- Rotas do Painel Principal ---
    case '/dashboard':
        // Mostra a página do dashboard (requer login).
        (new DashboardController())->index();
        break;

    // --- Rotas da Agenda ---
    case '/schedule':
        // Mostra a página da agenda (lista os eventos).
        (new ScheduleController())->index();
        break;
    case '/schedule/create': // Rota para processar o POST do formulário de adicionar evento.
        if ($method === 'POST') {
            // Chama o método create do ScheduleController.
            (new ScheduleController())->create();
        } else {
            // Se alguém tentar acessar /schedule/create via GET, redireciona.
            header('Location: /schedule');
            exit;
        }
        break;
    case '/schedule/delete': // Rota para o link de exclusão (ex: /schedule/delete?id=5).
        // Chama o método delete do ScheduleController.
        (new ScheduleController())->delete();
        break;

    // --- Rotas do Portfólio ---
    case '/portfolio':
        // Mostra a página do portfólio (lista as mídias).
        (new PortfolioController())->index();
        break;
    case '/portfolio/upload': // Rota para processar o POST do formulário de upload.
        if ($method === 'POST') {
            // Chama o método upload do PortfolioController.
            (new PortfolioController())->upload();
        } else {
            header('Location: /portfolio');
            exit;
        }
        break;
    case '/portfolio/delete': // Rota para o link de exclusão (ex: /portfolio/delete?id=10).
        // Chama o método delete do PortfolioController.
        (new PortfolioController())->delete();
        break;

    // --- Rotas da IA de Carreira ---
    case '/ai':
        // Mostra a página da IA (formulário).
        (new AiController())->index();
        break;
    case '/ai/ask': // Rota para processar o POST do formulário de pergunta.
        if ($method === 'POST') {
            // Chama o método ask do AiController.
            (new AiController())->ask();
        } else {
            header('Location: /ai');
            exit;
        }
        break;

    // --- Rotas do Admin ---
    case '/admin':
        // Mostra a página de administração (lista usuários).
        (new AdminController())->index();
        break;
    case '/admin/delete': // Rota para o link de exclusão de usuário (ex: /admin/delete?id=2).
        // Chama o método deleteUser do AdminController.
        (new AdminController())->deleteUser();
        break;

    // --- Rota para Conectar Spotify (Inicia o processo - Solução Temporária) ---
    case '/connect-spotify':
        // Verifica se o arquivo antigo existe antes de incluí-lo.
        $connectSpotifyFile = __DIR__ . '/../connect_spotify.php';
        if (file_exists($connectSpotifyFile)) {
            require $connectSpotifyFile;
        } else {
            http_response_code(500); // Erro interno do servidor
            echo "Erro: Arquivo connect_spotify.php não encontrado na raiz do projeto.";
        }
        break;

    // --- Rota de Callback do Spotify (Recebe a resposta - Solução Temporária) ---
    case '/spotify_callback.php': // Mantém o nome do arquivo antigo por compatibilidade com a URL registrada.
        // Verifica se o arquivo antigo existe antes de incluí-lo.
        $callbackSpotifyFile = __DIR__ . '/../spotify_callback.php';
        if (file_exists($callbackSpotifyFile)) {
            require $callbackSpotifyFile;
        } else {
             http_response_code(500);
             echo "Erro: Arquivo spotify_callback.php não encontrado na raiz do projeto.";
        }
        break;

    // --- Rota 404 (Página Não Encontrada) ---
    default:
        // Define o código de status HTTP para 404.
        http_response_code(404);
        // Tenta carregar a view de erro 404.
        // É melhor não instanciar um controller aqui só para chamar a view.
        $pageTitle = 'Página Não Encontrada'; // Define o título para a view 404
        $viewFile = __DIR__ . '/../views/errors/404.php'; // Caminho para a view 404
        if (file_exists($viewFile)) {
             // Define $currentPage para evitar erros no header, se aplicável
            $currentPage = 'error'; 
            // Inclui a view 404 (que por sua vez pode incluir header/footer se desejado)
            require $viewFile; 
        } else {
            // Fallback: exibe uma mensagem simples se a view 404 não existir.
            echo '<h1>404 - Página Não Encontrada</h1>';
        }
        break;
}