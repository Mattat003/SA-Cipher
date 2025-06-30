<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;
if ($fk_cargo != 1 && $fk_cargo != 4) {
    echo "Acesso negado";
    exit;
}

$jogo = null;

// Carrega listas de categorias
$generos = $pdo->query("SELECT pk_genero, nome_gen FROM genero ORDER BY nome_gen")->fetchAll(PDO::FETCH_ASSOC);
$estilos = $pdo->query("SELECT pk_estilo, nome_estilo FROM estilo ORDER BY nome_estilo")->fetchAll(PDO::FETCH_ASSOC);
$plataformas = $pdo->query("SELECT pk_plataforma, nome_plat FROM plataforma ORDER BY nome_plat")->fetchAll(PDO::FETCH_ASSOC);

$generos_selecionados = [];
$estilos_selecionados = [];
$plataformas_selecionadas = [];

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

    if ($jogo) {
        // Busca categorias já associadas
        $stmt = $pdo->prepare("SELECT genero_id FROM jogo_genero WHERE jogo_id = ?");
        $stmt->execute([$jogo['pk_jogo']]);
        $generos_selecionados = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $stmt = $pdo->prepare("SELECT estilo_id FROM jogo_estilo WHERE jogo_id = ?");
        $stmt->execute([$jogo['pk_jogo']]);
        $estilos_selecionados = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $stmt = $pdo->prepare("SELECT plataforma_id FROM jogo_plataforma WHERE jogo_id = ?");
        $stmt->execute([$jogo['pk_jogo']]);
        $plataformas_selecionadas = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } else {
        echo "<script>alert('Jogo não encontrado!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Alterar Jogo</title>
    <style>
        body {
            background-color: #12002b;
            color: #f0e6ff;
            font-family: 'Motiva Sans', 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 150vh;
            line-height: 1.6;
        }

        h2 {
            color: #c084fc;
            text-align: center;
            margin-top: 20px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .container {
            max-width: 700px;
            margin: 30px auto;
            background: #1e1b2e;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
            border: 1px solid #5d3bad;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #e9d5ff;
        }

        input[type="text"], input[type="date"], input[type="file"], select {
            width: 100%;
            padding: 12px 15px;
            margin-top: 8px;
            background: #252836;
            border: 1px solid #2a2540;
            border-radius: 8px;
            color: #f0e6ff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            font-size: 15px;
            box-sizing: border-box;
        }

        input:focus, select:focus {
            border-color: #7a5af5;
            background-color: #2a2e3c;
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 90, 245, 0.3);
        }

        .btn-custom {
            display: block;
            margin: 20px auto 0 auto;
            padding: 12px 30px;
            background: #510d96;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            text-align: center;
            text-decoration: none;
        }

        button:hover, .btn-custom:hover {
            background-color: #7a5af5;
            box-shadow: 0 0 12px rgba(122, 90, 245, 0.4);
        }

        img {
            margin-top: 10px;
            border-radius: 8px;
            border: 2px solid #5d3bad;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.6);
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            background-color: #2d1b4a;
            color: #c084fc;
            text-align: center;
            border: 1px solid #7a5af5;
        }

        .back-link {
        display: block;
        text-align: center;
        margin-top: 30px;
        color: #fff;
        background: #510d96;
        text-decoration: none;
        font-weight: 600;
        padding: 12px 25px;
        border: 1px solid #510d96;
        border-radius: 8px;
        max-width: 200px;
        margin-left: auto;
        margin-right: auto;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .back-link:hover {
        background: #7a5af5;
        color: #fff;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        transform: translateY(-1px);
    }

    .back-link:active {
        transform: translateY(0);
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    /* Responsivo */
    @media (max-width: 768px) {
        .container {
            margin: 10px;
            padding: 20px;
        }

        table th, table td {
            padding: 10px;
            font-size: 0.85em;
        }

        table a {
            margin-right: 5px;
        }
    }

    @media (max-width: 480px) {
        h2 {
            font-size: 1.8em;
        }

        .back-link {
            max-width: 100%;
        }
    }

    </style>
</head>
<body>
<div class="container">
    <h2>Alterar Jogo</h2>

    <!-- Formulário de busca -->
    <form method="post" class="mb-4">
        <label class="form-label">Buscar jogo por nome ou ID:</label>
        <input type="text" name="busca_jogo" class="form-control" required>
        <button type="submit" class="btn-custom">Buscar</button>
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

            <!-- Categorias -->
            <div class="mb-3">
                <label class="form-label">Gênero(s)</label>
                <select name="generos[]" multiple class="form-control" size="5">
                    <?php foreach ($generos as $g): ?>
                        <option value="<?= $g['pk_genero'] ?>" <?= in_array($g['pk_genero'], $generos_selecionados) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($g['nome_gen']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small style="color:#c084fc">Segure Ctrl (Windows) ou Command (Mac) para selecionar mais de um.</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Estilo(s)</label>
                <select name="estilos[]" multiple class="form-control" size="4">
                    <?php foreach ($estilos as $e): ?>
                        <option value="<?= $e['pk_estilo'] ?>" <?= in_array($e['pk_estilo'], $estilos_selecionados) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($e['nome_estilo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Plataforma(s)</label>
                <select name="plataformas[]" multiple class="form-control" size="5">
                    <?php foreach ($plataformas as $p): ?>
                        <option value="<?= $p['pk_plataforma'] ?>" <?= in_array($p['pk_plataforma'], $plataformas_selecionadas) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['nome_plat']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- Fim categorias -->

            <div class="btn-row">
                <button type="submit" class="btn-custom">Salvar Alterações</button>
            </div>
        </form>
    <?php endif; ?>

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success mt-3">Jogo alterado com sucesso!</div>
    <?php endif; ?>
    
</div>
<a href="adm.php" class="back-link">Voltar</a>
</body>
</html>