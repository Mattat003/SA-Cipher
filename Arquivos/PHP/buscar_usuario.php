<?php
session_start();
require_once 'conexao.php'; // Certifique-se de que este arquivo existe e faz a conexão PDO

// Verifica se o usuário está logado e tem perfil de adm (1) ou funcionário (2)
if (!isset($_SESSION['fk_cargo']) || ($_SESSION['fk_cargo'] != 1 && $_SESSION['fk_cargo'] != 2)) {
    echo "<script>alert('Acesso negado! Você não tem permissão para acessar esta página.'); window.location.href='principal.php';</script>";
    exit;
}

$usuarios = [];
$busca_realizada = false;

try {
    // Só faz SELECT se o formulário de busca for enviado e não estiver vazio
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['busca']) && !empty(trim($_POST['busca']))) {
        $busca = trim($_POST['busca']);
        $busca_realizada = true;
        $sql = "SELECT pk_usuario, nome_user, email_user FROM usuario";
        $params = [];
        if (is_numeric($busca)) {
            $sql .= " WHERE pk_usuario = :busca";
            $params[':busca'] = $busca;
        } else {
            $sql .= " WHERE nome_user LIKE :busca_nome";
            $params[':busca_nome'] = "%".$busca."%";
        }
        $sql .= " ORDER BY nome_user ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "<p style='color: red; text-align: center;'>Erro ao carregar usuários: " . htmlspecialchars($e->getMessage()) . "</p>";
    $usuarios = [];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Usuário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f6f6fa;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.05);
        }
        h2 { color: #2c056e; text-align: center; margin-bottom: 30px; font-size: 2em; font-weight: 700; }
        form {
            display: flex; flex-wrap: wrap; gap: 15px; margin-bottom: 30px; justify-content: center; align-items: center;
            padding: 15px; background: #e6e1f4; border-radius: 8px; box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
        }
        form label { font-weight: 600; color: #555; flex-shrink: 0; }
        form input[type="text"] {
            padding: 12px 18px; border: 1px solid #ddd; border-radius: 5px;
            flex-grow: 1; max-width: 350px; font-size: 1em; transition: border-color 0.2s, box-shadow 0.2s;
        }
        form input[type="text"]:focus {
            border-color: #510d96; box-shadow: 0 0 0 3px rgba(81, 13, 150, 0.2); outline: none;
        }
        form button {
            background: #510d96; color: #fff; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer;
            transition: background 0.2s ease, transform 0.1s ease; font-size: 1em; font-weight: 600;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        form button:hover {
            background: #2c056e; transform: translateY(-1px);
        }
        form button:active {
            transform: translateY(0); box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        table {
            width: 100%; border-collapse: collapse; margin-top: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-radius: 8px; overflow: hidden;
        }
        table th, table td {
            border: 1px solid #eee; padding: 15px 18px; text-align: left; vertical-align: middle;
        }
        table th {
            background: #e6e1f4; color: #2c056e; font-weight: 700; text-transform: uppercase; font-size: 0.9em;
        }
        table tr:nth-child(even) { background: #f9f9f9; }
        table tr:hover { background: #f0f0f0; }
        table a { color: #510d96; text-decoration: none; margin-right: 12px; font-weight: 500; transition: color 0.2s, text-decoration 0.2s; }
        table a:hover { color: #2c056e; text-decoration: underline; }
        p {
            text-align: center; color: #777; margin-top: 20px; font-size: 1.1em; padding: 10px; border: 1px dashed #ccc;
            border-radius: 5px; background: #fefefe;
        }
        .back-link {
            display: block; text-align: center; margin-top: 30px; color: #fff; background: #510d96; text-decoration: none;
            font-weight: 600; padding: 12px 25px; border: 1px solid #510d96; border-radius: 5px; max-width: 200px;
            margin-left: auto; margin-right: auto; transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .back-link:hover { background: #2c056e; color: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.3); transform: translateY(-1px); }
        .back-link:active { transform: translateY(0); box-shadow: 0 1px 3px rgba(0,0,0,0.2); }
        @media (max-width: 768px) {
            .container { margin: 10px; padding: 20px; }
            form { flex-direction: column; align-items: stretch; }
            form input[type="text"], form button { width: 100%; max-width: none; }
            table th, table td { padding: 10px; font-size: 0.85em; }
            table a { margin-right: 5px; }
        }
        @media (max-width: 480px) {
            h2 { font-size: 1.8em; }
            .back-link { max-width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pesquisar Usuários</h2>
        <!-- FORMULARIO PARA BUSCAR USUARIOS -->
        <form action="buscar_usuario.php" method="POST">
            <label for="busca">Buscar por ID ou Nome:</label>
            <input type="text" id="busca" name="busca" placeholder="Digite o ID ou nome do usuário" value="<?= isset($_POST['busca']) ? htmlspecialchars($_POST['busca']) : '' ?>">
            <button type="submit">Pesquisar</button>
        </form>
        <?php if ($busca_realizada): ?>
            <?php if (!empty($usuarios)): ?>
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
                        <?php foreach ($usuarios as $usuario): ?>
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
            <?php else: ?>
                <p>Nenhum usuário encontrado com os critérios de busca.</p>
            <?php endif; ?>
        <?php endif; ?>
        <a href="adm.php" class="back-link">Voltar ao Painel</a>
    </div>
</body>
</html>