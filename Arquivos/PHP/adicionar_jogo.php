<?php
session_start();
require_once 'conexao.php';

// Exibe todos os erros PHP (útil para debug)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica se o usuário tem permissão (apenas cargo 1 - admin)
$fk_cargo = $_SESSION['fk_cargo'] ?? null;
if ($fk_cargo != 1) {
    echo "Acesso negado";
    exit;
}

// Mensagem de feedback (sucesso/erro) via sessão
$mensagem = $_SESSION['msg_cadastro_jogo'] ?? '';
unset($_SESSION['msg_cadastro_jogo']);

// Busca todos os usuários para o select do formulário
$stmt = $pdo->query("SELECT pk_usuario, nome_user FROM usuario ORDER BY nome_user");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Se o formulário foi enviado (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe e sanitiza os dados do formulário
    $usuario_id = intval($_POST['usuario_id'] ?? 0);
    $nome_jogo = trim($_POST['nome_jogo'] ?? '');
    $url_jogo = trim($_POST['url_jogo'] ?? '');
    $imagem_jogo = trim($_POST['imagem_jogo'] ?? '');

    // Valida os campos obrigatórios
    if (!$usuario_id || !$nome_jogo || !$url_jogo) {
        $mensagem = "Usuário, nome do jogo e URL do jogo são obrigatórios.";
    } else {
        // Tenta inserir na biblioteca_usuario (evita duplicidade com INSERT IGNORE)
        $stmt = $pdo->prepare("INSERT IGNORE INTO biblioteca_usuario (usuario_id, nome_jogo, url_jogo, imagem_jogo) VALUES (?, ?, ?, ?)");
        $stmt->execute([$usuario_id, $nome_jogo, $url_jogo, $imagem_jogo]);
        if ($stmt->rowCount() > 0) {
            $mensagem = "Jogo adicionado à biblioteca do usuário!";
        } else {
            $mensagem = "Esse jogo já está na biblioteca desse usuário ou houve um erro!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Jogo ao Usuário</title>
    <style>
        body { background: #f8f8fc; font-family: Arial, sans-serif; }
        form { max-width: 400px; background: #fff; margin: 40px auto; border-radius: 10px; padding: 28px 25px 18px 25px; box-shadow: 0 4px 24px #0001; }
        h2 { text-align: center; color: #510d96; }
        label { color: #222; font-weight: 500; }
        input, select { width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #bbb; margin-bottom: 18px; }
        button { background: #510d96; color: #fff; border: none; padding: 8px 20px; border-radius: 6px; font-size: 1rem; margin-right: 8px; cursor: pointer; }
        button[type="reset"] { background: #bbb; color: #222; }
        .mensagem { text-align: center; color: #510d96; margin-bottom: 12px; font-weight: bold; }
        a { display: block; text-align: center; margin-top: 14px; color: #510d96; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <form action="adicionar_jogo.php" method="POST" autocomplete="off">
        <h2>Adicionar Jogo ao Usuário</h2>
        <?php if (!empty($mensagem)): ?>
            <div class="mensagem"><?= htmlspecialchars($mensagem); ?></div>
        <?php endif; ?>

        <label for="usuario_id">Usuário:</label>
        <select name="usuario_id" id="usuario_id" required>
            <option value="">Selecione o usuário</option>
            <?php foreach ($usuarios as $user): ?>
                <option value="<?= $user['pk_usuario'] ?>"><?= htmlspecialchars($user['nome_user']) ?> (ID <?= $user['pk_usuario'] ?>)</option>
            <?php endforeach; ?>
        </select>

        <label for="nome_jogo">Nome do Jogo:</label>
        <input type="text" id="nome_jogo" name="nome_jogo" required>

        <label for="url_jogo">URL do Jogo:</label>
        <input type="text" id="url_jogo" name="url_jogo" required>

        <label for="imagem_jogo">URL da Imagem (opcional):</label>
        <input type="text" id="imagem_jogo" name="imagem_jogo">

        <button type="submit">Adicionar</button>
        <button type="reset">Cancelar</button>
        <a href="adm.php">Voltar</a>
    </form>
</body>
</html>