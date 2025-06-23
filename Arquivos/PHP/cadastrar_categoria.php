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
        body { font-family: Arial, sans-serif; background: #f6f6fa; padding: 20px; }
        .container { max-width: 850px; margin: auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        h2 { color: #2c056e; text-align: center; }
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
            background: #f2f2fc; padding: 20px; border-radius: 10px;
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

        <a href="adm.php" class="back-link">Voltar ao Painel</a>
    </div>
</body>
</html>
