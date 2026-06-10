<?php
    require_once __DIR__ . '/../../controllers/AuthController.php';
    AuthController::protegerPagina();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Cardápio</title>
</head>
<body>

    <h2>Gestão de Cardápio</h2>

    <fieldset>
        <legend>Cadastrar Novo Prato</legend>
        <form id="formPrato">
            <div>
                <label>Nome do Prato:</label><br>
                <input type="text" name="nome" id="nome" required>
            </div>
            <br>
            <div>
                <label>Preço (R$):</label><br>
                <input type="number" step="0.01" name="preco" id="preco" required>
            </div>
            <br>
            <div>
                <label>ID da Categoria:</label><br>
                <input type="number" name="categoria_id" id="categoria_id" placeholder="Ex: 1">
            </div>
            <br>
            <button type="submit">Cadastrar Prato</button>
        </form>
    </fieldset>

    <br>

    <table border="1" width="100%" cellpadding="5" cellspacing="0">
        <thead style="background-color: #eee;">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="tabelaPratos">
            </tbody>
    </table>

    <script>
        function carregarPratos() {
            fetch('../../controllers/PratoController.php?acao=listar')
                .then(response => response.json())
                .then(dados => {
                    const tbody = document.getElementById('tabelaPratos');
                    tbody.innerHTML = '';
                    
                    dados.forEach(prato => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${prato.id}</td>
                                <td>${prato.nome}</td>
                                <td>${prato.categoria || 'Sem categoria'}</td>
                                <td>R$ ${prato.preco}</td>
                                <td>
                                    <button onclick="deletarPrato(${prato.id})">Excluir</button>
                                </td>
                            </tr>
                        `;
                    });
                });
        }

        document.getElementById('formPrato').addEventListener('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            formData.append('acao', 'cadastrar');

            fetch('../../controllers/PratoController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.sucesso) {
                    alert(data.mensagem);
                    this.reset();
                    carregarPratos();
                } else {
                    alert(data.mensagem);
                }
            });
        });

        function deletarPrato(id) {
            if(confirm('Tem certeza que deseja excluir?')) {
                let formData = new FormData();
                formData.append('acao', 'deletar');
                formData.append('id', id);

                fetch('../../controllers/PratoController.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.sucesso) {
                        carregarPratos();
                    }
                });
            }
        }

        carregarPratos();
    </script>
</body>
</html>