<?php

namespace App\Models;

/**
 * Representa a entidade Usuário (artista).
 * Esta é uma classe de dados simples (DTO - Data Transfer Object).
 * Ela define a "forma" de um usuário no sistema.
 */
class User {
    /**
     * @param int|null $id O ID do usuário (nulo se ainda não foi salvo no banco)
     * @param string $artistName O nome artístico do usuário
     * @param string $email O email de login
     * @param string $password O hash da senha
     * @param bool $isAdmin Define se o usuário é um administrador
     */
    public function __construct(
        public ?int $id,
        public string $artistName,
        public string $email,
        public string $password, // Este é o hash da senha
        public bool $isAdmin = false
    ) {}
}