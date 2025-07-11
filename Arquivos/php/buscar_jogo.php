<?php
session_start();
require_once 'conexao.php';

// Só permite acesso para cargos 1 e 2
$fk_cargo = $_SESSION['fk_cargo'] ?? null;
if ($fk_cargo != 1 && $fk_cargo != 2) {
    echo "Acesso negado";
    exit;
}

// Recupera os filtros do formulário
$busca = trim($_GET['busca'] ?? '');           // Filtro pelo nome do jogo
$genero = $_GET['genero'] ?? '';               // Filtro pelo gênero
$plataforma = $_GET['plataforma'] ?? '';       // Filtro pela plataforma
$resultados = [];

// Carrega opções de gênero e plataforma para os selects do formulário
$generos = $pdo->query("SELECT pk_genero, nome_gen FROM genero ORDER BY nome_gen")->fetchAll(PDO::FETCH_ASSOC);
$plataformas = $pdo->query("SELECT pk_plataforma, nome_plat FROM plataforma ORDER BY nome_plat")->fetchAll(PDO::FETCH_ASSOC);

// Monta a query de busca de jogos de acordo com os filtros selecionados
$sql = "SELECT j.* FROM jogo j
    LEFT JOIN jogo_genero jg ON j.pk_jogo = jg.jogo_id
    LEFT JOIN jogo_plataforma jp ON j.pk_jogo = jp.jogo_id
    WHERE 1=1"; // Inicia o WHERE para facilitar a concatenação dos outros filtros
$params = [];

if ($busca !== '') {
    $sql .= " AND j.nome_jogo LIKE :busca";
    $params[':busca'] = "%$busca%";
}
if ($genero !== '') {
    $sql .= " AND jg.genero_id = :genero";
    $params[':genero'] = $genero;
}
if ($plataforma !== '') {
    $sql .= " AND jp.plataforma_id = :plataforma";
    $params[':plataforma'] = $plataforma;
}

// Agrupa para evitar jogos duplicados caso tenha múltiplos gêneros/plataformas
$sql .= " GROUP BY j.pk_jogo ORDER BY j.nome_jogo ASC";

// Executa a consulta
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Buscar Jogos Físicos</title>
    <style>
    body {
        font-family: 'Motiva Sans', 'Segoe UI', sans-serif;
        background: #12002b;
        color: #f0e6ff;
        margin: 0;
        padding: 20px;
        line-height: 1.6;
    }
    .container {
        background: #1e1b2e;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        max-width: 800px;
        margin: 0 auto 40px auto;
    }
    h2, h3 {
        color: #c7b3e6;
        text-align: center;
        margin-bottom: 30px;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
    }
    .form-area {
        max-width: 100%;
        margin: 20px auto 40px auto;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .form-area input[type="text"], .form-area select {
        flex: 1;
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #3e2f6d;
        background: #1e1b2e;
        color: #f0e6ff;
        font-size: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        transition: border-color 0.3s, background-color 0.3s;
    }
    .form-area input[type="text"]:focus, .form-area select:focus {
        border-color: #9d7aff;
        background-color: #251f3d;
        outline: none;
        box-shadow: 0 0 0 3px rgba(157, 122, 255, 0.3);
    }
    .form-area button {
        padding: 12px 25px;
        background: #510d96;
        color: #fff;
        font-weight: 600;
        border: 1px solid #510d96;
        border-radius: 8px;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
    }
    .form-area button:hover {
        background: #7a5af5;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        transform: translateY(-1px);
    }
    .form-area button:active {
        transform: translateY(0);
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: #1e1b2e;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        border-radius: 10px;
        overflow: hidden;
    }
    table th, table td {
        border: 1px solid #3e2f6d;
        padding: 15px 18px;
        text-align: center;
        vertical-align: middle;
        color: #e9d5ff;
    }
    table th {
        background: #2a1f4d;
        color: #d4c2f0;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.9em;
    }
    table tr:nth-child(even) {
        background: #1a1828;
    }
    table tr:nth-child(odd) {
        background: #251f3d;
    }
    table tr:hover {
        background: #2d2449;
    }
    table a {
        color: #9d7aff;
        text-decoration: none;
        margin-right: 12px;
        font-weight: 600;
        transition: color 0.2s, text-decoration 0.2s;
    }
    table a:hover {
        color: #f0e6ff;
        text-decoration: underline;
    }
    img {
        max-width: 70px;
        max-height: 70px;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.5);
    }
    p {
        text-align: center;
        font-size: 1.1em;
        color: #c7b3e6;
        margin-top: 20px;
        padding: 12px;
        border: 1px dashed #3e2f6d;
        border-radius: 8px;
        background: #1e1b2e;
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
        .form-area {
            flex-direction: column;
            max-width: 100%;
        }
    }
    @media (max-width: 480px) {
        h2, h3 {
            font-size: 1.8em;
        }
        .back-link {
            max-width: 100%;
        }
        img {
            max-width: 50px;
            max-height: 50px;
        }
    }
    </style>
</head>
<body>
    <h2>Buscar Jogos</h2>
    <form method="get" action="buscar_jogo.php" autocomplete="off" class="form-area">
        <input type="text" name="busca" placeholder="Nome do jogo..." value="<?= htmlspecialchars($busca) ?>">
        <select name="genero">
            <option value="">Todos os Gêneros</option>
            <?php foreach ($generos as $gen): ?>
                <option value="<?= $gen['pk_genero'] ?>" <?= ($genero == $gen['pk_genero']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($gen['nome_gen']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="plataforma">
            <option value="">Todas as Plataformas</option>
            <?php foreach ($plataformas as $plat): ?>
                <option value="<?= $plat['pk_plataforma'] ?>" <?= ($plataforma == $plat['pk_plataforma']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($plat['nome_plat']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Buscar</button>
    </form>

    <h3>Resultados (<?= count($resultados) ?>):</h3>
    <?php if (count($resultados) > 0): ?>
        <table>
            <tr>
                <th>Imagem</th>
                <th>Nome</th>
                <th>Data de Lançamento</th>
            </tr>
            <?php foreach ($resultados as $jogo): ?>
                <tr>
                    <td>
                        <?php if (!empty($jogo['imagem_jogo'])): ?>
                            <img src="<?= htmlspecialchars($jogo['imagem_jogo']) ?>" alt="<?= htmlspecialchars($jogo['nome_jogo']) ?>">
                        <?php else: ?>
                            <span>Sem imagem</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($jogo['nome_jogo']) ?></td>
                    <td>
                        <?= ($jogo['data_lanc'] && $jogo['data_lanc'] != '0000-00-00') 
                            ? date('d/m/Y', strtotime($jogo['data_lanc'])) 
                            : 'Indefinida' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Nenhum jogo encontrado.</p>
    <?php endif; ?>
    <a href="adm.php" class="back-link">Voltar</a>
</body>
</html>