<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;
if ($fk_cargo != 1 && $fk_cargo != 2) {
    echo "Acesso negado";
    exit;
}

// Mapas de tabelas e colunas
$categorias = [
    'genero' => 'nome_gen',
    'idioma' => 'nome_idioma',
    'tema' => 'nome_tema',
    'plataforma' => 'nome_plat',
    'modo' => 'nome_modo',
    'estilo' => 'nome_estilo'
];

// Nomes legíveis com artigos corretos
$nomes_legiveis = [
    'estilo' => ['label' => 'Estilo', 'artigo' => 'do'],
    'genero' => ['label' => 'Gênero', 'artigo' => 'do'],
    'idioma' => ['label' => 'Idioma', 'artigo' => 'do'],
    'modo' => ['label' => 'Modo de Jogo', 'artigo' => 'do'],
    'plataforma' => ['label' => 'Plataforma', 'artigo' => 'da'],
    'tema' => ['label' => 'Tema', 'artigo' => 'do']        
];

$tipo = $_GET['tipo'] ?? null;
$mensagem = "";

// Cadastro de categoria
if ($tipo && isset($categorias[$tipo]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome'] ?? '');

    if (empty($nome)) {
        $mensagem = "O campo nome é obrigatório.";
    } else {
        try {
            // Verifica duplicidade
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM $tipo WHERE {$categorias[$tipo]} = :nome");
            $stmt->bindParam(':nome', $nome);
            $stmt->execute();

            if ($stmt->fetchColumn() > 0) {
                $mensagem = "Já existe esse nome cadastrado.";
            } else {
                // Insere nova categoria
                $sql = "INSERT INTO $tipo ({$categorias[$tipo]}) VALUES (:nome)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':nome', $nome);

                if ($stmt->execute()) {
                    $mensagem = "Categoria cadastrada com sucesso!";
                } else {
                    $mensagem = "Erro ao cadastrar categoria.";
                }
            }
        } catch (PDOException $e) {
            $mensagem = "Erro no banco: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Categorias</title>
    <style>
    body {
        font-family: 'Motiva Sans', 'Segoe UI', sans-serif;
        background: #12002b;
        color: #f0e6ff;
        margin: 0;
        padding: 20px;
        line-height: 1.6;
    }

    .container {
        background: #1e1b2e;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        max-width: 800px;
        margin: 0 auto 40px auto;
    }

    h2, h3 {
        color: #c7b3e6;
        text-align: center;
        margin-bottom: 30px;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
    }

    .form-area {
        max-width: 100%;
        margin: 20px auto 40px auto;
        display: flex;
        gap: 12px;
    }

    .form-area input[type="text"] {
        flex: 1;
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #3e2f6d;
        background: #1e1b2e;
        color: #f0e6ff;
        font-size: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        outline-offset: 0;
        transition: border-color 0.3s, background-color 0.3s;
    }

    .form-area input[type="text"]:focus {
        border-color: #9d7aff;
        background-color: #251f3d;
        outline: none;
        box-shadow: 0 0 0 3px rgba(157, 122, 255, 0.3);
    }

    .form-area button {
        padding: 12px 25px;
        background: #510d96;
        color: #fff;
        font-weight: 600;
        border: 1px solid #510d96;
        border-radius: 8px;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
    }

    .form-area button:hover {
        background: #7a5af5;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        transform: translateY(-1px);
    }

    .form-area button:active {
        transform: translateY(0);
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: #1e1b2e;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        border-radius: 10px;
        overflow: hidden;
    }

    table th, table td {
        border: 1px solid #3e2f6d;
        padding: 15px 18px;
        text-align: center;
        vertical-align: middle;
        color: #e9d5ff;
    }

    table th {
        background: #2a1f4d;
        color: #d4c2f0;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.9em;
    }

    table tr:nth-child(even) {
        background: #1a1828;
    }

    table tr:nth-child(odd) {
        background: #251f3d;
    }

    table tr:hover {
        background: #2d2449;
    }

    table a {
        color: #9d7aff;
        text-decoration: none;
        margin-right: 12px;
        font-weight: 600;
        transition: color 0.2s, text-decoration 0.2s;
    }

    table a:hover {
        color: #f0e6ff;
        text-decoration: underline;
    }

    img {
        max-width: 70px;
        max-height: 70px;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.5);
    }

    p {
        text-align: center;
        font-size: 1.1em;
        color: #c7b3e6;
        margin-top: 20px;
        padding: 12px;
        border: 1px dashed #3e2f6d;
        border-radius: 8px;
        background: #1e1b2e;
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

        .form-area {
            flex-direction: column;
            max-width: 100%;
        }
    }

    @media (max-width: 480px) {
        h2, h3 {
            font-size: 1.8em;
        }

        .back-link {
            max-width: 100%;
        }

        img {
            max-width: 50px;
            max-height: 50px;
        }
    }
    .btn-group {
            display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-bottom: 20px;
        }
        .btn-group a {
            padding: 10px 18px; background: #510d96; color: white; text-decoration: none; border-radius: 6px;
            font-weight: bold; transition: background 0.3s;
        }
        .btn-group a:hover { background: #2c056e; }
        form {
            display: flex; flex-direction: column; gap: 12px; max-width: 400px; margin: 0 auto 20px auto;
            background:rgb(11, 10, 17); padding: 20px; border-radius: 10px;
        }
        input[type="text"] {
            padding: 10px; border: 1px solid #ccc; border-radius: 6px;
        }
        button {
            padding: 10px; background: #510d96; color: white; border: none; border-radius: 6px;
            font-weight: bold; cursor: pointer;
        }
        button:hover { background: #2c056e; }
        .mensagem {
            text-align: center; font-weight: bold; color: #2c056e; margin-bottom: 15px;
        }
        .back-link {
            display: block; text-align: center; margin-top: 30px; background: #510d96; color: #fff;
            padding: 12px; border-radius: 5px; text-decoration: none; width: 200px; margin-left: auto; margin-right: auto;
        }
        .back-link:hover { background: #2c056e; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cadastrar Categorias</h2>

        <div class="btn-group">
            <?php foreach ($categorias as $cat => $col): ?>
                <a href="?tipo=<?= $cat ?>"><?= $nomes_legiveis[$cat]['label'] ?></a>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($mensagem)): ?>
            <div class="mensagem"><?= htmlspecialchars($mensagem) ?></div>
        <?php endif; ?>

        <?php if ($tipo && isset($categorias[$tipo])): ?>
            <form method="POST" action="cadastrar_categoria.php?tipo=<?= htmlspecialchars($tipo) ?>">
                <label for="nome">Nome <?= $nomes_legiveis[$tipo]['artigo'] ?> <?= $nomes_legiveis[$tipo]['label'] ?>:</label>
                <input type="text" name="nome" id="nome" placeholder="Digite o nome..." required>
                <button type="submit">Cadastrar</button>
            </form>
        <?php else: ?>
            <p style="text-align:center; font-style: italic;">Selecione uma categoria acima para cadastrar.</p>
        <?php endif; ?>
    </div>
    <a href="adm.php" class="back-link">Voltar</a>
</body>
</html>
