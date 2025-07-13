<?php
session_start();
require_once 'conexao.php';

// Permissão de acesso: só ADM (1) acessa
$fk_cargo = $_SESSION['fk_cargo'] ?? null;
if ($fk_cargo != 1) {
    echo "Acesso negado!";
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_jogo = $_GET['id'];
    $sql = "DELETE FROM jogo WHERE pk_jogo = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_jogo, PDO::PARAM_INT);

    try {
        if ($stmt->execute()) {
            echo "<script>alert('Jogo excluído com sucesso!'); window.location.href='excluir_jogo.php';</script>";
            exit;
        } else {
            echo "<script>alert('Erro ao excluir o jogo!');</script>";
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "<script>alert('Não é possível excluir este jogo pois existem locações pendentes vinculadas a ele.'); window.location.href='excluir_jogo.php';</script>";
        } else {
            echo "<script>alert('Erro ao excluir: " . htmlspecialchars($e->getMessage()) . "');</script>";
        }
    }
}
// Buscar todos os jogos para listar na tabela
$sql = "SELECT pk_jogo, nome_jogo, data_lanc, imagem_jogo FROM jogo ORDER BY nome_jogo ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$jogos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Jogo</title>
    <style>
body {
    font-family: 'Motiva Sans', 'Segoe UI', sans-serif;
    background: #12002b;
    color: #f0e6ff;
    margin: 0;
    padding: 20px;
    line-height: 1.6;
}

h2 {
    color: #c7b3e6;
    text-align: center;
    margin-bottom: 30px;
    font-size: 2em;
    font-weight: 700;
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
    text-align: left;
    vertical-align: middle;
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

table tr:hover {
    background: #2d2449;
}

table a,
button.btn-excluir {
    color: #9d7aff;
    text-decoration: none;
    margin-right: 12px;
    font-weight: 600;
    transition: color 0.2s, text-decoration 0.2s, background 0.2s;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1em;
    padding: 0;
}

table a:hover,
button.btn-excluir:hover {
    color: #f0e6ff;
    text-decoration: underline;
    background: none;
}

img {
    max-width: 70px;
    max-height: 70px;
    border-radius: 6px;
    display: block;
    margin: 0 auto;
}

p {
    text-align: center;
    color: #c7b3e6;
    margin-top: 20px;
    font-size: 1.1em;
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

    table a,
    button.btn-excluir {
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

    img {
        max-width: 40px;
        max-height: 40px;
    }
}
    </style>
</head>
<body>
    <h2>Excluir Jogo</h2>

    <?php if (!empty($jogos)): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Imagem</th>
                <th>Nome</th>
                <th>Data de Lançamento</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($jogos as $jogo): ?>
                <tr>
                    <td><?= htmlspecialchars($jogo['pk_jogo']) ?></td>
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
                 
                    <td>
                        <a href="excluir_jogo.php?id=<?= htmlspecialchars($jogo['pk_jogo']) ?>" onclick="return confirm('Tem certeza que deseja excluir este jogo?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Nenhum jogo encontrado.</p>
    <?php endif; ?>

    <br>
    <a class="back-link" href="adm.php">Voltar</a>
</body>
</html>