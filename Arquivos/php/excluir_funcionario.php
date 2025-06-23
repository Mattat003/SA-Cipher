<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;

// Permissão de acesso
if ($fk_cargo != 1 && $fk_cargo != 4) {
    echo "Acesso negado";
    exit;
}

// Excluir funcionário da tabela adm
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_adm = $_GET['id'];
    $sql = "DELETE FROM adm WHERE pk_adm = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_adm, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Funcionário excluído com sucesso!'); window.location.href='excluir_funcionario.php';</script>";
        exit;
    } else {
        echo "<script>alert('Erro ao excluir o funcionário!');</script>";
    }
}

// Buscar todos os funcionários da tabela adm
$sql = "SELECT * FROM adm ORDER BY nome_adm ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$adms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Funcionário</title>
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
    <h2>Excluir Funcionário</h2>

    <?php if (!empty($adms)): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th> Cargo </th>
                <th>Ações</th>
            </tr>
            <?php foreach ($adms as $adm): ?>
                <tr>
                    <td><?= htmlspecialchars($adm['pk_adm']) ?></td>
                    <td><?= htmlspecialchars($adm['nome_adm']) ?></td>
                    <td><?= htmlspecialchars($adm['email_adm']) ?></td>
                    <td><?= htmlspecialchars($adm['fk_cargo']) ?></td> 
                    <td>
                        <a href="excluir_funcionario.php?id=<?= htmlspecialchars($adm['pk_adm']) ?>" onclick="return confirm('Tem certeza que deseja excluir este funcionário?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Nenhum funcionário encontrado.</p>
    <?php endif; ?>

    <br>
    <a class="back-link" href="adm.php">Voltar</a>
</body>
</html>