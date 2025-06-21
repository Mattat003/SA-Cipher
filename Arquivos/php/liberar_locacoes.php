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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body style="background: #181818; color: white;">
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
    </div>
</body>
</html>