<?php session_start();
require_once 'conexao.php';
require_once 'funcoes_email.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Busca o usuário pelo e-mail correto
    $sql = "SELECT * FROM usuario WHERE email_user = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $senha_temporaria = gerarSenhaTemporaria();
        $senha_hash = password_hash($senha_temporaria, PASSWORD_DEFAULT);

        // Atualiza a senha no banco
        $sql = "UPDATE usuario SET senha_user = :senha, senha_temporaria = TRUE WHERE email_user = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        simularEnvioEmail($email, $senha_temporaria);
        echo "<script>alert('Uma senha temporária foi gerada e enviada (simulação). Verifique o arquivo emails_simulados.txt'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Email não encontrado!');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueceu a senha</title>
    <link rel="stylesheet" href="css/esqueceuSenha.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>
<body>
    <div class="login-container">
        <h1>Recuperar Senha</h1>
        <form action="esqueceuSenha.php" method="POST">
            <label for="email">Digite seu email cadastrado:</label>
            <input type="email" id="email" name="email" placeholder="Digite seu email" required>

            <button type="submit">Enviar senha temporária</button>
        </form>
    </div>
</body>
</html>
