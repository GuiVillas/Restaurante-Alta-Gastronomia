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
            echo json_encode($reservas) // Retorna json com as reservas
            break; // Parando

        default: // Criando caso padrão
            echo json_encode(['sucesso' => false, 'mensagem' => 'Ação não reconhecida.']); // Retorna json com mensagem de ação não reconhecida
            break; // Parando
    }
?>