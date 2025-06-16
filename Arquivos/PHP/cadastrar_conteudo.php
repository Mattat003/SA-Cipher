<?php
session_start();
if (!isset($_SESSION['tipo'])) {
    header("Location: login.php");
    exit();
}
require_once 'conexao.php';

// Lógica para adicionar conteúdo (você preencherá isso)
$mensagem = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Exemplo básico: Capturar dados do formulário
    // $titulo_conteudo = $_POST['titulo'];
    // $texto_conteudo = $_POST['texto'];

    // try {
    //     $stmt = $pdo->prepare("INSERT INTO conteudo (titulo, texto, fk_usuario) VALUES (?, ?, ?)");
    //     $stmt->execute([$titulo_conteudo, $texto_conteudo, $_SESSION['id_usuario']]);
    //     $mensagem = "<p class='success-message'>Conteúdo adicionado com sucesso!</p>";
    // } catch (PDOException $e) {
    //     $mensagem = "<p class='error-message'>Erro ao adicionar conteúdo: " . $e->getMessage() . "</p>";
    // }
    $mensagem = "<p class='success-message'>Lógica de adição de conteúdo será implementada aqui!</p>";
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Conteúdo</title>
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
            max-width: 800px;
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
            color: #fff;
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
            color: var(--light-purple);
            margin-bottom: 5px;
        }

        input[type="text"],
        textarea {
            padding: 10px;
            border: 1px solid #546E7A;
            border-radius: 5px;
            font-size: 1em;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
            background-color: #37474F;
            color: var(--text-color);
        }

        input[type="text"]:focus,
        textarea:focus {
            border-color: var(--light-purple);
            outline: none;
        }

        textarea {
            min-height: 150px;
            resize: vertical;
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
        <h1>Adicionar Novo Conteúdo</h1>

        <?php if ($mensagem): ?>
            <div class="message <?= strpos($mensagem, 'sucesso') !== false ? 'success-message' : 'error-message' ?>">
                <?= $mensagem ?>
            </div>
        <?php endif; ?>

        <form action="cadastrar_conteudo.php" method="post">
            <div>
                <label for="titulo">Título do Conteúdo:</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>
            <div>
                <label for="texto">Texto do Conteúdo:</label>
                <textarea id="texto" name="texto" required></textarea>
            </div>
            <button type="submit">Adicionar Conteúdo</button>
        </form>

        <a href="adm.php" class="back-button">Voltar ao Painel</a>
    </div>
</body>
</html>