<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ScheduleController;
use App\Controllers\PortfolioController;
use App\Controllers\AiController;
use App\Controllers\AdminController;
use App\Controllers\LandingController;
use App\Controllers\ProfileController; // <<< NOVO

$request_uri = $_SERVER['REQUEST_URI'];
$method     = $_SERVER['REQUEST_METHOD'];
$route      = strtok($request_uri, '?');

session_start();

switch ($route) {

    case '/':
    case '':
        (new LandingController())->index();
        break;

    // Auth
    case '/login':
        $c = new AuthController();
        if ($method === 'GET')   $c->showLogin();
        elseif ($method === 'POST') $c->handleLogin();
        break;

    case '/register':
        $c = new AuthController();
        if ($method === 'GET')   $c->showRegister();
        elseif ($method === 'POST') $c->handleRegister();
        break;

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
            header('Location: /schedule'); exit;
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
            header('Location: /portfolio'); exit;
        }
        break;
    case '/portfolio/delete':
        (new PortfolioController())->delete();
        break;

    // IA
    case '/ai':
        (new AiController())->index();
        break;
    case '/ai/ask':
        if ($method === 'POST') {
            (new AiController())->ask();
        } else {
            header('Location: /ai'); exit;
        }
        break;

    // Admin
    case '/admin':
        (new AdminController())->index();
        break;
    case '/admin/delete':
        (new AdminController())->deleteUser();
        break;

    // >>>>>>> PERFIL (NOVO)
    case '/profile/edit':
        (new ProfileController())->edit();
        break;
    case '/profile/update':
        if ($method === 'POST') {
            (new ProfileController())->update();
        } else {
            header('Location: /profile/edit'); exit;
        }
        break;

    // Spotify legado (se você tiver ainda)
    case '/connect-spotify':
        $file = __DIR__ . '/../connect_spotify.php';
        if (file_exists($file)) require $file;
        else { http_response_code(500); echo "Arquivo connect_spotify.php não encontrado"; }
        break;

    case '/spotify_callback.php':
        $file = __DIR__ . '/../spotify_callback.php';
        if (file_exists($file)) require $file;
        else { http_response_code(500); echo "Arquivo spotify_callback.php não encontrado"; }
        break;

    default:
        http_response_code(404);
        $pageTitle = 'Página Não Encontrada';
        $currentPage = 'error';
        $viewFile = __DIR__ . '/../views/errors/404.php';
        if (file_exists($viewFile)) require $viewFile;
        else echo '<h1>404 - Página Não Encontrada</h1>';
        break;
}
