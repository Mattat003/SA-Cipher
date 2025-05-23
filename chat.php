<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['pk_usuario'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['pk_usuario'];
$amigo_id = isset($_GET['amigo']) ? intval($_GET['amigo']) : 0;

if ($amigo_id <= 0 || $amigo_id === $usuario_id) {
    exit("Usuário inválido.");
}

// Verifica se são amigos
$stmt = $pdo->prepare("SELECT * FROM amigos WHERE usuario_id = :id AND amigo_id = :amigo");
$stmt->execute(['id' => $usuario_id, 'amigo' => $amigo_id]);
if ($stmt->rowCount() == 0) {
    exit("Vocês não são amigos.");
}

// Pega nome do amigo
$stmt = $pdo->prepare("SELECT nome_user FROM usuario WHERE pk_usuario = :id");
$stmt->execute(['id' => $amigo_id]);
$amigo = $stmt->fetch();
$amigo_nome = $amigo['nome_user'] ?? "Desconhecido";

// Pega mensagens entre os dois
$stmt = $pdo->prepare("
    SELECT * FROM mensagens
    WHERE (de_id = :usuario AND para_id = :amigo)
       OR (de_id = :amigo AND para_id = :usuario)
    ORDER BY data_envio ASC
");
$stmt->execute(['usuario' => $usuario_id, 'amigo' => $amigo_id]);
$mensagens = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Chat com <?= htmlspecialchars($amigo_nome) ?></title>
    <style>
        /* Estilos do chat */
    </style>
</head>
<body>
<div class="chat-container">
    <h2>Conversando com <?= htmlspecialchars($amigo_nome) ?></h2>

    <div class="mensagens">
        <?php foreach ($mensagens as $msg): ?>
            <div class="mensagem">
                <span class="de">
                    <?= ($msg['de_id'] == $usuario_id) ? 'Você' : htmlspecialchars($amigo_nome) ?>:
                </span>
                <span><?= htmlspecialchars($msg['mensagem']) ?></span><br>
                <small><?= $msg['data_envio'] ?></small>
            </div>
        <?php endforeach; ?>
    </div>

    <form class="formulario" action="enviar_mensagem.php" method="post">
        <input type="hidden" name="para_id" value="<?= $amigo_id ?>">
        <textarea name="mensagem" required placeholder="Digite sua mensagem..."></textarea>
        <button type="submit">Enviar</button>
    </form>
</div>
</body>
</html>
