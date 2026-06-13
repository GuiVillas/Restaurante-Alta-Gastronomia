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

    require_once __DIR__ . '/../models/Cliente.php'; // Importando o modelo Cliente

    header('Content-Type: application/json'); // Informando que a resposta vai ser JSON

    $acao = $_POST['acao'] ?? $_GET['acao'] ?? ''; // Obtendo a ação

    switch ($acao) { // Criando switch para decidir qual ação executar
        case 'listar': // Criando o caso de listar
            echo json_encode(Cliente::listar()); // Retornando a lista de clientes em JSON
            break; // Parando
        
        case 'pesquisar': // Criando o caso de pesquisar
            $termo = $_GET['termo'] ?? $_POST['termo'] ?? ''; // Obtendo o termo de pesquisa
            echo json_encode(!empty($termo) ? Cliente::pesquisar($termo) : Cliente::listar()); // Retornando os clientes em JSON
            break; // Parando

        case 'buscar': // Criando o caso de buscar por ID
            $id = $_GET['id'] ?? $_POST['id'] ?? 0; // Obtendo o ID do cliente
            echo json_encode(Cliente::buscarPorId($id)); // Retornando o cliente em JSON
            break; // Parando

        case 'cadastrar': // Criando o caso de cadastrar
            $nome = $_POST['nome'] ?? ''; // Obtendo o nome
            $email = $_POST['email'] ?? ''; // Obtendo o email
            $telefone = $_POST['telefone'] ?? ''; // Obtendo o telefone

            try { // Tentando cadastrar o cliente
                $sucesso = Cliente::cadastrar($nome, $email, $telefone); // Verificando se o cadastro foi bem-sucedido
                $msg = $sucesso ? 'Cliente cadastrado com sucesso!' : 'Erro ao cadastrar cliente.'; // Definindo a mensagem de acordo com o resultado do cadastro
            } catch (Exception $e) { // Capturando exceção caso o email já esteja cadastrado
                $sucesso = false; // Definindo o sucesso como falso
                $msg = 'Erro: Este e-mail já está cadastrado.'; // Definindo a mensagem de erro para email já cadastrado
            }

            echo json_encode(['sucesso' => $sucesso, 'mensagem' => $msg]); // Retornando a resposta em JSON
            break; // Parando

        case 'atualizar': // Criando o caso de atualizar
            $id = $_POST['id'] ?? null; // Obtendo o ID
            $nome = $_POST['nome'] ?? ''; // Obtendo o nome
            $email = $_POST['email'] ?? ''; // Obtendo o email
            $telefone = $_POST['telefone'] ?? ''; // Obtendo o telefone

            try { // Tentando atualizar o cliente
                $sucesso = Cliente::atualizar($id, $nome, $email, $telefone); // Verificando se a atualização foi bem-sucedida
                $msg = $sucesso ? 'Cliente atualizado com sucesso!' : 'Erro ao atualizar cliente.'; // Definindo a mensagem de acordo com o resultado da atualização
            } catch (Exception $e) { // Capturando exceção caso o email já esteja cadastrado
                $sucesso = false; // Definindo o sucesso como falso
                $msg = 'Erro: Este e-mail já está cadastrado para outro cliente.'; // Definindo a mensagem de erro para email já cadastrado
            }

            echo json_encode(['sucesso' => $sucesso, 'mensagem' => $msg]); // Retornando a resposta em JSON
            break; // Parando

        case 'deletar': // Criando o caso de deletar
            $id = $_POST['id'] ?? 0; // Obtendo o ID
            $sucesso = Cliente::deletar($id); // Obtendo se a exclusão foi bem-sucedida

            if ($sucesso) { // Se a exclusão foi bem-sucedida...
                echo json_encode(['sucesso' => true, 'mensagem' => 'Cliente deletado com sucesso!']); // Retornando mensagem de sucesso em JSON
            } else { // Se a exclusão falhou...
                echo json_encode(['sucesso' => false, 'mensagem' => 'Aviso: Não é possível excluir este cliente pois ele possui reservas cadastradas.']); // Retornando mensagem de erro em JSON
            }
            break; // Parando

        default: // Criando caso padrão
            echo json_encode(['sucesso' => false, 'mensagem' => 'Ação inválida.']); // Retornando mensagem de ação inválida em JSON
            break; // Parando
    }
?>