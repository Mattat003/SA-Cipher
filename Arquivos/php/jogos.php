<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['pk_usuario'])) {
    header('Location: login.php');
    exit();
}

// Consulta jogos disponíveis para locação
$stmt = $pdo->prepare("SELECT * FROM jogo WHERE disponivel_locacao = 1");
$stmt->execute();
$jogos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Jogos para Locação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="icon" type="image/png" href="../img/capybara.png" />
    <link rel="stylesheet" href="../css/jogos_locacao.css" />
</head>
<body>
    <header>
        <div class="logo">
            <h1>CIPHER</h1>
            <img src="../img/capybara.png" alt="Logo Capivara" height="48" />
        </div>
        <a href="index.php" class="voltar-btn">Voltar para o Início</a>
    </header>
    <div class="header">JOGOS PARA LOCAÇÃO</div>
    <div class="games-container">
        <?php foreach ($jogos as $jogo): ?>
            <div class="game-tile">
            <img src="<?= htmlspecialchars($jogo['imagem_jogo']) ?>" alt="<?= htmlspecialchars($jogo['nome_jogo']) ?>" />
                <h3><?= htmlspecialchars($jogo['nome_jogo']) ?></h3>
                <form method="post" action="locar_jogo.php">
                    <input type="hidden" name="jogo_id" value="<?= $jogo['pk_jogo'] ?>">
                    <button type="submit" class="locar-btn">Locar Jogo</button>
                </form>
            </div>
        <?php endforeach; ?>
        <?php if (count($jogos) == 0): ?>
            <div style="color: white;">Nenhum jogo disponível para locação no momento.</div>
        <?php endif; ?>
    </div>
</body>
</html>