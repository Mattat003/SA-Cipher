<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;

if ($fk_cargo != 1 && $fk_cargo != 4) {
    echo "Acesso negado";
    exit;
}


$funcionario = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['busca_funcionario'])) {
        $busca = trim($_POST['busca_funcionario']);

        if (is_numeric($busca)) {
            $sql = "SELECT * FROM funcionario WHERE pk_funcionario = :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
        } else {
            $sql = "SELECT * FROM funcionario WHERE nome_func LIKE :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca', "%$busca%", PDO::PARAM_STR);
        }

        $stmt->execute();
        $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$funcionario) {
            echo "<script>alert('Funcionário não encontrado!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Funcionário</title>

    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f6f6fa; 
            padding: 20px; 
        }
        h2 { 
            color: #2c056e;
            text-align: center; 
        }
        form {
             background: #fff; 
             padding: 20px; 
             border-radius: 8px; 
             max-width: 600px; 
             margin: 20px auto; 
             box-shadow: 0 4px 8px rgba(0,0,0,0.1);
             }
        label { 
            display: block; 
            margin-top: 15px; 
            font-weight: bold; 
        }
        input, select {
             width: 100%;
             padding: 10px; 
             margin-top: 5px; 
             border: 1px solid #ccc; 
             border-radius: 4px; 
            }
        button { 
            margin-top: 15px; 
            padding: 10px 20px; 
            background: #510d96; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
        }
        button:hover { 
            background: #2c056e; 
        }
        a { 
            display: block; 
            text-align: center; 
            margin-top: 20px; 
            color: #510d96; 
            text-decoration: none;
         }
        a:hover { 
            text-decoration: underline; 
        }
    </style>
</head>
<body>
    <h2>Alterar Funcionário</h2>

    <form action="alterar_funcionario.php" method="POST">
        <label for="busca_funcionario">Digite o ID ou Nome do funcionário:</label>
        <input type="text" id="busca_funcionario" name="busca_funcionario" required>
        <button type="submit">Buscar</button>
    </form>

    <?php if ($funcionario): ?>
        <form action="processa_alteracao_funcionario.php" method="POST">
            <input type="hidden" name="pk_funcionario" value="<?= htmlspecialchars($funcionario['pk_funcionario']) ?>">

            <label for="nome_func">Nome:</label>
            <input type="text" id="nome_func" name="nome_func" value="<?= htmlspecialchars($funcionario['nome_func']) ?>" required>

            <label for="email_func">Email:</label>
            <input type="email" id="email_func" name="email_func" value="<?= htmlspecialchars($funcionario['email_func']) ?>" required>

            <label for="senha_func">Nova Senha (preencha só se quiser alterar):</label>
            <input type="password" id="senha_func" name="senha_func">

            <label for="fk_cargo">Cargo:</label>
            <input type="text" id="fk_cargo" name="fk_cargo" value="<?= htmlspecialchars($funcionario['fk_cargo']) ?>">

            <button type="submit">Alterar</button>
        </form>
    <?php endif; ?>

    <a href="adm.php">Voltar</a>
</body>
</html>
