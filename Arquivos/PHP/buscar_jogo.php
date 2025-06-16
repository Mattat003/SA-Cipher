<?php
session_start();
if (!isset($_SESSION['tipo'])) {
    header("Location: login.php");
    exit();
}
require_once 'conexao.php';

$termo_busca = $_GET['termo_busca'] ?? '';
$jogos_encontrados = [];
$mensagem = '';

if ($termo_busca) {
    try {
        // ASSUMA QUE SUA TABELA DE JOGOS SEJA 'jogo' E TENHA COLUNAS COMO 'titulo_jogo', 'genero_jogo'
        $stmt = $pdo->prepare("SELECT pk_jogo, titulo_jogo, genero_jogo FROM jogo WHERE titulo_jogo LIKE :termo OR genero_jogo LIKE :termo ORDER BY titulo_jogo ASC");
        $stmt->bindValue(':termo', '%' . $termo_busca . '%', PDO::PARAM_STR);
        $stmt->execute();
        $jogos_encontrados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($jogos_encontrados)) {
            $mensagem = "<p class='info-message'>Nenhum jogo encontrado para '" . htmlspecialchars($termo_busca) . "'.</p>";
        } else {
            $mensagem = "<p class='success-message'>" . count($jogos_encontrados) . " jogo(s) encontrado(s) para '" . htmlspecialchars($termo_busca) . "'.</p>";
        }

    } catch (PDOException $e) {
        $mensagem = "<p class='error-message'>Erro ao buscar jogos: " . $e->getMessage() . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Buscar Jogo</title>
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
            max-width: 900px;
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
        .info-message {
            background-color: var(--info-blue);
            border: 1px solid #01579B;
        }

        form {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        input[type="search"] {
            padding: 10px;
            border: 1px solid #546E7A;
            border-radius: 5px;
            font-size: 1em;
            width: 70%;
            max-width: 400px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
            background-color: #37474F;
            color: var(--text-color);
        }

        input[type="search"]:focus {
            border-color: var(--light-purple);
            outline: none;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background: var(--primary-purple);
            color: var(--text-color);
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        button[type="submit"]:hover {
            background: var(--light-purple);
            color: var(--white-color);
            transform: translateY(-4px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.4);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #37474F;
            box-shadow: var(--shadow);
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #455A64;
            text-align: left;
            color: var(--text-color);
        }

        th {
            background-color: var(--primary-purple);
            color: var(--text-color);
            font-weight: 600;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #424242;
        }

        tr:hover {
            background-color: #546E7A;
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
        <h1>Buscar Jogo</h1>

        <?php if ($mensagem): ?>
            <div class="message <?= strpos($mensagem, 'sucesso') !== false ? 'success-message' : (strpos($mensagem, 'encontrado') !== false ? 'info-message' : 'error-message') ?>">
                <?= $mensagem ?>
            </div>
        <?php endif; ?>

        <form action="buscar_jogo.php" method="get">
            <input type="search" name="termo_busca" placeholder="Buscar por título ou gênero do jogo..." value="<?= htmlspecialchars($termo_busca) ?>">
            <button type="submit">Buscar</button>
        </form>

        <?php if (!empty($jogos_encontrados)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Jogo</th>
                        <th>Título do Jogo</th>
                        <th>Gênero</th>
                        </tr>
                </thead>
                <tbody>
                    <?php foreach ($jogos_encontrados as $jogo): ?>
                        <tr>
                            <td><?= htmlspecialchars($jogo['pk_jogo']) ?></td>
                            <td><?= htmlspecialchars($jogo['titulo_jogo']) ?></td>
                            <td><?= htmlspecialchars($jogo['genero_jogo']) ?></td>
                            </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($termo_busca && empty($jogos_encontrados)): ?>
            <?php endif; ?>

        <a href="adm.php" class="back-button">Voltar ao Painel</a>
    </div>
</body>
</html>