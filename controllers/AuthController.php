<!--
Criado por: Guilherme Villas Boas Braz
Data: 09/06/2026

Código controlador de autenticação.

Ele processa as requisições de login, 
verifica as credenciais e 
inicia a sessão para o usuário.
-->

<?php
    require_once __DIR__ . "/../config/database.php";

    class AuthController {
        public static function login($email, $senha) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $db = Database::getConnection();

            $stmt = $db->prepare("SELECT id, nome, email, senha, cargo FROM usuarios WHERE = ? LIMIT 1");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch();

            if ($usuario) {
                if ($senha == $usuario['senha']) {
                    session_regenerate_id(true);

                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_nome'] = $usuario['nome'];
                    $_SESSION['usuario_cargo'] = $usuario['cargo'];
                    $_SESSION['logado'] = true;

                    return true;
                }
            }
            return false;
        }

        public static function logout() {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION = array();
            session_destroy();

            header("Location: ../public/index.php");
            exit;
        }

        public static function protegerPagina() {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
                header("Location: login.php?erro=autenticacao");
                exit;
            }
        }
    }
?>