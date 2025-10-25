<?php

namespace Config;

use PDO;
use PDOException;

/**
 * Padrão de Projeto Singleton para a conexão com o banco de dados.
 * Garante que exista apenas UMA instância do PDO em toda a aplicação.
 */
class Database {
    
    private static ?PDO $instance = null;
    
    // --- CONFIGURE SEU BANCO DE DADOS AQUI ---
    private static string $host = 'localhost'; // Geralmente localhost
    private static string $db_name = 'artsync_db'; // O nome do seu banco
    private static string $username = 'root';      // Usuário do MySQL (padrão 'root')
    private static string $password = '';          // Senha do MySQL (padrão '' no WAMP/XAMPP)
    // --- FIM DA CONFIGURAÇÃO ---

    /**
     * O construtor é privado para impedir a criação de instâncias
     * com 'new Database()'.
     */
    private function __construct() {}

    /**
     * O método estático que controla o acesso à instância (conexão PDO).
     * Esta é a única forma de obter a conexão em qualquer parte do código.
     * Exemplo de uso: $db = Config\Database::getInstance();
     */
    public static function getInstance(): PDO {
        // Se a conexão ainda não foi criada...
        if (self::$instance === null) {
            try {
                // Monta a string de conexão DSN
                $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$db_name . ';charset=utf8mb4';
                // Cria a instância do PDO
                self::$instance = new PDO($dsn, self::$username, self::$password);
                // Configura o PDO para lançar exceções em caso de erro
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // Configura o PDO para retornar resultados como arrays associativos por padrão
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // Em um projeto real, logar o erro em vez de 'die'
                die('Erro de conexão com o banco de dados: ' . $e->getMessage());
            }
        }
        // Retorna a instância (nova ou existente)
        return self::$instance;
    }

    /** Impede a clonagem da instância. */
    private function __clone() {}

    /** Impede a desserialização da instância. */
    public function __wakeup() {}
}