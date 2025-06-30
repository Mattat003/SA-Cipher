<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário tem permissão de acesso (admin ou cargo 2)
if (!isset($_SESSION['fk_cargo']) || ($_SESSION['fk_cargo'] != 1 && $_SESSION['fk_cargo'] != 2)) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit;
}

// Define as tabelas e colunas de cada tipo de categoria disponível para busca
$categorias = [
    'genero' => ['pk_genero', 'nome_gen'],
    'idioma' => ['pk_idioma', 'nome_idioma'],
    'tema' => ['pk_tema', 'nome_tema'],
    'plataforma' => ['pk_plataforma', 'nome_plat'],
    'modo' => ['pk_modo', 'nome_modo'],
    'estilo' => ['pk_estilo', 'nome_estilo']
];

$tipo = $_GET['tipo'] ?? null; // tipo de categoria selecionado (ex: genero, idioma)
$busca_realizada = false;
$resultados = [];

if ($tipo && isset($categorias[$tipo])) {
    // Define os nomes das colunas para ID e Nome, conforme a categoria escolhida
    $col_id = $categorias[$tipo][0];
    $col_nome = $categorias[$tipo][1];

    // Se foi enviado o formulário de busca
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $busca = trim($_POST['busca'] ?? '');

        // Monta a query SQL de busca
        $sql = "SELECT $col_id, $col_nome FROM $tipo";
        $params = [];

        if ($busca !== '') {
            // Se busca for numérica, pesquisa por ID exato
            if (is_numeric($busca)) {
                $sql .= " WHERE $col_id = :busca_id";
                $params[':busca_id'] = $busca;
            } else {
                // Caso contrário, pesquisa por nome (LIKE)
                $sql .= " WHERE $col_nome LIKE :busca_nome";
                $params[':busca_nome'] = "%" . $busca . "%";
            }
        }

        // Executa uma consulta e armazena os resultados
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $busca_realizada = true;
        } catch (PDOException $e) {
            echo "<p style='color:red;'>Erro ao buscar: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Buscar Categorias</title>
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
    }

    .form-area input[type="text"] {
        flex: 1;
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #3e2f6d;
        background: #1e1b2e;
        color: #f0e6ff;
        font-size: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        outline-offset: 0;
        transition: border-color 0.3s, background-color 0.3s;
    }

    .form-area input[type="text"]:focus {
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
    .btn-group {
            display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-bottom: 20px;
        }
        .btn-group a {
            padding: 10px 18px; background: #510d96; color: white; text-decoration: none; border-radius: 6px;
            font-weight: bold; transition: background 0.3s;
        }
        .btn-group a:hover { background: #2c056e; }
        form {
            display: flex; flex-direction: column; gap: 12px; max-width: 400px; margin: 0 auto 20px auto;
            background:#2a0a4a; padding: 20px; border-radius: 10px;
        }
        input[type="text"] {
            padding: 10px; border: 1px solid #ccc; border-radius: 6px;
        }
        button {
            padding: 10px; background: #510d96; color: white; border: none; border-radius: 6px;
            font-weight: bold; cursor: pointer;
        }
        button:hover { background: #2c056e; }
        .msg {
            text-align: center; 
            color: #c7b3e6; 
            margin-bottom: 15px; 
            font-style: italic;
        }
        .back-link {
            display: block; text-align: center; margin-top: 30px; background: #510d96; color: #fff;
            padding: 12px; border-radius: 5px; text-decoration: none; width: 200px; margin-left: auto; margin-right: auto;
        }
        .back-link:hover { background: #2c056e; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Buscar por Categoria</h2>

        <div class="btn-group">
            <?php foreach ($categorias as $cat => $cols): ?>
                <a href="?tipo=<?= $cat ?>"><?= ucfirst($cat) ?></a>
            <?php endforeach; ?>
        </div>

        <?php if ($tipo && isset($categorias[$tipo])): ?>
            <form method="POST">
                <input type="text" name="busca" placeholder="Digite o ID ou nome da categoria" value="<?= htmlspecialchars($_POST['busca'] ?? '') ?>">
                <button type="submit">Buscar</button>
            </form>

            <?php if ($busca_realizada): ?>
                <?php if (!empty($resultados)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resultados as $linha): ?>
                                <tr>
                                    <td><?= htmlspecialchars($linha[$categorias[$tipo][0]]) ?></td>
                                    <td><?= htmlspecialchars($linha[$categorias[$tipo][1]]) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="msg">Nenhum resultado encontrado.</p>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <p class="msg">Escolha uma categoria para iniciar a busca.</p>
        <?php endif; ?>
    </div>
    <a href="adm.php" class="back-link">Voltar</a>
</body>
</html>
