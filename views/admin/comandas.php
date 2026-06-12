<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Controle de Comandas</title>
</head>
<body>

    <h2>Gerenciamento do Salão (Comandas Ativas)</h2>

    <fieldset>
        <legend>Abrir Nova Comanda (Pedido)</legend>
        <form id="formAbrirComanda">
            <label>ID da Mesa:</label>
            <input type="number" name="mesa_id" required>
            
            <label>ID do Garçom/Usuário:</label>
            <input type="number" name="usuario_id" required>
            
            <button type="submit">Abrir Comanda</button>
        </form>
    </fieldset>

    <br>

    <table border="1" width="45%" cellpadding="5" cellspacing="0" style="float: left;">
        <thead style="background-color: #d4edda;">
            <tr>
                <th>Nº Comanda (ID)</th>
                <th>Mesa</th>
                <th>Garçom</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="tabelaComandasAtivas"></tbody>
    </table>

    <div id="painelDetalhes" style="width: 50%; float: right; display: none; border: 1px solid #000; padding: 15px; background-color: #f9f9f9;">
        <h3 id="tituloComandaSelecionada">Detalhes da Comanda</h3>
        
        <form id="formAdicionarItem">
            <input type="hidden" name="pedido_id" id="comanda_id_atual">
            <label>ID Prato:</label>
            <input type="number" name="prato_id" required style="width: 60px;">
            <label>Qtd:</label>
            <input type="number" name="quantidade" value="1" min="1" required style="width: 50px;">
            <button type="submit">Lançar Item</button>
        </form>
        <br>
        
        <table border="1" width="100%" cellpadding="5" cellspacing="0">
            <thead>
                <tr style="background-color: #ddd;">
                    <th>Item</th>
                    <th>Qtd</th>
                    <th>Preço Unit.</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody id="itensConsumo"></tbody>
        </table>
        
        <p dir="rtl" style="font-size: 1.2em;"><strong>Total: R$ <span id="totalComanda">0.00</span></strong></p>
        
        <div style="text-align: right;">
            <button type="button" onclick="fecharConta()" style="background-color: #dc3545; color: white; padding: 10px;">Encerrar Comanda e Pagar</button>
        </div>
    </div>

    <div style="clear: both;"></div>
    <br><hr><br>

    <h2>Histórico de Vendas (Comandas Fechadas)</h2>
    <table border="1" width="100%" cellpadding="5" cellspacing="0">
        <thead style="background-color: #e2e3e5;">
            <tr>
                <th>Nº Comanda (ID)</th>
                <th>Mesa</th>
                <th>Garçom</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="tabelaHistorico"></tbody>
    </table>

    <script>
        function carregarAtivas() {
            fetch('../../controllers/PedidoController.php?acao=listar_ativas')
                .then(response => response.json())
                .then(dados => {
                    const tbody = document.getElementById('tabelaComandasAtivas');
                    tbody.innerHTML = '';
                    dados.forEach(c => {
                        tbody.innerHTML += `
                            <tr>
                                <td># ${c.id}</td>
                                <td>Mesa ${c.mesa_numero}</td>
                                <td>${c.garcom_nome}</td>
                                <td>
                                    <button onclick="verDetalhes(${c.id}, ${c.mesa_numero}, true)">Ver Conta / Lançar</button>
                                </td>
                            </tr>
                        `;
                    });
                });
        }

        function carregarHistorico() {
            fetch('../../controllers/PedidoController.php?acao=listar_historico')
                .then(response => response.json())
                .then(dados => {
                    const tbody = document.getElementById('tabelaHistorico');
                    tbody.innerHTML = '';
                    dados.forEach(c => {
                        tbody.innerHTML += `
                            <tr>
                                <td># ${c.id}</td>
                                <td>Mesa ${c.mesa_numero}</td>
                                <td>${c.garcom_nome}</td>
                                <td>
                                    <button onclick="verDetalhes(${c.id}, ${c.mesa_numero}, false)">Visualizar Extrato</button>
                                </td>
                            </tr>
                        `;
                    });
                });
        }

        function verDetalhes(pedidoId, numMesa, isAtiva) {
            document.getElementById('comanda_id_atual').value = pedidoId;
            let statusTexto = isAtiva ? '(Em aberto)' : '(Fechada)';
            document.getElementById('tituloComandaSelecionada').innerText = `Consumo Mesa ${numMesa} ${statusTexto}`;
            document.getElementById('painelDetalhes').style.display = 'block';
            
            document.getElementById('formAdicionarItem').style.display = isAtiva ? 'block' : 'none';
            document.querySelector("button[onclick='fecharConta()']").style.display = isAtiva ? 'inline-block' : 'none';
            
            fetch(`../../controllers/PedidoController.php?acao=ver_comanda&pedido_id=${pedidoId}`)
                .then(response => response.json())
                .then(dados => {
                    const tbody = document.getElementById('itensConsumo');
                    tbody.innerHTML = '';
                    if(dados.itens.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="4" align="center">Nenhum consumo registrado.</td></tr>';
                    } else {
                        dados.itens.forEach(item => {
                            tbody.innerHTML += `
                                <tr>
                                    <td>${item.prato_nome}</td>
                                    <td>${item.quantidade}</td>
                                    <td>R$ ${item.preco_unitario}</td>
                                    <td>R$ ${item.subtotal}</td>
                                </tr>
                            `;
                        });
                    }
                    document.getElementById('totalComanda').innerText = dados.total_geral;
                });
        }

        document.getElementById('formAbrirComanda').addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            formData.append('acao', 'abrir');

            fetch('../../controllers/PedidoController.php', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    alert(data.mensagem);
                    if(data.sucesso) {
                        this.reset();
                        carregarAtivas();
                    }
                });
        });

        document.getElementById('formAdicionarItem').addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            formData.append('acao', 'adicionar_item');

            fetch('../../controllers/PedidoController.php', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if(data.sucesso) {
                        let pedidoId = document.getElementById('comanda_id_atual').value;
                        document.getElementById('formAdicionarItem').reset();
                        document.getElementById('comanda_id_atual').value = pedidoId;
                        verDetalhes(pedidoId, '', true); 
                    } else {
                        alert(data.mensagem);
                    }
                });
        });

        function fecharConta() {
            let pedidoId = document.getElementById('comanda_id_atual').value;
            if(confirm('Deseja realmente encerrar esta comanda?')) {
                let formData = new FormData();
                formData.append('acao', 'fechar');
                formData.append('pedido_id', pedidoId);

                fetch('../../controllers/PedidoController.php', { method: 'POST', body: formData })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.mensagem);
                        if(data.sucesso) {
                            document.getElementById('painelDetalhes').style.display = 'none';
                            carregarAtivas();
                            carregarHistorico();
                        }
                    });
            }
        }

        carregarAtivas();
        carregarHistorico();
    </script>
</body>
</html>