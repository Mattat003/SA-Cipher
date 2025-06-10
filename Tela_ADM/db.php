<?php
// includes/db.php

// Definir o timezone para garantir consistência de datas e horas
date_default_timezone_set('America/Sao_Paulo'); // Altere para o seu timezone, se necessário

$host = 'localhost'; // Ou o endereço do seu servidor de banco de dados
$db   = 'cypher_corp_db'; // Nome do banco de dados que você criou
$user = 'root'; // Seu usuário do banco de dados
$pass = ''; // Sua senha do banco de dados (em desenvolvimento, pode ser vazia)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lançar exceções para erros
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     // Retornar resultados como arrays associativos
    PDO::ATTR_EMULATE_PREPARES   => false,                // Desabilitar emulação de prepared statements para segurança
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Em produção, você não deve exibir a mensagem de erro detalhada do banco de dados ao usuário.
    // Em vez disso, registre o erro e exiba uma mensagem genérica de erro.
    error_log('Erro de Conexão com o Banco de Dados: ' . $e->getMessage()); // Registra o erro no log do PHP
    die('Ocorreu um erro ao conectar ao banco de dados. Por favor, tente novamente mais tarde.'); // Mensagem genérica para o usuário
}

// Configurações de sessão mais seguras
// Assegura que a sessão só use cookies e que estes sejam seguros.
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1); // Torna o cookie inacessível via JavaScript
ini_set('session.cookie_secure', 1); // Envia o cookie apenas em conexões HTTPS (essencial para produção!)

// Define o tempo de vida do cookie de sessão (ex: 1 hora)
$session_lifetime = 3600; // 1 hora em segundos
session_set_cookie_params($session_lifetime);

// Garante que o ID da sessão seja regenerado periodicamente para prevenir Session Fixation
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['LAST_ACTIVITY'])) {
    $_SESSION['LAST_ACTIVITY'] = time();
}

// Regenerar ID da sessão a cada X segundos (ex: 30 minutos = 1800 segundos)
// Isso ajuda a prevenir ataques de Session Fixation
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    session_regenerate_id(true); // true para deletar a antiga sessão
    $_SESSION['LAST_ACTIVITY'] = time(); // Atualiza o tempo da última atividade
}
?>