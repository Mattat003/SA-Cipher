<?php
session_start();
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'adm') {
    header("Location: login.php");
    exit();
}
require_once 'conexao.php';

// Busca nome do cargo
$fk_cargo = $_SESSION['fk_cargo'] ?? null;
$nomeCargo = '';
if ($fk_cargo) {
    $stmt = $pdo->prepare("SELECT nome_cargo FROM cargo WHERE pk_cargo = :id");
    $stmt->bindParam(":id", $fk_cargo, PDO::PARAM_INT);
    $stmt->execute();
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $nomeCargo = $row['nome_cargo'];
    }
}
$nome = $_SESSION['adm'] ?? 'Administrador';

// ADICIONAR FUNCIONÁRIO
$mensagem = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar'])) {
    $nome_func = trim($_POST['nome_funcionario']);
    $email_func = trim($_POST['email_funcionario']);
    $senha_func = trim($_POST['senha_funcionario']);
    if ($nome_func && $email_func && $senha_func) {
        // Crie a senha criptografada
        $senha_hash = password_hash($senha_func, PASSWORD_DEFAULT);
        // Exemplo: inserindo na tabela usuario
        $stmtAdd = $pdo->prepare("INSERT INTO usuario (nome_user, email_user, senha_user) VALUES (:nome, :email, :senha)");
        $stmtAdd->bindParam(':nome', $nome_func);
        $stmtAdd->bindParam(':email', $email_func);
        $stmtAdd->bindParam(':senha', $senha_hash);
        if ($stmtAdd->execute()) {
            $mensagem = "Funcionário adicionado com sucesso!";
        } else {
            $mensagem = "Erro ao adicionar funcionário.";
        }
    } else {
        $mensagem = "Preencha todos os campos.";
    }
}

// EXCLUIR FUNCIONÁRIO
if (isset($_GET['excluir']) && is_numeric($_GET['excluir'])) {
    $idExcluir = intval($_GET['excluir']);
    $stmtExcluir = $pdo->prepare("DELETE FROM usuario WHERE pk_usuario = :id");
    $stmtExcluir->bindParam(':id', $idExcluir, PDO::PARAM_INT);
    if ($stmtExcluir->execute()) {
        $mensagem = "Funcionário excluído com sucesso!";
    } else {
        $mensagem = "Erro ao excluir funcionário.";
    }
}

// LISTA FUNCIONÁRIOS
$stmtList = $pdo->query("SELECT pk_usuario, nome_user, email_user FROM usuario ORDER BY nome_user ASC");
$funcionarios = $stmtList->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Administrador</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f3f3fa; margin:0; padding:0; }
        .adm-container { background: #fff; margin: 50px auto; max-width: 600px; border-radius: 10px; box-shadow:0 8px 32px #0003; padding: 32px; }
        h1 { color: #5b009d; }
        form { margin-bottom: 30px; }
        input[type="text"], input[type="email"], input[type="password"] { padding: 8px; margin: 8px 0; width: 98%; }
        button { background: #6a0dad; color: #fff; border:none; padding:8px 18px; border-radius:4px; cursor:pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px; border-bottom: 1px solid #ccc; }
        th { background: #eee; }
        .msg { color: green; margin-bottom: 12px; }
    </style>
</head>
<body>
    <div class="adm-container">
        <h1>Painel do Administrador</h1>
        <p>Bem-vindo, <strong><?= htmlspecialchars($nome) ?></strong>!</p>
        <p>Você está logado como <strong><?= htmlspecialchars($nomeCargo ?: 'Administrador') ?></strong></p>
        <?php if ($mensagem): ?><div class="msg"><?= htmlspecialchars($mensagem) ?></div><?php endif; ?>

        <h2>Adicionar Funcionário</h2>
        <form method="post">
            <input type="hidden" name="adicionar" value="1">
            <label>Nome:</label>
            <input type="text" name="nome_funcionario" required>
            <label>Email:</label>
            <input type="email" name="email_funcionario" required>
            <label>Senha:</label>
            <input type="password" name="senha_funcionario" required>
            <button type="submit">Adicionar</button>
        </form>

        <h2>Funcionários Cadastrados</h2>
        <table>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Ação</th>
            </tr>
            <?php foreach ($funcionarios as $func): ?>
            <tr>
                <td><?= htmlspecialchars($func['nome_user']) ?></td>
                <td><?= htmlspecialchars($func['email_user']) ?></td>
                <td>
                    <a href="?excluir=<?= $func['pk_usuario'] ?>" onclick="return confirm('Tem certeza que deseja excluir este funcionário?');">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <form action="logout.php" method="post">
            <button class="logout-btn" type="submit">Sair</button>
        </form>
        <p><a href="index.php">Voltar para a página inicial</a></p>
    </div>
</body>
</html>