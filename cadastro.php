<?php
session_start();
require_once 'conexao.php'; // conexão PDO

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];
    $confirma = $_POST["confirma-senha"];

    // Validações
    if (empty($nome) || empty($email) || empty($senha) || empty($confirma)) {
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

    // Verifica se o email já está cadastrado
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email_user = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "<script>alert('Este email já está cadastrado!'); window.history.back();</script>";
        exit();
    }

    // Criptografa a senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Insere no banco
    $sql = "INSERT INTO usuario (nome_user, email_user, senha_user, data_criacao, senha_temporaria) 
            VALUES (:nome, :email, :senha, NOW(), FALSE)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":nome", $nome);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":senha", $senha_hash);

    if ($stmt->execute()) {
        echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar!'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>
<body style="background: linear-gradient(135deg, #0f0c29, #302b63); display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px;">

    <form action="cadastro.php" method="POST" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); padding: 40px; border-radius: 15px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.36); width: 100%; max-width: 450px; color: white;">
        <fieldset style="border: none; display: flex; flex-direction: column; gap: 15px;">
            <legend style="font-size: 28px; font-weight: 600; text-align: center; margin-bottom: 25px; font-family: 'Poppins', sans-serif; background: linear-gradient(to right, #ffffff, #e0c3fc); -webkit-background-clip: text; background-clip: text; color: transparent;">
                Cadastro
            </legend>

            <label style="font-weight: 500; color: #e0c3fc; margin-top: 5px; font-size: 14px;">Nome</label>
            <input name="nome" type="text" maxlength="40" placeholder="Digite seu nome" required style="width: 100%; padding: 12px 15px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.1); margin-top: 5px; background: rgba(255, 255, 255, 0.05); color: white; outline: none; font-size: 15px; transition: all 0.3s ease;" />

            <label style="font-weight: 500; color: #e0c3fc; margin-top: 5px; font-size: 14px;">Email</label>
            <input name="email" type="email" maxlength="50" placeholder="Digite seu email" required style="width: 100%; padding: 12px 15px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.1); margin-top: 5px; background: rgba(255, 255, 255, 0.05); color: white; outline: none; font-size: 15px; transition: all 0.3s ease;" />

            <label style="font-weight: 500; color: #e0c3fc; margin-top: 5px; font-size: 14px;">Senha</label>
            <div style="position: relative; display: flex; align-items: center;">
                <input id="senha" name="senha" type="password" maxlength="255" placeholder="Digite sua senha (mínimo 8 caracteres)" required style="flex: 1; padding: 12px 15px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.1); margin-top: 5px; background: rgba(255, 255, 255, 0.05); color: white; outline: none; font-size: 15px; transition: all 0.3s ease; padding-right: 35px;" />
                <span class="material-symbols-outlined" onclick="mostrarSenha('senha', this)" style="position: absolute; right: 10px; cursor: pointer; user-select: none; color: #cbbde2; font-size: 20px; transition: color 0.3s ease;">visibility</span>
            </div>

            <label style="font-weight: 500; color: #e0c3fc; margin-top: 5px; font-size: 14px;">Confirme sua Senha</label>
            <div style="position: relative; display: flex; align-items: center;">
                <input id="confirma-senha" name="confirma-senha" type="password" maxlength="255" placeholder="Digite novamente" required style="flex: 1; padding: 12px 15px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.1); margin-top: 5px; background: rgba(255, 255, 255, 0.05); color: white; outline: none; font-size: 15px; transition: all 0.3s ease; padding-right: 35px;" />
                <span class="material-symbols-outlined" onclick="mostrarSenha('confirma-senha', this)" style="position: absolute; right: 10px; cursor: pointer; user-select: none; color: #cbbde2; font-size: 20px; transition: color 0.3s ease;">visibility</span>
            </div>

            <input type="submit" value="Cadastrar-se" style="background: linear-gradient(to right, #8639df, #6a0dad); color: white; padding: 15px; margin-top: 20px; cursor: pointer; font-size: 16px; font-weight: 600; border-radius: 8px; border: none; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(134, 57, 223, 0.3);" />
        </fieldset>
    </form>

    <script>
        function mostrarSenha(id, el) {
            const input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
                el.innerText = "visibility_off";
            } else {
                input.type = "password";
                el.innerText = "visibility";
            }
        }
    </script>

</body>
</html>
