<!--
Criado por: Guilherme Villas Boas Braz
Data: 10/06/2026

Código controlador de prato

Ele recebe requisições via POST/GET,
chama o Model e retorna a resposta no formato JSON.
-->

<?php
    require_once __DIR__ . '/../models/prato.php'; // Importando o Model

    header('Content-Type: application/json'); // Informando que a resposta vai ser JSON

    $acao = $_POST['acao'] ?? $_GET['acao'] ?? ''; // Obtendo a açaõ

    switch ($acao) { // Criando switch para decidir
        case 'listar': // Criando o caso de listar
            $pratos = Prato::listar(); // Obtendo a lista de pratos
            echo json_encode($pratos); // Retornando a lista de pratos em json
            break; // Parando

        case 'cadastrar': // Criando o caso de cadastrar
            $nome = $_POST['nome'] ?? ''; // Obtendo o nome
            $preco = $_POST['preco'] ?? 0.00; // Obtendo o preço
            $categoria_id = !empty($_POST['categoria_id']) ? $_POST['categoria_id'] : null; // Obtendo a categoria do prato
            $descricao = $_POST['descricao'] ?? ''; // Obtendo a descriçaõ
            $ativo = 1; // Ativo como padrão

            if (Prato::cadastrar($nome, $preco, $categoria_id, $descricao, $ativo)) { // Verifica se o prato foi cadastrado
                echo json_encode(['sucesso' => true, 'mensagem' => 'Prato cadastrado com sucesso.']); // Retorna mensagem de sucesso em JSON
            } else { // Se não...
                echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao cadastrar.']); // Retorna mensagem de erro em JSON
            }
            break; // Parando

        case 'deletar': // Criando o cado de deletar
            $id = $_POST['id'] ?? 0; // Obtendo o id do prato

            if (Prato::deletar($id)) { // Verifica se o prato foi deletado
                echo json_encode(['sucesso' => true]); // Retorna mensagem de sucesso em JSON
            } else {
                echo json_encode(['sucesso' => false]); // Retorna mensagem de erro em JSON
            }
            break; // Parando
        
        default: // Criando caso padrão
            echo json_encode(['sucesso' => false, 'mensagem' => 'Ação não reconhecida.']); // Retorna mensagem de ação não reconhecida em JSON
            break; // Parando
    }
?>