<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;

if ($fk_cargo != 1 && $fk_cargo != 4) {
    echo "Acesso negado";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pk_adm = $_POST['pk_adm'];
    $nome_adm = trim($_POST['nome_adm']);
    $email_adm = trim($_POST['email_adm']);
    $senha_user = trim($_POST['senha_user']);
    $fk_cargo = trim($_POST['fk_cargo']);

    if (empty($nome_adm) || empty($email_adm)) {
        echo "<script>alert('Nome e Email são obrigatórios!'); history.back();</script>";
        exit();
    }

    if (!empty($senha_user)) {
        // ATENÇÃO: sua tabela tem senha_user VARCHAR(16) — aumente para 255 se usar password_hash!
        $senha_hash = password_hash($senha_user, PASSWORD_DEFAULT);

        $sql = "UPDATE adm SET 
                    nome_adm = :nome_adm,
                    email_adm = :email_adm,
                    senha_user = :senha_user,
                    fk_cargo = :fk_cargo
                WHERE pk_adm = :pk_adm";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome_adm', $nome_adm);
        $stmt->bindParam(':email_adm', $email_adm);
        $stmt->bindParam(':senha_user', $senha_hash);
        $stmt->bindParam(':fk_cargo', $fk_cargo);
        $stmt->bindParam(':pk_adm', $pk_adm, PDO::PARAM_INT);
    } else {
        $sql = "UPDATE adm SET 
                    nome_adm = :nome_adm,
                    email_adm = :email_adm,
                    fk_cargo = :fk_cargo
                WHERE pk_adm = :pk_adm";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome_adm', $nome_adm);
        $stmt->bindParam(':email_adm', $email_adm);
        $stmt->bindParam(':fk_cargo', $fk_cargo);
        $stmt->bindParam(':pk_adm', $pk_adm, PDO::PARAM_INT);
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