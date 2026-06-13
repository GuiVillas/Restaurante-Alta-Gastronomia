<?php
    /*
    Criado por: Guilherme Villas Boas Braz
    Data: 12/06/2026

    Código de modelo para a entidade Cliente, 
    responsável por interagir com o banco de dados e realizar operações como listar, 
    pesquisar, cadastrar, atualizar e deletar clientes.
    */

    require_once __DIR__ . '/../config/database.php'; // Importando a classe de conexão com o banco de dados

    class Cliente { // Criando a classe Cliente
        public static function listar() { // Criando o método para listar os clientes
            $db = Database::getConnection(); // Obtendo a conexão com o banco de dados
            $sql = "SELECT * FROM cliente
                    ORDER BY nome ASC"; // Criando a consulta SQL para selecionar todos os clientes ordenados por nome
            $stmt = $db->query($sql); // Executando a consulta e obtendo o resultado
            return $stmt->fetchAll(); // Retornando o resultado como um array de clientes
        }

        public static function pesquisar($termo) { // Criando o método para pesquisar clientes por nome, email ou telefone
            $db = Database::getConnection(); // Obtendo a conexão com o banco de dados
            $sql = "SELECT * FROM cliente 
                    WHERE nome LIKE ? OR
                          email LIKE ? OR
                          telefone LIKE ?
                    ORDER BY nome ASC"; // Criando a consulta SQL para selecionar os clientes que correspondem ao termo de pesquisa, ordenados por nome
            $stmt = $db->prepare($sql); // Preparando a consulta para evitar SQL injection
            $termoLike = "%" . $termo . "%"; // Criando o termo de pesquisa com curingas para usar no LIKE
            $stmt->execute([$termoLike, $termoLike, $termoLike]); // Executando a consulta com os parâmetros de pesquisa
            return $stmt->fetchAll(); // Retornando o resultado como um array de clientes que correspondem ao termo de pesquisa
        }

        public static function buscarPorId($id) { // Criando o método para buscar um cliente por ID
            $db = Database::getConnection(); // Obtendo a conexão com o banco de dados
            $sql = "SELECT * FROM cliente
                    WHERE id = ?"; // Criando a consulta SQL para selecionar o cliente com o ID especificado
            $stmt = $db->prepare($sql); // Preparando a consulta para evitar SQL injection
            $stmt->execute([$id]); // Executando a consulta com o ID do cliente
            return $stmt->fetch(); // Retornando o resultado como um array associativo com os dados do cliente encontrado
        }

        public static function cadastrar($nome, $email, $telefone) { // Criando o método para cadastrar um novo cliente
            $db = Database::getConnection(); // Obtendo a conexão com o banco de dados
            $sql = "INSERT INTO cliente (nome, email, telefone)
                    VALUES (?, ?, ?)"; // Criando a consulta SQL para inserir um novo cliente com os dados fornecidos
            $stmt = $db->prepare($sql); // Preparando a consulta para evitar SQL injection
            return $stmt->execute([$nome, $email, $telefone]); // Executando a consulta com os dados do novo cliente e retornando o resultado da operação
        }

        public static function atualizar($id, $nome, $email, $telefone) { // Criando o método para atualizar os dados de um cliente existente
            $db = Database::getConnection(); // Obtendo a conexão com o banco de dados
            $sql = "UPDATE cliente
                    SET nome = ?, email = ?, telefone = ?
                    WHERE id = ?"; // Criando a consulta SQL para atualizar os dados do cliente com o ID especificado
            $stmt = $db->prepare($sql); // Preparando a consulta para evitar SQL injection
            return $stmt->execute([$nome, $email, $telefone, $id]); // Executando a consulta com os dados atualizados do cliente e retornando o resultado da operação
        }

        public static function deletar($id) { // Criando o método para deletar um cliente por ID
            $db = Database::getConnection(); // Obtendo a conexão com o banco de dados
            try { // Tentando deletar o cliente
                $sql = "DELETE FROM cliente
                        WHERE id = ?"; // Criando a consulta SQL para deletar o cliente com o ID especificado
                $stmt = $db->prepare($sql); // Preparando a consulta para evitar SQL injection
                return $stmt->execute([$id]); // Executando a consulta com o ID do cliente e retornando o resultado da operação
            } catch (PDOException $e) { // Capturando exceção caso haja um erro ao tentar deletar o cliente
                if ($e->getCode() == '23000') { // Código de erro para violação de chave estrangeira
                    return false; // Retornando falso para indicar que o cliente não pode ser deletado devido a restrições de integridade referencial
                }
                throw $e; // Re-throwing a exceção para ser tratada em outro lugar, caso seja um erro diferente
            }
        }
    }
?>