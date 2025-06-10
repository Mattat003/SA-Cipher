<?php
session_start();
require_once 'conexao.php';
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
    <link rel="stylesheet" href="../css/index.css" />
    <link rel="stylesheet" href="../css/biblioteca.css" />
    
    <!-- Fonte Motiva Sans -->
    <link href="https://fonts.cdnfonts.com/css/motiva-sans" rel="stylesheet" />
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../img/capybara.png" />
    
    <style>
        .games-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            padding: 20px;
        }
        .game-tile {
            background-color: #1a1a1a;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }
        .game-tile:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
        }
        .game-tile img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-bottom: 2px solid #333;
        }
        .game-tile h3 {
            color: #fff;
            padding: 15px 15px 5px;
            margin: 0;
            font-size: 1.1rem;
        }
        .game-tile a {
            display: inline-block;
            background: linear-gradient(45deg, #0062ff, #00a1ff);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            margin: 10px 15px 15px;
            transition: all 0.3s ease;
            text-align: center;
            width: calc(100% - 30px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        .game-tile a:hover {
            background: linear-gradient(45deg, #0051d1, #0088cc);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }
        .game-tile a:active {
            transform: translateY(0);
        }
        .header {
            color: white;
            font-size: 1.8rem;
            font-weight: bold;
            margin: 30px 20px 10px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }
        footer {
            background-color: #1a1a1a;
            color: white;
            padding: 30px 0;
            margin-top: 50px;
        }
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .footer-section h3 {
            color: #00a1ff;
            margin-bottom: 15px;
        }
        .footer-section p, .footer-section a {
            color: #ccc;
            margin-bottom: 10px;
            display: block;
            text-decoration: none;
        }
        .footer-section a:hover {
            color: #00a1ff;
        }
        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #333;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <h1>CIPHER</h1>
        <img src="../img/capybara.png" alt="Logo Capivara" />
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
        <a href="perfilnormal.php">
            <span class="material-symbols-outlined">account_circle</span>
        </a>
    </div>
</header>

<div class="container mt-3">
    <div id="results" class="text-white"></div>
</div>

<div class="container mt-5">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4" aria-label="Slide 5"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../img/tlou.jpg" class="d-block w-100" alt="Imagem 1" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>The Last of Us 2</h5>
                    <p>Uma jornada emocionante de sobrevivência.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="../img/red dead.jpg" class="d-block w-100" alt="Imagem 2" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>Red Dead Redemption 2</h5>
                    <p>Explore o Velho Oeste e viva uma história épica.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="../img/minecraft.jpeg" class="d-block w-100" alt="Imagem 3" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>Minecraft</h5>
                    <p>Construa, explore e crie sua própria aventura.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="../img/elden ring.jpg" class="d-block w-100" alt="Imagem 4" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>Elden Ring</h5>
                    <p>Levante-se e seja guiado pela graça para portar o poder do Anel Prístino e se torne um Lorde Prístino nas Terras Intermédias.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="../img/subnautica.jpg" class="d-block w-100" alt="Imagem 5" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>Subnáutica</h5>
                    <p>Mergulhe nas profundezas de um mundo subaquático alienígena repleto de maravilhas e perigos.</p>
                </div>
            </div>
        </div>
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

<div class="games-container">
    <!-- JOGOS ESTÁTICOS -->
    <div class="game-tile">
        <img src="../img/game1.jpg" alt="Grand Theft Auto 6" />
        <h3>Grand Theft Auto 6</h3>
        <a href="#" onclick="registrarEEntrar('Grand Theft Auto 6', 'URL'); return false;">JOGAR AGORA</a>
    </div>
    <div class="game-tile">
        <img src="../img/game2.jpg" alt="Jujutsu Kaisen Game Memory" />
        <h3>Jujutsu Kaisen Game Memory</h3>
        <a href="/SA-Cipher/Arquivos/PHP/Testes/endryo/JJk-Card-Game-main/index.html"
           onclick="registrarEEntrar('Jujutsu Kaisen Game Memory', '/SA-Cipher/Arquivos/PHP/Testes/endryo/JJk-Card-Game-main/index.html'); return false;">
           JOGAR AGORA
        </a>
    </div>
    <div class="game-tile">
        <img src="../img/game3.jpg" alt="Metroid Prime 4: Beyond" />
        <h3>Metroid Prime 4: Beyond</h3>
        <a href="#" onclick="registrarEEntrar('Metroid Prime 4: Beyond', 'URL'); return false;">JOGAR AGORA</a>
    </div>
    <div class="game-tile">
        <img src="../img/game4.jpg" alt="Pokémon Legends: Z-A" />
        <h3>Pokémon Legends: Z-A</h3>
        <a href="#" onclick="registrarEEntrar('Pokémon Legends: Z-A', 'URL'); return false;">JOGAR AGORA</a>
    </div>
    <div class="game-tile">
        <img src="../img/game5.jpg" alt="Death Stranding 2" />
        <h3>Death Stranding 2</h3>
        <a href="#" onclick="registrarEEntrar('Death Stranding 2', 'URL'); return false;">JOGAR AGORA</a>
    </div>
    <div class="game-tile">
        <img src="../img/game6.jpg" alt="Elden Ring: Nightreign" />
        <h3>Elden Ring: Nightreign</h3>
        <a href="#" onclick="registrarEEntrar('Elden Ring: Nightreign', 'URL'); return false;">JOGAR AGORA</a>
    </div>
    <div class="game-tile">
        <img src="../img/game7.jpg" alt="Final Fantasy VII Rebirth" />
        <h3>Final Fantasy VII Rebirth</h3>
        <a href="#" onclick="registrarEEntrar('Final Fantasy VII Rebirth', 'URL'); return false;">JOGAR AGORA</a>
    </div>
    <div class="game-tile">
        <img src="../img/game8.jpg" alt="League of Legends" />
        <h3>League of Legends</h3>
        <a href="#" onclick="registrarEEntrar('League of Legends', 'URL'); return false;">JOGAR AGORA</a>
    </div>
    
    <!-- JOGOS AUTOMÁTICOS DA PASTA games -->
    <?php
    $gamesDir = __DIR__ . '/games';
    $gamesUrlBase = 'games';
    if (is_dir($gamesDir)) {
        $games = scandir($gamesDir);
        foreach ($games as $game) {
            if ($game === '.' || $game === '..') continue;
            $gamePath = $gamesDir . '/' . $game;
            if (is_dir($gamePath)) {
                $mainFile = null;
                if (file_exists("$gamePath/index.html")) {
                    $mainFile = 'index.html';
                } elseif (file_exists("$gamePath/index.php")) {
                    $mainFile = 'index.php';
                } else {
                    continue;
                }
                $cover = null;
                foreach (['cover.jpg', 'cover.png', 'thumbnail.jpg', 'thumbnail.png'] as $img) {
                    if (file_exists("$gamePath/$img")) {
                        $cover = $gamesUrlBase . "/$game/$img";
                        break;
                    }
                }
                if (!$cover) $cover = '../img/default-game.jpg';
                $gameName = ucwords(str_replace(['-', '_'], ' ', $game));
                echo '
                <div class="game-tile">
                    <img src="' . htmlspecialchars($cover) . '" alt="' . htmlspecialchars($gameName) . '" />
                    <h3>' . htmlspecialchars($gameName) . '</h3>
                    <a href="' . $gamesUrlBase . '/' . $game . '/' . $mainFile . '" onclick="registrarEEntrar(\'' . addslashes($gameName) . '\', \'' . $gamesUrlBase . '/' . $game . '/' . $mainFile . '\'); return false;">JOGAR AGORA</a>
                </div>';
            }
        }
    }
    ?>
</div>

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
        <p>&copy; 2023 Cipher Games. Todos os direitos reservados.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
   document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchInput");
    const resultsContainer = document.getElementById("results");

    // Itens de exemplo para busca
    const items = {
        "Cyberpunk 2077": "cyberpunk.php",
        "The Last of Us": "tlou.php",
        "Red Dead Redemption 2": "rdr.php",
        "Minecraft": "minecraft.php",
        "God of War": "gow.php",
        "Hollow Knight": "hollowknight.php",
        "FIFA 22": "fifa.php"
    };

    // Limpar o campo de busca quando a página for carregada
    window.addEventListener('load', function() {
        searchInput.value = '';  // Limpa o campo de busca
        resultsContainer.innerHTML = '';  // Limpa qualquer resultado anterior
        resultsContainer.style.display = "none";  // Esconde a lista de resultados
    });

    // Função para tratar o input de busca
    searchInput.addEventListener("input", () => {
        const searchTerm = searchInput.value.toLowerCase().trim();
        resultsContainer.innerHTML = "";  // Limpa os resultados anteriores

        if (searchTerm === "") {
            resultsContainer.style.display = "none";  // Esconde resultados quando não há texto
            return;
        }

        const filteredItems = Object.keys(items).filter(item =>
            item.toLowerCase().includes(searchTerm)
        );

        if (filteredItems.length > 0) {
            filteredItems.forEach(item => {
                const link = document.createElement("a");
                link.className = "result-item";
                link.textContent = item;
                link.href = items[item];  // Vai para a página correspondente
                resultsContainer.appendChild(link);
            });
        } else {
            const noResult = document.createElement("div");
            noResult.className = "result-item";
            noResult.textContent = "Nenhum resultado encontrado.";
            resultsContainer.appendChild(noResult);
        }

        resultsContainer.style.display = "block";  // Exibe os resultados
    });
});
</script>
<script>
    function registrarEEntrar(nomeJogo, urlDestino) {
    fetch('registrar_entrada.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'nome_jogo=' + encodeURIComponent(nomeJogo)
    })
    .then(response => response.json())
    .then(data => {
        window.location.href = urlDestino;
    });
}
</script>

</body>
</html>