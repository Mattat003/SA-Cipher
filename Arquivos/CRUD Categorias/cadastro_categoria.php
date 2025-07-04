<?php
    session_start();
    require_once 'conexao.php';

    //VERIFICA SE O USUARIO TEM PERMISSÃO
    //supondo que o perfil 1 seja o ADM
    if($_SESSION['adm']!= 1){
        echo "<script>alert('acesso negado!');window.location.href=../PHP/adm.php</script>";
    }

    //Genero
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $nome_gen = $_POST['nome_gen'];

        $sql = "INSERT INTO genero(nome_gen) VALUES(:nome_gen)";
        $stmt = $pdo->prepare($sql);
        $stmt -> bindParam(':nome_gen', $nome_gen);

        if($stmt->execute()){
            echo "<script>alert('Gênero cadastrado com sucesso!');</script>";
        }else{
            echo "<script>alert('Erro ao cadastrar o gênero!');</script>";
        }
    }

    //Estilo
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $nome_estilo = $_POST['nome_estilo'];

        $sql = "INSERT INTO estilo(nome_estilo) VALUES(:nome_estilo)";
        $stmt = $pdo->prepare($sql);
        $stmt -> bindParam(':nome_estilo', $nome_estilo);

        if($stmt->execute()){
            echo "<script>alert('Estilo cadastrado com sucesso!');</script>";
        }else{
            echo "<script>alert('Erro ao cadastrar o estilo!');</script>";
        }
    }

    //Modo
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $nome_modo = $_POST['nome_modo'];

        $sql = "INSERT INTO modo(nome_modo) VALUES(:nome_modo)";
        $stmt = $pdo->prepare($sql);
        $stmt -> bindParam(':nome_modo', $nome_modo);

        if($stmt->execute()){
            echo "<script>alert('Produto cadastrado com sucesso!');</script>";
        }else{
            echo "<script>alert('Erro ao cadastrar produto!');</script>";
        }
    }

    //Tema
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $nome_tema = $_POST['nome_tema'];

        $sql = "INSERT INTO tema(nome_tema) VALUES(:nome_tema)";
        $stmt = $pdo->prepare($sql);
        $stmt -> bindParam(':nome_tema', $nome_tema);

        if($stmt->execute()){
            echo "<script>alert('Tema cadastrado com sucesso!');</script>";
        }else{
            echo "<script>alert('Erro ao cadastrar o tema!');</script>";
        }
    }

    //Idioma
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $nome_idioma = $_POST['nome_idioma'];

        $sql = "INSERT INTO idioma(nome_idioma) VALUES(:nome_idioma)";
        $stmt = $pdo->prepare($sql);
        $stmt -> bindParam(':nome_idioma', $nome_idioma);

        if($stmt->execute()){
            echo "<script>alert('Idioma cadastrado com sucesso!');</script>";
        }else{
            echo "<script>alert('Erro ao cadastrar o idioma!');</script>";
        }
    }

    //Plataforma
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $nome_plat = $_POST['nome_plat'];

        $sql = "INSERT INTO plataforma(nome_plat) VALUES(:nome_plat)";
        $stmt = $pdo->prepare($sql);
        $stmt -> bindParam(':nome_plat', $nome_plat);

        if($stmt->execute()){
            echo "<script>alert('Idioma cadastrado com sucesso!');</script>";
        }else{
            echo "<script>alert('Erro ao cadastrar o idioma!');</script>";
        }
    }

    $nome_adm = isset($_SESSION['adm']) ? $_SESSION['adm'] : '';
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Cadastrar Gênero</title>
        <link rel="stylesheet" href="crudCategoria.css">

    </head>
    <body>
        <header>
            <div class="logo">
                <h1>CIPHER</h1>
                <img src="../img/capybara.png" alt="Logo Capivara"/>
                <?php if ($nome_adm): ?>
                    <div class="welcome-message" style="color: white; margin-left: 15px; font-weight: bold;">
                        Bem-vindo, <?php echo htmlspecialchars($nome_adm); ?>!
                    </div>
                <?php endif; ?>
            </div>
        </header>

        <h2>Cadastrar <span id="categoria-titulo">Categoria</span></h2>
        <h5>Qual categoria gostaria de adicionar?</h5>
        <select id="categoria" onchange="mostrarFormulario()">
             <option value="categorias">-- Categorias --</option>
            <option value="estilo">Estilo</option>
            <option value="gênero">Gênero</option>
            <option value="idioma">Idioma</option>
            <option value="modo">Modo de Jogo</option>
            <option value="plataforma">Plataforma</option>
            <option value="tema">Tema</option>
        </select>
        
        <div class="formularios">
            <div id="form-gênero" class="form-container">
                <form action="cadastro_categoria.php" method="post">
                    <label for="nome_gen">Nome do Gênero:</label>
                    <input type="text" id="nome_gen" name="nome_gen" placeholder="Nome do Gênero" required>
                    
                    <button type="submit">Salvar Gênero</button>
                    <button type="reset">Cancelar</button>
                </form>
            </div>

            <div id="form-estilo" class="form-container">
                <form action="cadastro_categoria.php" method="post">
                    <label for="nome_estilo">Nome do Estilo:</label>
                    <input type="text" id="nome_estilo" name="nome_estilo" placeholder="Nome do Estilo" required>
                    
                    <button type="submit">Salvar Estilo</button>
                    <button type="reset">Cancelar</button>
                </form>
            </div>

            <div id="form-modo" class="form-container">
                <form action="cadastro_categoria.php" method="post">
                    <label for="nome_modo">Nome do Modo de Jogo:</label>
                    <input type="text" id="nome_modo" name="nome_modo" placeholder="Nome do Modo de Jogo" required>
                    
                    <button type="submit">Salvar Modo de Jogo</button>
                    <button type="reset">Cancelar</button>
                </form>
            </div>

            <div id="form-tema" class="form-container">
                <form action="cadastro_categoria.php" method="post">
                    <label for="nome_tema">Nome do Tema:</label>
                    <input type="text" id="nome_tema" name="nome_tema" placeholder="Nome do Tema" required>
                    
                    <button type="submit">Salvar Tema</button>
                    <button type="reset">Cancelar</button>
                </form>
            </div>

            <div id="form-idioma" class="form-container">
                <form action="cadastro_categoria.php" method="post">
                    <label for="nome_idioma">Nome do Idioma:</label>
                    <input type="text" id="nome_idioma" name="nome_idioma" placeholder="Nome do Idioma" required>
                    
                    <button type="submit">Salvar Idioma</button>
                    <button type="reset">Cancelar</button>
                </form>
            </div>

            <div id="form-plataforma" class="form-container">
                <form action="cadastro_categoria.php" method="post">
                    <label for="nome_plat">Nome da Plataforma:</label>
                    <input type="text" id="nome_plat" name="nome_plat" placeholder="Nome da Plataforma" required>
                    
                    <button type="submit">Salvar Plataforma</button>
                    <button type="reset">Cancelar</button>
                </form>
            </div>
        </div>
        


        <a class="btn-voltar" href="../PHP/adm.php">Voltar</a>

        <footer>
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Sobre Nós</h3>
                    <p>A Cipher é a maior plataforma de distribuição digital de jogos para PC, com uma biblioteca vasta e diversificada.</p>
                </div>
                <div class="footer-section">
                    <h3>Contato</h3>
                    <p>Email: contato@ciphergames.com</p>
                    <p>Telefone: (11) 1234-5678</p>
                </div>
                <div class="footer-section">
                    <h3>Localização</h3>
                    <p>Av. Paulista, 1000</p>
                    <p>São Paulo - SP, 01310-100</p>
                </div>
                <div class="footer-section">
                    <h3>Redes Sociais</h3>
                    <a href="#">Facebook</a>
                    <a href="#">Twitter</a>
                    <a href="#">Instagram</a>
                    <a href="#">YouTube</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Cipher Games. Todos os direitos reservados.</p>
            </div>
        </footer>

        <script>
            function mostrarFormulario() {
                const categoria = document.getElementById("categoria").value;
                document.getElementById("categoria-titulo").innerText = categoria.charAt(0).toUpperCase() + categoria.slice(1);

                const formularios = document.querySelectorAll(".form-container");
                formularios.forEach(form => form.classList.remove("visible"));

                if (categoria) {
                    document.getElementById("form-" + categoria).classList.add("visible");
                }
            }
        </script>
    </body>
</html>