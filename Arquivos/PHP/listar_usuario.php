<?php
session_start(); //
if (!isset($_SESSION['tipo'])) { //
    header("Location: login.php"); //
    exit(); //
}
require_once 'conexao.php'; //

try {
    $stmt = $pdo->query("SELECT pk_usuario, nome_user, email_user, data_criacao FROM usuario"); //
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC); //
} catch (PDOException $e) {
    die("Erro ao listar usuários: " . $e->getMessage()); //
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Usuários</title>
    <style>
        /* Reusing styles from adm.php for consistency */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        :root {
            --primary-purple: #5E35B1;
            --dark-purple: #311B92;
            --light-purple: #9575CD;
            --text-color: #f0f0f0;
            --white-color: #263238;
            --danger-red: #C62828;
            --success-green: #2E7D32;
            --info-blue: #0277BD;
            --shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
            --border-radius: 10px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, var(--dark-purple) 0%, #1A237E 100%);
            color: var(--text-color);
            display: flex;
            flex-direction: column;
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
            margin-top: 20px;
            margin-bottom: 20px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            color: var(--light-purple);
            margin-bottom: 20px;
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #37474F; /* Slightly lighter dark gray for table */
            border-radius: var(--border-radius);
            overflow: hidden; /* Ensures rounded corners apply to content */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #455A64; /* Darker border for rows */
            color: var(--text-color);
        }

        th {
            background-color: var(--primary-purple);
            font-weight: 600;
            color: #fff;
            text-transform: uppercase;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background-color: #4CAF50; /* Light green on hover for rows */
            transition: background-color 0.3s ease;
        }

        td a {
            padding: 5px 10px;
            margin: 2px;
            display: inline-block;
            text-decoration: none;
            background: var(--info-blue);
            color: #fff;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        td a:hover {
            background: #01579B; /* Darker blue on hover */
        }

        td a.delete-btn {
            background: var(--danger-red);
        }

        td a.delete-btn:hover {
            background: #B71C1C; /* Darker red on hover */
        }

        .back-btn {
            margin-top: 30px;
            padding: 12px 25px;
            display: inline-block;
            text-decoration: none;
            background: var(--primary-purple);
            color: var(--text-color);
            border: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            cursor: pointer;
        }

        .back-btn:hover {
            background: var(--light-purple);
            transform: translateY(-4px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.4);
        }

        p {
            margin-top: 20px;
            color: var(--light-purple);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lista de Usuários</h1>
        <?php if (count($usuarios) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Data de Criação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['pk_usuario']) ?></td>
                            <td><?= htmlspecialchars($usuario['nome_user']) ?></td>
                            <td><?= htmlspecialchars($usuario['email_user']) ?></td>
                            <td><?= htmlspecialchars($usuario['data_criacao']) ?></td>
                            <td>
                                <a href="editar_usuario.php?id=<?= $usuario['pk_usuario'] ?>">Editar</a> |
                                <a href="excluir_usuario.php?id=<?= $usuario['pk_usuario'] ?>" class="delete-btn" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum usuário encontrado.</p>
        <?php endif; ?>
    </div>
    <a href="adm.php" class="back-btn">Voltar ao Painel</a>
</body>
</html>