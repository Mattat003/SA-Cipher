<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['pk_usuario'])) {
    header('Location: login.php');
    exit();
}

$usuario_id = $_SESSION['pk_usuario'];

if (!isset($_POST['jogo_id'])) {
    header('Location: jogos.php?erro=Jogo não encontrado');
    exit();
}

$jogo_id = $_POST['jogo_id'];

// Verifica se usuário existe (opcional mas recomendado)
$stmt = $pdo->prepare("SELECT 1 FROM usuario WHERE pk_usuario = ?");
$stmt->execute([$usuario_id]);
if ($stmt->rowCount() == 0) {
    // Usuário não encontrado no banco, logout ou erro
    session_destroy();
    header('Location: login.php?erro=Usuário não encontrado');
    exit();
}

// Verifica se já há pedido pendente para esse usuário e jogo
$stmt = $pdo->prepare("SELECT 1 FROM locacoes_pendentes WHERE usuario_id = ? AND jogo_id = ? AND status = 'pendente'");
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
