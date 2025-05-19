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
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cadastro</title>
        <link rel = "stylesheet" href = "cadastro.css">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
<style>
    .password-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .password-container input {
        flex: 1;
        padding-right: 35px; /* espaço para o ícone */
    }

    .material-symbols-outlined {
        position: absolute;
        right: 10px;
        cursor: pointer;
        user-select: none;
    }
</style>
    </head>
    <body>
    <form action="cadastro.php" method="POST">
        <fieldset>
            <legend>Cadastro</legend>

            <label>Nome</label>
            <input name="nome" type="text" maxlength="40" placeholder="Digite seu nome" required>

            <label>Email</label>
            <input name="email" type="email" maxlength="50" placeholder="Digite seu email" required>

            <label>Senha</label>
            <div class="password-container">
            <input id="senha" name="senha" type="password" maxlength="255" placeholder="Digite sua senha (mínimo 8 caracteres)" required>
            <span class="material-symbols-outlined" onclick="mostrarSenha('senha', this)">visibility</span>
            </div>

            <label>Confirme sua Senha</label>
            <div class="password-container">
            <input id="confirma-senha" name="confirma-senha" type="password" maxlength="255" placeholder="Digite novamente" required>
            <span class="material-symbols-outlined" onclick="mostrarSenha('confirma-senha', this)">visibility</span>
            </div>

            </fieldset>
        <br>
        <input type="submit" value="Cadastrar-se">
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
