<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pega o email cru do POST, e limpa espaços em branco
    $email_raw = $_POST["email"] ?? '';
    $email = filter_var(trim($email_raw), FILTER_SANITIZE_EMAIL);

    // Valida o email
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Formato de email inválido!";
    } else {
        // Consulta no banco
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email_user = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifica a senha
            if (password_verify($_POST["senha"], $usuario['senha_user'])) {

                // Salvar infos na sessão
                $_SESSION['pk_usuario'] = $usuario['pk_usuario'];
                $_SESSION['usuario'] = $usuario['nome_user'];
                $_SESSION['foto_perfil'] = $usuario['foto_perfil'];

                // Redireciona conforme senha temporária
                if ($usuario['senha_temporaria']) {
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
            $erro = "Email ou senha inválidos!";
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
       * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Corpo com fundo em degradê escuro */
body {
    background: linear-gradient(135deg, #0f0c29, #302b63);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
}

/* Container do login */
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
}

/* Título */
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

/* Rótulos */
label {
    font-weight: 500;
    display: block;
    text-align: left;
    margin-bottom: 8px;
    color: #e0c3fc;
    font-size: 14px;
}

/* Caixa de entrada */
input {
    width: 100%;
    padding: 12px 15px;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    margin-bottom: 20px;
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

/* Placeholder */
input::placeholder {
    color: #cbbde2;
    opacity: 0.7;
}

/* Botão */
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

/* Link de voltar */
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

/* Mensagem de erro */
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

    <form action="login.php" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required placeholder="Digite seu email" value="<?= htmlspecialchars($email_raw ?? '') ?>">

        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required placeholder="Digite sua senha">

        <button type="submit">Entrar</button>
    </form>

    <p><a href="esqueceuSenha.php">Esqueci minha senha</a></p>
    <p><a href="cadastro.php">Criar conta</a></p>
</div>
</body>
</html>
