<?php
    /*
    Criado por: Guilherme Villas Boas Braz
    Data: 12/06/2026

    Código de controle para gerenciar as operações relacionadas aos clientes, 
    como listar, pesquisar, cadastrar, atualizar e deletar clientes. 
    Ele recebe as requisições via POST ou GET, 
    processa a ação solicitada e retorna uma resposta em formato JSON para o frontend. 
    O código também inclui tratamento de erros para garantir que as operações sejam realizadas de forma segura e eficiente.
    */

    require_once __DIR__ . '/../models/Cliente.php';

    header('Content-Type: application/json');

    $acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

    switch ($acao) {
        case 'listar':
            echo json_encode(Cliente::listar());
            break;
        
        case 'pesquisar':
            $termo = $_GET['termo'] ?? $_POST['termo'] ?? '';
            echo json_encode(!empty($termo) ? Cliente::pesquisar($termo) : Cliente::listar());
            break;

        case 'buscar':
            $id = $_GET['id'] ?? $_POST['id'] ?? 0;
            echo json_encode(Cliente::buscarPorId($id));
            break;

        case 'cadastrar':
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $telefone = $_POST['telefone'] ?? '';

            try {
                $sucesso = Cliente::cadastrar($nome, $email, $telefone);
                $msg = $sucesso ? 'Cliente cadastrado com sucesso!' : 'Erro ao cadastrar cliente.';
            } catch (Exception $e) {
                $sucesso = false;
                $msg = 'Erro: Este e-mail já está cadastrado.';
            }

            echo json_encode(['sucesso' => $sucesso, 'mensagem' => $msg]);
            break;

        case 'atualizar':
            $id = $_POST['id'] ?? null;
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $telefone = $_POST['telefone'] ?? '';

            try {
                $sucesso = Cliente::atualizar($id, $nome, $email, $telefone);
                $msg = $sucesso ? 'Cliente atualizado com sucesso!' : 'Erro ao atualizar cliente.';
            } catch (Exception $e) {
                $sucesso = false;
                $msg = 'Erro: Este e-mail já está cadastrado para outro cliente.';
            }

            echo json_encode(['sucesso' => $sucesso, 'mensagem' => $msg]);
            break;

        case 'deletar':
            $id = $_POST['id'] ?? 0;
            $sucesso = Cliente::deletar($id);

            if ($sucesso) {
                echo json_encode(['sucesso' => true, 'mensagem' => 'Cliente deletado com sucesso!']);
            } else {
                echo json_encode(['sucesso' => false, 'mensagem' => 'Aviso: Não é possível excluir este cliente pois ele possui reservas cadastradas.']);
            }
            break;

        default:
            echo json_encode(['sucesso' => false, 'mensagem' => 'Ação inválida.']);
            break;
    }
?>