<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['pk_usuario'])) {
    header('Location: login.php');
    exit();
}

$mensagem = '';
$tipo_mensagem = '';

// Processar a busca
$termo_busca = $_GET['busca'] ?? '';
$condicao_busca = '';
$parametros = [];

if (!empty($termo_busca)) {
    $condicao_busca = " WHERE nome_jogo LIKE :termo_busca";
    $parametros[':termo_busca'] = '%' . $termo_busca . '%';
}

// Buscar todos os jogos
$stmt = $pdo->prepare("SELECT pk_jogo, nome_jogo, imagem_jogo, url_jogo FROM jogo" . $condicao_busca);
$stmt->execute($parametros);
$jogos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogos Disponíveis</title>
    <style>
        body {
            font-family: 'Motiva Sans', sans-serif;
            background-color: #12002b;
            color: #f0e6ff;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #1e1b2e;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6);
            border: 1px solid #5d3bad;
        }
        h2 {
            text-align: center;
            color: #c084fc;
            margin-bottom: 30px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }
        .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        .search-bar input[type="text"] {
            width: 70%;
            padding: 10px 15px;
            border-radius: 8px 0 0 8px;
            border: 1px solid #7a5af5;
            background-color: #2e2152;
            color: #f0e6ff;
            font-size: 1rem;
            outline: none;
        }
        .search-bar button {
            padding: 10px 20px;
            border-radius: 0 8px 8px 0;
            border: none;
            background-color: #510d96;
            color: white;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        .search-bar button:hover {
            background-color: #7a5af5;
        }
        .game-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 25px;
            justify-content: center;
        }
        .game-card {
            background-color: #2e2152;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
            border: 1px solid #7a5af5;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .game-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.6);
        }
        .game-card img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-bottom: 1px solid #5d3bad;
        }
        .game-card h3 {
            font-size: 1.2rem;
            color: #e9d5ff;
            margin: 15px 10px 10px;
            word-wrap: break-word;
        }
        .game-card .actions {
            padding: 15px;
            background-color: #1e1b2e;
            border-top: 1px solid #5d3bad;
        }
        .btn-primary {
            display: block;
            width: calc(100% - 20px);
            margin: 0 auto;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            background-color: #510d96;
            color: white;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }
        .btn-primary:hover {
            background-color: #7a5af5;
            transform: translateY(-2px);
        }
        .alert {
            background: #2e2152;
            border: 1px solid #7a5af5;
            color: #e9d5ff;
            padding: 15px;
            border-radius: 8px;
            max-width: 500px;
            margin: 20px auto;
            text-align: center;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Jogos Disponíveis</h2>

        <?php if (!empty($mensagem)): ?>
            <p class="alert <?= $tipo_mensagem ?>"><?= htmlspecialchars($mensagem) ?></p>
        <?php endif; ?>

        <form action="jogos.php" method="GET" class="search-bar">
            <input type="text" name="busca" placeholder="Buscar jogo..." value="<?= htmlspecialchars($termo_busca) ?>">
            <button type="submit">Buscar</button>
        </form>

        <?php if (count($jogos) > 0): ?>
            <div class="game-grid">
                <?php foreach ($jogos as $jogo): ?>
                    <div class="game-card">
                        <img src="<?= htmlspecialchars($jogo['imagem_jogo']) ?>" alt="<?= htmlspecialchars($jogo['nome_jogo']) ?>">
                        <h3><?= htmlspecialchars($jogo['nome_jogo']) ?></h3>
                        <div class="actions">
                            <a href="confirmar_locacao.php?jogo_id=<?= $jogo['pk_jogo'] ?>" class="btn-primary">Locar Jogo</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Nenhum jogo encontrado.</div>
        <?php endif; ?>

        <a href="index.php" class="back-link">Voltar para a Biblioteca</a>
    </div>
</body>
</html>