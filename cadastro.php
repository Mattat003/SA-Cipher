<?php
session_start();
require_once 'conexao.php'; // conecta ao banco

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $usuario = trim($_POST["usuario"]);
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];
    $confirma = $_POST["confirma-senha"];

    // Validação simples
    if (empty($nome) || empty($usuario) || empty($email) || empty($senha) || empty($confirma)) {
        echo "<script>alert('Preencha todos os campos!'); window.history.back();</script>";
        exit();
    }

    if ($senha !== $confirma) {
        echo "<script>alert('As senhas não coincidem!'); window.history.back();</script>";
        exit();
    }

    if (strlen($senha) < 8) {
        echo "<script>alert('A senha deve ter no mínimo 8 caracteres!'); window.history.back();</script>";
        exit();
    }

    // Verifica se o email já existe
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email_user = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "<script>alert('Este email já está cadastrado!'); window.history.back();</script>";
        exit();
    }

    // Verifica se o usuário já existe
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE usuario_user = :usuario");
    $stmt->bindParam(":usuario", $usuario);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "<script>alert('Este nome de usuário já está em uso!'); window.history.back();</script>";
        exit();
    }

    // Hash da senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Inserção no banco
    $sql = "INSERT INTO usuario (nome_user, usuario_user, email_user, senha_user, senha_temporaria) 
            VALUES (:nome, :usuario, :email, :senha, FALSE)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":nome", $nome);
    $stmt->bindParam(":usuario", $usuario);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":senha", $senha_hash);

    if ($stmt->execute()) {
        echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar!'); window.history.back();</script>";
    }
}
?>





<form action="cadastro.php" method="POST">

    <fieldset>
        <legend>Cadastro</legend>

        <label>Nome</label>
        <input name="nome" type="text" maxlength="15" placeholder="Digite seu nome">
        <br>
        <label>Usuário</label>
        <input name="usuario" type="text" maxlength="15" placeholder="Digite o nome de usuário">
        <br>
        <label>Email</label>
        <input name="email" type="email" required maxlength="50" placeholder="Digite seu email">
        <br>
        <label>Senha</label>
        <div class="password-container">
            <input id="senha" name="senha" type="password" maxlength="20" placeholder="Digite sua senha (mínimo 8 caracteres)">
            <span class="material-symbols-outlined" onclick="mostrarSenha('senha', this)">visibility</span>
        </div>
        <br>
        <label>Confirme sua Senha</label>
        <div class="password-container">
            <input id="confirma-senha" name="confirma-senha" type="password" maxlength="20" placeholder="Digite novamente">
            <span class="material-symbols-outlined" onclick="mostrarSenha('confirma-senha', this)">visibility</span>
        </div>
        
    </fieldset>
    <br>
    <input type="submit" value="Cadastrar-se">
</form>
