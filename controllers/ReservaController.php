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
        
        case 'buscar': // Caso de buscar por ID
            $id = $_GET['id'] ?? 0; // Obtendo o ID da reserva que será buscada
            echo json_encode(Reserva::buscarPorId($id)); // Retornando json com a reserva encontrada
            break; // Parando

        case 'cadastrar': // Caso de cadastrar
        case 'atualizar': // Caso de atualizar
            $id = $_POST['id'] ?? null; // Obtendo o ID da reserva
            $cliente_id = $_POST['cliente_id'] ?? null; // Obtendo o ID do cliente que fez a reserva
            $mesa_id = $_POST['mesa_id'] ?? null; // Obtendo o ID da mesa reservada
            $data = $_POST['data_reserva'] ?? ''; // Obtendo a data da reserva
            $hora = $_POST['hora_reserva'] ?? ''; // Obtendo a hora da reserva
            $pessoas = $_POST['num_pessoas'] ?? 1; // Obtendo o número de pessoas para a reserva
            $status = $_POST['status'] ?? 'Confirmada'; // Obtendo o status da reserva
            $obs = $_POST['observacoes'] ?? ''; // Obtendo as observações da reserva

            if ($acao === 'cadastrar') { // Se a ação for cadastrar...
                $sucesso = Reserva::cadastrar($cliente_id, $mesa_id, $data, $hora, $pessoas, $status, $obs); // Tentando cadastrar a reserva
                $msg = $sucesso ? 'Reserva cadastrada!' : 'Erro ao cadastrar!'; // Definindo a mensagem de acordo com o resultado do cadastro
            } else { // Se a ação for atualizar...
                $sucesso = Reserva::atualizar($id, $cliente_id, $mesa_id, $data, $hora, $pessoas, $status, $obs); // Tentando atualizar a reserva
                $msg = $sucesso ? 'Reserva atualizada!' : 'Erro ao atualizar!'; // Definindo a mensagem de acordo com o resultado da atualização
            }

            echo json_encode(['sucesso' => $sucesso, 'mensagem' => $msg]); // Retornando a resposta em JSON indicando o sucesso ou falha da operação
            break; // Parando

        case 'deletar': // Caso de deletar
            $id = $_POST['id']; // Obtendo o ID da reserva que será deletada
            $sucesso = Reserva::deletar($id); // Tentando deletar a reserva
            echo json_encode(['sucesso' => $sucesso]); // Retornando a resposta em JSON indicando o sucesso ou falha da exclusão
            break; // Parando

        default: // Criando caso padrão
            echo json_encode(['sucesso' => false, 'mensagem' => 'Ação não reconhecida.']); // Retorna json com mensagem de ação não reconhecida
            break; // Parando
    }
?>