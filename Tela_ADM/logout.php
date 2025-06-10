<?php
// auth/logout.php
session_start();
require_once 'db.php'; // Inclui para usar o logSecurityEvent
require_once 'functions.php';

// Registra o evento de logout
if (isset($_SESSION['user_id'])) {
    logSecurityEvent($pdo, 'logout_success', 'Usuário fez logout.', $_SESSION['user_id'], $_SERVER['REMOTE_ADDR']);
}

// Destruir todas as variáveis de sessão
$_SESSION = array();

// Se for desejado matar completamente a sessão, também limpe o cookie de sessão.
// Nota: Isso destruirá a sessão, e não apenas os dados da sessão!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruir a sessão.
session_destroy();

// Redirecionar para a página de login com uma mensagem de sucesso
header('Location: login.php?logged_out=true');
exit;
?>