<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;
if ($fk_cargo != 1 && $fk_cargo != 2) {
    echo "Acesso negado";
    exit;
}

$categorias = [
    'genero' => 'nome_gen',
    'idioma' => 'nome_idioma',
    'tema' => 'nome_tema',
    'plataforma' => 'nome_plat',
    'modo' => 'nome_modo',
    'estilo' => 'nome_estilo'
];

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

if ($tipo && isset($categorias[$tipo])) {
    $coluna = $categorias[$tipo];

    // Excluir
    if (isset($_GET['excluir'])) {
        $id = (int) $_GET['excluir'];
        $pdo->prepare("DELETE FROM $tipo WHERE pk_$tipo = ?")->execute([$id]);
        $mensagem = "Item excluído com sucesso!";
    }

    // Alterar
    if (isset($_POST['alterar_id'], $_POST['novo_nome'])) {
        $id = (int) $_POST['alterar_id'];
        $novo = trim($_POST['novo_nome']);
        if ($novo !== '') {
            $stmt = $pdo->prepare("UPDATE $tipo SET $coluna = ? WHERE pk_$tipo = ?");
            $stmt->execute([$novo, $id]);
            $mensagem = "Item alterado com sucesso!";
        }
    }

    // Cadastrar
    if (isset($_POST['nome'])) {
        $nome = trim($_POST['nome']);
        if ($nome !== '') {
            $existe = $pdo->prepare("SELECT COUNT(*) FROM $tipo WHERE $coluna = ?");
            $existe->execute([$nome]);
            if ($existe->fetchColumn() == 0) {
                $stmt = $pdo->prepare("INSERT INTO $tipo ($coluna) VALUES (?)");
                $stmt->execute([$nome]);
                $mensagem = "Categoria cadastrada com sucesso!";
            } else {
                $mensagem = "Essa categoria já existe.";
            }
        }
    }

    // Lista os dados
    $dados = $pdo->query("SELECT pk_$tipo, $coluna FROM $tipo ORDER BY $coluna")->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Gerenciar Categorias</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
        <style>
            body { 
                background: #12002b; 
                color: #fff; 
                padding: 20px; 
                font-family: Arial; 
            }
            .container { 
                background: #1e1b2e; 
                padding: 30px; 
                border-radius: 12px; 
                max-width: 800px; 
                margin: auto; 
            }
            h2 { 
                text-align: center; 
                color: #c7b3e6; 
                margin-bottom: 20px; 
            }
            .btn-group a, button { 
                background: #510d96; 
                color: white; 
                border: none; 
                padding: 10px 18px; 
                border-radius: 6px; 
                text-decoration: none; 
                font-weight: bold; 
            }
            .btn-group a:hover, button:hover { 
                background: #2c056e; 
            }
            .btn-group { 
                display: flex; 
                flex-wrap: wrap; 
                gap: 10px; 
                justify-content: center; 
                margin-bottom: 20px; 
            }
            .mensagem { 
                text-align: center; 
                margin-bottom: 15px; 
                font-weight: bold; 
                color: #9d7aff; 
            }
            table { 
                width: 100%; 
                background: #1a1828; 
                color: #fff; 
                border-radius: 8px; 
                overflow: hidden; 
                margin-top: 20px; 
            }
            table th, table td { 
                padding: 12px; 
                border: 1px solid #3e2f6d; 
                text-align: center; 
            }
            table a {
                color: #c084fc;
                text-decoration: none;
                font-weight: 500;
                transition: color 0.2s, text-decoration 0.2s;
            }
            table a:hover {
                color: #7a5af5;
                text-decoration: underline;
            }
            form.inline { 
                display: inline-block; 
            }
            input[type=text] { 
                padding: 8px; 
                border-radius: 5px; 
                border: 1px solid #ccc; 
            }
            .back-link { 
                display: block; 
                text-align: center; 
                margin-top: 30px; 
                background: #510d96; 
                color: #fff; 
                padding: 12px; 
                border-radius: 5px; 
                text-decoration: none; 
                max-width: 200px; 
                margin-left: auto; 
                margin-right: auto; 
            }
            /* Responsivo */
            @media (max-width: 480px) {
                h2 {
                    font-size: 1.8em;
                }

                .back-link {
                    max-width: 100%;
                }
            }
            .msg {
                text-align: center; 
                color: #c7b3e6; 
                margin-bottom: 15px; 
                font-style: italic;
                border: 1px dashed #3e2f6d;
                border-radius: 8px;
                padding: 12px;
            }
            
        </style>
    </head>
    <body>
    <div class="container">
        <h2>Gerenciar Categorias</h2>

        <div class="btn-group">
            <?php foreach ($categorias as $cat => $col): ?>
                <a href="?tipo=<?= $cat ?>"><?= $nomes_legiveis[$cat]['label'] ?></a>
            <?php endforeach; ?>
        </div>

        <?php if ($tipo && isset($categorias[$tipo])): ?>
            <?php if (!empty($mensagem)): ?><div class="mensagem"><?= htmlspecialchars($mensagem) ?></div><?php endif; ?>

            <?php if (!empty($dados)): ?>
                <table>
                    <thead>
                        <tr><th>Nome</th><th>Ações</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dados as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item[$coluna]) ?></td>
                                <td>
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="alterar_id" value="<?= $item["pk_$tipo"] ?>">
                                        <input type="text" name="novo_nome" placeholder="Novo nome" required>
                                        <button type="submit">Alterar</button>
                                    </form>
                                    <button><a href="?tipo=<?= $tipo ?>&excluir=<?= $item["pk_$tipo"] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php else: ?>
            <p style="text-align:center;" class="msg">Selecione uma categoria acima para gerenciar.</p>
        <?php endif; ?>
    </div>
    <a href="adm.php" class="back-link">Voltar</a>
    </body>
</html>