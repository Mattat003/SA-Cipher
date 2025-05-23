<?php
session_start();
require_once 'conexao.php';

// Garante que usuário está logado e com id salvo na sessão
if (!isset($_SESSION['pk_usuario'])) {
    echo "<script>alert('Acesso negado! Faça login primeiro.'); window.location.href = 'login.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_SESSION['pk_usuario'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Validações básicas
    if ($nova_senha !== $confirmar_senha) {
        echo "<script>alert('As senhas não coincidem!');</script>";
    } elseif (strlen($nova_senha) < 8) {
        echo "<script>alert('A senha deve ter no mínimo 8 caracteres!');</script>";
    } else {
        // Hash da nova senha
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        // Atualiza senha no banco e desativa senha temporária
        $sql = "UPDATE usuario SET senha_user = :senha, senha_temporaria = FALSE WHERE pk_usuario = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':id', $id_usuario);

        if ($stmt->execute()) {
            // Destroi a sessão para forçar novo login com senha definitiva
            session_destroy();
            echo "<script>alert('Senha alterada com sucesso! Faça login novamente.'); window.location.href = 'login.php';</script>";
            exit();
        } else {
            echo "<script>alert('Erro ao alterar senha!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Senha</title>
    <link rel="stylesheet" href="css/alterar_senha.css"> <!-- se tiver -->
</head>
<body>
    <div class="container">
        <h2>Olá, <?php echo htmlspecialchars($_SESSION['usuario']); ?>, altere sua senha temporária</h2>

        <form method="POST" action="alterar_senha.php">
            <label for="nova_senha">Nova senha:</label>
            <input type="password" name="nova_senha" id="nova_senha" required minlength="8" placeholder="Digite nova senha">

            <label for="confirmar_senha">Confirme a nova senha:</label>
            <input type="password" name="confirmar_senha" id="confirmar_senha" required minlength="8" placeholder="Confirme a senha">

            <button type="submit">Salvar nova senha</button>
        </form>
    </div>
</body>
</html>
