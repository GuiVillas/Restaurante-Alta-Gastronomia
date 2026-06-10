<?php
    require_once __DIR__ . '/../../controllers/AuthController.php';

    AuthController::protegerPagina();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <div style="padding: 20px;">
        <h1>Painel - Alta Gastronomia</h1>
        <p>Bem-vindo(a), <strong><?= htmlspecialchars($_SESSION['usuario_nome']) ?></strong> (Cargo: <?= htmlspecialchars($_SESSION['usuario_cargo']) ?>)</p>
        
        <hr>
        <h3>Menu do Sistema</h3>
        <ul>
            <li><a href="reservas.php">Gerenciar Reservas</a></li>
            <li><a href="cardapio.php">Gerenciar Pratos</a></li>
            <li><a href="relatorios.php">Relatórios de Faturamento</a></li>
        </ul>
        <hr>
        
        <a href="logout.php" style="color: red;">Sair do Sistema</a>
    </div>
</body>
</html>