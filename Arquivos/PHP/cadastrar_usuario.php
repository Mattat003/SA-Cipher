<?php
session_start();
if (!isset($_SESSION['tipo'])) {
    header("Location: login.php");
    exit();
}
require_once 'conexao.php'; // Certifique-se de que este caminho está correto

// Lógica para adicionar usuário (você preencherá isso)
$mensagem = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Exemplo básico: Capturar dados do formulário
    // $nome = $_POST['nome_usuario'];
    // $email = $_POST['email_usuario'];
    // $senha = password_hash($_POST['senha_usuario'], PASSWORD_DEFAULT); // Sempre hash senhas!
    // $fk_cargo = $_POST['cargo_usuario'];

    // try {
    //     $stmt = $pdo->prepare("INSERT INTO usuario (nome_usuario, email_usuario, senha_usuario, fk_cargo) VALUES (?, ?, ?, ?)");
    //     $stmt->execute([$nome, $email, $senha, $fk_cargo]);
    //     $mensagem = "<p class='success-message'>Usuário cadastrado com sucesso!</p>";
    // } catch (PDOException $e) {
    //     $mensagem = "<p class='error-message'>Erro ao cadastrar usuário: " . $e->getMessage() . "</p>";
    // }
    $mensagem = "<p class='success-message'>Lógica de cadastro de usuário será implementada aqui!</p>";
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        :root {
            --primary-purple: #5E35B1; /* Deep Purple */
            --dark-purple: #311B92;    /* Darker Deep Purple */
            --light-purple: #9575CD;   /* Medium Purple for backgrounds */
            --text-color: #f0f0f0;     /* Lighter text for dark backgrounds */
            --white-color: #263238;    /* Dark gray for containers */
            --danger-red: #C62828;     /* Darker Crimson */
            --success-green: #2E7D32;  /* Darker Green */
            --info-blue: #0277BD;      /* Darker Blue */
            --shadow: 0 6px 20px rgba(0, 0, 0, 0.4); /* Stronger shadow */
            --border-radius: 10px;     /* Slightly larger radius */
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, var(--dark-purple) 0%, #1A237E 100%);
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            box-sizing: border-box;
        }

        .container {
            background-color: var(--white-color);
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            max-width: 600px;
            width: 100%;
            text-align: center;
            animation: fadeIn 1s ease-out;
            margin-bottom: 20px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            color: var(--light-purple);
            margin-bottom: 20px;
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.05);
        }

        .message {
            padding: 10px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            font-weight: 600;
            color: #fff; /* Ensure messages are visible on dark backgrounds */
        }

        .success-message {
            background-color: var(--success-green);
            border: 1px solid #1B5E20;
        }

        .error-message {
            background-color: var(--danger-red);
            border: 1px solid #B71C1C;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            text-align: left;
        }

        label {
            font-weight: 600;
            color: var(--light-purple); /* Lighter text for labels */
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            padding: 10px;
            border: 1px solid #546E7A; /* Darker border */
            border-radius: 5px;
            font-size: 1em;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
            background-color: #37474F; /* Dark background for inputs */
            color: var(--text-color); /* Light text in inputs */
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus {
            border-color: var(--light-purple);
            outline: none;
        }

        button[type="submit"] {
            padding: 12px 25px;
            background: var(--primary-purple);
            color: var(--text-color);
            border: none;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
        }

        button[type="submit"]:hover {
            background: var(--light-purple);
            color: var(--white-color);
            transform: translateY(-4px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.4);
        }

        .back-button {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 25px;
            background: var(--light-purple);
            color: var(--white-color);
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .back-button:hover {
            background: var(--primary-purple);
            color: var(--text-color);
            transform: translateY(-4px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cadastrar Novo Usuário</h1>

        <?php if ($mensagem): ?>
            <div class="message <?= strpos($mensagem, 'sucesso') !== false ? 'success-message' : 'error-message' ?>">
                <?= $mensagem ?>
            </div>
        <?php endif; ?>

        <form action="cadastrar_usuario.php" method="post">
            <div>
                <label for="nome_usuario">Nome de Usuário:</label>
                <input type="text" id="nome_usuario" name="nome_usuario" required>
            </div>
            <div>
                <label for="email_usuario">Email:</label>
                <input type="email" id="email_usuario" name="email_usuario" required>
            </div>
            <div>
                <label for="senha_usuario">Senha:</label>
                <input type="password" id="senha_usuario" name="senha_usuario" required>
            </div>
            <div>
                <label for="fk_cargo">Cargo:</label>
                <select id="fk_cargo" name="fk_cargo" required>
                    <option value="">Selecione um cargo</option>
                    <option value="1">Administrador</option>
                    <option value="2">Funcionário</option>
                    </select>
            </div>
            <button type="submit">Cadastrar Usuário</button>
        </form>

        <a href="adm.php" class="back-button">Voltar ao Painel</a>
    </div>
</body>
</html>