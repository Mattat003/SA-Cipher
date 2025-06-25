<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $locacao_id = $_POST['locacao_id'] ?? null;
    $nova_data_expiracao = $_POST['nova_data_expiracao'] ?? null;

    if ($locacao_id && $nova_data_expiracao) {
        $stmt = $pdo->prepare("UPDATE locacoes_pendentes SET data_expiracao = :data_expiracao WHERE id = :id");
        $stmt->bindParam(':data_expiracao', $nova_data_expiracao);
        $stmt->bindParam(':id', $locacao_id);
        $stmt->execute();
    }
}

header('Location: liberar_locacoes.php');
exit;
