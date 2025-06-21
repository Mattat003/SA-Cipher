<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['pk_usuario'])) {
    header('Location: login.php');
    exit();
}

$usuario_id = $_SESSION['pk_usuario'];

// Busca usuários que não são amigos nem têm pedido pendente
$sql = "
    SELECT u.pk_usuario AS id, u.nome_user AS nome
    FROM usuario u
    WHERE u.pk_usuario != ?
      AND u.pk_usuario NOT IN (
        SELECT amigo_id FROM amigos WHERE usuario_id = ?
      )
      AND u.pk_usuario NOT IN (
        SELECT para_id FROM pedidos_amizade WHERE de_id = ? AND status = 'pendente'
      )
      AND u.pk_usuario NOT IN (
        SELECT de_id FROM pedidos_amizade WHERE para_id = ? AND status = 'pendente'
      )
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $usuario_id, $usuario_id, $usuario_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$usuarios = $result->fetch_all(MYSQLI_ASSOC);
?>

<h2>Usuários disponíveis para enviar pedido</h2>

<?php if (count($usuarios) == 0): ?>
    <p>Nenhum usuário disponível para enviar pedido.</p>
<?php else: ?>
    <ul>
        <?php foreach($usuarios as $user): ?>
            <li>
                <?= htmlspecialchars($user['nome']) ?>
                <form action="enviar_pedido.php" method="post" style="display:inline;">
                    <input type="hidden" name="para_id" value="<?= $user['id'] ?>">
                    <button type="submit">Enviar pedido de amizade</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<a href="chat.php">Voltar ao chat</a>
