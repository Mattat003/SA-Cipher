<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;

if ($fk_cargo != 1 && $fk_cargo != 4) {
    echo "Acesso negado";
    exit;
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario_id = intval($_POST['usuario_id'] ?? 0);
    $jogo_id = intval($_POST['jogo_id'] ?? 0);
    $quantidade = intval($_POST['quantidade'] ?? 1);

    // Busca dados do usuário
    $stmt = $pdo->prepare("SELECT nome_user, email_user FROM usuario WHERE pk_usuario = ?");
    $stmt->execute([$usuario_id]);
    $cliente = $stmt->fetch();

    // Busca dados do jogo físico
    $stmt = $pdo->prepare("SELECT estoque, preco, nome_jogo FROM jogo_fisico WHERE pk_jogo_fisico = ?");
    $stmt->execute([$jogo_id]);
    $jogo = $stmt->fetch();

    if (!$cliente) {
        $mensagem = "<p style='color:red;'>Usuário não encontrado.</p>";
    } elseif (!$jogo) {
        $mensagem = "<p style='color:red;'>Jogo não encontrado.</p>";
    } elseif ($jogo['estoque'] < $quantidade) {
        $mensagem = "<p style='color:red;'>Estoque insuficiente para {$jogo['nome_jogo']}.</p>";
    } else {
        // Registrar venda (inserir em compras)
        $stmt = $pdo->prepare("INSERT INTO compras (nome, email, cartao, data_validade, jogo_id, data_compra) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([
            $cliente['nome_user'],
            $cliente['email_user'],
            'VENDA-FISICA',
            '00/00',
            $jogo_id
        ]);
        // Atualizar estoque
        $stmt = $pdo->prepare("UPDATE jogo_fisico SET estoque = estoque - ? WHERE pk_jogo_fisico = ?");
        $stmt->execute([$quantidade, $jogo_id]);

        // Busca um código não utilizado do jogo
        $stmt = $pdo->prepare("SELECT pk_codgame, codigo FROM codigo_game WHERE usado = 0 AND jogo_id = ? LIMIT 1");
        $stmt->execute([$jogo_id]);
        $codigo = $stmt->fetch();

        if ($codigo) {
            // Marca o código como usado e associa ao usuário
            $update = $pdo->prepare("UPDATE codigo_game SET usado = 1, usuario_id = ? WHERE pk_codgame = ?");
            $update->execute([$usuario_id, $codigo['pk_codgame']]);
            $mensagem = "<p style='color:green;'>Venda registrada com sucesso!<br>Seu código: <b>{$codigo['codigo']}</b></p>";
        } else {
            $mensagem = "<p style='color:green;'>Venda registrada, porém não há código digital disponível para esse jogo.</p>";
        }
    }
}

// Buscar usuários e jogos para os selects
$usuarios = $pdo->query("SELECT pk_usuario, nome_user FROM usuario ORDER BY nome_user")->fetchAll(PDO::FETCH_ASSOC);
$jogos = $pdo->query("SELECT pk_jogo_fisico, nome_jogo, estoque FROM jogo_fisico WHERE estoque > 0 ORDER BY nome_jogo")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registrar Venda de Jogo Físico</title>
</head>
<body>
    <h2>Registrar Venda de Jogo Físico</h2>
    <?= $mensagem ?>
    <form method="post" action="registrar_venda.php" autocomplete="off">
        <label>Cliente:</label>
        <select name="usuario_id" required>
            <option value="">Selecione</option>
            <?php foreach ($usuarios as $u): ?>
                <option value="<?= $u['pk_usuario'] ?>"><?= htmlspecialchars($u['nome_user']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Jogo Físico:</label>
        <select name="jogo_id" required>
            <option value="">Selecione</option>
            <?php foreach ($jogos as $j): ?>
                <option value="<?= $j['pk_jogo_fisico'] ?>">
                    <?= htmlspecialchars($j['nome_jogo']) ?> (Estoque: <?= $j['estoque'] ?>)
                </option>
            <?php endforeach; ?>
        </select><br>

        <label>Quantidade:</label>
        <input type="number" name="quantidade" min="1" value="1" required><br>

        <button type="submit">Registrar Venda</button>
    </form>
</body>
</html>