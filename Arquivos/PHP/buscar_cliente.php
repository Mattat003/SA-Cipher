<?php
session_start();
if (!isset($_SESSION['tipo'])) {
    header("Location: login.php");
    exit();
}
require_once 'conexao.php';

$termo_busca = $_GET['termo_busca'] ?? '';
$clientes_encontrados = [];
$mensagem = '';

if ($termo_busca) {
    try {
        // ASSUMA QUE SUA TABELA DE CLIENTES SEJA 'cliente' E TENHA COLUNAS COMO 'nome_cliente', 'email_cliente'
        $stmt = $pdo->prepare("SELECT pk_cliente, nome_cliente, email_cliente FROM cliente WHERE nome_cliente LIKE :termo OR email_cliente LIKE :termo ORDER BY nome_cliente ASC");
        $stmt->bindValue(':termo', '%' . $termo_busca . '%', PDO::PARAM_STR);
        $stmt->execute();
        $clientes_encontrados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($clientes_encontrados)) {
            $mensagem = "<p class='info-message'>Nenhum cliente encontrado para '" . htmlspecialchars($termo_busca) . "'.</p>";
        } else {
            $mensagem = "<p class='success-message'>" . count($clientes_encontrados) . " cliente(s) encontrado(s) para '" . htmlspecialchars($termo_busca) . "'.</p>";
        }

    } catch (PDOException $e) {
        $mensagem = "<p class='error-message'>Erro ao buscar clientes: " . $e->getMessage() . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Buscar Cliente</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        :root {
            --primary-purple: #7B68EE;
            --dark-purple: #4B0082;
            --light-purple: #E6E6FA;
            --text-color: #333;
            --white-color: #fff;
            --danger-red: #DC143C;
            --success-green: #28a745;
            --info-blue: #17a2b8;
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            --border-radius: 8px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, var(--light-purple) 0%, var(--primary-purple) 100%);
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
            color: var(--dark-purple);
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
        }

        .success-message {
            background-color: #d4edda;
            color: var(--success-green);
            border: 1px solid #c3e6cb;
        }

        .error-message {
            background-color: #f8d7da;
            color: var(--danger-red);
            border: 1px solid #f5c6cb;
        }
        .info-message {
            background-color: #d1ecf1;
            color: var(--info-blue);
            border: 1px solid #bee5eb;
        }

        form {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        input[type="search"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
            width: 70%;
            max-width: 400px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="search"]:focus {
            border-color: var(--primary-purple);
            outline: none;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background: var(--dark-purple);
            color: var(--white-color);
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        button[type="submit"]:hover {
            background: var(--primary-purple);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: var(--white-color);
            box-shadow: var(--shadow);
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #eee;
            text-align: left;
        }

        th {
            background-color: var(--dark-purple);
            color: var(--white-color);
            font-weight: 600;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .back-button {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 25px;
            background: var(--primary-purple);
            color: var(--white-color);
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .back-button:hover {
            background: var(--dark-purple);
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Buscar Cliente</h1>

        <?php if ($mensagem): ?>
            <div class="message <?= strpos($mensagem, 'sucesso') !== false ? 'success-message' : (strpos($mensagem, 'encontrado') !== false ? 'info-message' : 'error-message') ?>">
                <?= $mensagem ?>
            </div>
        <?php endif; ?>

        <form action="buscar_cliente.php" method="get">
            <input type="search" name="termo_busca" placeholder="Buscar por nome ou email do cliente..." value="<?= htmlspecialchars($termo_busca) ?>">
            <button type="submit">Buscar</button>
        </form>

        <?php if (!empty($clientes_encontrados)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Cliente</th>
                        <th>Nome do Cliente</th>
                        <th>Email do Cliente</th>
                        </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes_encontrados as $cliente): ?>
                        <tr>
                            <td><?= htmlspecialchars($cliente['pk_cliente']) ?></td>
                            <td><?= htmlspecialchars($cliente['nome_cliente']) ?></td>
                            <td><?= htmlspecialchars($cliente['email_cliente']) ?></td>
                            </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($termo_busca && empty($clientes_encontrados)): ?>
            <?php endif; ?>

        <a href="adm.php" class="back-button">Voltar ao Painel</a>
    </div>
</body>
</html>