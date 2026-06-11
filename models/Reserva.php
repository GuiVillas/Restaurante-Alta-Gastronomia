<?php
    /*
    Criado por: Guilherme Villas Boas Braz
    Data: 11/06/2026

    Código de modelo da reserva

    Ele conversa com o banco de dados sobre a reserva.
    */

    require_once __DIR__ . '/../config/database.php'; // Importando o arquivo de conexão com o banco de dados

    class Reserva { // Criando classe Reserva
        public static function listar() { // Criando método para listar as reservas
            $db = Database::getConnection(); // Obtendo conexão com o banco de dados
            $sql = "SELECT r.id, r.data_reserva, r.hora_reserva, r.num_pessoas, r.status, 
                        c.nome AS cliente_nome, m.numero AS mesa_numero 
                    FROM reserva r
                    INNER JOIN cliente c ON r.cliente_id = c.id
                    INNER JOIN mesa m ON r.mesa_id = m.id
                    ORDER BY r.data_reserva ASC, r.hora_reserva ASC"; // Criando a string do comando de consulta
            $stmt = $db->query($sql); // Executando o a consulta
            return $stmt->fetchAll(); // Retornando os registros da consulta
        }

        public static function pesquisar($termo) { // Criando método para pesquisar reserva
            $db = Database::getConnection(); // Obtendo conexão com o banco de dados
            $sql = "SELECT r.id, r.data_reserva, r.hora_reserva, r.num_pessoas, r.status,
                        c.nome AS cliente_nome, m.numero AS mesa_numero 
                    FROM reserva r
                    INNER JOIN cliente c ON r.cliente_id = c.id
                    INNER JOIN mesa m ON r.mesa_id = m.id
                    WHERE c.nome LIKE ? OR r.status LIKE ?
                    ORDER BY r.data_reserva ASC"; // Criando a string do comando de consulta
            $stmt = $db->prepare($sql); // Preparando o comando de consulta
            $termoLike = "%" . $termo . "%"; // Preparando o critério de pesquisa com qualquer coisa antes ou depois
            $stmt->execute([$termoLike, $termoLike]); // Executando o comando de consulta
            return $stmt->fetchAll(); // Retornando os registros da consulta
        }

        public static function buscarPorId($id) {
            $db = Database::getConnection();
            $sql = "SELECT * FROM reserva 
                    WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        }

        public static function cadastrar($cliente_id, $mesa_id, $data, $hora, $pessoas, $status, $obs) {
            $db = Database::getConnection();
            $sql = "INSERT INTO reserva (cliente_id, mesa_id, data_reserva, hora_reserva, num_pessoas, status, observacoes)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            return $stmt->execute([$cliente_id, $mesa_id, $data, $hora, $pessoas, $status, $obs]);
        }

        public static function atualizar($id, $cliente_id, $mesa_id, $data, $hora, $pessoas, $status, $obs) {
            $db = Database::getConnection();
            $sql = "UPDATE reserva
                    SET cliente_id = ?, mesa_id = ?, data_reserva =?, hora_reserva = ?, num_pessoas = ?, status = ?, observacoes = ?
                    WHERE id = ?";
            $stmt = $db->prepare($sql);
            return $stmt->execute([$cliente_id, $mesa_id, $data, $hora, $pessoas, $status, $obs, $id]);
        }

        public static function deletar($id) {
            $db = Database::getConnection();
            $sql = "DELETE FROM reserva
                    WHERE id = ?";
            $stmt = $db->prepare($sql);
            return $stmt->execute([$id]);
        }
    }
?>