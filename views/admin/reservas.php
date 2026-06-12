<?php
// views/admin/reservas.php
// require_once __DIR__ . '/../../controllers/AuthController.php';
// AuthController::protegerPagina(); 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Reservas</title>
</head>
<body>

    <h2>Gestão de Reservas</h2>

    <fieldset>
        <legend id="tituloForm">Nova Reserva</legend>
        <form id="formReserva">
            <input type="hidden" name="id" id="id"> <label>ID Cliente:</label>
            <input type="number" name="cliente_id" id="cliente_id" required>
            
            <label>ID Mesa:</label>
            <input type="number" name="mesa_id" id="mesa_id" required><br><br>

            <label>Data:</label>
            <input type="date" name="data_reserva" id="data_reserva" required>
            
            <label>Hora:</label>
            <input type="time" name="hora_reserva" id="hora_reserva" required><br><br>

            <label>Nº Pessoas:</label>
            <input type="number" name="num_pessoas" id="num_pessoas" required>
            
            <label>Status:</label>
            <select name="status" id="status">
                <option value="Confirmada">Confirmada</option>
                <option value="Cancelada">Cancelada</option>
                <option value="Concluída">Concluída</option>
            </select><br><br>

            <label>Observações:</label><br>
            <textarea name="observacoes" id="observacoes" rows="3" cols="40"></textarea><br><br>

            <button type="submit" id="btnSalvar">Salvar Reserva</button>
            <button type="button" onclick="limparFormulario()">Cancelar Edição</button>
        </form>
    </fieldset>

    <br>

    <fieldset>
        <legend>Pesquisar</legend>
        <input type="text" id="campoPesquisa" placeholder="Nome do cliente ou status...">
        <button type="button" onclick="pesquisarReservas()">Buscar</button>
        <button type="button" onclick="carregarReservas()">Ver Todos</button>
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
                <th>Ações</th> </tr>
        </thead>
        <tbody id="tabelaReservas"></tbody>
    </table>

    <script>
        function renderizarTabela(dados) {
            const tbody = document.getElementById('tabelaReservas');
            tbody.innerHTML = ''; 
            
            if (dados.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" align="center">Nenhuma reserva encontrada.</td></tr>';
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
                        <td>
                            <button onclick="editarReserva(${reserva.id})">Editar</button>
                            <button onclick="deletarReserva(${reserva.id})">Excluir</button>
                        </td>
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

        document.getElementById('formReserva').addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            
            let acao = document.getElementById('id').value ? 'atualizar' : 'cadastrar';
            formData.append('acao', acao);

            fetch('../../controllers/ReservaController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.mensagem);
                if (data.sucesso) {
                    limparFormulario();
                    carregarReservas();
                }
            });
        });

        function editarReserva(id) {
            fetch('../../controllers/ReservaController.php?acao=buscar&id=' + id)
                .then(response => response.json())
                .then(dados => {
                    // Preenche o formulário com os dados vindos do banco
                    document.getElementById('id').value = dados.id;
                    document.getElementById('cliente_id').value = dados.cliente_id;
                    document.getElementById('mesa_id').value = dados.mesa_id;
                    document.getElementById('data_reserva').value = dados.data_reserva;
                    document.getElementById('hora_reserva').value = dados.hora_reserva;
                    document.getElementById('num_pessoas').value = dados.num_pessoas;
                    document.getElementById('status').value = dados.status;
                    document.getElementById('observacoes').value = dados.observacoes;
                    
                    document.getElementById('tituloForm').innerText = "Editar Reserva #" + dados.id;
                    document.getElementById('btnSalvar').innerText = "Atualizar Reserva";
                    window.scrollTo(0, 0);
                });
        }

        function limparFormulario() {
            document.getElementById('formReserva').reset();
            document.getElementById('id').value = '';
            document.getElementById('tituloForm').innerText = "Nova Reserva";
            document.getElementById('btnSalvar').innerText = "Salvar Reserva";
        }

        function deletarReserva(id) {
            if(confirm('Tem certeza que deseja excluir esta reserva?')) {
                let formData = new FormData();
                formData.append('acao', 'deletar');
                formData.append('id', id);

                fetch('../../controllers/ReservaController.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.sucesso) {
                        carregarReservas();
                    } else {
                        alert('Erro ao excluir.');
                    }
                });
            }
        }

        carregarReservas();
    </script>
</body>
</html>