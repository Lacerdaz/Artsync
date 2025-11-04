<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ScheduleController;
use App\Controllers\PortfolioController;
use App\Controllers\AiController;
use App\Controllers\AdminController;
use App\Controllers\LandingController;
use App\Controllers\SpotifyController;

// Pega a URL e o método da requisição
$request_uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$route = strtok($request_uri, '?');

session_start();

switch ($route) {
    // Página inicial
    case '/':
    case '':
        (new LandingController())->index();
        break;

    // Login
    case '/login':
        $controller = new AuthController();
        if ($method === 'GET') {
            $controller->showLogin();
        } elseif ($method === 'POST') {
            $controller->handleLogin();
        }
        break;

    // Registro
    case '/register':
        $controller = new AuthController();
        if ($method === 'GET') {
            $controller->showRegister();
        } elseif ($method === 'POST') {
            $controller->handleRegister();
        }
        break;

    // Logout
    case '/logout':
        (new AuthController())->logout();
        break;

    // Dashboard
    case '/dashboard':
        (new DashboardController())->index();
        break;

    // Agenda
    case '/schedule':
        (new ScheduleController())->index();
        break;
    case '/schedule/create':
        if ($method === 'POST') {
            (new ScheduleController())->create();
        } else {
            header('Location: /schedule');
            exit;
        }
        break;
    case '/schedule/delete':
        (new ScheduleController())->delete();
        break;

    // Portfólio
    case '/portfolio':
        (new PortfolioController())->index();
        break;
    case '/portfolio/upload':
        if ($method === 'POST') {
            (new PortfolioController())->upload();
        } else {
            header('Location: /portfolio');
            exit;
        }
        break;
    case '/portfolio/delete':
        (new PortfolioController())->delete();
        break;

    // IA de Carreira
    case '/ai':
        (new AiController())->index();
        break;
    case '/ai/ask':
        if ($method === 'POST') {
            (new AiController())->ask();
        } else {
            header('Location: /ai');
            exit;
        }
        break;

    // Spotify (corrigido!)
    case '/connect-spotify':
        (new SpotifyController())->connect();
        break;
    case '/spotify_callback':
        (new SpotifyController())->callback();
        break;

    // Admin
    case '/admin':
        (new AdminController())->index();
        break;
    case '/admin/delete':
        (new AdminController())->deleteUser();
        break;

    // 404
    default:
        http_response_code(404);
        $pageTitle = 'Página Não Encontrada';
        $currentPage = 'error';
        $viewFile = __DIR__ . '/../views/errors/404.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo '<h1>404 - Página Não Encontrada</h1>';
        }
        break;
}
