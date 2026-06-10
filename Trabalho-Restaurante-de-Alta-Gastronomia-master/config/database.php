<!--
Criado por: Guilherme Villas Boas Braz
Data: 09/06/2026

Código para conexão com o banco de dados.

Ele usa as credenciais padrões do USBWebServer e 
ativa o modo de erros do PDO para ajudar na hora 
de achar as falhas durante o desenvolvimento.
-->

<?php
    define('DB_HOST', 'localhost'); // Definindo o host
    define('DB_USER', 'root'); // Definindo o usuário
    define('DB_PASS', 'usbw'); // Definindo a senha
    define('DB_NAME', 'restaurante_alta_gastronomia'); // Definindo o nome do banco de dados

    class Database { // Criando uma classe para o banco de dados
        private static $instance = null; // Criando uma variável para armazenar a conexão

        public static function getConnection() { // Criando um método para fazer a conexão
            if (self::$instance === null) { // Verifica se não existe conexão
                try { // Iniciando tentativa
                    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4"; // Definindo o DSN (Data Source Name)

                    self::$instance = new PDO($dsn, DB_USER, DB_PASS, [ // Criando o PDO (PHP Data Objects)
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Configurando para mostrar erros
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Configurando o retorno dos resultados
                        PDO::ATTR_EMULATE_PREPARES => false, // Por segurança
                    ]);
                } catch (PDOException $e) { // Tratamento de erros
                    die("Erro na conexão com o banco de dados: " . $e->getMessage()); // Exibindo o erro
                }
            }
            return self::$instance; // Retornando a conexão
        }
    }
?>