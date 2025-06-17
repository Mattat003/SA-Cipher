<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;

if ($fk_cargo != 1 && $fk_cargo != 4) {
    echo "Acesso negado";
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pk_funcionario = $_POST['pk_funcionario'];
    $nome_func = trim($_POST['nome_func']);
    $email_func = trim($_POST['email_func']);
    $senha_func = trim($_POST['senha_func']);
    $fk_cargo = trim($_POST['fk_cargo']);

    if (empty($nome_func) || empty($email_func)) {
        echo "<script>alert('Nome e Email são obrigatórios!'); history.back();</script>";
        exit();
    }

    if (!empty($senha_func)) {
        $senha_hash = password_hash($senha_func, PASSWORD_DEFAULT);

        $sql = "UPDATE funcionario SET 
                    nome_func = :nome_func,
                    email_func = :email_func,
                    senha_func = :senha_func,
                    fk_cargo = :fk_cargo
                WHERE pk_funcionario = :pk_funcionario";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome_func', $nome_func);
        $stmt->bindParam(':email_func', $email_func);
        $stmt->bindParam(':senha_func', $senha_hash);
        $stmt->bindParam(':fk_cargo', $fk_cargo);
        $stmt->bindParam(':pk_funcionario', $pk_funcionario, PDO::PARAM_INT);
    } else {
        $sql = "UPDATE funcionario SET 
                    nome_func = :nome_func,
                    email_func = :email_func,
                    fk_cargo = :fk_cargo
                WHERE pk_funcionario = :pk_funcionario";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome_func', $nome_func);
        $stmt->bindParam(':email_func', $email_func);
        $stmt->bindParam(':fk_cargo', $fk_cargo);
        $stmt->bindParam(':pk_funcionario', $pk_funcionario, PDO::PARAM_INT);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Funcionário alterado com sucesso!'); window.location.href='alterar_funcionario.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar o funcionário!'); history.back();</script>";
    }

} else {
    echo "<script>alert('Requisição inválida!'); window.location.href='alterar_funcionario.php';</script>";
}
?>