<?php
session_start();
require 'conexao.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica se o usuário está logado
if (!isset($_SESSION['pk_usuario'])) {
    die("Acesso negado");
}

$usuario_id = $_SESSION['pk_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amigo_email = $_POST['email'] ?? '';

    if ($amigo_email) {
        try {
            // Buscar o usuário pelo email
            $stmt = $pdo->prepare("SELECT pk_usuario FROM usuario WHERE email_user = ?");
            $stmt->execute([$amigo_email]);
            $amigo = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($amigo) {
                $amigo_id = $amigo['pk_usuario'];

                if ($amigo_id == $usuario_id) {
                    echo "Você não pode enviar pedido para si mesmo.";
                    exit;
                }

                // Verificar se já são amigos
                $stmt = $pdo->prepare("SELECT id FROM amigos WHERE usuario_id = ? AND amigo_id = ?");
                $stmt->execute([$usuario_id, $amigo_id]);
                if ($stmt->fetch()) {
                    echo "Você já é amigo dessa pessoa.";
                    exit;
                }

                // Verificar se já existe pedido pendente
                $stmt = $pdo->prepare("SELECT id FROM pedidos_amizade WHERE 
                    ((de_id = ? AND para_id = ?) OR (de_id = ? AND para_id = ?)) 
                    AND status = 'pendente'");
                $stmt->execute([$usuario_id, $amigo_id, $amigo_id, $usuario_id]);
                if ($stmt->fetch()) {
                    echo "Já existe um pedido de amizade pendente entre vocês.";
                    exit;
                }

                // Inserir novo pedido de amizade
                $stmt = $pdo->prepare("INSERT INTO pedidos_amizade (de_id, para_id, status) VALUES (?, ?, 'pendente')");
                if ($stmt->execute([$usuario_id, $amigo_id])) {
                    echo "Pedido de amizade enviado com sucesso!";
                } else {
                    echo "Erro ao enviar pedido de amizade.";
                }
            } else {
                echo "Usuário não encontrado.";
            }
        } catch (PDOException $e) {
            echo "Erro ao processar o pedido: " . $e->getMessage();
        }
    } else {
        echo "Informe o email do amigo.";
    }

    exit;
}
?>
