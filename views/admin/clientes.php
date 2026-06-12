<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Clientes</title>
</head>
<body>

    <h2>Cadastro de Clientes</h2>

    <fieldset>
        <legend id="tituloForm">Novo Cliente</legend>
        <form id="formCliente">
            <input type="hidden" name="id" id="id">
            
            <label>Nome Completo:</label><br>
            <input type="text" name="nome" id="nome" required size="40"><br><br>
            
            <label>E-mail:</label><br>
            <input type="email" name="email" id="email" required size="40"><br><br>

            <label>Telefone:</label><br>
            <input type="text" name="telefone" id="telefone" required size="20" placeholder="(XX) XXXXX-XXXX"><br><br>

            <button type="submit" id="btnSalvar">Salvar Cliente</button>
            <button type="button" onclick="limparFormulario()">Cancelar Edição</button>
        </form>
    </fieldset>

    <br>

    <fieldset>
        <legend>Pesquisar Cliente</legend>
        <input type="text" id="campoPesquisa" placeholder="Nome, email ou telefone..." size="40">
        <button type="button" onclick="pesquisarClientes()">Buscar</button>
        <button type="button" onclick="carregarClientes()">Ver Todos</button>
    </fieldset>

    <br>

    <table border="1" width="100%" cellpadding="5" cellspacing="0">
        <thead style="background-color: #eee;">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>Cadastrado em</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="tabelaClientes"></tbody>
    </table>

    <script>
        function renderizarTabela(dados) {
            const tbody = document.getElementById('tabelaClientes');
            tbody.innerHTML = ''; 
            
            if (dados.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" align="center">Nenhum cliente encontrado.</td></tr>';
                return;
            }

            dados.forEach(cliente => {
                let dataCriacao = cliente.criado_em.split(' ')[0].split('-').reverse().join('/');
                
                tbody.innerHTML += `
                    <tr>
                        <td>${cliente.id}</td>
                        <td>${cliente.nome}</td>
                        <td>${cliente.email}</td>
                        <td>${cliente.telefone}</td>
                        <td>${dataCriacao}</td>
                        <td>
                            <button onclick="editarCliente(${cliente.id})">Editar</button>
                            <button onclick="deletarCliente(${cliente.id})">Excluir</button>
                        </td>
                    </tr>
                `;
            });
        }

        function carregarClientes() {
            document.getElementById('campoPesquisa').value = '';
            fetch('../../controllers/ClienteController.php?acao=listar')
                .then(response => response.json())
                .then(dados => renderizarTabela(dados));
        }

        function pesquisarClientes() {
            let termo = document.getElementById('campoPesquisa').value;
            fetch('../../controllers/ClienteController.php?acao=pesquisar&termo=' + encodeURIComponent(termo))
                .then(response => response.json())
                .then(dados => renderizarTabela(dados));
        }

        document.getElementById('formCliente').addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let acao = document.getElementById('id').value ? 'atualizar' : 'cadastrar';
            formData.append('acao', acao);

            fetch('../../controllers/ClienteController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.mensagem);
                if (data.sucesso) {
                    limparFormulario();
                    carregarClientes();
                }
            });
        });

        function editarCliente(id) {
            fetch('../../controllers/ClienteController.php?acao=buscar&id=' + id)
                .then(response => response.json())
                .then(dados => {
                    document.getElementById('id').value = dados.id;
                    document.getElementById('nome').value = dados.nome;
                    document.getElementById('email').value = dados.email;
                    document.getElementById('telefone').value = dados.telefone;
                    
                    document.getElementById('tituloForm').innerText = "Editar Cliente #" + dados.id;
                    document.getElementById('btnSalvar').innerText = "Atualizar Cliente";
                    window.scrollTo(0, 0);
                });
        }

        function limparFormulario() {
            document.getElementById('formCliente').reset();
            document.getElementById('id').value = '';
            document.getElementById('tituloForm').innerText = "Novo Cliente";
            document.getElementById('btnSalvar').innerText = "Salvar Cliente";
        }

        function deletarCliente(id) {
            if(confirm('Tem certeza que deseja excluir este cliente?')) {
                let formData = new FormData();
                formData.append('acao', 'deletar');
                formData.append('id', id);

                fetch('../../controllers/ClienteController.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.mensagem);
                    if(data.sucesso) {
                        carregarClientes();
                    }
                });
            }
        }

        carregarClientes();
    </script>
</body>
</html>