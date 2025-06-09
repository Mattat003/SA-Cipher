<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'conexao.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(['erro' => 'Usuário não autenticado']);
    exit;
}

$usuario = $_SESSION['usuario'];
$stmt = $pdo->prepare("SELECT pk_usuario FROM usuario WHERE nome_user = ?");
$stmt->execute([$usuario]);
$pk_usuario = $stmt->fetchColumn();

if (!$pk_usuario) {
    http_response_code(400);
    echo json_encode(['erro' => 'Usuário não encontrado']);
    exit;
}

if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['erro' => 'Nenhum arquivo enviado ou erro no upload']);
    exit;
}

$foto = $_FILES['foto'];
$ext = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
$permitidas = ['jpg', 'jpeg', 'png', 'gif'];
if (!in_array($ext, $permitidas)) {
    http_response_code(400);
    echo json_encode(['erro' => 'Formato inválido']);
    exit;
}

if (!is_dir('../uploads')) {
    mkdir('../uploads', 0755, true);
}

$nome_arquivo = 'perfil_' . $pk_usuario . '_' . uniqid() . '.' . $ext;
$caminho = '../uploads/' . $nome_arquivo;

if (move_uploaded_file($foto['tmp_name'], $caminho)) {
    $caminho_relativo = 'uploads/' . $nome_arquivo;
    $stmt = $pdo->prepare("UPDATE usuario SET foto_perfil = ? WHERE pk_usuario = ?");
    $stmt->execute([$caminho_relativo, $pk_usuario]);
    echo json_encode(['ok' => true, 'caminho' => $caminho_relativo]);
} else {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao salvar arquivo']);
}
?>