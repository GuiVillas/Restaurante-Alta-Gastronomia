<?php
    require_once __DIR__ . '/../models/Pedido.php';

    /**
     * Controlador para gerenciar as operações relacionadas aos pedidos (comandas).
     * 
     * Recebe requisições HTTP, executa as operações no modelo Pedido
     * e retorna respostas em formato JSON.
     */

    // Define o cabeçalho para resposta JSON
    header('Content-Type: application/json');

    // Obtém a ação a ser executada a partir dos parâmetros GET ou POST
    $acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

    /**
     * Switch para determinar qual operação executar com base na ação recebida.
      - 'listar_ativas': Retorna a lista de comandas ativas.
      - 'listar_historico': Retorna o histórico de comandas fechadas.
      - 'abrir': Abre uma nova comanda para uma mesa e usuário específicos.
      - 'fechar': Fecha uma comanda existente.
      - 'adicionar_item': Adiciona um item a uma comanda ativa.
      - 'ver_comanda': Retorna os itens e total de uma comanda específica.
     */
    switch ($acao) {
        case 'listar_ativas':
            echo json_encode(Pedido::listarAtivas());
            break;
            
        case 'listar_historico':
            echo json_encode(Pedido::listarHistorico());
            break;

        case 'abrir':
            $mesa_id = $_POST['mesa_id'] ?? null;
            $usuario_id = $_POST['usuario_id'] ?? null;

            $novo_id = Pedido::abrirComanda($mesa_id, $usuario_id);
            if ($novo_id) {
                echo json_encode(['sucesso' => true, 'id' => $novo_id, 'mensagem' => 'Comanda aberta com sucesso.']);
            } else {
                echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao abrir a comanda.']);
            }
            break;
        
        case 'fechar':
            $pedido_id = $_POST['pedido_id'] ?? 0;
            if (Pedido::fecharComanda($pedido_id)) {
                echo json_encode(['sucesso' => true, 'mensagem' => 'Comanda fechada com sucesso.']);
            } else {
                echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao fechar a comanda.']);
            }
            break;
        
        case 'adicionar_item':
            $pedido_id = $_POST['pedido_id'] ?? null;
            $prato_id = $_POST['prato_id'] ?? null;
            $quantidade = $_POST['quantidade'] ?? 1;

            if (Pedido::adicionarItem($pedido_id, $prato_id, $quantidade)) {
                echo json_encode(['sucesso' => true, 'mensagem' => 'Item adicionado com sucesso.']);
            } else {
                echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao adicionar o item.']);
            }
            break;
        
        case 'ver_comanda':
            $pedido_id = $_GET['pedido_id'] ?? null;
            $itens = Pedido::listarItensComanda($pedido_id);

            $total_geral = 0;
            foreach ($itens as $item) {
                $total_geral = $total_geral + $item['subtotal'];
            }

            echo json_encode(['itens' => $itens, 'total_geral' => number_format($total_geral, 2, '.', '')]);
            break;
        
        default:
            echo json_encode(['sucesso' => false, 'mensagem' => 'Ação inválida.']);
            break;
    }
?>