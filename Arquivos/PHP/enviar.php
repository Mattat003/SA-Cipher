<?php
session_start();
require 'conexao.php';

// Define que a saída será JSON
header('Content-Type: application/json; charset=utf-8');

// Verifica login
if (!isset($_SESSION['pk_usuario'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Usuário não autenticado']);
    exit;
}

$usuario_id = $_SESSION['pk_usuario'];
$para_id = isset($_POST['para_id']) ? (int)$_POST['para_id'] : 0;
$mensagem = trim($_POST['mensagem'] ?? '');

if ($para_id <= 0 || $mensagem === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Dados inválidos']);
    exit;
}

// Verifica se são amigos — usando PDO
$stmt = $pdo->prepare("SELECT id FROM amigos WHERE usuario_id = :usuario_id AND amigo_id = :amigo_id");
$stmt->execute(['usuario_id' => $usuario_id, 'amigo_id' => $para_id]);
if (!$stmt->fetch()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Você não pode enviar mensagem para esse usuário']);
    exit;
}

// Insere mensagem no banco
$stmt = $pdo->prepare("INSERT INTO mensagens (de_id, para_id, mensagem) VALUES (:de_id, :para_id, :mensagem)");
$executou = $stmt->execute([
    'de_id' => $usuario_id,
    'para_id' => $para_id,
    'mensagem' => $mensagem
]);

if ($executou) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erro ao salvar mensagem']);
}

exit;
?>
