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
    // Consulta para selecionar todos os Cliente
    // A coluna 'perfil' foi temporariamente removida do SELECT devido ao erro "Column not found".
    // Assim que a coluna for adicionada ao seu banco de dados, avise para que possamos incluí-la novamente.
    $sql = "SELECT pk_usuario, nome_user, email_user FROM usuario ORDER BY nome_user ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Em um ambiente de produção, registre o erro em vez de exibi-lo diretamente
    // error_log("Erro no banco de dados: " . $e->getMessage());
    echo "<p style='color: red; text-align: center;'>Erro ao carregar Cliente: " . htmlspecialchars($e->getMessage()) . "</p>";
    $usuarios = []; // Garante que o array de Cliente esteja vazio em caso de erro
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Cliente</title>
    <style>
    body {
        background-color: #12002b;
        color: #f0e6ff;
        font-family: 'Motiva Sans', 'Segoe UI', sans-serif;
        margin: 0;
        padding: 20px;
        min-height: 100vh;
        line-height: 1.6;
    }

    .container {
        max-width: 900px;
        margin: 20px auto;
        background: #1e1b2e;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
        border: 1px solid #5d3bad;
    }

    h2 {
        color: #c084fc;
        text-align: center;
        margin-bottom: 30px;
        font-size: 2em;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: #252836;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(0,0,0,0.2);
    }

    table th, table td {
        border: 1px solid #2a2540;
        padding: 15px 18px;
        text-align: left;
        vertical-align: middle;
        color: #f0e6ff;
    }

    table th {
        background: #5d3bad;
        color: #fff;
        text-transform: uppercase;
        font-size: 0.9em;
    }

    table tr:nth-child(even) {
        background: #2a2e3c;
    }

    table tr:hover {
        background: #373b51;
    }

    table a {
        color: #c084fc;
        text-decoration: none;
        margin-right: 12px;
        font-weight: 500;
        transition: color 0.2s, text-decoration 0.2s;
    }

    table a:hover {
        color: #7a5af5;
        text-decoration: underline;
    }

    p {
        text-align: center;
        color: #c4b5fd;
        margin-top: 20px;
        font-size: 1.1em;
        padding: 10px;
        border: 1px dashed #5d3bad;
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
        <h2>Lista de Cliente</h2>

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
    </div>
    <a href="adm.php" class="back-link">Voltar</a>
</body>
</html>