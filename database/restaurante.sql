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

INSERT INTO cliente (telefone, email, nome) VALUES
('27999990001', 'carlos.silva@email.com', 'Carlos Silva'),
('27999990002', 'ana.costa@email.com', 'Ana Costa'),
('27999990003', 'marcos.oliveira@email.com', 'Marcos Oliveira'),
('27999990004', 'juliana.santos@email.com', 'Juliana Santos'),
('27999990005', 'pedro.almeida@email.com', 'Pedro Almeida');

INSERT INTO mesa (status, capacidade, numero) VALUES
('Disponível', 2, 1),
('Disponível', 2, 2),
('Ocupada', 4, 3),
('Reservada', 4, 4),
('Disponível', 6, 5),
('Disponível', 6, 6),
('Ocupada', 8, 7),
('Disponível', 8, 8),
('Reservada', 10, 9),
('Disponível', 12, 10);

INSERT INTO usuario (nome, email, senha, cargo) VALUES
('João Gerente', 'gerente@restaurante.com', '123456', 'Gerente'),
('Marina Chef', 'chef@restaurante.com', '123456', 'Chef'),
('Lucas Garçom', 'garcom1@restaurante.com', '123456', 'Garçom'),
('Fernanda Garçom', 'garcom2@restaurante.com', '123456', 'Garçom');

INSERT INTO categoria_prato (nome, descricao) VALUES
('Entradas', 'Pratos para iniciar a refeição'),
('Pratos Principais', 'Pratos principais do cardápio'),
('Sobremesas', 'Sobremesas gourmet'),
('Bebidas', 'Bebidas alcoólicas e não alcoólicas');

INSERT INTO prato (categoria_prato_id, preco, nome, ativo, descricao) VALUES
(1, 38.90, 'Bruschetta Italiana', TRUE, 'Pão artesanal com tomate e manjericão'),
(1, 45.50, 'Carpaccio de Filé', TRUE, 'Finas fatias de filé ao molho especial'),

(2, 120.00, 'Filé Mignon ao Molho Trufado', TRUE, 'Filé mignon com molho de trufas negras'),
(2, 98.50, 'Salmão Grelhado Premium', TRUE, 'Salmão com legumes salteados'),
(2, 89.90, 'Risoto de Camarão', TRUE, 'Risoto cremoso com camarões frescos'),

(3, 32.00, 'Petit Gateau', TRUE, 'Bolo quente de chocolate com sorvete'),
(3, 28.50, 'Cheesecake de Frutas Vermelhas', TRUE, 'Cheesecake artesanal'),

(4, 12.00, 'Água Mineral', TRUE, 'Água mineral sem gás'),
(4, 18.00, 'Suco Natural', TRUE, 'Suco de frutas frescas'),
(4, 45.00, 'Vinho Tinto Taça', TRUE, 'Taça de vinho tinto selecionado');

INSERT INTO reserva (
    cliente_id,
    mesa_id,
    hora_reserva,
    data_reserva,
    status,
    observacoes,
    num_pessoas
) VALUES
(1, 4, '20:00:00', '2025-08-15', 'Confirmada', 'Aniversário de casamento', 4),
(2, 9, '21:00:00', '2025-08-15', 'Pendente', NULL, 8),
(3, 5, '19:30:00', '2025-08-16', 'Confirmada', 'Mesa próxima à janela', 5);

INSERT INTO pedido (mesa_id, usuario_id) VALUES
(3, 3),
(7, 4),
(4, 3);

INSERT INTO contem (
    prato_id,
    pedido_id,
    quantidade,
    preco_unitario
) VALUES

(1, 1, 2, 38.90),
(3, 1, 2, 120.00),
(10, 1, 2, 45.00),

(2, 2, 1, 45.50),
(5, 2, 3, 89.90),
(9, 2, 3, 18.00),

(4, 3, 2, 98.50),
(6, 3, 2, 32.00),
(8, 3, 2, 12.00);