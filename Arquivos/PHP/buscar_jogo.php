<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;

// Só permite acesso para cargos 1 e 4
if ($fk_cargo != 1 && $fk_cargo != 4) {
    echo "Acesso negado";
    exit;
}

$busca = trim($_GET['busca'] ?? '');
$resultados = [];

if ($busca !== '') {
    $stmt = $pdo->prepare(
        "SELECT * FROM jogo_fisico 
        WHERE nome_jogo LIKE ? OR plataforma LIKE ? OR desenvolvedora LIKE ?"
    );
    $like = "%$busca%";
    $stmt->execute([$like, $like, $like]);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Se não buscou, mostra todos
    $stmt = $pdo->query("SELECT * FROM jogo_fisico");
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Buscar Jogos Físicos</title>
</head>
<body>
    <h2>Buscar Jogos Físicos</h2>
    <form method="get" action="buscar_jogo_fisico.php" autocomplete="off">
        <input type="text" name="busca" placeholder="Nome, plataforma ou desenvolvedora..." value="<?= htmlspecialchars($busca) ?>">
        <button type="submit">Buscar</button>
    </form>

    <h3>Resultados:</h3>
    <?php if (count($resultados) > 0): ?>
        <table border="1" cellpadding="6" cellspacing="0">
            <tr>
                <th>Nome</th>
                <th>Plataforma</th>
                <th>Desenvolvedora</th>
                <th>Gênero</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Data de Cadastro</th>
            </tr>
            <?php foreach ($resultados as $jogo): ?>
                <tr>
                    <td><?= htmlspecialchars($jogo['nome_jogo']) ?></td>
                    <td><?= htmlspecialchars($jogo['plataforma']) ?></td>
                    <td><?= htmlspecialchars($jogo['desenvolvedora']) ?></td>
                    <td><?= htmlspecialchars($jogo['genero']) ?></td>
                    <td>R$ <?= number_format($jogo['preco'], 2, ',', '.') ?></td>
                    <td><?= $jogo['estoque'] ?></td>
                    <td><?= $jogo['data_cadastro'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Nenhum jogo encontrado.</p>
    <?php endif; ?>
</body>
</html>