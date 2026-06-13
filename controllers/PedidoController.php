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

    require_once __DIR__ . '/../models/Pedido.php'; // Importando o modelo Pedido

    header('Content-Type: application/json'); // Informando que a resposta vai ser JSON

    $acao = $_POST['acao'] ?? $_GET['acao'] ?? ''; // Obtendo a ação da requisição

    switch ($acao) { // Criando switch para decidir qual ação executar
        case 'listar_ativas': // Criando o caso de listar as comandas ativas
            echo json_encode(Pedido::listarAtivas()); // Retornando a lista de comandas ativas em JSON
            break; // Parando
            
        case 'listar_historico': // Criando o caso de listar o histórico de comandas
            echo json_encode(Pedido::listarHistorico()); // Retornando a lista de comandas do histórico em JSON
            break; // Parando

        case 'abrir': // Criando o caso de abrir uma nova comanda
            $mesa_id = $_POST['mesa_id'] ?? null; // Obtendo o ID da mesa
            $usuario_id = $_POST['usuario_id'] ?? null; // Obtendo o ID do usuário que está abrindo a comanda

            $novo_id = Pedido::abrirComanda($mesa_id, $usuario_id); // Abrindo a comanda
            if ($novo_id) { // Se a comanda foi aberta com sucesso...
                echo json_encode(['sucesso' => true, 'id' => $novo_id, 'mensagem' => 'Comanda aberta com sucesso.']); // Retornando a resposta em JSON com o ID da nova comanda
            } else { // Se houve um erro ao abrir a comanda...
                echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao abrir a comanda.']); // Retornando a resposta em JSON indicando o erro
            }
            break; // Parando
        
        case 'fechar': // Criando o caso de fechar a comanda
            $pedido_id = $_POST['pedido_id'] ?? 0; // Obtendo o ID da comanda que será fechada
            if (Pedido::fecharComanda($pedido_id)) { // Se a comanda foi fechada com sucesso...
                echo json_encode(['sucesso' => true, 'mensagem' => 'Comanda fechada com sucesso.']); // Retornando a resposta em JSON indicando o sucesso
            } else {
                echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao fechar a comanda.']); // Retornando a resposta em JSON indicando o erro
            }
            break; // Parando
        
        case 'adicionar_item': // Criando o caso de adicionar um item à comanda
            $pedido_id = $_POST['pedido_id'] ?? null; // Obtendo o ID da comanda que o item será adicionado
            $prato_id = $_POST['prato_id'] ?? null; // Obtendo o ID do prato que será adicionado
            $quantidade = $_POST['quantidade'] ?? 1; // Obtendo a quantidade do item que será adicionado

            if (Pedido::adicionarItem($pedido_id, $prato_id, $quantidade)) { // Se o item foi adicionado com sucesso...
                echo json_encode(['sucesso' => true, 'mensagem' => 'Item adicionado com sucesso.']); // Retornando a resposta em JSON indicando o sucesso
            } else { // Se houve um erro ao adicionar o item...
                echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao adicionar o item.']); // Retornando a resposta em JSON indicando o erro
            }
            break; // Parando
        
        case 'ver_comanda': // Criando o caso de visualizar os itens da comanda
            $pedido_id = $_GET['pedido_id'] ?? null; // Obtendo o ID da comanda que será visualizada
            $itens = Pedido::listarItensComanda($pedido_id); // Listando os itens da comanda

            $total_geral = 0; // Inicializando a variável para calcular o total geral da comanda
            foreach ($itens as $item) { // Iterando sobre os itens da comanda para calcular o total geral
                $total_geral = $total_geral + $item['subtotal']; // Somando o subtotal de cada item ao total geral
            }

            echo json_encode(['itens' => $itens, 'total_geral' => number_format($total_geral, 2, '.', '')]); // Retornando os itens da comanda e o total geral em JSON
            break; // Parando
        
        default: // Criando o caso padrão
            echo json_encode(['sucesso' => false, 'mensagem' => 'Ação inválida.']); // Retornando a resposta em JSON indicando que a ação é inválida
            break; // Parando
    }
?>