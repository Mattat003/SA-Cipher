<?php
session_start(); //
if (!isset($_SESSION['tipo']) || $_SESSION['fk_cargo'] != 1) { // Apenas Admin pode excluir
    header("Location: login.php"); //
    exit(); //
}
require_once 'conexao.php'; //

$mensagem = ''; //
if (isset($_GET['id']) && !empty($_GET['id'])) { // Changed to GET request
    $id_usuario = $_GET['id']; //
    $current_user_id = $_SESSION['id_usuario'] ?? null; // Ensure current_user_id is set

    if ($id_usuario == $current_user_id) { //
        $mensagem = "<p class='error-message'>Você não pode excluir seu próprio usuário!</p>"; //
    } else {
        try {
            $stmt = $pdo->prepare("DELETE FROM usuario WHERE pk_usuario = :id"); //
            $stmt->bindParam(":id", $id_usuario, PDO::PARAM_INT); //
            if ($stmt->execute()) { //
                $mensagem = "<p class='success-message'>Usuário excluído com sucesso!</p>"; //
                // No redirect here, so the message can be displayed
            } else {
                $mensagem = "<p class='error-message'>Erro ao excluir usuário.</p>"; //
            }
        } catch (PDOException $e) {
            $mensagem = "<p class='error-message'>Erro ao excluir usuário: " . $e->getMessage() . "</p>"; //
        }
    }
}

$usuarios = []; //
try {
    $stmt = $pdo->prepare("SELECT u.pk_usuario, u.nome_user AS username, u.email_user AS email_usuario, c.nome_cargo
                           FROM usuario u
                           JOIN adm a ON u.pk_usuario = a.pk_adm  -- Assuming users managed by admin are in adm table or there's a link
                           JOIN cargo c ON a.fk_cargo = c.pk_cargo
                           WHERE u.pk_usuario != :current_user_id -- Não lista o próprio usuário logado
                           ORDER BY u.nome_user ASC"); // Adjusted to nome_user from bd.sql
    $stmt->bindParam(":current_user_id", $_SESSION['id_usuario'], PDO::PARAM_INT); //
    $stmt->execute(); //
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC); //

    if (empty($usuarios) && !isset($_GET['id'])) { // Adjusted to GET request
         $mensagem = "<p class='info-message'>Não há outros usuários para excluir.</p>"; //
    }

} catch (PDOException $e) {
    $mensagem = "<p class='error-message'>Erro ao carregar lista de usuários: " . $e->getMessage() . "</p>"; //
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Usuário</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        :root {
            --primary-purple: #5E35B1; /* Deep Purple */
            --dark-purple: #311B92;    /* Darker Deep Purple */
            --light-purple: #9575CD;   /* Medium Purple for backgrounds */
            --text-color: #f0f0f0;     /* Lighter text for dark backgrounds */
            --white-color: #263238;    /* Dark gray for containers */
            --danger-red: #C62828;     /* Darker Crimson */
            --success-green: #2E7D32;  /* Darker Green */
            --info-blue: #0277BD;      /* Darker Blue */
            --shadow: 0 6px 20px rgba(0, 0, 0, 0.4); /* Stronger shadow */
            --border-radius: 10px;     /* Slightly larger radius */
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, var(--dark-purple) 0%, #1A237E 100%);
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            box-sizing: border-box;
        }

        .container {
            background-color: var(--white-color);
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            max-width: 900px;
            width: 100%;
            text-align: center;
            animation: fadeIn 1s ease-out;
            margin-bottom: 20px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            color: var(--light-purple);
            margin-bottom: 20px;
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.05);
        }

        .message {
            padding: 10px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            font-weight: 600;
            color: #fff;
        }

        .success-message {
            background-color: var(--success-green);
            border: 1px solid #1B5E20;
        }

        .error-message {
            background-color: var(--danger-red);
            border: 1px solid #B71C1C;
        }
        .info-message {
            background-color: var(--info-blue);
            border: 1px solid #01579B;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #37474F;
            box-shadow: var(--shadow);
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #455A64;
            text-align: left;
            color: var(--text-color);
        }

        th {
            background-color: var(--primary-purple);
            color: var(--text-color);
            font-weight: 600;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #424242;
        }

        tr:hover {
            background-color: #546E7A;
        }

        .action-button {
            padding: 8px 15px;
            background-color: var(--danger-red);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease