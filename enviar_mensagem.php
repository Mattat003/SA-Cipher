<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['pk_usuario']) || !isset($_POST['para_id'], $_POST['mensagem'])) {
    header("Location: login.php");
    exit;
}

$de_id = $_SESSION['pk_usuario'];
$para_id = intval($_POST['para_id']);
$mensagem = trim($_POST['mensagem']);

if ($mensagem !== '') {
    $stmt = $pdo->prepare("INSERT INTO mensagens (de_id, para_id, mensagem) VALUES (:de, :para, :mensagem)");
    $stmt->execute([
        'de' => $de_id,
        'para' => $para_id,
        'mensagem' => $mensagem
    ]);
}

header("Location: chat.php?amigo=$para_id");
exit;
