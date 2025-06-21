<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['pk_usuario'])) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['jogo_id'])) {
    $usuario_id = $_SESSION['pk_usuario'];
    $jogo_id = $_POST['jogo_id'];

    // Verifica se já há um pedido pendente
    $stmt = $pdo->prepare("SELECT * FROM locacoes_pendentes WHERE usuario_id = ? AND jogo_id = ? AND status = 'pendente'");
    $stmt->execute([$usuario_id, $jogo_id]);
    if ($stmt->rowCount() > 0) {
        header('Location: jogos.php?msg=Já existe um pedido pendente para este jogo');
        exit();
    }

    // Insere pedido de locação pendente
    $stmt = $pdo->prepare("INSERT INTO locacoes_pendentes (usuario_id, jogo_id) VALUES (?, ?)");
    $stmt->execute([$usuario_id, $jogo_id]);
    header('Location: jogos.php?msg=Pedido de locação enviado. Aguarde a liberação do administrador.');
    exit();
}
header('Location: jogos.php?erro=Jogo não encontrado');
exit();