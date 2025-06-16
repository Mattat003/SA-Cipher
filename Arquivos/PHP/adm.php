<?php
session_start();
if (!isset($_SESSION['tipo'])) {
    header("Location: login.php");
    exit();
}
require_once 'conexao.php';

// Pega o fk_cargo da sessão
$fk_cargo = $_SESSION['fk_cargo'] ?? null;
$nomeCargo = '';
$nome = $_SESSION['adm'] ?? 'Usuário';

// Busca o nome do cargo na tabela cargo
if ($fk_cargo) {
    $stmt = $pdo->prepare("SELECT nome_cargo FROM cargo WHERE pk_cargo = :id");
    $stmt->bindParam(":id", $fk_cargo, PDO::PARAM_INT);
    $stmt->execute();
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $nomeCargo = $row['nome_cargo'];
    }
}

// Exemplo: defina os IDs dos cargos
define('CARGO_ADMIN', 1);
define('CARGO_FUNCIONARIO', 2);
// Adicione outros cargos conforme sua tabela

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do <?=htmlspecialchars($nomeCargo ?: 'Usuário')?></title>
    <style>
        .painel-func { margin: 20px 0; }
        button, .painel-func a { padding:7px 18px; margin:2px; display:inline-block; text-decoration:none; background:#008; color:#fff; border:none; border-radius:5px; }
        button.logout-btn { background:#900; }
    </style>
</head>
<body>
    <h1>Painel do <?=htmlspecialchars($nomeCargo ?: 'Usuário')?></h1>
    <p>Bem-vindo, <strong><?= htmlspecialchars($nome) ?></strong>!</p>
    <p>Você está logado como <strong><?= htmlspecialchars($nomeCargo ?: 'Usuário') ?></strong></p>
    <div class="painel-func">
        <?php if ($fk_cargo == CARGO_ADMIN): ?>
            <!-- Admin: pode tudo -->
            <a href="cadastrar_usuario.php">Adicionar Usuário</a>
            <a href="buscar_usuario.php">Pesquisar Usuários</a>
            <a href="listar_usuario.php">Listar Usuários</a>
            <a href="excluir_usuario.php">Excluir Usuário</a>
            <!-- Adicione mais funções conforme necessário -->
        <?php elseif ($fk_cargo == CARGO_FUNCIONARIO): ?>
            <a href="cadastrar_conteudo.php">Adicionar Conteúdo</a>
            <a href = "cadastrar_usuario.php"> Adicionar Usuário</a>
            <a href = "buscar_cliente.php"> Buscar Cliente </a>
            <a href = "buscar_fornecedor.php"> Buscar Fornecedor </a>
            <a href = "buscar_jogo.php"> Buscar Jogo </a>
            <!-- Outras funções exclusivas deste cargo -->
        <?php else: ?>
            <!-- Outros cargos: personalize aqui -->
            <p>Você não possui permissões administrativas.</p>
        <?php endif; ?>
    </div>
    <form action="logout.php" method="post">
        <button class="logout-btn" type="submit">Sair</button>
    </form>
</body>
</html>