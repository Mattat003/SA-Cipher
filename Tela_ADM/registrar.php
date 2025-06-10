<?php
// auth/registrar.php
session_start();
require_once 'db.php';
require_once 'functions.php';

// Redireciona se o usuário já estiver logado (não deveria registrar novamente)
if (isLoggedIn()) {
    header('Location: ../Hamburguer.html'); // Ou para o painel principal
    exit;
}

$message = '';
$username_value = '';
$email_value = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Validação CSRF
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!validateCsrfToken($csrf_token)) {
        $message = '<div class="alert alert-danger">Erro de segurança: Requisição inválida.</div>';
        logSecurityEvent($pdo, 'csrf_attack', 'Tentativa de registro com token CSRF inválido.', null, $_SERVER['REMOTE_ADDR']);
    } else {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        $username_value = escapeHtml($username);
        $email_value = escapeHtml($email);

        if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            $message = '<div class="alert alert-danger">Por favor, preencha todos os campos.</div>';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = '<div class="alert alert-danger">Formato de e-mail inválido.</div>';
        } elseif ($password !== $confirm_password) {
            $message = '<div class="alert alert-danger">As senhas não coincidem.</div>';
        } elseif (strlen($password) < 8) { // Senha mais forte, mínimo 8 caracteres
            $message = '<div class="alert alert-danger">A senha deve ter pelo menos 8 caracteres.</div>';
        } else {
            try {
                // Verificar se usuário ou e-mail já existem
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
                $stmt->execute([$username, $email]);
                if ($stmt->fetchColumn() > 0) {
                    $message = '<div class="alert alert-danger">Nome de usuário ou e-mail já estão em uso.</div>';
                } else {
                    // Hash da senha
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Inserir novo usuário
                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')"); // 'user' como role padrão
                    if ($stmt->execute([$username, $email, $hashed_password])) {
                        $message = '<div class="alert alert-success">Conta criada com sucesso! Você pode fazer login agora.</div>';
                        logSecurityEvent($pdo, 'user_registration', 'Novo usuário registrado: ' . $username, null, $_SERVER['REMOTE_ADDR']);
                        // Limpar os campos após o sucesso
                        $username_value = '';
                        $email_value = '';
                    } else {
                        $message = '<div class="alert alert-danger">Erro ao registrar. Por favor, tente novamente.</div>';
                    }
                }
            } catch (PDOException $e) {
                error_log("Erro no banco de dados durante o registro: " . $e->getMessage());
                $message = '<div class="alert alert-danger">Ocorreu um erro ao registrar. Por favor, tente novamente.</div>';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar - Cypher Corporation</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #7B1FA2;
            --primary-light: #9C27B0;
            --secondary-color: #1976D2;
            --secondary-light: #2196F3;
            --text-dark: #212121;
            --text-medium: #424242;
            --text-light: #757575;
            --text-white: #FFFFFF;
        }

        body {
            font-family: 'Inter', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f0f2f5;
            color: var(--text-dark);
            margin: 0;
        }
        .auth-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h1 {
            color: var(--primary-color);
            margin-bottom: 30px;
            font-weight: 600;
        }
        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-medium);
        }
        .input-group input[type="email"],
        .input-group input[type="password"],
        .input-group input[type="text"] {
            width: calc(100% - 20px);
            padding: 12px 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }
        .input-group input:focus {
            border-color: var(--secondary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.2); /* secondary-color com 20% de opacidade */
        }
        .btn-primary {
            width: 100%;
            padding: 12px 20px;
            background-color: var(--primary-color);
            color: var(--text-white);
            border: none;
            border-radius: 6px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: var(--primary-light);
        }
        .auth-link {
            display: block;
            margin-top: 1.5rem;
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
        }
        .auth-link:hover {
            text-decoration: underline;
        }
        .alert {
            padding: 10px;
            margin-bottom: 1rem;
            border-radius: 4px;
            font-size: 0.95rem;
            text-align: left;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <h1>Registrar</h1>
        <?php echo $message; ?>
        <form method="POST" action="registrar.php">
            <input type="hidden" name="csrf_token" value="<?php echo escapeHtml(generateCsrfToken()); ?>">
            <div class="input-group">
                <label for="username">Nome de Usuário</label>
                <input type="text" id="username" name="username" required value="<?php echo $username_value; ?>">
            </div>
            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required value="<?php echo $email_value; ?>">
            </div>
            <div class="input-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirmar Senha</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn-primary">Registrar</button>
            <a href="login.php" class="auth-link">Já tem uma conta? Faça Login</a>
        </form>
    </div>
</body>
</html>