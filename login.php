    <?php
    session_start();
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once 'conexao.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Higienização das entradas (evita ataques de XSS)
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $senha = $_POST["senha"];

        // Verificar se o email é válido
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro = "Formato de email inválido!";
        } else {
            // Consultar no banco de dados
            $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email_user = :email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verifica a senha
                if (password_verify($senha, $usuario['senha_user'])) {

                    // Salvar informações do usuário na sessão, incluindo a foto de perfil
                    $_SESSION['pk_usuario'] = $usuario['pk_usuario'];
                    $_SESSION['usuario'] = $usuario['nome_user'];
                    $_SESSION['foto_perfil'] = $usuario['foto_perfil']; // Aqui armazenamos a foto de perfil

                    // Se a senha for temporária, direciona o usuário para a troca de senha
                    if ($usuario['senha_temporaria']) {
                        header("Location: alterar_senha.php");
                        exit();
                    } else {
                        // Se a senha estiver ok, redireciona para a página principal
                        session_write_close(); // Garante que a sessão seja salva
                        header("Location: index.php");
                        exit();
                    }
                } else {
                    // Senha incorreta
                    $erro = "Email ou senha inválidos!";
                }
            } else {
                // Email não encontrado
                $erro = "Email ou senha inválidos!";
            }
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8"/>
        <title>Login</title>
        <style>
/* Reset básico */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Corpo com fundo em degradê escuro */
body {
    background: linear-gradient(135deg, #0f0c29, #302b63);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
}

/* Container do login */
.login-container {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 40px;
    border-radius: 15px;
    text-align: center;
    color: white;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.36);
}

/* Título */
h2 {
    font-size: 28px;
    margin-bottom: 25px;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to right, #ffffff, #e0c3fc);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    font-weight: 600;
}

/* Rótulos */
label {
    font-weight: 500;
    display: block;
    text-align: left;
    margin-bottom: 8px;
    color: #e0c3fc;
    font-size: 14px;
}

/* Caixa de entrada */
input {
    width: 100%;
    padding: 12px 15px;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    margin-bottom: 20px;
    background: rgba(255, 255, 255, 0.05);
    color: white;
    outline: none;
    font-size: 15px;
    transition: all 0.3s ease;
}

input:focus {
    border-color: #9a66df;
    box-shadow: 0 0 0 3px rgba(154, 102, 223, 0.2);
}

/* Placeholder */
input::placeholder {
    color: #cbbde2;
    opacity: 0.7;
}

/* Botão */
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
    margin-top: 10px;
}

button:hover {
    background: linear-gradient(to right, #6a0dad, #5b009d);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(134, 57, 223, 0.4);
}

/* Link de voltar */
p a {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #cbbde2;
    font-size: 14px;
    text-decoration: none;
}

p a:hover {
    color: #e0c3fc;
    text-decoration: underline;
}

/* Mensagem de erro */
p[style='color: #ffcccc;'] {
    color: #ffcccc;
    font-size: 14px;
    margin-top: -10px;
}

            </style>
    </head>
    <body>
    <div class="login-container">
        <h2>Login</h2>

        <!-- Exibe mensagem de erro se houver -->
        <?php if (isset($erro)) { echo "<p style='color: #ffcccc;'>$erro</p>"; } ?>

        <form action="login.php" method="post">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required placeholder="Digite seu email">

            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required placeholder="Digite sua senha">

            <button type="submit">Entrar</button>
        </form>

        <p><a href="esqueceuSenha.php">Esqueci minha senha</a></p>
        <p><a href="cadastro.php">Criar conta</a></p>
    </div>
    </body>
    </html>

    <?php
    session_start();
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once 'conexao.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Higienização das entradas
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        $senha = $_POST["senha"];

        // Verificar se o email é válido
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro = "Formato de email inválido!";
        } else {
            // Primeiro verifica na tabela de administradores
            $stmt = $pdo->prepare("SELECT a.*, c.nivel_cargo FROM adm a JOIN cargo c ON a.fk_cargo = c.pk_cargo WHERE email_adm = :email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verifica a senha
                if (password_verify($senha, $admin['senha_user'])) {
                    // Salvar informações do admin na sessão
                    $_SESSION['pk_adm'] = $admin['pk_adm'];
                    $_SESSION['usuario'] = $admin['nome_adm'];
                    $_SESSION['is_admin'] = true;
                    $_SESSION['nivel_cargo'] = $admin['nivel_cargo'];

                    // Redireciona para o painel de admin
                    header("Location: admin.php");
                    exit();
                } else {
                    // Senha incorreta
                    $erro = "Email ou senha inválidos!";
                }
            } else {
                // Se não encontrou na tabela de admins, verifica na tabela de usuários comuns
                $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email_user = :email");
                $stmt->bindParam(":email", $email);
                $stmt->execute();

                if ($stmt->rowCount() == 1) {
                    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Verifica a senha
                    if (password_verify($senha, $usuario['senha_user'])) {
                        // Salvar informações do usuário na sessão
                        $_SESSION['pk_usuario'] = $usuario['pk_usuario'];
                        $_SESSION['usuario'] = $usuario['nome_user'];

                        // Se a senha for temporária, direciona para troca de senha
                        if ($usuario['senha_temporaria']) {
                            header("Location: alterar_senha.php");
                            exit();
                        } else {
                            // Redireciona para a página principal
                            header("Location: index.php");
                            exit();
                        }
                    } else {
                        // Senha incorreta
                        $erro = "Email ou senha inválidos!";
                    }
                } else {
                    // Email não encontrado
                    $erro = "Email ou senha inválidos!";
                }
            }
        }
    }
?>
