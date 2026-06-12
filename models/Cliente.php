<?php
    /*
    Criado por: Guilherme Villas Boas Braz
    Data: 12/06/2026

    Código de modelo para a entidade Cliente, 
    responsável por interagir com o banco de dados e realizar operações como listar, 
    pesquisar, cadastrar, atualizar e deletar clientes.
    */

    require_once __DIR__ . '/../config/database.php';

    class Cliente {
        public static function listar() {
            $db = Database::getConnection();
            $sql = "SELECT * FROM cliente
                    ORDER BY nome ASC";
            $stmt = $db->query($sql);
            return $stmt->fetchAll();
        }

        public static function pesquisar($termo) {
            $db = Database::getConnection();
            $sql = "SELECT * FROM cliente 
                    WHERE nome LIKE ? OR
                          email LIKE ? OR
                          telefone LIKE ?
                    ORDER BY nome ASC";
            $stmt = $db->prepare($sql);
            $termoLike = "%" . $termo . "%";
            $stmt->execute([$termoLike, $termoLike, $termoLike]);
            return $stmt->fetchAll();
        }

        public static function buscarPorId($id) {
            $db = Database::getConnection();
            $sql = "SELECT * FROM cliente
                    WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        }

        public static function cadastrar($nome, $email, $telefone) {
            $db = Database::getConnection();
            $sql = "INSERT INTO cliente (nome, email, telefone)
                    VALUES (?, ?, ?)";
            $stmt = $db->prepare($sql);
            return $stmt->execute([$nome, $email, $telefone]);
        }

        public static function atualizar($id, $nome, $email, $telefone) {
            $db = Database::getConnection();
            $sql = "UPDATE cliente
                    SET nome = ?, email = ?, telefone = ?
                    WHERE id = ?";
            $stmt = $db->prepare($sql);
            return $stmt->execute([$nome, $email, $telefone, $id]);
        }

        public static function deletar($id) {
            $db = Database::getConnection();
            try {
                $sql = "DELETE FROM cliente
                        WHERE id = ?";
                $stmt = $db->prepare($sql);
                return $stmt->execute([$id]);
            } catch (PDOException $e) {
                if ($e->getCode() == '23000') {
                    return false;
                }
                throw $e;
            }
        }
    }
?>