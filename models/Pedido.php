<?php
    /*
    Criado por: Guilherme Villas Boas Braz
    Data: 12/06/2026

    Código de modelo de Pedido
    */

    require_once __DIR__ . '/../config/database.php';

    class Pedido {
        public static function listarAtivas() {
            $db = Database::getConnection();
            $sql = "SELECT p.id, p.data_pedido, m.numero AS mesa_numero, u.nome AS garcom_nome
                    FROM pedido p
                    INNER JOIN mesa m ON p.mesa_id = m.id
                    INNER JOIN usuario u ON p.usuario_id = u.id
                    WHERE p.status = 'Ativa'
                    ORDER BY p.id ASC";
            $stmt = $db->query($sql);
            return $stmt->fetchAll();
        }

        public static function listarHistorico() {
            $db = Database::getConnection();
            $sql = "SELECT p.id, p.data_pedido, m.numero AS mesa_numero, u.nome AS garcom_nome
                    FROM pedido p
                    INNER JOIN mesa m ON p.mesa_id = m.id
                    INNER JOIN usuario u ON p.usuario_id = u.id
                    WHERE p.status = 'Fechada'
                    ORDER BY p.id ASC";
            $stmt = $db->query($sql);
            return $stmt->fetchAll();
        }

        public static function abrirComanda($mesa_id, $usuario_id) {
            $db = Database::getConnection();
            $sql = "INSERT INTO pedido (mesa_id, usuario_id) VALUES (?, ?)";
            $stmt = $db->prepare($sql);

            if ($stmt->execute([$mesa_id, $usuario_id])) {
                return $db->lastInsertId();
            }
            return false;
        }

        public static function fecharComanda($pedido_id) {
            $db = Database::getConnection();
            $sql = "UPDATE pedido 
                    SET status = 'Fechada'
                    WHERE id = ?";
            $stmt = $db->prepare($sql);
            return $stmt->execute([$pedido_id]);
        }

        public static function adicionarItem($pedido_id, $prato_id, $quantidade) {
            $db = Database::getConnection();

            $stmtPrato = $db->prepare('SELECT preco FROM prato WHERE id = ? LIMIT 1');
            $stmtPrato->execute([$prato_id]);
            $prato = $stmtPrato->fetch();

            if (!$prato) {
                return false;
            }

            $preco_unitario = $prato['preco'];

            $sql = 'INSERT INTO prato_pedido (pedido_id, prato_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)';
            $stmt = $db->prepare($sql);
            return $stmt->execute([$pedido_id, $prato_id, $quantidade, $preco_unitario]);
        }

        public static function listarItensComanda($pedido_id) {
            $db = Database::getConnection();
            $sql = 'SELECT pp.id, pr.nome AS prato_nome, pp.quantidade, pp.preco_unitario, (pp.quantidade * pp.preco_unitario) AS subtotal
                    FROM prato_pedido pp
                    INNER JOIN prato pr ON pp.prato_id = pr.id
                    WHERE pp.pedido_id = ?';
            $stmt = $db->prepare($sql);
            $stmt->execute([$pedido_id]);
            return $stmt->fetchAll();
        }
    }
?>