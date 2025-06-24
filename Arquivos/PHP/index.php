<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['pk_usuario'])) {
    header('Location: login.php');
    exit();
}

$nomeUsuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';
$meu_id = $_SESSION['pk_usuario'];

$stmt = $pdo->prepare("
    SELECT nome_jogo, imagem_jogo, url_jogo
    FROM biblioteca_usuario
    WHERE usuario_id = ?
");
$stmt->execute([$meu_id]);
$meus_jogos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Index</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="../css/index.css" />
    <link rel="stylesheet" href="../css/biblioteca.css" />
    <link href="https://fonts.cdnfonts.com/css/motiva-sans" rel="stylesheet" />
    <link rel="icon" type="image/png" href="../img/capybara.png" />
    <style>
        .games-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            padding: 75px;
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
            background: linear-gradient(45deg,rgb(91, 21, 148),rgb(59, 15, 179));
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
            background: linear-gradient(45deg,rgb(77, 31, 184),rgb(149, 51, 230));
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
            color:rgb(255, 255, 255);
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
        .search-results {
            position: absolute;
            background: #222;
            border: 1px solid #111;
            z-index: 99;
            width: 100%;
            max-width: 350px;
            left: 0;
            top: 45px;
            border-radius: 0 0 10px 10px;
            display: none;
        }
        .result-item {
            padding: 10px;
            color: #fff;
            cursor: pointer;
            display: block;
            text-decoration: none;
        }
        .result-item:hover {
            background: #00a1ff;
            color: #fff;
        }
        .busca {
            position: relative;
            display: inline-block;
            width: 350px;
        }
       header a[href="jogos.php"] {
            color:rgb(255, 255, 255);
            background: none;
            border: none;
            font-family: 'Motiva Sans', Arial, Helvetica, sans-serif;
            font-size: 1.05rem;
            font-weight: 600;
            padding: 3px 10px;
            margin: 0 12px 0 450px;
            border-radius: 6px;
            letter-spacing: 0.5px;
            text-decoration: none;
            transition: color 0.17s, background 0.17s, text-decoration 0.19s;
            box-shadow: none;
            outline: none;
            line-height: 1.4;
            vertical-align: middle;
            display: inline-block;
        }

        header a[href="jogos.php"]:hover,
        header a[href="jogos.php"]:focus {
            color: #fff;
            background:rgba(41, 17, 129, 0.84);
            text-decoration: none;
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
              <a href="jogos.php"> Jogos </a>
        </div>
    <?php endif; ?>


    <div class="busca">
        <span class="lupa">&#128269;</span>
        <input type="text" id="searchInput" placeholder="Buscar na sua biblioteca..." autocomplete="off" />
        <div id="results" class="search-results"></div>
    </div>

    <div class="perfil">
        <a href="perfilnormal.php">
            <span class="material-symbols-outlined">account_circle</span>
        </a>
    </div>
</header>

<div class="container mt-3">
    <div id="searchFeedback" class="text-white"></div>
</div>

<div class="container mt-5">
    <!-- seu carousel aqui (sem alterações) -->
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
        <!-- ... código do carousel ... -->
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

<div class="games-container" id="minhaBiblioteca">
    <?php foreach ($meus_jogos as $jogo): ?>
        <div class="game-tile">
            <img src="<?= htmlspecialchars($jogo['imagem_jogo']) ? htmlspecialchars($jogo['imagem_jogo']) : '../img/semImage.jpg'?>" alt="<?= htmlspecialchars($jogo['nome_jogo']) ?>" />
            <h3><?= htmlspecialchars($jogo['nome_jogo']) ?></h3>
            <a href="<?= htmlspecialchars($jogo['url_jogo']) ?>"
                onclick="registrarEEntrar('<?= addslashes($jogo['nome_jogo']) ?>', '<?= addslashes($jogo['url_jogo']) ?>'); return false;">
                JOGAR AGORA
            </a>
        </div>
    <?php endforeach; ?>
</div>

<footer>
    <div class="footer-content">
        <div class="footer-section">
            <h3>Sobre Nós</h3>
            <p>A Cipher é a maior plataforma de gerenciamento digital de jogos, com uma biblioteca vasta e diversificada.</p>
        </div>
        <div class="footer-section">
            <h3>Contato</h3>
            <p>Email: contatociphergames@gmail.com</p>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
const userGames = <?php
    $jsArray = [];
    foreach ($meus_jogos as $jogo) {
        $jsArray[] = [
            "nome" => $jogo["nome_jogo"],
            "url" => $jogo["url_jogo"] ?: "#",
            "imagem" => $jogo["imagem_jogo"] ?: "../img/default-game.jpg"
        ];
    }
    echo json_encode($jsArray, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>;
</script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchInput");
    const resultsContainer = document.getElementById("results");
    const bibliotecaContainer = document.getElementById("minhaBiblioteca");

    window.addEventListener('load', function() {
        searchInput.value = '';
        resultsContainer.innerHTML = '';
        resultsContainer.style.display = "none";
    });

    searchInput.addEventListener("input", () => {
        const searchTerm = searchInput.value.toLowerCase().trim();
        resultsContainer.innerHTML = "";

        if (searchTerm === "") {
            resultsContainer.style.display = "none";
            bibliotecaContainer.querySelectorAll(".game-tile").forEach(tile => tile.style.display = "");
            return;
        }

        const filteredGames = userGames.filter(jogo =>
            jogo.nome.toLowerCase().includes(searchTerm)
        );

        if (filteredGames.length > 0) {
            filteredGames.forEach(jogo => {
                const link = document.createElement("a");
                link.className = "result-item";
                link.textContent = jogo.nome;
                link.href = jogo.url;
                link.onclick = function(e) {
                    e.preventDefault();
                    registrarEEntrar(jogo.nome, jogo.url);
                };
                resultsContainer.appendChild(link);
            });
        } else {
            const noResult = document.createElement("div");
            noResult.className = "result-item";
            noResult.textContent = "Nenhum resultado encontrado.";
            resultsContainer.appendChild(noResult);
        }

        resultsContainer.style.display = "block";

        bibliotecaContainer.querySelectorAll(".game-tile").forEach(tile => {
            const title = tile.querySelector("h3").textContent.toLowerCase();
            tile.style.display = title.includes(searchTerm) ? "" : "none";
        });
    });

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
            resultsContainer.style.display = "none";
        }
    });
});
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
