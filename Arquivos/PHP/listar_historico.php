<?php
session_start();
require_once 'conexao.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['usuario'])) {
    echo json_encode([]);
    exit;
}

$usuario = $_SESSION['usuario'];
$sql = "SELECT nome_jogo, hora_entrada FROM historico_jogos WHERE usuario = ? ORDER BY hora_entrada DESC LIMIT 20";
$stmt = $pdo->prepare($sql);
$stmt->execute([$usuario]);
$historico = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($historico);
?>