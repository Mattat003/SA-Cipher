<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['pk_usuario'])) {
    header('Location: login.php');
    exit();
}

// Pega o nome do usuário da sessão
$nomeUsuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Index</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    
    <!-- Material Symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    
    <!-- Seu CSS personalizado -->
    <link rel="stylesheet" href="css/index.css" />
    <link rel="stylesheet" href="endryo/biblioteca.css" />
    
    <!-- Fonte Motiva Sans -->
    <link href="https://fonts.cdnfonts.com/css/motiva-sans" rel="stylesheet" />
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="img/capybara.png" />
</head>
<body>

<header>
    <div class="logo">
        <h1>CIPHER</h1>
        <img src="img/capybara.png" alt="Logo Capivara" />
    </div>

    <?php if ($nomeUsuario): ?>
        <div class="welcome-message" style="color: white; margin-left: 15px; font-weight: bold;">
            Bem-vindo, <?php echo htmlspecialchars($nomeUsuario); ?>!
        </div>
    <?php endif; ?>

    <div class="busca">
        <span class="lupa">&#128269;</span>
        <input type="text" id="searchInput" placeholder="Buscar..." />
        <div id="results" class="search-results"></div>
    </div>

    <div class="perfil">
        <a href="../SA-Cipher/perfilnormal.html">
            <span class="material-symbols-outlined">account_circle</span>
        </a>
    </div>
</header>

<!-- Conteúdo da página (carrossel, biblioteca, etc) -->

<div class="container mt-3">
    <div id="results" class="text-white"></div>
</div>

<div class="container mt-5">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
        <!-- Indicadores -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4" aria-label="Slide 5"></button>
        </div>

        <!-- Slides -->
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="img/tlou.jpg" class="d-block w-100" alt="Imagem 1" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>The Last of Us 2</h5>
                    <p>Uma jornada emocionante de sobrevivência.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="img/red dead.jpg" class="d-block w-100" alt="Imagem 2" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>Red Dead Redemption 2</h5>
                    <p>Explore o Velho Oeste e viva uma história épica.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="img/minecraft.jpeg" class="d-block w-100" alt="Imagem 3" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>Minecraft</h5>
                    <p>Construa, explore e crie sua própria aventura.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="img/elden ring.jpg" class="d-block w-100" alt="Imagem 4" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>Elden Ring</h5>
                    <p>Levante-se e seja guiado pela graça para portar o poder do Anel Prístino e se torne um Lorde Prístino nas Terras Intermédias.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="img/subnautica.jpg" class="d-block w-100" alt="Imagem 5" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>Subnáutica</h5>
                    <p>Mergulhe nas profundezas de um mundo subaquático alienígena repleto de maravilhas e perigos.</p>
                </div>
            </div>
        </div>

        <!-- Navegação -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Próximo</span>
        </button>
    </div>
</div>

<div class="header">BIBLIOTECA DE JOGOS</div>

<div class="library">
    <div class="game-tile">
        <img src="endryo/img/game1.jpg" alt="Jogo 1" />
        <h3>Grand Theft Auto 6</h3>
        <a href="../SA-Cipher/endryo/Jogo-do-Mario-main/index.html" class="play-btn">Jogar</a>
    </div>
    <div class="game-tile">
        <img src="endryo/img/game2.jpg" alt="Jogo 2" />
        <h3>Jujutsu Kaisen Game Memory</h3>
        <a href="../SA-Cipher/endryo/JJk-Card-Game-main/index.html" class="play-btn">Jogar</a>
    </div>
    <div class="game-tile">
        <img src="endryo/img/game3.jpg" alt="Jogo 3" />
        <h3>Metroid Prime 4: Beyond</h3>
        <button class="play-btn">Jogar</button>
    </div>
    <div class="game-tile">
        <img src="endryo/img/game4.jpg" alt="Jogo 4" />
        <h3>Pokémon Legends: Z-A</h3>
        <button class="play-btn">Jogar</button>
    </div>
    <div class="game-tile">
        <img src="endryo/img/game5.jpg" alt="Jogo 5" />
        <h3>Death Stranding 2</h3>
        <button class="play-btn">Jogar</button>
    </div>
    <div class="game-tile">
        <img src="endryo/img/game6.jpg" alt="Jogo 6" />
        <h3>Elden Ring: Nightreign</h3>
        <button class="play-btn">Jogar</button>
    </div>
    <div class="game-tile">
        <img src="endryo/img/game7.jpg" alt="Jogo 7" />
        <h3>Final Fantasy VII Rebirth</h3>
        <button class="play-btn">Jogar</button>
    </div>
    <div class="game-tile">
        <img src="endryo/img/game8.jpg" alt="Jogo 8" />
        <h3>League of Legends</h3>
        <button class="play-btn">Jogar</button>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/index.js"></script>
<script>
    window.addEventListener("pageshow", () => {
        document.getElementById("searchInput").value = "";
        const results = document.getElementById("results");
        results.innerHTML = "";
        results.style.display = "none";
    });

    function toggleMenu() {
        const menu = document.getElementById("sideMenu");
        menu.classList.toggle("open");
    }
</script>

</body>
</html>
