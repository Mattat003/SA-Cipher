<?php
session_start();
require_once 'conexao.php';

// Só permite acesso para cargos 1 e 2
$fk_cargo = $_SESSION['fk_cargo'] ?? null;
if ($fk_cargo != 1 && $fk_cargo != 2) {
    echo "Acesso negado";
    exit;
}

$busca = trim($_GET['busca'] ?? '');
$resultados = [];

// Ajuste os nomes dos campos conforme seu banco de dados.
// Exemplo de estrutura: 
// id_jogo | nome_jogo | data_lanc | fk_plataforma | fk_desenvolvedora | fk_genero | imagem_jogo | link_gif

if ($busca !== '') {
    $stmt = $pdo->prepare(
        "SELECT * FROM jogo 
        WHERE nome_jogo LIKE ?"
    );
    $like = "%$busca%";
    $stmt->execute([$like]);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $pdo->query("SELECT * FROM jogo");
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Buscar Jogos Físicos</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        h2 { color: #222; }
        table { background: #fff; border-collapse: collapse; margin: 24px 0; }
        th, td { padding: 8px 14px; border: 1px solid #ccc; text-align: center; }
        th { background: #eee; }
        img { max-width: 70px; max-height: 70px; border-radius: 6px; }
        .form-area { margin: 24px 0; }
    </style>
</head>
<body>
    <h2>Buscar Jogos Físicos</h2>
    <form method="get" action="buscar_jogo.php" autocomplete="off" class="form-area">
        <input type="text" name="busca" placeholder="Nome do jogo..." value="<?= htmlspecialchars($busca) ?>">
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
</body>
</html>