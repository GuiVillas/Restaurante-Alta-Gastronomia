<?php
    require_once __DIR__ . '/../config/database.php';

    /**
     * Classe resposável pelo gerenciamento dos pedidos/comandas.
     * 
     * Contém métodos para listar comandas ativas, 
     * listar histórico de comandas, 
     * abrir comanda, 
     * fechar comanda, 
     * adicionar itens à comanda e 
     * listar itens de uma comanda.
     */
    class Pedido {

        /**
         * Retorna todas as comandas ativas.
         * 
         * @return array Lista de pedidos ativos com número da mesa e nome do garçom
         */
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

        /**
         * Retorna o histórico de comandas fechadas.
         * 
         * @return array Lista de pedidos fechados com número da mesa e nome do garçom
         */
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

        /**
         * Abre uma nova comanda para a mesa.
         * 
         * @param int #mesa_id ID da mesa.
         * @param int $usuario_id ID do garçom responsável.
         * @return int|false ID do perdido criado ou false em caso de erro.
         */
        public static function abrirComanda($mesa_id, $usuario_id) {
            $db = Database::getConnection();
            $sql = "INSERT INTO pedido (mesa_id, usuario_id) VALUES (?, ?)";
            $stmt = $db->prepare($sql);

            if ($stmt->execute([$mesa_id, $usuario_id])) {
                return $db->lastInsertId();
            }
            return false;
        }

        /**
         * Fecha uma comanda existente.
         * 
         * @param int $pedido_id ID do pedido a ser fechado.
         * @return bool True em caso de sucesso, False caso contrário.
         */
        public static function fecharComanda($pedido_id) {
            $db = Database::getConnection();
            $sql = "UPDATE pedido 
                    SET status = 'Fechada'
                    WHERE id = ?";
            $stmt = $db->prepare($sql);
            return $stmt->execute([$pedido_id]);
        }

        /**
         * Adiciona um item à comanda.
         * 
         * O preço do prato é armazenado no momento da venda para
         * preservar o histórico caso o valor do prato seja alterado futuramente.
         * 
         * @param int $pedido_id ID do pedido.
         * @param int $prato_id ID do prato.
         * @param int $quantidade Quantidade solicitada.
         * @return bool True em caso de sucesso, False caso contrário.
         */
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

        /**
         * Lista todos os itens da comanda.
         * 
         * Retorna o nome do prato, quantidade, preço unitário e subtotal.
         * 
         * @param int $pedido_id ID do pedido.
         * @return array Lista de itens da comanda.
         */
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