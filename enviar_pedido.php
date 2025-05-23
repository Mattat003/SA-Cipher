<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['pk_usuario']) || !isset($_POST['para_id'])) {
    header("Location: amizades.php");
    exit;
}

$de_id = $_SESSION['pk_usuario'];
$para_id = intval($_POST['para_id']);

if ($de_id === $para_id) {
    exit("Você não pode adicionar a si mesmo.");
}

// Verifica se já existe pedido
$stmt = $pdo->prepare("SELECT * FROM pedidos_amizade WHERE de_id = :de AND para_id = :para");
$stmt->execute(['de' => $de_id, 'para' => $para_id]);

if ($stmt->rowCount() === 0) {
    $stmt = $pdo->prepare("INSERT INTO pedidos_amizade (de_id, para_id) VALUES (:de, :para)");
    $stmt->execute(['de' => $de_id, 'para' => $para_id]);
}

header("Location: amizades.php");
exit;
