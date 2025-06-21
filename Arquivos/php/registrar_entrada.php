<?php
session_start();
require_once 'conexao.php';
date_default_timezone_set('America/Sao_Paulo');

if (!isset($_SESSION['usuario']) || !isset($_POST['nome_jogo'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Dados incompletos']);
    exit;
}

$usuario = $_SESSION['usuario'];
$nome_jogo = $_POST['nome_jogo'];
$hora_entrada = date('Y-m-d H:i:s');

$stmt = $pdo->prepare("INSERT INTO historico_jogos (usuario, nome_jogo, hora_entrada) VALUES (?, ?, ?)");
$ok = $stmt->execute([$usuario, $nome_jogo, $hora_entrada]);

echo json_encode(['sucesso' => $ok]);
?>