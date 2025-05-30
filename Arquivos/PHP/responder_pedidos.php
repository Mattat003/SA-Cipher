<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['pk_usuario'])) {
    header('Location: login.php');
    exit();
}

$usuario_id = $_SESSION['pk_usuario'];
$pedido_id = isset($_POST['pedido_id']) ? (int)$_POST['pedido_id'] : 0;
$resposta = $_POST['resposta'] ?? '';

if ($pedido_id <= 0 || !in_array($resposta, ['aceitar','recusar'])) {
    exit('Dados inválidos');
}

// Verifica se o pedido existe e é para o usuário logado
$stmt = $conn->prepare("SELECT de_id, para_id FROM pedidos_amizade WHERE id = ? AND para_id = ? AND status = 'pendente'");
$stmt->bind_param("ii", $pedido_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    exit('Pedido não encontrado ou já respondido');
}

$pedido = $result->fetch_assoc();
$stmt->close();

if ($resposta === 'aceitar') {
    // Atualiza status do pedido
    $stmt = $conn->prepare("UPDATE pedidos_amizade SET status = 'aceito' WHERE id = ?");
    $stmt->bind_param("i", $pedido_id);
    if (!$stmt->execute()) {
        $stmt->close();
        exit('Erro ao aceitar pedido.');
    }
    $stmt->close();

    // Insere amizade (bidirecional)
    $stmt = $conn->prepare("INSERT INTO amigos (usuario_id, amigo_id) VALUES (?, ?), (?, ?)");
    $stmt->bind_param("iiii", $pedido['de_id'], $pedido['para_id'], $pedido['para_id'], $pedido['de_id']);
    if (!$stmt->execute()) {
        $stmt->close();
        exit('Erro ao criar amizade.');
    }
    $stmt->close();

    echo "Pedido aceito com sucesso!";
} else {
    // Atualiza status para recusado
    $stmt = $conn->prepare("UPDATE pedidos_amizade SET status = 'recusado' WHERE id = ?");
    $stmt->bind_param("i", $pedido_id);
    if (!$stmt->execute()) {
        $stmt->close();
        exit('Erro ao recusar pedido.');
    }
    $stmt->close();

    echo "Pedido recusado com sucesso!";
}

echo "<br><a href='pedidos_recebidos.php'>Voltar</a>";
?>
