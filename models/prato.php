<!--
Criado por: Guilherme Villas Boas Braz
Data: 10/06/2026

Código de modelo do prato

Ele conversa com o banco de dados sobre o prato.
-->

<?php
    require_once __DIR__ . "/../config/database.php"; // Importando o arquivo de conexão com o banco de dados

    class Prato { // Criando uma classe prato
        public static function listar() { // Criando método para listar os pratos
            $db = Database::getConnection(); // Obtendo conexão com o banco de dados
            $sql = "SELECT p.id, p.nome, p.preco, p.ativo, c.ativo, c.nome as categoria
                    FROM prato p LEFT JOIN categoria_prato c ON p.categoria_prato_id = c.id
                    ORDER BY p.id DESC"; // Criando string do comando de consulta
            $stmt = $db->query($sql); // Executando consulta
            return $stmt->fetchAll(); // Retornando os registros
        }

        public static function cadastrar($nome, $preco, $categoria_id, $descricao, $ativo) { // Criando método para cadatrar prato
            $db = Database::getConnection(); // Obtendo conexão com o banco de dados
            $sql = "INSERT INTO prato (nome, preco, categoria_prato_id, descricao, ativo)
                    VALUES (?, ?, ?, ?, ?)"; // Criando string do comando de inserção
            $stmt = $db->prepare($sql); // Preparando a inserção
            return $stmt->execute([$nome, $preco, $categoria_id, $descricao, $ativo]); // Retorna a execução da inserção
        }

        public static function deletar($id) { // Criando método para deletar prato
            $db = Database::getConnection(); // Obtendo conexão com o banco de dados
            $sql = "DELETE FROM prato WHERE id = ?"; // Criando string do comando de exclusão
            $stmt = $db->prepare($sql); // Preparando comando de exclusão
            return $stmt->execute([$id]); // Retornando a execução da exclusão
        }
    }
?>