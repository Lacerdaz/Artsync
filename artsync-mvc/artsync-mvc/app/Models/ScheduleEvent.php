<?php

namespace App\Models;

/**
 * Representa a entidade de um Evento da Agenda.
 */
class ScheduleEvent {
    /**
     * @param int|null $id O ID do evento (nulo se ainda não foi salvo)
     * @param int $userId O ID do usuário dono deste evento
     * @param string $title O título do evento
     * @param string $eventDate A data e hora do evento (Formato YYYY-MM-DDTHH:MM)
     * @param string|null $notes Anotações opcionais sobre o evento
     */
    public function __construct(
        public ?int $id,
        public int $userId,
        public string $title,
        public string $eventDate, 
        public ?string $notes
    ) {}
}