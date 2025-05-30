<?php
session_start();
require 'conexao.php';

header('Content-Type: application/json');

if (!isset($_SESSION['pk_usuario'])) {
    http_response_code(401);
    echo json_encode([]);
    exit;
}

$usuario_id = $_SESSION['pk_usuario'];
$amigo_id = filter_input(INPUT_GET, 'amigo_id', FILTER_VALIDATE_INT);

if (!$amigo_id || $amigo_id <= 0) {
    echo json_encode(['error' => 'ID de amigo inválido']);
    exit;
}

try {
    // Verifica se são amigos
    $stmt = $pdo->prepare("SELECT 1 FROM amigos WHERE usuario_id = ? AND amigo_id = ?");
    $stmt->execute([$usuario_id, $amigo_id]);
    
    if (!$stmt->fetch()) {
        echo json_encode(['error' => 'Vocês não são amigos']);
        exit;
    }

    // Busca mensagens (formato simplificado para o frontend)
    $stmt = $pdo->prepare("
        SELECT 
            id,
            de_id,
            para_id,
            mensagem,
            DATE_FORMAT(data_envio, '%H:%i') as hora
        FROM mensagens 
        WHERE (de_id = ? AND para_id = ?) OR (de_id = ? AND para_id = ?)
        ORDER BY data_envio ASC
    ");
    $stmt->execute([$usuario_id, $amigo_id, $amigo_id, $usuario_id]);
    
    $mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'messages' => $mensagens
    ]);
    
} catch (PDOException $e) {
    error_log("Erro no mensagens.php: " . $e->getMessage());
    echo json_encode(['error' => 'Erro ao carregar mensagens']);
}