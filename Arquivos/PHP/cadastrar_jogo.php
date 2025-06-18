<?php
session_start();
require_once 'conexao.php';
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$fk_cargo = $_SESSION['fk_cargo'] ?? null;

// Apenas quem tem cargo de Adm pode cadastrar funcionário
if ($fk_cargo != 1) {
    echo "Acesso negado";
    exit;
}

$mensagem = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validação básica dos campos
    $nome_jogo = trim($_POST['nome_jogo'] ?? '');
    $plataforma = trim($_POST['plataforma'] ?? '');
    $desenvolvedora = trim($_POST['desenvolvedora'] ?? '');
    $genero = trim($_POST['genero'] ?? '');
    $preco = floatval($_POST['preco'] ?? 0);
    $estoque = intval($_POST['estoque'] ?? 0);

    if ($nome_jogo && $plataforma && $preco > 0 && $estoque >= 0) {
        $stmt = $pdo->prepare("INSERT INTO jogo_fisico (nome_jogo, plataforma, desenvolvedora, genero, preco, estoque) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $nome_jogo,
            $plataforma,
            $desenvolvedora,
            $genero,
            $preco,
            $estoque
        ]);
        $mensagem = "<p style='color:green;'>Jogo cadastrado com sucesso!</p>";
    } else {
        $mensagem = "<p style='color:red;'>Preencha todos os campos obrigatórios corretamente.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Jogo Físico</title>
</head>
<body>
    <h2>Cadastrar Jogo Físico</h2>
    <?= $mensagem ?>
    <form action="adicionar_jogo_fisico.php" method="post" autocomplete="off">
        <label>Nome do Jogo:</label> <input type="text" name="nome_jogo" required><br>
        <label>Plataforma:</label> <input type="text" name="plataforma" required><br>
        <label>Desenvolvedora:</label> <input type="text" name="desenvolvedora"><br>
        <label>Gênero:</label> <input type="text" name="genero"><br>
        <label>Preço:</label> <input type="number" name="preco" step="0.01" min="0" required><br>
        <label>Estoque:</label> <input type="number" name="estoque" min="0" value="1" required><br>
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>