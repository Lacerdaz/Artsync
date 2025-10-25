<?php

namespace App\Models;

/**
 * Representa a entidade de um item do Portfólio.
 */
class PortfolioItem {
    /**
     * @param int|null $id O ID do item (nulo se ainda não foi salvo)
     * @param int $userId O ID do usuário dono deste item
     * @param string $title O título da mídia
     * @param string $filePath O caminho acessível pela web (ex: /uploads/img.jpg)
     * @param string|null $description Uma descrição opcional
     */
    public function __construct(
        public ?int $id,
        public int $userId,
        public string $title,
        public string $filePath, 
        public ?string $description = null
    ) {}
}