<?php
session_start();

// Verifica se o usuário está logado como admin
if (!isset($_SESSION['is_admin'])) {
    header('Location: login.php');
    exit();
}

require_once 'conexao.php';

// Busca informações do admin
$stmt = $pdo->prepare("SELECT a.*, c.nome_cargo FROM adm a JOIN cargo c ON a.fk_cargo = c.pk_cargo WHERE a.pk_adm = :id");
$stmt->bindParam(':id', $_SESSION['pk_adm']);
$stmt->execute();
$admin = $stmt->fetch();

if (!$admin) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Meu Perfil - Administrador</title>
    
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
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-info {
            margin-bottom: 20px;
        }
        .info-label {
            font-weight: bold;
            color: #6c757d;
        }
        .admin-badge {
            background-color: #6f42c1;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.8em;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <span class="material-symbols-outlined" style="font-size: 100px;">admin_panel_settings</span>
            <h1><?php echo htmlspecialchars($admin['nome_adm']); ?> <span class="admin-badge">ADMIN</span></h1>
            <p class="text-muted"><?php echo htmlspecialchars($admin['nome_cargo']); ?></p>
        </div>

        <div class="profile-info">
            <div class="row mb-3">
                <div class="col-md-3 info-label">Nome:</div>
                <div class="col-md-9"><?php echo htmlspecialchars($admin['nome_adm']); ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 info-label">Email:</div>
                <div class="col-md-9"><?php echo htmlspecialchars($admin['email_adm']); ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 info-label">Cargo:</div>
                <div class="col-md-9"><?php echo htmlspecialchars($admin['nome_cargo']); ?></div>
            </div>
        </div>

        <div class="text-center">
            <a href="admin.php" class="btn btn-primary">Painel de Administração</a>
            <a href="alterar_senha.php" class="btn btn-secondary">Alterar Senha</a>
            <a href="index.php" class="btn btn-outline-dark">Voltar</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>