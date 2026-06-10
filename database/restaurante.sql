CREATE DATABASE IF NOT EXISTS restaurante_alta_gastronomia;

USE restaurante_alta_gastronomia;

CREATE TABLE cliente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    telefone VARCHAR(100) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email VARCHAR(100) UNIQUE NOT NULL,
    nome VARCHAR(100) NOT NULL
);

CREATE TABLE mesa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status VARCHAR(100) NOT NULL,
    capacidade INT NOT NULL,
    numero INT NOT NULL
);

CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(256) NOT NULL,
    cargo VARCHAR(100) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categoria_prato (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao VARCHAR(255) UNIQUE
);

CREATE TABLE reserva (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    mesa_id INT,
    hora_reserva TIME NOT NULL,
    data_reserva DATE NOT NULL,
    status VARCHAR(100) NOT NULL,
    observacoes VARCHAR(255),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    num_pessoas INT NOT NULL,
    FOREIGN KEY (cliente_id) REFERENCES cliente(id),
    FOREIGN KEY (mesa_id) REFERENCES mesa(id)
);

CREATE TABLE prato (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_prato_id INT,
    preco DECIMAL(10, 2) NOT NULL,
    nome VARCHAR(100) NOT NULL,
    ativo BOOLEAN NOT NULL,
    descricao VARCHAR(255),
    FOREIGN KEY (categoria_prato_id) REFERENCES categoria_prato(id)
);

CREATE TABLE pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mesa_id INT,
    usuario_id INT,
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (mesa_id) REFERENCES mesa(id),
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE contem (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prato_id INT,
    pedido_id INT,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (prato_id) REFERENCES prato(id),
    FOREIGN KEY (pedido_id) REFERENCES pedido(id)
);