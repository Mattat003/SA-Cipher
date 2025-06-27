<?php
session_start();
require_once 'conexao.php';

$fk_cargo_sessao = $_SESSION['fk_cargo'] ?? null;

if ($fk_cargo_sessao != 1 && $fk_cargo_sessao != 4) {
    echo "Acesso negado";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pk_adm = $_POST['pk_adm'];
    $nome_adm = trim($_POST['nome_adm']);
    $email_adm = trim($_POST['email_adm']);
    $senha_user = trim($_POST['senha_user']);
    $fk_cargo = trim($_POST['fk_cargo']);

    // Campos obrigatórios
    if (empty($nome_adm) || empty($email_adm) || empty($fk_cargo)) {
        echo "<script>alert('Nome, E-mail e Cargo são obrigatórios!'); history.back();</script>";
        exit();
    }

    // Verifica duplicidade de e-mail (para outro usuário)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM adm WHERE email_adm = :email_adm AND pk_adm != :pk_adm");
    $stmt->bindParam(':email_adm', $email_adm);
    $stmt->bindParam(':pk_adm', $pk_adm, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->fetchColumn() > 0) {
        echo "<script>alert('E-mail já cadastrado para outro funcionário!'); history.back();</script>";
        exit();
    }

    if (!empty($senha_user)) {
        if (strlen($senha_user) < 8) {
            echo "<script>alert('A senha deve ter no mínimo 8 caracteres.'); history.back();</script>";
            exit();
        }
        // ATENÇÃO: senha_user na tabela deve ser VARCHAR(255)
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