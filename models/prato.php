<?php
    require_once __DIR__ . "/../config/database.php";

    /**
     * Modelo para gerenciar as operações relacionadas aos pratos.
     * 
     * Contém métodos estáticos para listar, 
     * cadastrar e deletar pratos no banco de dados.
     */
    class Prato {

        /**
         * Retorna a lista de todos os pratos, ordenados por ID.
         * 
         * Utiliza uma consulta SQL com JOIN para obter os dados da tabela prato 
         * e a categoria correspondente.
         * 
         * @return array Lista de pratos com suas categorias.
         */
        public static function listar() {
            $db = Database::getConnection();
            $sql = "SELECT p.id, p.nome, p.preco, p.ativo, c.nome as categoria
                    FROM prato p LEFT JOIN categoria_prato c ON p.categoria_prato_id = c.id
                    ORDER BY p.id ASC";
            $stmt = $db->query($sql);
            return $stmt->fetchAll();
        }

        /**
         * Cadastra um novo prato com os dados fornecidos.
         * 
         * Realiza uma inserção SQL para adicionar um novo prato à tabela prato.
         * 
         * @param string $nome O nome do prato.
         * @param float $preco O preço do prato.
         * @param int $categoria_id O ID da categoria do prato.
         * @param string $descricao A descrição do prato.
         * @param bool $ativo O status de ativação do prato.
         */
        public static function cadastrar($nome, $preco, $categoria_id, $descricao, $ativo) {
            $db = Database::getConnection();
            $sql = "INSERT INTO prato (nome, preco, categoria_prato_id, descricao, ativo)
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            return $stmt->execute([$nome, $preco, $categoria_id, $descricao, $ativo]);
        }

        /**
         * Deleta um prato, se possível (verifica dependências).
         * 
         * Realiza uma exclusão SQL para remover um prato da tabela prato,
         * verificando se não há dependências em outras tabelas (ex: itens de pedido).
         * 
         * @param int $id O ID do prato a ser deletado.
         * @return bool Retorna true se o prato foi deletado, false caso contrário.
         */
        public static function deletar($id) {
            $db = Database::getConnection();
            $sql = "DELETE FROM prato WHERE id = ?";
            $stmt = $db->prepare($sql);
            return $stmt->execute([$id]);
        }
    }
?>