CREATE database restaurante_alta_gastronomia;

CREATE TABLE cliente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    telefone VARCHAR(100) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email VARCHAR(100) UNIQUE NOT NULL,
    nome VARCHAR(100) NOT NULL

);

CREATE TABLE reserva (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT FOREIGN KEY,
    mesa_id INT FOREIGN KEY,
    hora_reserva TIME NOT NULL,
    data_reserva DATE NOT NULL,
    status VARCHAR(100) NOT NULL,
    observacoes VARCHAR(100),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    num_pessoas INT NOT NULL

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

CREATE TABLE pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mesa_id INT FOREIGN KEY,
    usuario_id INT FOREIGN KEY,
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);

CREATE TABLE contem (
    prato_id INT FOREIGN KEY,
    pedido_id INT FOREIGN KEY,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL NOT NULL

);

CREATE TABLE prato (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_prato_id INT FOREIGN KEY,
    preco DECIMAL NOT NULL,
    nome VARCHAR(100) NOT NULL,
    ativo BOOLEAN NOT NULL,
    descricao VARCHAR(100)

);

CREATE TABLE categoria_prato (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao VARCHAR(100) UNIQUE

);