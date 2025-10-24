<?php

namespace App\Controllers;

use App\Models\ScheduleEvent;
use App\Repositories\PDO\PdoScheduleRepository;

class ScheduleController extends AuthController {
    
    private PdoScheduleRepository $repository;

    public function __construct() {
        parent::__construct();
        $this->checkAuth();
        $this->repository = new PdoScheduleRepository();
    }

    /**
     * Ação: Exibe a página da agenda com os eventos.
     */
    public function index(): void {
        $events = $this->repository->getByUserId($_SESSION['user_id']);
        
        $this->view('schedule/index', [
            'pageTitle' => 'Minha Agenda',
            'currentPage' => 'schedule',
            'events' => $events,
            'feedback' => $_SESSION['feedback'] ?? null
        ]);
        unset($_SESSION['feedback']); // Limpa a mensagem
    }

    /**
     * Ação: Processa o formulário de criação de novo evento.
     */
    public function create(): void {
        $title = $_POST['event_title'] ?? '';
        $date = $_POST['event_date'] ?? '';
        $notes = $_POST['notes'] ?? '';

        if (empty($title) || empty($date)) {
            $_SESSION['feedback'] = ['type' => 'error', 'message' => 'Título e data são obrigatórios.'];
            header('Location: /schedule');
            exit;
        }

        $event = new ScheduleEvent(
            null,
            $_SESSION['user_id'],
            $title,
            $date,
            $notes
        );

        if ($this->repository->save($event)) {
            $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Evento agendado com sucesso!'];
        } else {
            $_SESSION['feedback'] = ['type' => 'error', 'message' => 'Erro ao agendar evento.'];
        }

        header('Location: /schedule');
        exit;
    }

    /**
     * Ação: Processa a exclusão de um evento.
     */
    public function delete(): void {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->repository->delete((int)$id, $_SESSION['user_id']);
            $_SESSION['feedback'] = ['type' => 'success', 'message' => 'Evento excluído.'];
        }
        header('Location: /schedule');
        exit;
    }
}