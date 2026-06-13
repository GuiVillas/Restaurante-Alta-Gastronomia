<?php
    require_once __DIR__ . '/../config/database.php';

    /**
     * Modelo para gerenciar as operações relacionadas aos clientes.
     * 
     * Contém métodos estáticos para listar, pesquisar, buscar por ID, 
     * cadastrar, atualizar e deletar clientes no banco de dados.
     */
    class Cliente {

        /**
         * Retorna a lista de todos os clientes, ordenados por nome.
         * 
         * Utiliza uma consulta SQL simples para obter os dados da tabela cliente.
         * 
         * @return array Lista de clientes.
         */
        public static function listar() {
            $db = Database::getConnection();
            $sql = "SELECT * FROM cliente
                    ORDER BY nome ASC";
            $stmt = $db->query($sql);
            return $stmt->fetchAll();
        }
        
        /**
         * Retorna a lista de clientes que correspondem ao termo de pesquisa.
         * 
         * Realiza uma consulta SQL utilizando LIKE para buscar por nome, email ou telefone.
         * 
         * @param string $termo O termo de pesquisa para filtrar os clientes.
         * @return array Lista de clientes que correspondem ao termo.
         */
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

        /**
         * Retorna os detalhes de um cliente específico por ID.
         * 
         * Realiza uma consulta SQL para buscar um cliente com base no ID fornecido.
         * 
         * @param int $id O ID do cliente a ser buscado.
         * @return array|false Os detalhes do cliente ou false se não encontrado.
         */
        public static function buscarPorId($id) {
            $db = Database::getConnection();
            $sql = "SELECT * FROM cliente
                    WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        }

        /**
         * Cadastra um novo cliente com os dados fornecidos.
         * 
         * Realiza uma inserção SQL para adicionar um novo cliente à tabela cliente.
         * 
         * @param string $nome O nome do cliente.
         * @param string $email O e-mail do cliente.
         * @param string $telefone O telefone do cliente.
         */
        public static function cadastrar($nome, $email, $telefone) {
            $db = Database::getConnection();
            $sql = "INSERT INTO cliente (nome, email, telefone)
                    VALUES (?, ?, ?)";
            $stmt = $db->prepare($sql);
            return $stmt->execute([$nome, $email, $telefone]);
        }

        /**
         * Atualiza os dados de um cliente existente.
         * 
         * Realiza uma atualização SQL para modificar os dados de um cliente com base no ID fornecido.
         * 
         * @param int $id O ID do cliente a ser atualizado.
         * @param string $nome O novo nome do cliente.
         * @param string $email O novo e-mail do cliente.
         * @param string $telefone O novo telefone do cliente.
         */
        public static function atualizar($id, $nome, $email, $telefone) {
            $db = Database::getConnection();
            $sql = "UPDATE cliente
                    SET nome = ?, email = ?, telefone = ?
                    WHERE id = ?";
            $stmt = $db->prepare($sql);
            return $stmt->execute([$nome, $email, $telefone, $id]);
        }

        /**
         * Deleta um cliente, se possível (verifica dependências).
         * 
         * Realiza uma exclusão SQL para remover um cliente da tabela cliente.
         * 
         * Se o cliente tiver reservas ou pedidos associados, 
         * a exclusão falhará devido às restrições de chave estrangeira.
         * 
         * @param int $id O ID do cliente a ser deletado.
         * @return bool True se a exclusão foi bem-sucedida, false se fal
         */
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