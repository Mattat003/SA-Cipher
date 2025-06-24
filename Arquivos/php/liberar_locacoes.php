<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;
$nomeCargo = '';
$nome = $_SESSION['adm'] ?? 'Funcionário';

// Buscar locações pendentes
$stmt = $pdo->prepare("
    SELECT l.id, u.nome_user, j.nome_jogo, l.data_pedido 
    FROM locacoes_pendentes l
    JOIN usuario u ON l.usuario_id = u.pk_usuario
    JOIN jogo j ON l.jogo_id = j.pk_jogo
    WHERE l.status = 'pendente'
    ORDER BY l.data_pedido ASC
");
$stmt->execute();
$locacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Liberação de Locações</title>
    <style>
        body {
            background-color: #12002b;
            font-family: 'Motiva Sans', 'Segoe UI', sans-serif;
            color: #f0e6ff;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }

        h2 {
            text-align: center;
            color: #c084fc;
            margin-bottom: 30px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #1e1b2e;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6);
            border: 1px solid #5d3bad;
            border-radius: 12px;
            overflow: hidden;
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
        }

        th {
            background-color: #2e2152;
            color: #c084fc;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        tr:nth-child(even) {
            background-color: #252836;
        }

        tr:hover {
            background-color: #322f4c;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
        }

        .btn-success {
            background: #510d96;
        }

        .btn-success:hover {
            background: #7a5af5;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            transform: translateY(-1px);
        }

        .btn-danger {
            background: #b42348;
        }

        .btn-danger:hover {
            background: #e11d48;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            transform: translateY(-1px);
        }

        .alert {
            background: #2e2152;
            border: 1px solid #7a5af5;
            color: #e9d5ff;
            padding: 15px;
            border-radius: 8px;
            max-width: 500px;
            margin: 20px auto;
            text-align: center;
        }

        form {
            display: inline;
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
    <div class="container mt-5">
        <h2>Pedidos de Locação Pendentes</h2>
        <?php if (count($locacoes) > 0): ?>
        <table class="table table-dark table-bordered mt-4">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Jogo</th>
                    <th>Data do Pedido</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($locacoes as $loc): ?>
                <tr>
                    <td><?= htmlspecialchars($loc['nome_user']) ?></td>
                    <td><?= htmlspecialchars($loc['nome_jogo']) ?></td>
                    <td><?= htmlspecialchars($loc['data_pedido']) ?></td>
                    <td>
                        <form method="post" action="processar_liberacao.php" style="display:inline;">
                            <input type="hidden" name="locacao_id" value="<?= $loc['id'] ?>">
                            <button type="submit" name="acao" value="liberar" class="btn btn-success btn-sm">Liberar</button>
                        </form>
                        <form method="post" action="processar_liberacao.php" style="display:inline;">
                            <input type="hidden" name="locacao_id" value="<?= $loc['id'] ?>">
                            <button type="submit" name="acao" value="recusar" class="btn btn-danger btn-sm">Recusar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="alert alert-info">Nenhum pedido de locação pendente.</div>
        <?php endif; ?>
        <a href="adm.php" class="back-link">Voltar</a>
    </div>
</body>
</html>