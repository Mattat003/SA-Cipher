<?php
session_start();
require_once 'conexao.php';
$fk_cargo = $_SESSION['fk_cargo'] ?? null;
$nomeCargo = '';
$nome = $_SESSION['adm'] ?? 'Funcionário';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_jogo = $_POST['nome_jogo'];
    $data_lanc = $_POST['data_lanc'];
    $url_jogo = $_POST['url_jogo'];
    $imagem_jogo = null;

    // Crie a pasta uploads se não existir
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    if (isset($_FILES['imagem_jogo']) && $_FILES['imagem_jogo']['error'] == 0) {
        $ext = pathinfo($_FILES['imagem_jogo']['name'], PATHINFO_EXTENSION);
        $imagem_jogo = 'uploads/' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['imagem_jogo']['tmp_name'], $imagem_jogo);
    }

    $stmt = $pdo->prepare(
        "INSERT INTO jogo (nome_jogo, data_lanc, imagem_jogo, url_jogo, disponivel_locacao)
         VALUES (?, ?, ?, ?, 1)"
    );
    $stmt->execute([$nome_jogo, $data_lanc,  $imagem_jogo, $url_jogo]);
    header('Location: cadastrar_jogo.php?sucesso=1');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Cadastrar Jogo</title>
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
            max-width: 400px;
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
            margin-top: 20px;
            margin-bottom: 30px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        label {
            color: #e9d5ff;
            font-weight: 500;
            display: block;
            margin-top: 12px;
            margin-bottom: 6px;
        }

        input, select {
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

        input:focus, select:focus {
            border-color: #7a5af5;
            background-color: #2a2e3c;
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 90, 245, 0.3);
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L6 6L11 1' stroke='%23c084fc' stroke-width='2'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 12px 8px;
            cursor: pointer;
        }

        option {
            background-color: #252836;
            color: #f0e6ff;
        }

        button[type="submit"] {
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

        .alert-success {
            text-align: center;
            color: #c084fc;
            background: #2e2152;
            border: 1px solid #7a5af5;
            padding: 12px;
            border-radius: 8px;
            margin: 20px auto;
            max-width: 400px;
        }

        .back-link {
        display: block;
        text-align: center;
        margin-top: -25px;
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
<body class="bg-dark text-light">
<?php if (isset($_GET['sucesso'])): ?>
<script>
    alert("Jogo cadastrado com sucesso!");
</script>
<?php endif; ?>
<div class="container mt-5">
    <form method="post" enctype="multipart/form-data">
        <h2>Cadastrar Novo Jogo</h2>
        <div class="mb-3">
            <label class="form-label">Nome do Jogo</label>
            <input type="text" name="nome_jogo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Data de Lançamento</label>
            <input type="date" name="data_lanc" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Link do Jogo</label>
            <input type="text" name="url_jogo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Imagem do Jogo</label>
            <input type="file" name="imagem_jogo" class="form-control" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Cadastrar Jogo</button>
    </form>
</div>
<a href="adm.php" class="back-link">Voltar</a>
</body>
</html>