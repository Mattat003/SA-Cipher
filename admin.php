<?php
session_start();

// Verifica se o usuário está logado como admin
if (!isset($_SESSION['is_admin']) {
    header('Location: login.php');
    exit();
}

require_once 'conexao.php';

// Pega o nome do usuário da sessão
$nomeUsuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';

// Funções administrativas
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_user'])) {
        $userId = $_POST['user_id'];
        $stmt = $pdo->prepare("DELETE FROM usuario WHERE pk_usuario = :id");
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
    }
}

// Buscar todos os usuários
$stmt = $pdo->query("SELECT pk_usuario, nome_user, email_user FROM usuario");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Painel de Administração</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    
    <!-- Material Symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    
    <!-- Seu CSS personalizado -->
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .admin-header {
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .user-table {
            width: 100%;
        }
        .user-table th {
            background-color: #343a40;
            color: white;
        }
        .action-btn {
            margin: 2px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="text-center">Painel de Administração</h1>
            <p class="text-center">Bem-vindo, <?php echo htmlspecialchars($nomeUsuario); ?> (Admin)</p>
            <a href="index.php" class="btn btn-secondary">Voltar ao Site</a>
        </div>

        <h2>Gerenciamento de Usuários</h2>
        <table class="table table-striped table-bordered user-table">
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
                    <td><?php echo $usuario['pk_usuario']; ?></td>
                    <td><?php echo htmlspecialchars($usuario['nome_user']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['email_user']); ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="user_id" value="<?php echo $usuario['pk_usuario']; ?>">
                            <button type="submit" name="delete_user" class="btn btn-danger btn-sm action-btn" 
                                    onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                Excluir
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>