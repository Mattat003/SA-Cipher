<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;
if ($fk_cargo != 1 && $fk_cargo != 4) {
    echo "Acesso negado";
    exit;
}

$jogo = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['busca_jogo'])) {
    $busca = trim($_POST['busca_jogo']);

    if (is_numeric($busca)) {
        $stmt = $pdo->prepare("SELECT * FROM jogo WHERE pk_jogo = ?");
        $stmt->execute([$busca]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM jogo WHERE nome_jogo LIKE ?");
        $stmt->execute(["%$busca%"]);
    }

    $jogo = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$jogo) {
        echo "<script>alert('Jogo não encontrado!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Alterar Jogo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark text-light">
<div class="container mt-5">
    <h2>Alterar Jogo</h2>

    <!-- Formulário de busca -->
    <form method="post" class="mb-4">
        <label class="form-label">Buscar jogo por nome ou ID:</label>
        <div class="input-group">
            <input type="text" name="busca_jogo" class="form-control" required>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
    </form>

    <!-- Exibe formulário de edição se jogo for encontrado -->
    <?php if ($jogo): ?>
        <form method="post" action="processa_alteracao_jogo.php" enctype="multipart/form-data">
            <input type="hidden" name="pk_jogo" value="<?= $jogo['pk_jogo'] ?>">

            <div class="mb-3">
                <label class="form-label">Nome do Jogo</label>
                <input type="text" name="nome_jogo" class="form-control" value="<?= htmlspecialchars($jogo['nome_jogo']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Data de Lançamento</label>
                <input type="date" name="data_lanc" class="form-control" value="<?= $jogo['data_lanc'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Desenvolvedora</label>
                <input type="text" name="desenvolvedora" class="form-control" value="<?= $jogo['desenvolvedora'] ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Link do Jogo</label>
                <input type="text" name="url_jogo" class="form-control" value="<?= htmlspecialchars($jogo['url_jogo']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Imagem Atual</label><br>
                <?php if ($jogo['imagem_jogo']): ?>
                    <img src="<?= $jogo['imagem_jogo'] ?>" alt="Imagem atual" style="max-width: 200px;"><br>
                <?php else: ?>
                    <em>Nenhuma imagem enviada.</em><br>
                <?php endif; ?>
                <label class="form-label mt-2">Nova Imagem (opcional)</label>
                <input type="file" name="imagem_jogo" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-success">Salvar Alterações</button>
            <a href="adm.php" class="btn btn-secondary">Cancelar</a>
        </form>
    <?php endif; ?>

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success mt-3">Jogo alterado com sucesso!</div>
    <?php endif; ?>
</div>
</body>
</html>
