/*
Dev: João Pedro de Oliveira - JP
Data: 02/12/2024
Coloquei algumas observações aqui no banco de dados, se der algo errado corre pro fim desse arquivo
*/

DROP DATABASE IF EXISTS nexdrone;

-- Expressão SQL para criar banco de dados
CREATE DATABASE nexdrone;

-- Expressão SQL para informar à IDE que este é o banco que estará em uso.
USE nexdrone;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL,
    senha VARCHAR(255) NOT NULL
);

-- Criando a tabela de Empresas
CREATE TABLE Empresas(
    CodEmpresa INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    NomeEmpresa VARCHAR(100) NOT NULL,
    Telefone VARCHAR(100),
    Email VARCHAR(100)
);

CREATE UNIQUE INDEX IDX_Empresas
    ON Empresas(NomeEmpresa);

-- Criando a tabela de Funcionários
CREATE TABLE Funcionarios(
    CodFuncionario INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    Funcao ENUM('piloto', 'técnico', 'administrativo') NOT NULL,
    Nome VARCHAR(100) NOT NULL,
    Telefone VARCHAR(100),
    Email VARCHAR(100)
);

CREATE UNIQUE INDEX IDX_Funcionarios_Email
    ON Funcionarios(Email);

-- Criando a tabela de drones
CREATE TABLE drones(
    CodDrone INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    CodEmpresa INT NOT NULL,
    Autonomia DECIMAL(10,2) NOT NULL,
    Disponibilidade ENUM('disponível', 'indisponível') NOT NULL DEFAULT 'disponível',
    CodFuncionarioResponsavel INT NOT NULL
);

-- Índices para Drones
CREATE INDEX IDX_Drones_CodEmpresa
    ON drones(CodEmpresa);

CREATE INDEX IDX_Drones_CodFuncionarioResponsavel
    ON drones(CodFuncionarioResponsavel);

-- Chaves estrangeiras em drones
ALTER TABLE drones ADD CONSTRAINT FK_Drones_Empresas
    FOREIGN KEY(CodEmpresa) REFERENCES Empresas(CodEmpresa);

ALTER TABLE drones ADD CONSTRAINT FK_Drones_Funcionarios
    FOREIGN KEY(CodFuncionarioResponsavel) REFERENCES Funcionarios(CodFuncionario);

-- Criando a tabela de Pedidos
CREATE TABLE Pedidos(
    CodPedido INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    CodDrone INT NOT NULL,
    CodEmpresa INT NOT NULL,
    DataPedido DATETIME NOT NULL,
    StatusPedido VARCHAR(50) NOT NULL
);

-- Índices para Pedidos
CREATE INDEX IDX_Pedidos_CodDrone
    ON Pedidos(CodDrone);

CREATE INDEX IDX_Pedidos_CodEmpresa
    ON Pedidos(CodEmpresa);

-- Chaves estrangeiras em Pedidos
ALTER TABLE Pedidos ADD CONSTRAINT FK_Pedidos_Drones
    FOREIGN KEY(CodDrone) REFERENCES drones(CodDrone);

ALTER TABLE Pedidos ADD CONSTRAINT FK_Pedidos_Empresas
    FOREIGN KEY(CodEmpresa) REFERENCES Empresas(CodEmpresa);

-- Expressão SQL para cadastrar um usuário
INSERT INTO usuarios (usuario, senha) VALUES ('admin', MD5('admin123'));

-- Obs: Adaptado para banco NexDrone conforme solicitação
