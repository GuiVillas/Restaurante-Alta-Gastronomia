<?php
    require_once __DIR__ . '/../../controllers/AuthController.php';
    AuthController::protegerPagina(); 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Reservas</title>
</head>
<body>

    <h2>Gestão de Reservas e Pesquisa (AJAX)</h2>

    <fieldset>
        <legend>Pesquisar Registros</legend>
        <input type="text" id="campoPesquisa" placeholder="Nome do cliente ou status..." size="40">
        <button type="button" onclick="pesquisarReservas()">Buscar</button>
        <button type="button" onclick="carregarReservas()">Limpar Filtro</button>
    </fieldset>

    <br>

    <table border="1" width="100%" cellpadding="5" cellspacing="0">
        <thead style="background-color: #eee;">
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Mesa</th>
                <th>Data</th>
                <th>Hora</th>
                <th>Pessoas</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="tabelaReservas">
            </tbody>
    </table>

    <script>
        function renderizarTabela(dados) {
            const tbody = document.getElementById('tabelaReservas');
            tbody.innerHTML = ''; 
            
            if (dados.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" align="center">Nenhuma reserva encontrada.</td></tr>';
                return;
            }

            dados.forEach(reserva => {
                let dataFormatada = reserva.data_reserva.split('-').reverse().join('/');
                
                tbody.innerHTML += `
                    <tr>
                        <td>${reserva.id}</td>
                        <td>${reserva.cliente_nome}</td>
                        <td>Mesa ${reserva.mesa_numero}</td>
                        <td>${dataFormatada}</td>
                        <td>${reserva.hora_reserva}</td>
                        <td>${reserva.num_pessoas}</td>
                        <td>${reserva.status}</td>
                    </tr>
                `;
            });
        }

        function carregarReservas() {
            document.getElementById('campoPesquisa').value = '';
            fetch('../../controllers/ReservaController.php?acao=listar')
                .then(response => response.json())
                .then(dados => renderizarTabela(dados));
        }

        function pesquisarReservas() {
            let termo = document.getElementById('campoPesquisa').value;
            fetch('../../controllers/ReservaController.php?acao=pesquisar&termo=' + encodeURIComponent(termo))
                .then(response => response.json())
                .then(dados => renderizarTabela(dados));
        }

        carregarReservas();
    </script>
</body>
</html>