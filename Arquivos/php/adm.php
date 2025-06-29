<?php
session_start();
if (!isset($_SESSION['tipo'])) {
    header("Location: login.php");
    exit();
}
require_once 'conexao.php';

// Pega o fk_cargo da sessão
$fk_cargo = $_SESSION['fk_cargo'] ?? null;
$nomeCargo = '';
$nome = $_SESSION['adm'] ?? 'Funcionário';


// Busca o nome do cargo na tabela cargo
if ($fk_cargo) {
    $stmt = $pdo->prepare("SELECT nome_cargo FROM cargo WHERE pk_cargo = :id");
    $stmt->bindParam(":id", $fk_cargo, PDO::PARAM_INT);
    $stmt->execute();
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $nomeCargo = $row['nome_cargo'];
    }
}

// Defina os IDs dos cargos
define('CARGO_ADMIN', 1);
define('CARGO_FUNCIONARIO', 2);

// Defina as permissões/menus para cada cargo
$menus = [
    CARGO_ADMIN => [
        "Clientes" => [
            "Alterar Clientes" => "alterar_usuario.php",
            "Adicionar Clientes" => "cadastrar_usuario.php",
            "Pesquisar Clientes" => "buscar_usuario.php",
            "Listar Clientes" => "listar_usuario.php",
            "Excluir Clientes" => "excluir_usuario.php",
          
        ],
        "Funcionarios" => [
            "Alterar Funcionários" => "alterar_funcionario.php",
            "Adicionar Funcionários" => "cadastrar_funcionario.php",
            "Pesquisar Funcionários" => "buscar_funcionario.php",
            "Listar Funcionários" => "listar_funcionario.php",
            "Excluir Funcionários" => "excluir_funcionario.php",
            
        ],
       "Jogos" => [
            "Alterar Jogo" => "alterar_jogo.php",
            "Adicionar Jogo" => "cadastrar_jogo.php",
            "Pesquisar Jogo" => "buscar_jogo.php",
            "Lista de Jogos" => "listar_jogo.php",
            "Locações pendentes" => "liberar_locacoes.php",
            
            
        ],
        "Categorias" => [
            "Adicionar Categorias" => "cadastrar_categoria.php",
            "Pesquisar Categorias" => "buscar_categoria.php",
            "Gerenciar Categorias" => "listar_categoria.php",
        ]
    ],
    CARGO_FUNCIONARIO => [
        "Clientes" => [
            "Adicionar Clientes" => "cadastrar_usuario.php",
            "Pesquisar Clientes" => "buscar_usuario.php",
            "Listar Clientes" => "listar_usuario.php",
        ],
        "Funcionários" => [
            "Pesquisar Funcionários" => "buscar_funcionario.php",
            "Listar Funcionários" => "listar_funcionario.php",
        ],
       
        "Jogos" => [
            "Pesquisar Jogo" => "buscar_jogo.php",
            "Lista de Jogos" => "listar_jogo.php",
        ],
        "Categorias" => [
            "Pesquisar Categorias" => "buscar_categoria.php",
            "Listar Categorias" => "listar_categoria.php",
        ]
    ],
];

// Defina o menu do cargo logado
$menus_cargo = $menus[$fk_cargo] ?? [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do <?=htmlspecialchars($nomeCargo ?: 'Clientes')?> </title>
    <style>
    body {
        font-family: 'Motiva Sans', 'Segoe UI', sans-serif;
        background: #12002b;
        color: #f0e6ff;
        margin: 0;
    }

    header {
        background: linear-gradient(135deg, #1e003a, #2a0a4a);
        color: #f0e6ff;
        padding: 18px 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        border-bottom: 1px solid #5d3bad;
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .logout-btn {
        background: #7a0c2e;
        color: #f0e6ff;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        box-shadow: 0 0 10px rgba(160, 30, 70, 0.4);
        transition: background 0.3s, box-shadow 0.3s;
    }

    .logout-btn:hover {
        background: #a3153f;
        box-shadow: 0 0 15px rgba(255, 50, 100, 0.6);
    }

    nav {
        background: #1e1b2e;
        padding: 18px 0;
        border-bottom: 1px solid #5d3bad;
    }

    ul.menu {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        gap: 18px;
        justify-content: center;
        flex-wrap: wrap;
    }

    ul.menu > li {
        position: relative;
    }

    ul.menu > li > button,
    ul.menu > li > a {
        background: #2b204d;
        color: #c7b3e6;
        padding: 10px 22px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        border: 1px solid #3e2f6d;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
    }

    ul.menu > li > button:hover,
    ul.menu > li > a:hover {
        background: #7a5af5;
        color: #fff;
        border-color: #9d7aff;
        box-shadow:
            0 0 10px rgba(122, 90, 245, 0.6),
            inset 0 0 5px rgba(122, 90, 245, 0.3);
    }

    ul.dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background: #252836;
        min-width: 200px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
        border: 1px solid #5d3bad;
        border-radius: 0 0 10px 10px;
        padding: 0;
        z-index: 100;
        animation: fadeIn 0.3s ease-out;
    }

    ul.menu > li.open > ul.dropdown-menu,
    ul.menu > li:hover > ul.dropdown-menu {
        display: block;
    }

    ul.dropdown-menu li {
        list-style: none;
    }

    ul.dropdown-menu a {
        display: block;
        padding: 12px 20px;
        color: #f0e6ff;
        text-decoration: none;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        border-bottom: 1px solid #2a2e3c;
    }

    ul.dropdown-menu a:hover {
        background-color: rgba(93, 59, 173, 0.2);
        color: white;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsivo */
    @media (max-width: 700px) {
        ul.menu {
            flex-direction: column;
            gap: 0;
        }

        ul.menu > li {
            margin-bottom: 10px;
        }

        ul.dropdown-menu {
            position: static;
            box-shadow: none;
            border-radius: 0;
            border-left: none;
            border-right: none;
        }
    }
</style>

    <script>
    // Dropdown para mobile/touch e acessibilidade
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('ul.menu > li > button').forEach(function(btn){
            btn.addEventListener('click', function(e){
                e.preventDefault();
                let li = btn.parentElement;
                // Fecha outros abertos
                document.querySelectorAll('ul.menu > li.open').forEach(x=>{
                    if(x!==li) x.classList.remove('open');
                });
                li.classList.toggle('open');
            });
        });
        // Fecha dropdown ao clicar fora
        document.addEventListener('click', function(e){
            document.querySelectorAll('ul.menu > li.open').forEach(function(li){
                if (!li.contains(e.target)) li.classList.remove('open');
            });
        });
    });
    </script>
</head>
<body>
    <header>
        <div>
            <h2>Painel do <?=htmlspecialchars($nomeCargo ?: 'Clientes')?></h2>
            <span>Bem-vindo, <strong><?=htmlspecialchars($nome)?></strong>!</span>
        </div>
        <form action="logout.php" method="post" style="margin:0;">
            <button class="logout-btn" type="submit">Sair</button>
        </form>
    </header>

    <?php if ($menus_cargo): ?>
        <nav>
            <ul class="menu">
                <?php foreach ($menus_cargo as $categoria => $links): ?>
                    <li>
                        <button type="button" aria-haspopup="true" aria-expanded="false"><?= htmlspecialchars($categoria) ?></button>
                        <ul class="dropdown-menu">
                            <?php foreach ($links as $titulo => $url): ?>
                                <li><a href="<?= htmlspecialchars($url) ?>"><?= htmlspecialchars($titulo) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    <?php else: ?>
        <div style="margin:40px auto; text-align:center;">
            <p>Você não possui permissões administrativas.</p>
        </div>
    <?php endif; ?>
</body>
</html>