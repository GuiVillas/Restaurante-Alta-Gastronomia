<?php
    require_once __DIR__ . "/../config/database.php";

    /**
     * Classe responsável por gerenciar a autenticação dos usuários, incluindo login, logout e proteção de páginas.
     * 
     * Contém métodos estáticos para facilitar o acesso sem a necessidade de instanciar a classe. 
     * O método `login` verifica as credenciais do usuário e inicia uma sessão, 
     * enquanto o método `logout` encerra a sessão atual. 
     * O método `protegerPagina` é utilizado para restringir o acesso a páginas protegidas, 
     * redirecionando usuários não autenticados para a página de login.
     */
    class AuthController {

        /**
         * Realiza o login do usuário verificando as credenciais fornecidas.
         * 
         * @param string $email O email do usuário.
         * @param string $senha A senha do usuário.
         */
        public static function login($email, $senha) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $db = Database::getConnection();

            $stmt = $db->prepare("SELECT id, nome, email, senha, cargo FROM usuario WHERE email = ? LIMIT 1");
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

        /**
         * Realiza o logout do usuário, 
         * destruindo a sessão atual e 
         * redirecionando para a página de login.
         */
        public static function logout() {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION = array();
            session_destroy();

            header("Location: ../admin/login.php");
            exit;
        }

        /**
         * Protege páginas que requerem autenticação,
         */
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