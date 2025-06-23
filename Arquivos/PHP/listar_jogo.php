<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado e tem perfil de adm (1) ou funcionário (2)
if (!isset($_SESSION['fk_cargo']) || ($_SESSION['fk_cargo'] != 1 && $_SESSION['fk_cargo'] != 2)) {
    echo "<script>alert('Acesso negado! Você não tem permissão para acessar esta página.'); window.location.href='principal.php';</script>";
    exit;
}

$jogos = []; // Inicializa o array para armazenar resultados

try {
    // Consulta para selecionar todos os jogos
    $sql = "SELECT pk_jogo, nome_jogo, data_lanc, imagem_jogo  FROM jogo ORDER BY nome_jogo ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $jogos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "<p style='color: red; text-align: center;'>Erro ao carregar jogos: " . htmlspecialchars($e->getMessage()) . "</p>";
    $jogos = [];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Jogos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f6f6fa;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.05);
        }
        h2 {
            color: #2c056e;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2em;
            font-weight: 700;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-radius: 8px;
            overflow: hidden;
        }
        table th, table td {
            border: 1px solid #eee;
            padding: 15px 18px;
            text-align: left;
            vertical-align: middle;
        }
        table th {
            background: #e6e1f4;
            color: #2c056e;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.9em;
        }
        table tr:nth-child(even) {
            background: #f9f9f9;
        }
        table tr:hover {
            background: #f0f0f0;
        }
        img {
            max-width: 70px;
            max-height: 70px;
            border-radius: 6px;
            display: block;
            margin: 0 auto;
        }
        table a {
            color: #510d96;
            text-decoration: none;
            margin-right: 12px;
            font-weight: 500;
            transition: color 0.2s, text-decoration 0.2s;
        }
        table a:hover {
            color: #2c056e;
            text-decoration: underline;
        }
        p {
            text-align: center;
            color: #777;
            margin-top: 20px;
            font-size: 1.1em;
            padding: 10px;
            border: 1px dashed #ccc;
            border-radius: 5px;
            background: #fefefe;
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
            border-radius: 5px;
            max-width: 200px;
            margin-left: auto;
            margin-right: auto;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .back-link:hover {
            background: #2c056e;
            color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            transform: translateY(-1px);
        }
        .back-link:active {
            transform: translateY(0);
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
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
    <div class="container">
        <h2>Lista de Jogos</h2>
        <?php if (!empty($jogos)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagem</th>
                        <th>Nome</th>
                        <th>Data de Lançamento</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jogos as $jogo) : ?>
                        <tr>
                            <td><?= htmlspecialchars($jogo['pk_jogo']); ?></td>
                            <td>
                                <?php if (!empty($jogo['imagem_jogo'])): ?>
                                    <img src="<?= htmlspecialchars($jogo['imagem_jogo']); ?>" alt="<?= htmlspecialchars($jogo['nome_jogo']); ?>">
                                <?php else: ?>
                                    <span>Sem imagem</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($jogo['nome_jogo']); ?></td>
                            <td>
                                <?= ($jogo['data_lanc'] && $jogo['data_lanc'] != '0000-00-00')
                                    ? date('d/m/Y', strtotime($jogo['data_lanc']))
                                    : 'Indefinida' ?>
                            </td>
                            <td>
                                <a href="alterar_jogo.php?id=<?= htmlspecialchars($jogo['pk_jogo']); ?>">Alterar</a>
                                <a href="excluir_jogo.php?id=<?= htmlspecialchars($jogo['pk_jogo']); ?>" onclick="return confirm('Tem certeza que deseja excluir este jogo?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Nenhum jogo cadastrado no sistema ou erro ao carregar.</p>
        <?php endif; ?>

        <a href="adm.php" class="back-link">Voltar ao Painel</a>
    </div>
</body>
</html>