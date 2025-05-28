<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['pk_usuario'])) {
    die("Acesso negado");
}

$usuario_id = $_SESSION['pk_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amigo_email = $_POST['email'] ?? '';

    if ($amigo_email) {
        // Buscar o usuário pelo email
        $stmt = $pdo->prepare("SELECT pk_usuario FROM usuario WHERE email_user = :email");
        $stmt->execute(['email' => $amigo_email]);
        $amigo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($amigo) {
            $amigo_id = $amigo['pk_usuario'];

            if ($amigo_id == $usuario_id) {
                echo "Você não pode enviar pedido para si mesmo.";
                exit;
            }

            // Verificar se já são amigos
            $stmt = $pdo->prepare("SELECT id FROM amigos WHERE usuario_id = :uid AND amigo_id = :aid");
            $stmt->execute(['uid' => $usuario_id, 'aid' => $amigo_id]);

            if ($stmt->rowCount() > 0) {
                echo "Você já é amigo dessa pessoa.";
                exit;
            }

            // Verificar se já existe pedido pendente
            $stmt = $pdo->prepare("
                SELECT id FROM pedidos_amizade 
                WHERE ((de_id = :uid AND para_id = :aid) OR (de_id = :aid AND para_id = :uid)) 
                AND status = 'pendente'
            ");
            $stmt->execute(['uid' => $usuario_id, 'aid' => $amigo_id]);

            if ($stmt->rowCount() > 0) {
                echo "Já existe um pedido de amizade pendente entre vocês.";
                exit;
            }

            // Inserir pedido
            $stmt = $pdo->prepare("INSERT INTO pedidos_amizade (de_id, para_id, status) VALUES (:uid, :aid, 'pendente')");
            if ($stmt->execute(['uid' => $usuario_id, 'aid' => $amigo_id])) {
                echo "Pedido de amizade enviado com sucesso!";
            } else {
                echo "Erro ao enviar pedido de amizade.";
            }

        } else {
            echo "Usuário não encontrado.";
        }
    } else {
        echo "Informe o email do amigo.";
    }
    exit;
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Enviar pedido de amizade</title>
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  body {
    background: linear-gradient(135deg, #0f0c29, #302b63);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
    color: white;
  }

  .container {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 15px;
    padding: 40px;
    max-width: 400px;
    width: 100%;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.36);
    text-align: center;
  }

  h2 {
    font-size: 26px;
    margin-bottom: 25px;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to right, #ffffff, #e0c3fc);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    font-weight: 600;
  }

  input[type="email"] {
    width: 100%;
    padding: 12px 15px;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    margin-bottom: 20px;
    background: rgba(255, 255, 255, 0.05);
    color: white;
    font-size: 15px;
    outline: none;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
  }

  input[type="email"]::placeholder {
    color: #cbbde2;
    opacity: 0.7;
  }

  input[type="email"]:focus {
    border-color: #9a66df;
    box-shadow: 0 0 0 3px rgba(154, 102, 223, 0.2);
  }

  button {
    background: linear-gradient(to right, #8639df, #6a0dad);
    color: white;
    padding: 14px;
    border: none;
    width: 100%;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(134, 57, 223, 0.3);
  }

  button:hover {
    background: linear-gradient(to right, #6a0dad, #5b009d);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(134, 57, 223, 0.4);
  }

  p {
    margin-top: 20px;
    font-size: 14px;
  }

  p a {
    color: #cbbde2;
    text-decoration: none;
  }

  p a:hover {
    color: #e0c3fc;
    text-decoration: underline;
  }
</style>
</head>
<body>
<div class="container">
    <h2>Enviar pedido de amizade</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Email do amigo" required>
        <button type="submit">Enviar pedido</button>
    </form>
    <p><a href="chat.php">Voltar ao chat</a></p>
</div>
</body>
</html>
