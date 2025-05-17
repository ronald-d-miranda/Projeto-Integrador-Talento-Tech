<?php

class DatabaseConfig {
    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'db_clinica';

    private function __construct() {
        try {
            $dsnNoDb = "mysql:host={$this->host};charset=utf8mb4";
            $pdo = new PDO($dsnNoDb, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            $pdo->exec("CREATE DATABASE IF NOT EXISTS {$this->database} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            $dsn = "mysql:host={$this->host};dbname={$this->database};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);

            $this->createTables();

        } catch (PDOException $e) {
            die("Erro de conexão: " . $e->getMessage());
        }
    }

    private function createTables() {
        $sql = "
            CREATE TABLE IF NOT EXISTS tb_pessoa (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(255) NOT NULL,
                rg VARCHAR(20),
                cpf VARCHAR(14),
                data_nasc DATE,
                sexo VARCHAR(10),
                email VARCHAR(255),
                senha VARCHAR(255),
                telefone VARCHAR(20)
            );

            CREATE TABLE IF NOT EXISTS tb_medico (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_pessoa INT,
                registro_crf VARCHAR(20) NOT NULL,
                matricula VARCHAR(20),
                especializacao VARCHAR(100),
                FOREIGN KEY (id_pessoa) REFERENCES tb_pessoa(id),
                UNIQUE KEY (id_pessoa)
            );

            CREATE TABLE IF NOT EXISTS tb_paciente (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_pessoa INT,
                metodo_pagamento VARCHAR(100),
                logradouro VARCHAR(255),
                numero VARCHAR(20),
                bairro VARCHAR(100),
                cidade VARCHAR(100),
                uf VARCHAR(2),
                FOREIGN KEY (id_pessoa) REFERENCES tb_pessoa(id),
                UNIQUE KEY (id_pessoa)
            );

            CREATE TABLE IF NOT EXISTS tb_consulta (
                id INT AUTO_INCREMENT PRIMARY KEY,
                data DATE NOT NULL,
                id_medico INT,
                id_paciente INT,
                diagnostico TEXT,
                receita TEXT,
                presencial BOOLEAN NOT NULL,
                FOREIGN KEY (id_medico) REFERENCES tb_medico(id),
                FOREIGN KEY (id_paciente) REFERENCES tb_paciente(id)
            );

            CREATE TABLE IF NOT EXISTS tb_exame (
                id INT AUTO_INCREMENT PRIMARY KEY,
                data DATE NOT NULL,
                id_paciente INT,
                id_consulta INT,
                tipo VARCHAR(100) NOT NULL,
                resultado TEXT,
                FOREIGN KEY (id_paciente) REFERENCES tb_paciente(id),
                FOREIGN KEY (id_consulta) REFERENCES tb_consulta(id)
            );
        ";

        $this->conn->exec($sql);
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new DatabaseConfig();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>
