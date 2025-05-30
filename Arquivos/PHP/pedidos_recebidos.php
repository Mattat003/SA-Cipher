<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['pk_usuario'])) {
    die("Acesso negado");
}

$usuario_id = $_SESSION['pk_usuario'];
$mensagem = '';
$tipo_mensagem = '';

if (isset($_POST['acao'], $_POST['pedido_id'])) {
    $acao = $_POST['acao'];
    $pedido_id = (int)$_POST['pedido_id'];

    if ($acao === 'aceitar') {
        $stmt = $pdo->prepare("SELECT de_id, para_id FROM pedidos_amizade WHERE id = :id AND para_id = :para_id AND status = 'pendente'");
        $stmt->execute(['id' => $pedido_id, 'para_id' => $usuario_id]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($pedido) {
            $de_id = $pedido['de_id'];
            $para_id = $pedido['para_id'];

            $stmt = $pdo->prepare("INSERT INTO amigos (usuario_id, amigo_id) VALUES (?, ?), (?, ?)");
            $stmt->execute([$de_id, $para_id, $para_id, $de_id]);

            $stmt = $pdo->prepare("UPDATE pedidos_amizade SET status = 'aceito' WHERE id = ?");
            $stmt->execute([$pedido_id]);

            $mensagem = "Pedido aceito com sucesso!";
            $tipo_mensagem = "sucesso";
        } else {
            $mensagem = "Pedido não encontrado ou inválido.";
            $tipo_mensagem = "erro";
        }
    } elseif ($acao === 'recusar') {
        $stmt = $pdo->prepare("UPDATE pedidos_amizade SET status = 'recusado' WHERE id = :id AND para_id = :para_id");
        if ($stmt->execute(['id' => $pedido_id, 'para_id' => $usuario_id])) {
            $mensagem = "Pedido recusado.";
            $tipo_mensagem = "erro";
        } else {
            $mensagem = "Erro ao recusar pedido.";
            $tipo_mensagem = "erro";
        }
    }
}

// Buscar pedidos pendentes recebidos pelo usuário
$stmt = $pdo->prepare("
    SELECT p.id, u.nome_user AS nome, u.email_user AS email 
    FROM pedidos_amizade p 
    JOIN usuario u ON p.de_id = u.pk_usuario 
    WHERE p.para_id = :uid AND p.status = 'pendente'
");
$stmt->execute(['uid' => $usuario_id]);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pedidos de amizade recebidos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f0c29, #302b63);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            color: white;
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 40px;
            border-radius: 15px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.36);
        }

        h2 {
            font-size: 26px;
            margin-bottom: 20px;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #ffffff, #e0c3fc);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-align: center;
        }

        ul {
            list-style: none;
            margin-top: 10px;
            padding: 0;
        }

        li {
            margin-bottom: 15px;
            background: rgba(255, 255, 255, 0.05);
            padding: 10px 15px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .info-e-botoes {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        form {
            display: inline;
        }

        button {
            padding: 6px 12px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button[name="acao"][value="aceitar"] {
            background: linear-gradient(to right, #4caf50, #388e3c);
            color: white;
        }

        button[name="acao"][value="aceitar"]:hover {
            background: linear-gradient(to right, #388e3c, #2e7d32);
        }

        button[name="acao"][value="recusar"] {
            background: linear-gradient(to right, #f44336, #d32f2f);
            color: white;
        }

        button[name="acao"][value="recusar"]:hover {
            background: linear-gradient(to right, #d32f2f, #b71c1c);
        }

        a {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #cbbde2;
            text-decoration: none;
        }

        a:hover {
            color: #e0c3fc;
        }

        .mensagem {
            text-align: center;
            margin-bottom: 20px;
            font-size: 15px;
        }

        .mensagem.sucesso {
            color: #90ee90;
        }

        .mensagem.erro {
            color: #ff7f7f;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Pedidos de amizade recebidos</h2>

    <?php if (!empty($mensagem)): ?>
        <p class="mensagem <?= $tipo_mensagem ?>"><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <?php if (count($pedidos) === 0): ?>
        <p style="text-align:center;">Você não tem pedidos de amizade pendentes.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($pedidos as $pedido): ?>
                <li>
                    <div class="info-e-botoes">
                        <span><?= htmlspecialchars($pedido['nome']) ?> (<?= htmlspecialchars($pedido['email']) ?>)</span>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                            <button type="submit" name="acao" value="aceitar">Aceitar</button>
                            <button type="submit" name="acao" value="recusar">Recusar</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <a href="chat.php">Voltar ao chat</a>
</div>
</body>
</html>
