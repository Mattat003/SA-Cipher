<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;

if ($fk_cargo != 1 && $fk_cargo != 2) {
    echo "Acesso negado";
    exit;
}

// Mensagem de resposta
$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome_user'] ?? '');
    $email = trim($_POST['email_user'] ?? '');
    $senha = $_POST['senha_user'] ?? '';
    $foto = ""; // Você pode implementar upload depois se quiser

    // Validação simples
    if (empty($nome) || empty($email) || empty($senha)) {
        $mensagem = "Todos os campos são obrigatórios.";
    } else {
        // Verifica e-mail duplicado
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuario WHERE email_user = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            $mensagem = "E-mail já cadastrado!";
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuario (nome_user, email_user, senha_user, data_criacao, foto_perfil) VALUES (:nome, :email, :senha, NOW(), :foto)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha_hash);
            $stmt->bindParam(':foto', $foto);

            if ($stmt->execute()) {
                $mensagem = "Usuário cadastrado com sucesso!";
            } else {
                $mensagem = "Erro ao cadastrar usuário!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
    <style>
    body {
        background-color: #12002b;
        font-family: 'Motiva Sans', 'Segoe UI', sans-serif;
        color: #f0e6ff;
        margin: 0;
        padding: 0;
        min-height: 100vh;
    }

    form {
        max-width: 350px;
        background: #1e1b2e;
        margin: 60px auto;
        border-radius: 12px;
        padding: 28px 25px 20px 25px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6);
        border: 1px solid #5d3bad;
    }

    h2 {
        text-align: center;
        color: #c084fc;
        margin-bottom: 20px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    }

    label {
        color: #e9d5ff;
        font-weight: 500;
        display: block;
        margin-top: 12px;
        margin-bottom: 6px;
    }

    input {
        width: 100%;
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #2a2540;
        background: #252836;
        color: #f0e6ff;
        margin-bottom: 18px;
        font-size: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        box-sizing: border-box;
    }

    input:focus {
        border-color: #7a5af5;
        background-color: #2a2e3c;
        outline: none;
        box-shadow: 0 0 0 3px rgba(122, 90, 245, 0.3);
    }

    /* BOTÃO idêntico ao .back-link */
    button[type="submit"],
    button[type="reset"]  {
        display: block;
        text-align: center;
        background: #510d96;
        color: #fff;
        font-weight: 600;
        padding: 12px 25px;
        border: 1px solid #510d96;
        border-radius: 8px;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.2s;
        width: 100%;
        margin-bottom: 10px;
    }

    button:hover {
        background: #7a5af5;
        color: #fff;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        transform: translateY(-1px);
    }

    button:active {
        transform: translateY(0);
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

   

    .mensagem {
        text-align: center;
        color: #c084fc;
        margin-bottom: 14px;
        font-weight: bold;
    }

    a {
        display: block;
        text-align: center;
        margin-top: 18px;
        color: #c084fc;
        text-decoration: none;
        transition: 0.2s;
    }

    a:hover {
        text-decoration: underline;
        color: #e9d5ff;
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
    <form action="cadastrar_usuario.php" method="POST" autocomplete="off">
        <h2>Cadastrar Usuário</h2>
        <?php if (!empty($mensagem)): ?>
            <div class="mensagem"><?= htmlspecialchars($mensagem); ?></div>
        <?php endif; ?>
        <label for="nome_user">Nome:</label>
        <input type="text" id="nome_user" name="nome_user" required>

        <label for="email_user">E-mail:</label>
        <input type="email" id="email_user" name="email_user" required>

        <label for="senha_user">Senha:</label>
        <input type="password" id="senha_user" name="senha_user" required>
        

        <button type="submit">Cadastrar</button>
        <button type="reset">Cancelar</button>
    </form>
    <a href="adm.php" class="back-link">Voltar</a>
</body>
</html>