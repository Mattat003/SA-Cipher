<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_raw = $_POST["email"] ?? '';
    $email = filter_var(trim($email_raw), FILTER_SANITIZE_EMAIL);

    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Formato de email inválido!";
    } else {
        // 1. Tenta como usuário
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email_user = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($_POST["senha"], $usuario['senha_user']) || $_POST["senha"] === $usuario['senha_user']) {
                $_SESSION['pk_usuario'] = $usuario['pk_usuario'];
                $_SESSION['usuario'] = $usuario['nome_user'];
                $_SESSION['foto_perfil'] = $usuario['foto_perfil'] ?? null;
                $_SESSION['tipo'] = 'usuario';

                if (!empty($usuario['senha_temporaria'])) {
                    header("Location: alterar_senha.php");
                    exit();
                } else {
                    session_write_close();
                    header("Location: index.php");
                    exit();
                }
            } else {
                $erro = "Email ou senha inválidos!";
            }
        } else {
            // 2. Tenta como admin
            $stmt2 = $pdo->prepare("SELECT * FROM adm WHERE email_adm = :email");
            $stmt2->bindParam(":email", $email);
            $stmt2->execute();

            if ($stmt2->rowCount() == 1) {
                $adm = $stmt2->fetch(PDO::FETCH_ASSOC);
                if (password_verify($_POST["senha"], $adm['senha_user']) || $_POST["senha"] === $adm['senha_user']) {
                    $_SESSION['pk_adm'] = $adm['pk_adm'];
                    $_SESSION['adm'] = $adm['nome_adm'];
                    $_SESSION['tipo'] = 'adm';
                    $_SESSION['fk_cargo'] = $adm['fk_cargo']; 
                    session_write_close();
                    header("Location: adm.php");
                    exit();
                } else {
                    $erro = "Email ou senha inválidos!";
                }
            } else {
                $erro = "Email ou senha inválidos!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"/>
    <title>Login</title>
    <style>
       /* Seu CSS permanece igual */
       * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
body {
    background: linear-gradient(135deg, #0f0c29, #302b63);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
}
.login-container {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 40px;
    border-radius: 15px;
    text-align: center;
    color: white;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.36);
    animation: fadeIn 1s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

h2 {
    font-size: 28px;
    margin-bottom: 25px;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to right, #ffffff, #e0c3fc);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    font-weight: 600;
}
label {
    font-weight: 500;
    display: block;
    text-align: left;
    margin-bottom: 8px;
    color: #e0c3fc;
    font-size: 14px;
}
.input-group {
    position: relative;
    margin-bottom: 20px;
}

input {
    width: 100%;
    padding: 12px 15px;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(255, 255, 255, 0.05);
    color: white;
    outline: none;
    font-size: 15px;
    transition: all 0.3s ease;
}
input:focus {
    border-color: #9a66df;
    box-shadow: 0 0 0 3px rgba(154, 102, 223, 0.2);
}
input::placeholder {
    color: #cbbde2;
    opacity: 0.7;
}

.toggle-password {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #cbbde2;
    font-size: 18px;
    transition: color 0.2s ease;
}

.toggle-password:hover {
    color: #e0c3fc;
}

button {
    background: linear-gradient(to right, #8639df, #6a0dad);
    color: white;
    padding: 14px;
    border: none;
    width: 100%;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(134, 57, 223, 0.3);
    margin-top: 10px;
}
button:hover {
    background: linear-gradient(to right, #6a0dad, #5b009d);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(134, 57, 223, 0.4);
}
p a {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #cbbde2;
    font-size: 14px;
    text-decoration: none;
}
p a:hover {
    color: #e0c3fc;
    text-decoration: underline;
}
p[style='color: #ffcccc;'] {
    color: #ffcccc;
    font-size: 14px;
    margin-top: -10px;
}
    </style>
</head>
<body>
<div class="login-container">
    <h2>Login</h2>
    <?php if (isset($erro)) { echo "<p style='color: #ffcccc;'>$erro</p>"; } ?>
    <form action="login.php" method="post" autocomplete="off">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required placeholder="Digite seu email" value="<?= htmlspecialchars($email_raw ?? '') ?>">
        
        <label for="senha">Senha:</label>
        <div class="input-group">
            <input type="password" name="senha" id="senha" required placeholder="Digite sua senha">
            <span class="toggle-password" onclick="togglePasswordVisibility()">👁️</span>
        </div>
        
        <button type="submit">Entrar</button>
    </form>
    <p><a href="esqueceuSenha.php">Esqueci minha senha</a></p>
    <p><a href="cadastro.php">Criar conta</a></p>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('senha');
        const toggleButton = document.querySelector('.toggle-password');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleButton.textContent = '🙈'; // Change to hide icon
        } else {
            passwordField.type = 'password';
            toggleButton.textContent = '👁️'; // Change to show icon
        }
    }
</script>
</body>
</html>