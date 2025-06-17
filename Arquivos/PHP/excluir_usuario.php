<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;

if ($fk_cargo != 1 && $fk_cargo != 4) {
    echo "Acesso negado";
    exit;
}


// Inicializa a variável para armazenar os usuários
$usuarios = [];

// Busca todos os usuários cadastrados, em ordem alfabética
$sql = "SELECT * FROM usuario ORDER BY nome_user ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Se um ID for passado via GET, exclui o usuário
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_usuario = $_GET['id'];

    $sql = "DELETE FROM usuario WHERE pk_usuario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Usuário excluído com sucesso!'); window.location.href='excluir_usuario.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir o usuário!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Usuário</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background: #f6f6fa;
        margin: 0;
        padding: 20px;
        color: #333;
        line-height: 1.6;
    }

    h2 {
        color: #2c056e;
        text-align: center;
        margin-bottom: 30px;
        font-size: 2em;
        font-weight: 700;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border-radius: 8px;
        overflow: hidden;
    }

    table th, table td {
        border: 1px solid #eee;
        padding: 15px 18px;
        text-align: left;
        vertical-align: middle;
    }

    table th {
        background: #e6e1f4;
        color: #2c056e;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.9em;
    }

    table tr:nth-child(even) {
        background: #f9f9f9;
    }

    table tr:hover {
        background: #f0f0f0;
    }

    table a {
        color: #510d96;
        text-decoration: none;
        margin-right: 12px;
        font-weight: 500;
        transition: color 0.2s, text-decoration 0.2s;
    }

    table a:hover {
        color: #2c056e;
        text-decoration: underline;
    }

    p {
        text-align: center;
        color: #777;
        margin-top: 20px;
        font-size: 1.1em;
        padding: 10px;
        border: 1px dashed #ccc;
        border-radius: 5px;
        background: #fefefe;
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
        border-radius: 5px;
        max-width: 200px;
        margin-left: auto;
        margin-right: auto;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .back-link:hover {
        background: #2c056e;
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
    <h2>Excluir Usuário</h2>

    <?php if (!empty($usuarios)): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Data Criação</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['pk_usuario']) ?></td>
                    <td><?= htmlspecialchars($usuario['nome_user']) ?></td>
                    <td><?= htmlspecialchars($usuario['email_user']) ?></td>
                    <td><?= htmlspecialchars($usuario['data_criacao']) ?></td>
                    <td>
                        <a href="excluir_usuario.php?id=<?= htmlspecialchars($usuario['pk_usuario']) ?>" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Nenhum usuário encontrado.</p>
    <?php endif; ?>

    <br>
    <a href="adm.php">Voltar</a>
</body>
</html>
