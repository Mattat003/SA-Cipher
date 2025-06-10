<?php
// auth/login.php
session_start();
require_once 'db.php';
require_once 'functions.php';


// Redireciona se o usuário já estiver logado
if (isLoggedIn()) {
    // Redirecionar para o painel de administração (Hamburguer.html)
    // Ajuste este caminho conforme a localização real do seu Hamburguer.html
    header('Location: ../admin/Hamburguer.html'); // <--- Linha ajustada: presumindo Hamburguer.html está em 'admin/'
    exit;
}

$message = '';
$email_value = ''; // Para manter o valor do e-mail no formulário

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Validação CSRF
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!validateCsrfToken($csrf_token)) {
        $message = '<div class="alert alert-danger">Erro de segurança: Requisição inválida.</div>';
        logSecurityEvent($pdo, 'csrf_attack', 'Tentativa de login com token CSRF inválido.', null, $_SERVER['REMOTE_ADDR']);
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $email_value = escapeHtml($email); // Escapa para exibir no campo de e-mail

        if (empty($email) || empty($password)) {
            $message = '<div class="alert alert-danger">Por favor, preencha todos os campos.</div>';
            logSecurityEvent($pdo, 'login_failure', 'Campos vazios no login.', null, $_SERVER['REMOTE_ADDR']);
        } else {
            try {
                // 2. Busca o usuário pelo e-mail
                $stmt = $pdo->prepare("SELECT id, username, email, password, role FROM users WHERE email = ? AND status = 'active'");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // 3. Verifica a senha
                if ($user && password_verify($password, $user['password'])) {
                    // Login bem-sucedido
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['LAST_ACTIVITY'] = time(); // Atualiza o último acesso da sessão
                    session_regenerate_id(true); // Regenera o ID da sessão para prevenir Session Fixation

                    // Atualiza last_login_at e last_login_ip no banco de dados
                    $stmt_update_login = $pdo->prepare("UPDATE users SET last_login_at = NOW(), last_login_ip = ? WHERE id = ?");
                    $stmt_update_login->execute([$_SERVER['REMOTE_ADDR'], $user['id']]);

                    logSecurityEvent($pdo, 'login_success', 'Login bem-sucedido.', $user['id'], $_SERVER['REMOTE_ADDR']);

                    // Redirecionamento após login bem-sucedido
                    // AJUSTE ESTE CAMINHO CONFORME A LOCALIZAÇÃO REAL DO SEU ARQUIVO Hamburguer.html
                    if ($user['role'] === 'admin' || $user['role'] === 'editor') {
                        // Redireciona admins e editores para o painel de administração
                        header('Location: ../admin/Hamburguer.html'); // <--- Caminho ajustado
                    } else {
                        // Para usuários comuns, redireciona para uma página de usuário padrão, se houver
                        // Por enquanto, vamos redirecionar para o admin também, ou ajuste para sua home
                        header('Location: ../index.html'); // Ou uma página de usuário
                    }
                    exit;
                } else {
                    // Credenciais inválidas (email não encontrado ou senha incorreta)
                    $message = '<div class="alert alert-danger">Credenciais inválidas.</div>';
                    logSecurityEvent($pdo, 'login_failure', 'Tentativa de login com credenciais inválidas.', null, $_SERVER['REMOTE_ADDR']);
                }
            } catch (PDOException $e) {
                // Erro de banco de dados
                error_log("Erro no banco de dados durante o login: " . $e->getMessage());
                $message = '<div class="alert alert-danger">Ocorreu um erro no servidor. Por favor, tente novamente mais tarde.</div>';
                logSecurityEvent($pdo, 'database_error', 'Erro de DB no login: ' . $e->getMessage(), null, $_SERVER['REMOTE_ADDR']);
            }
        }
    }
}

// Mensagem de logout (se vier de logout.php)
if (isset($_GET['logged_out']) && $_GET['logged_out'] === 'true') {
    $message = '<div class="alert alert-success">Você foi desconectado com sucesso.</div>';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cypher Corporation</title>
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
            box-sizing: border-box;
        }
        h1 {
            color: var(--primary-color);
            margin-bottom: 25px;
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
        .input-group input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }
        .input-group input[type="email"]:focus,
        .input-group input[type="password"]:focus {
            border-color: var(--primary-light);
            outline: none;
            box-shadow: 0 0 0 3px rgba(156, 39, 176, 0.2);
        }
        .btn-primary {
            background-color: var(--primary-color);
            color: var(--text-white);
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            width: 100%;
            box-sizing: border-box;
        }
        .btn-primary:hover {
            background-color: var(--primary-light);
            transform: translateY(-2px);
        }
        .auth-link {
            display: block;
            margin-top: 20px;
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
        <h1>Login</h1>
        <?php echo $message; ?>
        <form method="POST" action="login.php">
            <input type="hidden" name="csrf_token" value="<?php echo escapeHtml(generateCsrfToken()); ?>">
            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required value="<?php echo $email_value; ?>">
            </div>
            <div class="input-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary">Entrar</button>
            <a href="registrar.php" class="auth-link">Não tem uma conta? Registre-se</a>
        </form>
    </div>
    <script>
        // Pequeno script para preencher o token CSRF no carregamento da página
        document.addEventListener('DOMContentLoaded', function() {
            // Este script é um placeholder. O PHP deve gerar o token e injetá-lo diretamente no HTML.
            // No entanto, para fins de exemplo, aqui está como ele seria preenchido se o PHP já tivesse gerado
            // e colocado o token em uma variável JavaScript ou um elemento HTML.
            // Para maior segurança, o PHP deve renderizar o valor diretamente.
            // Exemplo: <input type="hidden" name="csrf_token" value="<?= escapeHtml(generateCsrfToken()) ?>">
            const csrfInput = document.querySelector('input[name="csrf_token"]');
            if (csrfInput) {
                // Aqui, o PHP deve gerar o token e passá-lo para o JS ou direto para o value.
                // Como este é o HTML puro, não podemos gerar PHP aqui.
                // Mas, o PHP que renderiza este HTML DEVE injetar o token.
                // Exemplo hipotético de como o PHP faria isso:
                // csrfInput.value = "<?php echo generateCsrfToken(); ?>";
            }
        });
    </script>
</body>
</html>