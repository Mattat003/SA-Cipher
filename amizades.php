<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['pk_usuario'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['pk_usuario'];
$usuario_nome = $_SESSION['usuario'];

// Buscar todos os outros usuários
$stmt = $pdo->prepare("SELECT pk_usuario, nome_user FROM usuario WHERE pk_usuario != :id");
$stmt->execute(['id' => $usuario_id]);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar amizades existentes
$stmt = $pdo->prepare("SELECT amigo_id FROM amigos WHERE usuario_id = :id");
$stmt->execute(['id' => $usuario_id]);
$amigos = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Buscar pedidos enviados
$stmt = $pdo->prepare("SELECT para_id FROM pedidos_amizade WHERE de_id = :id AND status = 'pendente'");
$stmt->execute(['id' => $usuario_id]);
$pendentes = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Amizades</title>
</head>
<body>
    <h2>Bem-vindo, <?php echo htmlspecialchars($usuario_nome); ?>!</h2>
    <h3>Usuários disponíveis:</h3>
    <ul>
        <?php foreach ($usuarios as $usuario): ?>
            <li>
                <?php echo htmlspecialchars($usuario['nome_user']); ?>

                <?php if (in_array($usuario['pk_usuario'], $amigos)): ?>
                    - <a href="chat.php?amigo=<?php echo $usuario['pk_usuario']; ?>">Conversar</a>

                <?php elseif (in_array($usuario['pk_usuario'], $pendentes)): ?>
                    - Pedido enviado

                <?php else: ?>
                    - <form action="enviar_pedido.php" method="post" style="display:inline;">
                        <input type="hidden" name="para_id" value="<?php echo $usuario['pk_usuario']; ?>">
                        <button type="submit">Adicionar</button>
                      </form>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
