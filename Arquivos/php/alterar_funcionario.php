<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;

if ($fk_cargo != 1 && $fk_cargo != 4) {
    echo "Acesso negado";
    exit;
}

$adm = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['busca_funcionario'])) {
        $busca = trim($_POST['busca_funcionario']);

        if (is_numeric($busca)) {
            $sql = "SELECT * FROM adm WHERE pk_adm = :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
        } else {
            $sql = "SELECT * FROM adm WHERE nome_adm LIKE :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca', "%$busca%", PDO::PARAM_STR);
        }

        $stmt->execute();
        $adm = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$adm) {
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
            background-color: #12002b;
            color: #f0e6ff;
            font-family: 'Motiva Sans', 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            line-height: 1.6;
        }

        h2 {
            color: #c084fc;
            text-align: center;
            margin-top: 20px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        form {
            background: #1e1b2e;
            padding: 20px;
            border-radius: 12px;
            max-width: 600px;
            margin: 20px auto;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
            border: 1px solid #5d3bad;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #e9d5ff;
        }

            input, select, textarea {
        width: 100%;
        padding: 12px 15px;
        margin-top: 8px;
        background: #252836;
        border: 1px solid #2a2540;
        border-radius: 8px;
        color: #f0e6ff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        box-sizing: border-box;    
        font-size: 15px;           
    }

    input:focus, select:focus, textarea:focus {
        border-color: #7a5af5;
        background-color: #2a2e3c;
        outline: none;
        box-shadow: 0 0 0 3px rgba(122, 90, 245, 0.3);
    }

        button {
        display: block;
        margin: 20px auto 0 auto; 
        padding: 12px 30px;
        background: #510d96;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.3s;
    }

        button:hover {
            background-color: #7a5af5;
            box-shadow: 0 0 12px rgba(122, 90, 245, 0.4);
        }

        a {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #c084fc;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        
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
    <h2>Alterar Funcionário</h2>

    <form action="alterar_funcionario.php" method="POST">
        <label for="busca_funcionario">Digite o ID ou Nome do funcionário:</label>
        <input type="text" id="busca_funcionario" name="busca_funcionario" required>
        <button type="submit">Buscar</button>
    </form>

    <?php if ($adm): ?>
        <form action="processa_alteracao_funcionario.php" method="POST">
            <input type="hidden" name="pk_adm" value="<?= htmlspecialchars($adm['pk_adm']) ?>">

            <label for="nome_adm">Nome:</label>
            <input type="text" id="nome_adm" name="nome_adm" value="<?= htmlspecialchars($adm['nome_adm']) ?>" required>

            <label for="email_adm">Email:</label>
            <input type="email" id="email_adm" name="email_adm" value="<?= htmlspecialchars($adm['email_adm']) ?>" required>

            <label for="senha_user">Nova Senha (preencha só se quiser alterar):</label>
            <input type="password" id="senha_user" name="senha_user">

            <label for="fk_cargo">Cargo:</label>
            <input type="text" id="fk_cargo" name="fk_cargo" value="<?= htmlspecialchars($adm['fk_cargo']) ?>">

            <button type="submit">Alterar</button>
        </form>
    <?php endif; ?>

    <a href="adm.php" class="back-link">Voltar</a>
</body>
</html>