<?php

namespace Stack\Fest\Config;

use PDO;
use PDOException;

class Database
{
    private static $instance;
    private PDO $pdo;

    private function __construct()
    {
        // Configuração da conexão
        $host = 'localhost';
        $dbname = 'event_management';
        $username = 'root';
        $password = 'root';

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->runSqlScript();
        } catch (PDOException $e) {
            die("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }

    private function runSqlScript()
    {
        $sqlScript = file_get_contents(__DIR__ . '/../../database.sql');
        try {
            $this->pdo->exec($sqlScript);
        } catch (PDOException $e) {
            die("Erro ao executar o script SQL: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
