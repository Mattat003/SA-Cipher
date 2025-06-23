<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['pk_usuario'])) {
    header('Location: login.php');
    exit();
}

$busca = trim($_GET['busca'] ?? '');
$categoria_tipo = $_GET['categoria_tipo'] ?? '';
$categoria_id = $_GET['categoria_id'] ?? '';

$sql = "SELECT DISTINCT j.* FROM jogo j
        LEFT JOIN jogo_genero jg ON j.pk_jogo = jg.jogo_id
        LEFT JOIN genero g ON jg.genero_id = g.pk_genero
        LEFT JOIN jogo_estilo je ON j.pk_jogo = je.jogo_id
        LEFT JOIN estilo e ON je.estilo_id = e.pk_estilo
        LEFT JOIN jogo_plataforma jp ON j.pk_jogo = jp.jogo_id
        LEFT JOIN plataforma p ON jp.plataforma_id = p.pk_plataforma
        WHERE j.disponivel_locacao = 1";

$params = [];

if ($busca !== '') {
    $sql .= " AND j.nome_jogo LIKE :busca";
    $params[':busca'] = "%$busca%";
}

if ($categoria_tipo !== '' && $categoria_id !== '') {
    if ($categoria_tipo === 'genero') {
        $sql .= " AND g.pk_genero = :categoria_id";
        $params[':categoria_id'] = $categoria_id;
    } elseif ($categoria_tipo === 'estilo') {
        $sql .= " AND e.pk_estilo = :categoria_id";
        $params[':categoria_id'] = $categoria_id;
    } elseif ($categoria_tipo === 'plataforma') {
        $sql .= " AND p.pk_plataforma = :categoria_id";
        $params[':categoria_id'] = $categoria_id;
    }
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$jogos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$generos = $pdo->query("SELECT pk_genero, nome_gen FROM genero ORDER BY nome_gen")->fetchAll(PDO::FETCH_ASSOC);
$estilos = $pdo->query("SELECT pk_estilo, nome_estilo FROM estilo ORDER BY nome_estilo")->fetchAll(PDO::FETCH_ASSOC);
$plataformas = $pdo->query("SELECT pk_plataforma, nome_plat FROM plataforma ORDER BY nome_plat")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Jogos para Locação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="icon" type="image/png" href="../img/capybara.png" />
    <link rel="stylesheet" href="../css/jogos_locacao.css" />
    <style>
        .filtro-form {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        .filtro-form input,
        .filtro-form select,
        .filtro-form button {
            padding: 10px;
            font-size: 1em;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .filtro-form button {
            background: #510d96;
            color: white;
            border: none;
            transition: 0.2s;
        }
        .filtro-form button:hover {
            background: #2c056e;
        }
        h6{
            text-align: left;
            padding-left: 25px;
        }
    </style>
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

<form method="GET" class="filtro-form">
    <input type="text" name="busca" placeholder="Buscar por nome..." value="<?= htmlspecialchars($busca) ?>" />

    <select name="categoria_tipo" id="categoria_tipo">
        <option value="">Selecionar Categoria </option>

        <optgroup label="Gêneros">
            <?php foreach ($generos as $gen): ?>
                <option value="genero" <?= ($categoria_tipo === 'genero' && $categoria_id == $gen['pk_genero']) ? 'selected' : '' ?> data-id="<?= $gen['pk_genero'] ?>">
                    <?= htmlspecialchars($gen['nome_gen']) ?>
                </option>
            <?php endforeach; ?>
        </optgroup>

        <optgroup label="Estilos">
            <?php foreach ($estilos as $est): ?>
                <option value="estilo" <?= ($categoria_tipo === 'estilo' && $categoria_id == $est['pk_estilo']) ? 'selected' : '' ?> data-id="<?= $est['pk_estilo'] ?>">
                    <?= htmlspecialchars($est['nome_estilo']) ?>
                </option>
            <?php endforeach; ?>
        </optgroup>

        <optgroup label="Plataformas">
            <?php foreach ($plataformas as $plat): ?>
                <option value="plataforma" <?= ($categoria_tipo === 'plataforma' && $categoria_id == $plat['pk_plataforma']) ? 'selected' : '' ?> data-id="<?= $plat['pk_plataforma'] ?>">
                    <?= htmlspecialchars($plat['nome_plat']) ?>
                </option>
            <?php endforeach; ?>
        </optgroup>
    </select>

    <input type="hidden" name="categoria_id" id="categoria_id" value="<?= htmlspecialchars($categoria_id) ?>" />

    <button type="submit">Filtrar</button>
</form>

<div class="games-container">
    <?php if (count($jogos) === 0): ?>
        <div style="color: white; text-align:center;">Nenhum jogo encontrado com os critérios selecionados.</div>
    <?php endif; ?>

    <?php foreach ($jogos as $jogo): ?>
        <div class="game-tile">
            <img src="<?= htmlspecialchars($jogo['imagem_jogo']) ? htmlspecialchars($jogo['imagem_jogo']) : '../img/semImage.jpg'?>" alt="<?= htmlspecialchars($jogo['nome_jogo']) ?>" />
            <h3><?= htmlspecialchars($jogo['nome_jogo']) ?></h3>
            <h6><?= htmlspecialchars($jogo['desenvolvedora']) ?></h6>
            <form method="post" action="locar_jogo.php">
                <input type="hidden" name="jogo_id" value="<?= $jogo['pk_jogo'] ?>">
                <button type="submit" class="locar-btn">Locar Jogo</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<script>
    const selectCategoria = document.getElementById('categoria_tipo');
    const hiddenCategoriaId = document.getElementById('categoria_id');

    selectCategoria.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const categoriaId = selectedOption.getAttribute('data-id') || '';
        hiddenCategoriaId.value = categoriaId;
    });

    window.addEventListener('DOMContentLoaded', () => {
        const selectedOption = selectCategoria.options[selectCategoria.selectedIndex];
        const categoriaId = selectedOption.getAttribute('data-id') || '';
        hiddenCategoriaId.value = categoriaId;
    });
</script>

</body>
</html>
