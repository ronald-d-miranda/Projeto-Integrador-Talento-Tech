-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS sistema_medico;
USE sistema_medico;

-- Tabela pessoa (classe base)
CREATE TABLE IF NOT EXISTS pessoa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    rg VARCHAR(20) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    data_nascimento DATE NOT NULL,
    sexo ENUM('M', 'F', 'Outro') NOT NULL,
    email VARCHAR(255) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    tipo ENUM('paciente', 'medico') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela paciente (herda de pessoa)
CREATE TABLE IF NOT EXISTS paciente (
    id INT PRIMARY KEY,
    metodo_pagamento VARCHAR(50),
    logradouro VARCHAR(255) NOT NULL,
    numero VARCHAR(10) NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    uf CHAR(2) NOT NULL,
    FOREIGN KEY (id) REFERENCES pessoa(id) ON DELETE CASCADE
);

-- Tabela médico (herda de pessoa)
CREATE TABLE IF NOT EXISTS medico (
    id INT PRIMARY KEY,
    crm VARCHAR(20) NOT NULL UNIQUE,
    matricula VARCHAR(20) NOT NULL,
    especializacao VARCHAR(100) NOT NULL,
    FOREIGN KEY (id) REFERENCES pessoa(id) ON DELETE CASCADE
);

-- Tabela consulta
CREATE TABLE IF NOT EXISTS consulta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data DATETIME NOT NULL,
    medico_id INT NOT NULL,
    paciente_id INT NOT NULL,
    diagnostico TEXT,
    receita TEXT,
    presencial BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (medico_id) REFERENCES medico(id),
    FOREIGN KEY (paciente_id) REFERENCES paciente(id)
);

-- Tabela exame
CREATE TABLE IF NOT EXISTS exame (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    data DATE NOT NULL,
    tipo VARCHAR(100) NOT NULL,
    resultado TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (paciente_id) REFERENCES paciente(id)
);

-- Índices para melhorar a performance
CREATE INDEX idx_pessoa_email ON pessoa(email);
CREATE INDEX idx_pessoa_cpf ON pessoa(cpf);
CREATE INDEX idx_medico_crm ON medico(crm);
CREATE INDEX idx_consulta_data ON consulta(data);
CREATE INDEX idx_consulta_medico ON consulta(medico_id);
CREATE INDEX idx_consulta_paciente ON consulta(paciente_id);
CREATE INDEX idx_exame_paciente ON exame(paciente_id);
CREATE INDEX idx_exame_data ON exame(data);

