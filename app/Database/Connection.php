<?php

namespace App\Database;

use PDO;
use PDOException;

class Connection {
    /**
     * @var PDO|null A instância única da conexão PDO (Singleton).
     */
    private static ?PDO $instance = null;

    /**
     * Obtém a instância da conexão com o banco de dados.
     * Se a conexão ainda não existir, ela será criada.
     *
     * @return PDO A instância da conexão PDO.
     */
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            // Lê as credenciais do banco de dados do arquivo .env
            $host = $_ENV['DB_HOST'] ?? 'localhost';
            $db   = $_ENV['DB_DATABASE'] ?? 'test';
            $user = $_ENV['DB_USERNAME'] ?? 'root';
            $pass = $_ENV['DB_PASSWORD'] ?? '';
            $port = $_ENV['DB_PORT'] ?? '3306';
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lança exceções em caso de erro
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retorna resultados como arrays associativos
                PDO::ATTR_EMULATE_PREPARES   => false,                  // Usa prepares nativos do MySQL
            ];

            try {
                // Cria a instância PDO
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                // Em caso de falha na conexão, encerra a aplicação e exibe o erro
                // Em produção, é recomendado logar o erro em vez de exibi-lo
                die("Erro de conexão com o banco de dados: " . $e->getMessage());
            }
        }

        return self::$instance;
    }

    /**
     * O construtor privado impede a criação de instâncias diretas (new Connection()).
     */
    private function __construct() {}

    /**
     * O método clone privado impede a clonagem da instância.
     */
    private function __clone() {}
}

