<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;

if ($fk_cargo != 1 && $fk_cargo != 4) {
    echo "Acesso negado";
    exit;
}

// Mensagem de resposta
$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome_user'] ?? '');
    $email = trim($_POST['email_user'] ?? '');
    $senha = $_POST['senha_user'] ?? '';
    $foto = ""; // Você pode implementar upload depois se quiser

    // Validação simples
    if (empty($nome) || empty($email) || empty($senha)) {
        $mensagem = "Todos os campos são obrigatórios.";
    } else {
        // Verifica e-mail duplicado
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuario WHERE email_user = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            $mensagem = "E-mail já cadastrado!";
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuario (nome_user, email_user, senha_user, data_criacao, foto_perfil) VALUES (:nome, :email, :senha, NOW(), :foto)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha_hash);
            $stmt->bindParam(':foto', $foto);

            if ($stmt->execute()) {
                $mensagem = "Usuário cadastrado com sucesso!";
            } else {
                $mensagem = "Erro ao cadastrar usuário!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
    <style>
        body { background: #f8f8fc; font-family: Arial, sans-serif; }
        form { max-width: 350px; background: #fff; margin: 40px auto; border-radius: 10px; padding: 28px 25px 18px 25px; box-shadow: 0 4px 24px #0001; }
        h2 { text-align: center; color: #510d96; }
        label { color: #222; font-weight: 500; }
        input { width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #bbb; margin-bottom: 18px; }
        button { background: #510d96; color: #fff; border: none; padding: 8px 20px; border-radius: 6px; font-size: 1rem; margin-right: 8px; cursor: pointer; }
        button[type="reset"] { background: #bbb; color: #222; }
        .mensagem { text-align: center; color: #510d96; margin-bottom: 12px; font-weight: bold; }
        a { display: block; text-align: center; margin-top: 14px; color: #510d96; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <form action="cadastrar_usuario.php" method="POST" autocomplete="off">
        <h2>Cadastrar Usuário</h2>
        <?php if (!empty($mensagem)): ?>
            <div class="mensagem"><?= htmlspecialchars($mensagem); ?></div>
        <?php endif; ?>
        <label for="nome_user">Nome:</label>
        <input type="text" id="nome_user" name="nome_user" required>

        <label for="email_user">E-mail:</label>
        <input type="email" id="email_user" name="email_user" required>

        <label for="senha_user">Senha:</label>
        <input type="password" id="senha_user" name="senha_user" required>
        

        <button type="submit">Cadastrar</button>
        <button type="reset">Cancelar</button>
        <a href="adm.php">Voltar</a>
    </form>
</body>
</html>