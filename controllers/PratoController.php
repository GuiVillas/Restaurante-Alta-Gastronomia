<?php
    require_once __DIR__ . '/../models/Prato.php';

    /**
     * Controlador para gerenciar as operações relacionadas aos pratos.
     * 
     * Recebe requisições HTTP, executa as operações no modelo Prato
     * e retorna respostas em formato JSON.
     */

    // Define o cabeçalho para resposta JSON
    header('Content-Type: application/json');

    // Obtém a ação a ser executada a partir dos parâmetros GET ou POST
    $acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

    /**
     * Switch para determinar qual operação executar com base na ação recebida.
      - 'listar': Retorna a lista de todos os pratos.
      - 'cadastrar': Cadastra um novo prato com os dados fornecidos.
      - 'deletar': Deleta um prato, se possível (verifica dependências).
     */
    switch ($acao) {
        case 'listar':
            $pratos = Prato::listar();
            echo json_encode($pratos);
            break;

        case 'cadastrar':
            $nome = $_POST['nome'] ?? '';
            $preco = $_POST['preco'] ?? 0.00;
            $categoria_id = !empty($_POST['categoria_id']) ? $_POST['categoria_id'] : null;
            $descricao = $_POST['descricao'] ?? '';
            $ativo = 1;

            if (Prato::cadastrar($nome, $preco, $categoria_id, $descricao, $ativo)) {
                echo json_encode(['sucesso' => true, 'mensagem' => 'Prato cadastrado com sucesso.']);
            } else {
                echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao cadastrar.']);
            }
            break;

        case 'deletar':
            $id = $_POST['id'] ?? 0;

            if (Prato::deletar($id)) {
                echo json_encode(['sucesso' => true]);
            } else {
                echo json_encode(['sucesso' => false]);
            }
            break;
        
        default:
            echo json_encode(['sucesso' => false, 'mensagem' => 'Ação não reconhecida.']);
            break;
    }
?>