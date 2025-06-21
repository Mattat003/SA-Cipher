<?php
session_start();
require_once 'conexao.php';
$fk_cargo = $_SESSION['fk_cargo'] ?? null;
$nomeCargo = '';
$nome = $_SESSION['adm'] ?? 'Funcionário';

$codigos = $pdo->query("SELECT pk_codgame, codigo FROM codigo_game")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_jogo = $_POST['nome_jogo'];
    $data_lanc = $_POST['data_lanc'];
    $fk_codigo = $_POST['fk_codigo'];
    $url_jogo = $_POST['url_jogo'];
    $imagem_jogo = null;

    // Crie a pasta uploads se não existir
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    if (isset($_FILES['imagem_jogo']) && $_FILES['imagem_jogo']['error'] == 0) {
        $ext = pathinfo($_FILES['imagem_jogo']['name'], PATHINFO_EXTENSION);
        $imagem_jogo = 'uploads/' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['imagem_jogo']['tmp_name'], $imagem_jogo);
    }

    $stmt = $pdo->prepare(
        "INSERT INTO jogo (nome_jogo, data_lanc, fk_codigo, imagem_jogo, url_jogo, disponivel_locacao)
         VALUES (?, ?, ?, ?, ?, 1)"
    );
    $stmt->execute([$nome_jogo, $data_lanc, $fk_codigo, $imagem_jogo, $url_jogo]);
    header('Location: cadastrar_jogo.php?sucesso=1');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Cadastrar Jogo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark text-light">
<div class="container mt-5">
    <h2>Cadastrar Novo Jogo</h2>
    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success">Jogo cadastrado com sucesso!</div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Nome do Jogo</label>
            <input type="text" name="nome_jogo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Data de Lançamento</label>
            <input type="date" name="data_lanc" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Código do Game</label>
            <select name="fk_codigo" class="form-control" required>
                <option value="">Selecione</option>
                <?php foreach ($codigos as $codigo): ?>
                    <option value="<?= $codigo['pk_codgame'] ?>"><?= htmlspecialchars($codigo['codigo']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Link do Jogo</label>
            <input type="text" name="url_jogo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Imagem do Jogo</label>
            <input type="file" name="imagem_jogo" class="form-control" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Cadastrar Jogo</button>
    </form>
</div>
</body>
</html>