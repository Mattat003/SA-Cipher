<?php
session_start();
require_once 'conexao.php'; // Certifique-se de que este arquivo existe e faz a conexão PDO

// Verifica se o usuário está logado e tem perfil de adm (1) ou funcionário (2)
// Assumindo que 'fk_cargo' na sessão corresponde aos IDs definidos em 'adm.php'
if (!isset($_SESSION['fk_cargo']) || ($_SESSION['fk_cargo'] != 1 && $_SESSION['fk_cargo'] != 2)) {
    echo "<script>alert('Acesso negado! Você não tem permissão para acessar esta página.'); window.location.href='principal.php';</script>";
    exit;
}

$usuarios = []; // Inicializa o array para armazenar resultados

try {
    // Consulta para selecionar todos os usuários
    // A coluna 'perfil' foi temporariamente removida do SELECT devido ao erro "Column not found".
    // Assim que a coluna for adicionada ao seu banco de dados, avise para que possamos incluí-la novamente.
    $sql = "SELECT pk_usuario, nome_user, email_user FROM usuario ORDER BY nome_user ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Em um ambiente de produção, registre o erro em vez de exibi-lo diretamente
    // error_log("Erro no banco de dados: " . $e->getMessage());
    echo "<p style='color: red; text-align: center;'>Erro ao carregar usuários: " . htmlspecialchars($e->getMessage()) . "</p>";
    $usuarios = []; // Garante que o array de usuários esteja vazio em caso de erro
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Usuários</title>
    <style>
        /* Estilos baseados no layout e cores do adm.php */
        body {
            font-family: Arial, sans-serif;
            background: #f6f6fa; /* Cor de fundo suave */
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            background: #fff; /* Fundo branco para o conteúdo principal */
            padding: 30px;
            border-radius: 8px; /* Cantos arredondados */
            box-shadow: 0 4px 24px rgba(0,0,0,0.05); /* Sombra suave */
        }

        h2 {
            color: #2c056e; /* Cor escura do cabeçalho, similar ao do adm.php */
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
            overflow: hidden; /* Garante que a borda arredondada seja visível */
        }

        table th, table td {
            border: 1px solid #eee;
            padding: 15px 18px;
            text-align: left;
            vertical-align: middle;
        }

        table th {
            background: #e6e1f4; /* Fundo do cabeçalho da tabela */
            color: #2c056e;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.9em;
        }

        table tr:nth-child(even) {
            background: #f9f9f9; /* Zebra striping para melhor leitura */
        }
        table tr:hover {
            background: #f0f0f0; /* Efeito de hover na linha */
        }

        table a {
            color: #510d96; /* Cor do link, similar ao botão */
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
            color: #fff; /* Texto branco */
            background: #510d96; /* Fundo roxo como os botões */
            text-decoration: none;
            font-weight: 600;
            padding: 12px 25px;
            border: 1px solid #510d96; /* Borda da mesma cor */
            border-radius: 5px;
            max-width: 200px; /* Largura máxima para o botão "Voltar" */
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

        /* Media Queries para Responsividade */
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
        <h2>Lista de Usuários</h2>

        <?php if (!empty($usuarios)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario) : ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['pk_usuario']); ?></td>
                            <td><?= htmlspecialchars($usuario['nome_user']); ?></td>
                            <td><?= htmlspecialchars($usuario['email_user']); ?></td>
                            <td>
                                <a href="alterar_usuario.php?id=<?= htmlspecialchars($usuario['pk_usuario']); ?>">Alterar</a>
                                <a href="excluir_usuario.php?id=<?= htmlspecialchars($usuario['pk_usuario']); ?>" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Nenhum usuário cadastrado no sistema ou erro ao carregar.</p>
        <?php endif; ?>

        <a href="adm.php" class="back-link">Voltar ao Painel</a>
    </div>
</body>
</html>