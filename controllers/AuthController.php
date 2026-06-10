<!--
Criado por: Guilherme Villas Boas Braz
Data: 09/06/2026

Código controlador de autenticação.

Ele processa as requisições de login, 
verifica as credenciais e 
inicia a sessão para o usuário.
-->

<?php
    require_once __DIR__ . "/../config/database.php"; // Importando o database.php

    class AuthController { // Criando classe para controle de autenticação
        public static function login($email, $senha) { // Criando método para login
            if (session_status() === PHP_SESSION_NONE) { // Verificando se não existe sessão
                session_start(); // Criando sessão
            }

            $db = Database::getConnection(); // Pegando conexão PDO

            $stmt = $db->prepare("SELECT id, nome, email, senha, cargo FROM usuarios WHERE = ? LIMIT 1"); // Preparando consulta
            $stmt->execute([$email]); // Executando consulta com o email
            $usuario = $stmt->fetch(); // Obtendo resultado da consulta

            if ($usuario) { // Verificando se o usuário existe
                if ($senha == $usuario['senha']) { // Verificando se as senhas batem
                    session_regenerate_id(true); // Regenerando a sessão

                    $_SESSION['usuario_id'] = $usuario['id']; // Guardando o id do usuário
                    $_SESSION['usuario_nome'] = $usuario['nome']; // Guardando o nome
                    $_SESSION['usuario_cargo'] = $usuario['cargo']; // Guardando o cargo
                    $_SESSION['logado'] = true; // Marcando o usuário como autenticado

                    return true; // Retornando que deu certo
                }
            }
            return false; // Retornando que deu errado
        }

        public static function logout() { // Criando método para logout
            if (session_status() === PHP_SESSION_NONE) { // Verificando se não existe uma sessão
                session_start(); // Criando uma sessão
            }

            $_SESSION = array(); // Limpando as variáveis da sessão
            session_destroy(); // Apagando a sessão

            header("Location: ../public/index.php"); // Enviando o usuário para a página principal
            exit; // Saindo
        }

        public static function protegerPagina() { // Criando método para proteger as páginas de não autenticados
            if (session_status() === PHP_SESSION_NONE) { // Verificando se não existe uma sessão
                session_start(); // Criando uma sessão
            }

            if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) { // Verificando se o usuário não está logado
                header("Location: login.php?erro=autenticacao"); // Enviando o usuário para a página de autenticaçaõ
                exit; // Saindo
            }
        }
    }
?>