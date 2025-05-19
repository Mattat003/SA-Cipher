<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email_user = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($senha, $usuario['senha_user'])) {

            if ($usuario['senha_temporaria']) {
                $_SESSION['pk_usuario'] = $usuario['pk_usuario'];
                $_SESSION['usuario'] = $usuario['nome_user'];
                header("Location: alterar_senha.php");
                exit();
            } else {
                $_SESSION['pk_usuario'] = $usuario['pk_usuario'];
                $_SESSION['usuario'] = $usuario['nome_user'];

                session_write_close(); // garante gravação da sessão
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
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"/>
    <title>Login</title>
    <link rel="stylesheet" href="css/Login.css">
</head>
<body>
<div class="login-container">
    <h2>Login</h2>

    <?php if (isset($erro)) { echo "<p style='color: #ffcccc;'>$erro</p>"; } ?>

    <form action="login.php" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required placeholder="Digite seu email">

        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required placeholder="Digite sua senha">

        <button type="submit">Entrar</button>
    </form>

    <p><a href="esqueceuSenha.php">Esqueci minha senha</a></p>
    <p><a href="cadastro.php">Criar conta</a></p>
</div>
</body>
</html>
