<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;

if ($fk_cargo != 1 && $fk_cargo != 4) {
    echo "Acesso negado";
    exit;
}
// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe os dados do formulário
    $pk_usuario = $_POST['pk_usuario'];
    $nome_user = trim($_POST['nome_user']);
    $email_user = trim($_POST['email_user']);
    $senha_user = trim($_POST['senha_user']);

    // Validação simples
    if (empty($nome_user) || empty($email_user)) {
        echo "<script>alert('Nome e E-mail são obrigatórios!'); history.back();</script>";
        exit();
    }

    // Se o campo senha estiver preenchido, atualiza a senha também
    if (!empty($senha_user)) {
        $senha_hash = password_hash($senha_user, PASSWORD_DEFAULT);

        $sql = "UPDATE usuario SET 
                    nome_user = :nome_user,
                    email_user = :email_user,
                    senha_user = :senha_user
                WHERE pk_usuario = :pk_usuario";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome_user', $nome_user);
        $stmt->bindParam(':email_user', $email_user);
        $stmt->bindParam(':senha_user', $senha_hash);
        $stmt->bindParam(':pk_usuario', $pk_usuario, PDO::PARAM_INT);
    } else {
        // Se a senha estiver em branco, não atualiza a senha
        $sql = "UPDATE usuario SET 
                    nome_user = :nome_user,
                    email_user = :email_user
                WHERE pk_usuario = :pk_usuario";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome_user', $nome_user);
        $stmt->bindParam(':email_user', $email_user);
        $stmt->bindParam(':pk_usuario', $pk_usuario, PDO::PARAM_INT);
    }

    // Executa a atualização
    if ($stmt->execute()) {
        echo "<script>alert('Usuário alterado com sucesso!'); window.location.href='alterar_usuario.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar o usuário!'); history.back();</script>";
    }

} else {
    echo "<script>alert('Requisição inválida!'); window.location.href='alterar_usuario.php';</script>";
}
?>