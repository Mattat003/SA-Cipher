<?php
session_start();
if (!isset($_SESSION['tipo'])) {
    header("Location: login.php");
    exit();
}
require_once 'conexao.php';

// Pega o fk_cargo da sessão
$fk_cargo = $_SESSION['fk_cargo'] ?? null;
$nomeCargo = '';
$nome = $_SESSION['adm'] ?? 'Usuário';

// Busca o nome do cargo na tabela cargo
if ($fk_cargo) {
    $stmt = $pdo->prepare("SELECT nome_cargo FROM cargo WHERE pk_cargo = :id");
    $stmt->bindParam(":id", $fk_cargo, PDO::PARAM_INT);
    $stmt->execute();
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $nomeCargo = $row['nome_cargo'];
    }
}

// Exemplo: defina os IDs dos cargos
define('CARGO_ADMIN', 1);
define('CARGO_GERENCIADOR_CONTEUDO', 2);
// Adicione outros cargos conforme sua tabela

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do <?=htmlspecialchars($nomeCargo ?: 'Usuário')?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        :root {
            --primary-purple: #7B68EE; /* MediumSlateBlue */
            --dark-purple: #4B0082;    /* Indigo */
            --light-purple: #E6E6FA;   /* Lavender */
            --text-color: #333;
            --white-color: #fff;
            --danger-red: #DC143C;     /* Crimson */
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            --border-radius: 8px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, var(--light-purple) 0%, var(--primary-purple) 100%);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
        }

        .container {
            background-color: var(--white-color);
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            max-width: 900px;
            width: 90%;
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            color: var(--dark-purple);
            margin-bottom: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.05);
        }

        p {
            margin-bottom: 15px;
            line-height: 1.6;
        }

        strong {
            color: var(--primary-purple);
        }

        .painel-func {
            margin: 30px 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }

        .painel-func a,
        button {
            padding: 12px 25px;
            margin: 5px;
            display: inline-block;
            text-decoration: none;
            background: var(--dark-purple);
            color: var(--white-color);
            border: none;
            border-radius: 25px; /* Pill shape for buttons/links */
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .painel-func a:hover,
        button:hover {
            background: var(--primary-purple);
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .painel-func a::before,
        button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transition: width 0.4s ease, height 0.4s ease, top 0.4s ease, left 0.4s ease;
            transform: translate(-50%, -50%);
            z-index: 0;
        }

        .painel-func a:hover::before,
        button:hover::before {
            width: 200%;
            height: 200%;
            top: -50%;
            left: -50%;
        }

        .painel-func a span,
        button span {
            position: relative;
            z-index: 1;
        }

        button.logout-btn {
            background: var(--danger-red);
            margin-top: 20px;
        }

        button.logout-btn:hover {
            background: #FF4500; /* OrangeRed */
            transform: translateY(-3px);
        }

        .painel-func p {
            color: var(--dark-purple);
            font-style: italic;
            padding: 10px;
            border: 1px dashed var(--primary-purple);
            border-radius: var(--border-radius);
            background-color: var(--light-purple);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Painel do <?=htmlspecialchars($nomeCargo ?: 'Usuário')?></h1>
        <p>Bem-vindo, <strong><?= htmlspecialchars($nome) ?></strong>!</p>
        <p>Você está logado como <strong><?= htmlspecialchars($nomeCargo ?: 'Usuário') ?></strong></p>
        <div class="painel-func">
        <?php if ($fk_cargo == CARGO_ADMIN): ?>
                <a href="cadastrar_usuario.php"><span>Adicionar Usuário</span></a>
                <a href="buscar_usuario.php"><span>Pesquisar Usuários</span></a>
                <a href="listar_usuario.php"><span>Listar Usuários</span></a>
                <a href="excluir_usuario.php"><span>Excluir Usuário</span></a> <?php elseif ($fk_cargo == CARGO_GERENCIADOR_CONTEUDO): ?>
                <a href="cadastrar_conteudo.php"><span>Adicionar Conteúdo</span></a>
                <?php else: ?>
                <p>Você não possui permissões administrativas.</p>
            <?php endif; ?>
        </div>
        <form action="logout.php" method="post">
            <button class="logout-btn" type="submit"><span>Sair</span></button>
        </form>
    </div>
</body>
</html>