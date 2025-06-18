<?php
session_start();
require_once 'conexao.php';
require_once 'funcoes.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;
if ($fk_cargo != 1 && $fk_cargo != 4) {
    echo "Acesso negado";
    exit;
}

$mensagem = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $codigo = trim($_POST['codigo'] ?? '');
    $jogo_id = intval($_POST['jogo_id'] ?? 0);
    if (!$codigo) {
        $codigo = gerarCodigoJogo();
    }
    if ($jogo_id > 0 && $codigo) {
        $stmt = $pdo->prepare("INSERT INTO codigo_game (codigo, jogo_id, usado) VALUES (?, ?, 0)");
        $stmt->execute([$codigo, $jogo_id]);
        $mensagem = "<p style='color:green;'>Código cadastrado: <b>$codigo</b></p>";
    } else {
        $mensagem = "<p style='color:red;'>Selecione o jogo e informe/cadastre o código.</p>";
    }
}

$jogos = $pdo->query("SELECT pk_jogo_fisico, nome_jogo FROM jogo_fisico ORDER BY nome_jogo")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Código de Jogo</title>
</head>
<body>
    <h2>Cadastrar Código Digital para Jogo</h2>
    <?= $mensagem ?>
    <form method="post" action="adicionar_codigo.php">
        <label>Jogo:</label>
        <select name="jogo_id" required>
            <option value="">Selecione</option>
            <?php foreach ($jogos as $j): ?>
                <option value="<?= $j['pk_jogo_fisico'] ?>"><?= htmlspecialchars($j['nome_jogo']) ?></option>
            <?php endforeach; ?>
        </select><br>
        <label>Código (deixe em branco para gerar automaticamente):</label>
        <input type="text" name="codigo" maxlength="40"><br>
        <button type="submit">Cadastrar Código</button>
    </form>
</body>
</html>