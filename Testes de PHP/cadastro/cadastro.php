<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Cadastro </title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" type="text/css" href="css/cadastro.css">
</head>
<body>
    <form>
        <fieldset>
            <legend>Cadastro</legend>

            <label>Nome</label>
            <input name="nome" type="text" maxlength="15" placeholder="Digite seu nome">
            <br>
            <label>Email</label>
            <input name="email" type="email" required maxlength="50" placeholder="Digite seu email">
            <br>
            <label>Senha</label>
            <div class="password-container">
                <input id="senha" name="senha" type="password" maxlength="8" placeholder="Digite sua senha">
                <span class="material-symbols-outlined" onclick="mostrarSenha('senha', this)">visibility</span>
            </div>
            <BR>
            <label>Confirme sua Senha</label>
            <div class="password-container">
                <input id="confirma-senha" name="confirma-senha" type="password" maxlength="8" placeholder="Digite novamente">
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

    <?php
    //Dados pra conecta o banco de dados
    $servername = "localhost"; //acessa o banco no xampp
    $username = "root"; //User padrão no xampp
    $password = ""; //senha padrao no xampp (em branco)
    $dbname = "bd_cypher"; //nome do banco
    
    //Criando a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    //Verificando se a conexão ta de boa
    if ($conn->connect_error){
        die("Falha na conexão:".$conn->connect_error);
    }

    //receber os dados
    $nome_user = $_POST['nome'];
    $email_user = $_POST["email"];
    $senha_user = $_POST["senha"];

    //Criando o comando SQL para inserir os dados na tabela usuario
    $sql = "INSERT INTO usuario (nome_user, email_user, senha_user) VALUES ('$nome_user', '$email_user', '$senha_user')";
    
    //Executando o comando SQL
    if ($conn->query($sql) === TRUE){
        echo "Cadastro realizado com sucesso!";
    } else{
        echo "Erro: ".$conn->error;
    }

    //Fecha a conexão
    $conn->close();
    ?>

</body>
</html>