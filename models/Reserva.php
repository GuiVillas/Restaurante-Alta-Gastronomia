<?php
    /*
    Criado por: Guilherme Villas Boas Braz
    Data: 11/06/2026

    Código de modelo da reserva

    Ele conversa com o banco de dados sobre a reserva.
    */

    require_once __DIR__ . '/../config/database.php';

    class Prato {
        public static function listar() {
            $db = Database::getConnection();
            $sql = "SELECT p.id, p.nome, p.preco, p.ativo, c.nome as categoria
                    FROM prato p
                    LEFT JOIN categoria_prato c ON p.categoria_prato_id = c.id
                    ORDER BY p.id ASC";
            $stmt = $db->query($sql);
            return $stmt->fetchAll();
        }

        public static function cadastrar($nome, $preco, $categoria_id, $descricao, $ativo) {
            $db = Database::getConnection();
            $sql = "INSERT INTO prato (nome, preco, categoria_prato_id, descricao, ativo) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->query($sql);
            return $stmt->execute([$nome, $preco, $categoria_id, $descricao, $ativo]);
        }

        public static function deletar($id) {
            $db = Database::getConnection();
            $sql = "DELETE FROM prato WHERE id = ?"
            $stmt = $db->query($sql);
            return $stmt->execute([$id]);
        }
    }
?>