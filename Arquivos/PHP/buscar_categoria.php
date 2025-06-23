<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['fk_cargo']) || ($_SESSION['fk_cargo'] != 1 && $_SESSION['fk_cargo'] != 2)) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit;
}

// Categorias disponíveis
$categorias = [
    'genero' => ['pk_genero', 'nome_gen'],
    'idioma' => ['pk_idioma', 'nome_idioma'],
    'tema' => ['pk_tema', 'nome_tema'],
    'plataforma' => ['pk_plataforma', 'nome_plat'],
    'modo' => ['pk_modo', 'nome_modo'],
    'estilo' => ['pk_estilo', 'nome_estilo']
];

$tipo = $_GET['tipo'] ?? null;
$busca_realizada = false;
$resultados = [];

if ($tipo && isset($categorias[$tipo])) {
    $col_id = $categorias[$tipo][0];
    $col_nome = $categorias[$tipo][1];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $busca = trim($_POST['busca'] ?? '');

        $sql = "SELECT $col_id, $col_nome FROM $tipo";
        $params = [];

        if ($busca !== '') {
            if (is_numeric($busca)) {
                $sql .= " WHERE $col_id = :busca_id";
                $params[':busca_id'] = $busca;
            } else {
                $sql .= " WHERE $col_nome LIKE :busca_nome";
                $params[':busca_nome'] = "%" . $busca . "%";
            }
        }

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
        body { font-family: Arial; background: #f6f6fa; padding: 20px; }
        .container { max-width: 850px; margin: auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        h2 { color: #2c056e; text-align: center; }
        .btn-group {
            display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-bottom: 20px;
        }
        .btn-group a {
            padding: 10px 18px; background: #510d96; color: white; text-decoration: none; border-radius: 6px;
            font-weight: bold; transition: background 0.3s;
        }
        .btn-group a:hover { background: #2c056e; }
        form {
            display: flex; gap: 10px; justify-content: center; margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 10px; border: 1px solid #ccc; border-radius: 5px; width: 300px;
        }
        button {
            padding: 10px 20px; background: #510d96; color: white; border: none; border-radius: 5px; cursor: pointer;
        }
        button:hover { background: #2c056e; }
        table {
            width: 100%; border-collapse: collapse; margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd; padding: 10px; text-align: left;
        }
        th {
            background: #e6e1f4; color: #2c056e;
        }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .msg {
            text-align: center; color: #555; font-style: italic; margin-top: 20px;
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

        <a href="adm.php" class="back-link">Voltar ao Painel</a>
    </div>
</body>
</html>
