<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;

if ($fk_cargo != 1 && $fk_cargo != 4) {
    echo "Acesso negado";
    exit;
}

// Inicializa variável
$usuario = null;

// Busca o usuário se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['busca_usuario'])) {
    $busca = trim($_POST['busca_usuario']);

    if (is_numeric($busca)) {
        $sql = "SELECT * FROM usuario WHERE pk_usuario = :busca";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM usuario WHERE nome_user LIKE :busca";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca', "%$busca%", PDO::PARAM_STR);
    }

    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo "<script>alert('Usuário não encontrado!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Usuário</title>
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
    <h2>Alterar Usuário</h2>

    <!-- Formulário de busca -->
    <form action="alterar_usuario.php" method="POST">
        <label for="busca_usuario">Digite o ID ou Nome do usuário:</label>
        <input type="text" id="busca_usuario" name="busca_usuario" required>
        <button type="submit">Buscar</button>
    </form>

    <?php if ($usuario): ?>
        <!-- Formulário de alteração -->
        <form action="processa_alteracao_usuario.php" method="POST">
            <input type="hidden" name="pk_usuario" value="<?= htmlspecialchars($usuario['pk_usuario']) ?>">

            <label for="nome_user">Nome:</label>
            <input type="text" id="nome_user" name="nome_user" value="<?= htmlspecialchars($usuario['nome_user']) ?>" required>

            <label for="email_user">E-mail:</label>
            <input type="email" id="email_user" name="email_user" value="<?= htmlspecialchars($usuario['email_user']) ?>" required>

            <label for="senha_user">Nova Senha:</label>
            <input type="password" id="senha_user" name="senha_user" placeholder="Digite a nova senha (ou deixe em branco para não alterar)">

            <button type="submit">Alterar</button>
            <button type="reset">Cancelar</button>
        </form>
    <?php endif; ?>

    <a href="adm.php">Voltar</a>
</body>
</html>
