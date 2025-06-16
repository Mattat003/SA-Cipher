<?php
session_start();
require_once 'conexao.php'; // Certifique-se de que este caminho está correto

// Define os IDs de cargo, assim como em adm.php
define('CARGO_ADMIN', 1);

// Verifica se o usuário está logado e se tem permissão de administrador
if (!isset($_SESSION['tipo']) || $_SESSION['fk_cargo'] != CARGO_ADMIN) {
    header("Location: login.php");
    exit();
}

$mensagem = '';

// Lógica para exclusão de usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario'])) {
    $id_usuario_a_excluir = $_POST['id_usuario'];

    // Impede que o próprio administrador tente excluir a si mesmo
    if ($id_usuario_a_excluir == $_SESSION['id_usuario']) {
        $mensagem = "<p class='error-message'>Você não pode excluir sua própria conta enquanto estiver logado.</p>";
    } else {
        try {
            $pdo->beginTransaction();

            // Opcional: Se houver tabelas relacionadas com chave estrangeira,
            // você precisará definir a ação ON DELETE CASCADE ou excluir registros relacionados primeiro.
            // Por exemplo, se um usuário tem posts, você pode querer excluir os posts primeiro:
            // $stmt_delete_posts = $pdo->prepare("DELETE FROM posts WHERE fk_usuario = :id");
            // $stmt_delete_posts->bindParam(':id', $id_usuario_a_excluir, PDO::PARAM_INT);
            // $stmt_delete_posts->execute();

            $stmt = $pdo->prepare("DELETE FROM usuario WHERE pk_usuario = :id");
            $stmt->bindParam(':id', $id_usuario_a_excluir, PDO::PARAM_INT);

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $pdo->commit();
                    $mensagem = "<p class='success-message'>Usuário excluído com sucesso!</p>";
                } else {
                    $pdo->rollBack();
                    $mensagem = "<p class='error-message'>Usuário não encontrado ou já foi excluído.</p>";
                }
            } else {
                $pdo->rollBack();
                $mensagem = "<p class='error-message'>Erro ao excluir usuário: " . implode(" - ", $stmt->errorInfo()) . "</p>";
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            $mensagem = "<p class='error-message'>Erro de banco de dados: " . $e->getMessage() . "</p>";
        }
    }
}

// Lógica para listar todos os usuários (exceto o próprio administrador)
$usuarios = [];
try {
    $stmt = $pdo->prepare("SELECT u.pk_usuario, u.nome_usuario, u.email_usuario, c.nome_cargo
                           FROM usuario u
                           JOIN cargo c ON u.fk_cargo = c.pk_cargo
                           WHERE u.pk_usuario != :current_user_id
                           ORDER BY u.nome_usuario ASC");
    $stmt->bindParam(':current_user_id', $_SESSION['id_usuario'], PDO::PARAM_INT);
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensagem = "<p class='error-message'>Erro ao carregar lista de usuários: " . $e->getMessage() . "</p>";
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Usuário - Painel Administrativo</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        :root {
            --primary-purple: #7B68EE; /* MediumSlateBlue */
            --dark-purple: #4B0082;    /* Indigo */
            --light-purple: #E6E6FA;   /* Lavender */
            --text-color: #333;
            --white-color: #fff;
            --danger-red: #DC143C;     /* Crimson */
            --success-green: #28a745;  /* Success green */
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            --border-radius: 8px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, var(--light-purple) 0%, var(--primary-purple) 100%);
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            box-sizing: border-box;
        }

        .container {
            background-color: var(--white-color);
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            max-width: 900px;
            width: 100%;
            text-align: center;
            animation: fadeIn 1s ease-out;
            margin-bottom: 20px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            color: var(--dark-purple);
            margin-bottom: 20px;
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.05);
        }

        .message {
            padding: 10px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            font-weight: 600;
        }

        .success-message {
            background-color: #d4edda;
            color: var(--success-green);
            border: 1px solid #c3e6cb;
        }

        .error-message {
            background-color: #f8d7da;
            color: var(--danger-red);
            border: 1px solid #f5c6cb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: var(--white-color);
            box-shadow: var(--shadow);
            border-radius: var(--border-radius);
            overflow: hidden; /* Para border-radius funcionar com thead/tbody */
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #eee;
            text-align: left;
        }

        th {
            background-color: var(--dark-purple);
            color: var(--white-color);
            font-weight: 600;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .action-button {
            padding: 8px 15px;
            background: var(--danger-red);
            color: var(--white-color);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .action-button:hover {
            background: #FF4500; /* OrangeRed */
            transform: translateY(-2px);
        }

        .back-button {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 25px;
            background: var(--primary-purple);
            color: var(--white-color);
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .back-button:hover {
            background: var(--dark-purple);
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }
    </style>
    <script>
        function confirmarExclusao(nomeUsuario) {
            return confirm("Tem certeza que deseja excluir o usuário '" + nomeUsuario + "'? Esta ação é irreversível.");
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Excluir Usuário</h1>

        <?php if ($mensagem): ?>
            <div class="message <?= strpos($mensagem, 'sucesso') !== false ? 'success-message' : 'error-message' ?>">
                <?= $mensagem ?>
            </div>
        <?php endif; ?>

        <?php if (empty($usuarios)): ?>
            <p>Nenhum outro usuário encontrado para exclusão.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome de Usuário</th>
                        <th>Email</th>
                        <th>Cargo</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['pk_usuario']) ?></td>
                            <td><?= htmlspecialchars($usuario['nome_usuario']) ?></td>
                            <td><?= htmlspecialchars($usuario['email_usuario']) ?></td>
                            <td><?= htmlspecialchars($usuario['nome_cargo']) ?></td>
                            <td>
                                <form action="excluir_usuario.php" method="post" onsubmit="return confirmarExclusao('<?= htmlspecialchars($usuario['nome_usuario']) ?>');">
                                    <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario['pk_usuario']) ?>">
                                    <button type="submit" class="action-button">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <a href="adm.php" class="back-button">Voltar ao Painel</a>
    </div>
</body>
</html>