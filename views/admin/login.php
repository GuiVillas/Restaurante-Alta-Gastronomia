<?php
    require_once __DIR__ . "/../../controllers/AuthController.php";

    $mensagem_erro = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        // $senha = $_POST['senha'] ?? ''; // Acho que estamos usando uma versão antiga do php
        $senha = isset($_POST['senha']) ? $_POST['senha'] : ''; // Peguei isso do chat gpt, mas faz a mesma coisa

        if (AuthController::login($email, $senha)) {
            header("Location: dashboard.php");
            exit;
        } else {
            $mensagem_erro = "E-mail ou senha incorretos.";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ccc;">
        <h2>Login</h2>
        
        <?php 
            if (!empty($mensagem_erro)) {
                echo '<p style="color: red;">' . htmlspecialchars($mensagem_erro) . '</p>';
            }
        ?>

        <form action="login.php" method="POST">
            <div>
                <label>E-mail:</label><br>
                <input type="email" name="email" required placeholder="admin@restaurante.com">
            </div>
            <br>
            <div>
                <label>Senha:</label><br>
                <input type="password" name="senha" required placeholder="••••••••">
            </div>
            <br>
            <button type="submit">Entrar no Sistema</button>
        </form>
    </div>
</body>
</html>