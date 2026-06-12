<?php
    /*
    Criado por: Guilherme Villas Boas Braz
    Data: 11/06/2026

    Código de controle de reserva.

    Ele recebe as requisições do front-end e
    decidi se vai listar tudo ou fazer uma busca filtrada.
    */

    require_once __DIR__ . '/../models/Reserva.php'; // Importado o Model de Reserva

    header('Content-Type: application/json'); // Informando que a resposta vai ser JSON

    $acao = $_POST['acao'] ?? $_GET['acao'] ?? ''; // Obtendo a ação que foi requisitada

    switch ($acao) { // Criando switch para decidir
        case 'listar': // Caso de listar
            $reservas = Reserva::listar(); // Obtendo lista das reservas
            echo json_encode($reservas); // Retornando json com as reservas
            break; // Parando

        case 'pesquisar': // Caso de pesquisar
            $termo = $_GET['termo'] ?? ''; // Obtendo o filtro
            if (!empty($termo)) { // Verificando se o filtro não está vazio
                $reservas = Reserva::pesquisar($termo); // Retornando reservas com o filtro
            } else { // Se não...
                $reservas = Reserva::listar(); // Retorna lista completa de reservas
            }
            echo json_encode($reservas); // Retorna json com as reservas
            break; // Parando
        
        case 'buscar':
            $id = $_GET['id'] ?? 0;
            echo json_encode(Reserva::buscarPorId($id));
            break;

        case 'cadastrar':
        case 'atualizar':
            $id = $_POST['id'] ?? null;
            $cliente_id = $_POST['cliente_id'] ?? null;
            $mesa_id = $_POST['mesa_id'] ?? null;
            $data = $_POST['data_reserva'] ?? '';
            $hora = $_POST['hora_reserva'] ?? '';
            $pessoas = $_POST['num_pessoas'] ?? 1;
            $status = $_POST['status'] ?? 'Confirmada';
            $obs = $_POST['observacoes'] ?? '';

            if ($acao === 'cadastrar') {
                $sucesso = Reserva::cadastrar($cliente_id, $mesa_id, $data, $hora, $pessoas, $status, $obs);
                $msg = $sucesso ? 'Reserva cadastrada!' : 'Erro ao cadastrar!';
            } else {
                $sucesso = Reserva::atualizar($id, $cliente_id, $mesa_id, $data, $hora, $pessoas, $status, $obs);
                $msg = $sucesso ? 'Reserva atualizada!' : 'Erro ao atualizar!';
            }

            echo json_encode(['sucesso' => $sucesso, 'mensagem' => $msg]);
            break;

        case 'deletar':
            $id = $_POST['id'];
            $sucesso = Reserva::deletar($id);
            echo json_encode(['sucesso' => $sucesso]);
            break;

        default: // Criando caso padrão
            echo json_encode(['sucesso' => false, 'mensagem' => 'Ação não reconhecida.']); // Retorna json com mensagem de ação não reconhecida
            break; // Parando
    }
?>