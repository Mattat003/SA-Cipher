<?php
session_start();
require_once 'conexao.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(['erro' => 'Usuário não autenticado']);
    exit;
}

$usuario = $_SESSION['usuario'];

$stmt = $pdo->prepare("SELECT pk_usuario, foto_perfil FROM usuario WHERE nome_user = ?");
$stmt->execute([$usuario]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && !empty($user['foto_perfil']) && file_exists('../' . $user['foto_perfil'])) {
    // Remove arquivo físico
    unlink('../' . $user['foto_perfil']);
}

$stmt = $pdo->prepare("UPDATE usuario SET foto_perfil = NULL WHERE pk_usuario = ?");
$stmt->execute([$user['pk_usuario']]);

echo json_encode(['ok' => true]);
?>