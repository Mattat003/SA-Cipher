<?php
session_start();

// Verifica se o usuário está logado como admin
if (!isset($_SESSION['is_admin'])) {
    header('Location: login.php');
    exit();
}

require_once 'conexao.php';

// Pega o nome do admin da sessão
$nomeAdmin = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';
$nivelCargo = isset($_SESSION['nivel_cargo']) ? $_SESSION['nivel_cargo'] : 0;

// Funções administrativas baseadas no nível de cargo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $nivelCargo >= 3) {
    if (isset($_POST['delete_user'])) {
        $userId = $_POST['user_id'];
        $stmt = $pdo->prepare("DELETE FROM usuario WHERE pk_usuario = :id");
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
    } elseif (isset($_POST['add_game'])) {
        // Implementar adição de jogos
    }
}

// Buscar todos os usuários (apenas para admins com nível 3+)
$usuarios = [];
if ($nivelCargo >= 3) {
    $stmt = $pdo->query("SELECT pk_usuario, nome_user, email_user, data_criacao FROM usuario");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Buscar todos os jogos
$stmt = $pdo->query("SELECT j.*, d.nome_dev as desenvolvedora, p.nome_publi as publicadora 
                     FROM jogo j 
                     JOIN desenvolvedora d ON j.fk_dev = d.pk_dev
                     JOIN publicadora p ON j.fk_pub = p.pk_publi");
$jogos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar informações do admin atual
$stmt = $pdo->prepare("SELECT a.*, c.nome_cargo FROM adm a JOIN cargo c ON a.fk_cargo = c.pk_cargo WHERE a.pk_adm = :id");
$stmt->bindParam(':id', $_SESSION['pk_adm']);
$stmt->execute();
$adminAtual = $stmt->fetch();
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
        .user-table, .game-table {
            width: 100%;
        }
        .user-table th, .game-table th {
            background-color: #343a40;
            color: white;
        }
        .action-btn {
            margin: 2px;
        }
        .admin-nav {
            margin-bottom: 30px;
        }
        .admin-nav .nav-link {
            font-weight: 500;
        }
        .admin-nav .nav-link.active {
            background-color: #6c757d;
            color: white;
        }
        .admin-info {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="text-center">Painel de Administração</h1>
            <p class="text-center">Bem-vindo, <?php echo htmlspecialchars($nomeAdmin); ?></p>
            <a href="index.php" class="btn btn-secondary">Voltar ao Site</a>
        </div>

        <div class="admin-info">
            <h3>Suas Informações</h3>
            <p><strong>Cargo:</strong> <?php echo htmlspecialchars($adminAtual['nome_cargo']); ?></p>
            <p><strong>Nível de Acesso:</strong> <?php echo $nivelCargo; ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($adminAtual['email_adm']); ?></p>
        </div>

        <ul class="nav nav-tabs admin-nav">
            <li class="nav-item">
                <a class="nav-link active" href="#usuarios" data-bs-toggle="tab">Usuários</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#jogos" data-bs-toggle="tab">Jogos</a>
            </li>
            <?php if ($nivelCargo >= 4): ?>
            <li class="nav-item">
                <a class="nav-link" href="#admins" data-bs-toggle="tab">Administradores</a>
            </li>
            <?php endif; ?>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="usuarios">
                <h2>Gerenciamento de Usuários</h2>
                <?php if ($nivelCargo >= 3): ?>
                    <table class="table table-striped table-bordered user-table">
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
                                <td><?php echo $usuario['pk_usuario']; ?></td>
                                <td><?php echo htmlspecialchars($usuario['nome_user']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['email_user']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($usuario['data_criacao'])); ?></td>
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
                <?php else: ?>
                    <div class="alert alert-warning">Seu nível de acesso não permite gerenciar usuários.</div>
                <?php endif; ?>
            </div>

            <div class="tab-pane fade" id="jogos">
                <h2>Gerenciamento de Jogos</h2>
                <table class="table table-striped table-bordered game-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Data de Lançamento</th>
                            <th>Desenvolvedora</th>
                            <th>Publicadora</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jogos as $jogo): ?>
                        <tr>
                            <td><?php echo $jogo['pk_jogo']; ?></td>
                            <td><?php echo htmlspecialchars($jogo['nome_jogo']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($jogo['data_lanc'])); ?></td>
                            <td><?php echo htmlspecialchars($jogo['desenvolvedora']); ?></td>
                            <td><?php echo htmlspecialchars($jogo['publicadora']); ?></td>
                            <td>
                                <a href="editar_jogo.php?id=<?php echo $jogo['pk_jogo']; ?>" class="btn btn-primary btn-sm action-btn">Editar</a>
                                <a href="#" class="btn btn-danger btn-sm action-btn">Excluir</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php if ($nivelCargo >= 4): ?>
                    <a href="adicionar_jogo.php" class="btn btn-success">Adicionar Novo Jogo</a>
                <?php endif; ?>
            </div>

            <?php if ($nivelCargo >= 4): ?>
            <div class="tab-pane fade" id="admins">
                <h2>Gerenciamento de Administradores</h2>
                <p>Aqui você pode adicionar ou remover administradores.</p>
                <!-- Adicione o formulário e a tabela para gerenciar admins aqui -->
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>