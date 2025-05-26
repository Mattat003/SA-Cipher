<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['pk_usuario']) || !isset($_GET['jogo_id']) || !isset($_GET['redirect'])) {
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['pk_usuario'];
$jogo_id = $_GET['jogo_id'];
$redirect_url = $_GET['redirect'];

// Verifica se o jogo existe (opcional)
// $stmt = $pdo->prepare("SELECT id FROM jogo WHERE id = ?");
// $stmt->execute([$jogo_id]);
// $jogo = $stmt->fetch();

// Registra no histórico
try {
    $stmt = $pdo->prepare("INSERT INTO historico_jogos (usuario_id, jogo_id) VALUES (?, ?)");
    $stmt->execute([$usuario_id, $jogo_id]);
} catch (PDOException $e) {
    // Você pode logar o erro se quiser
    error_log("Erro ao registrar jogo: " . $e->getMessage());
}

// Redireciona para o jogo
header("Location: " . $redirect_url);
exit;
?>