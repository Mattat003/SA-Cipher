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
$nome = $_SESSION['adm'] ?? 'Usuário';

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
        "Usuários" => [
            "Adicionar Usuário" => "cadastrar_usuario.php",
            "Pesquisar Usuários" => "buscar_usuario.php",
            "Listar Usuários" => "listar_usuario.php",
            "Excluir Usuário" => "excluir_usuario.php",
        ],
        "Funcionarios" => [
            "Buscar Funcionario" => "buscar_funcionario.php",
            "Cadastrar Funcionario" => "cadastrar_funcionario.php"
        ],
        "Jogos" => [
            "Buscar Jogo" => "buscar_jogo.php",
            "Adicionar Jogo" => "adicionar_jogo.php",
        ],
        "Desenvolvedoras" => [
            "Buscar Desenvolvedora" => "buscar_desenvolvedora.php",
            "Adicionar Desenvolvedora" => "cadastrar_desenvolvedora.php",
        ]
    ],
    CARGO_FUNCIONARIO => [
        "Conteúdo" => [
            "Adicionar Conteúdo" => "cadastrar_conteudo.php",
        ],
        "Usuários" => [
            "Adicionar Usuário" => "cadastrar_usuario.php",
        ],
        "Clientes e Fornecedores" => [
            "Buscar Cliente" => "buscar_cliente.php",
            "Buscar Fornecedor" => "buscar_fornecedor.php",
        ],
        "Jogos" => [
            "Buscar Jogo" => "buscar_jogo.php",
        ]
    ],
    // Outros cargos podem ser adicionados aqui
];

// Defina o menu do cargo logado
$menus_cargo = $menus[$fk_cargo] ?? [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do <?=htmlspecialchars($nomeCargo ?: 'Usuário')?> </title>
    <style>
        body { font-family: Arial, sans-serif; background: #f6f6fa; margin: 0; }
        header { background: #2c056e; color: #fff; padding: 18px 28px; display: flex; justify-content: space-between; align-items: center; }
        .logout-btn { background: #900; color: #fff; padding: 8px 16px; border: none; border-radius: 5px; cursor:pointer;}
        nav { background: #fff; padding: 18px 0; }
        ul.menu { list-style: none; padding: 0; margin: 0; display: flex; gap: 18px; justify-content: center; }
        ul.menu > li { position: relative; }
        ul.menu > li > button, ul.menu > li > a {
            color: #2c056e; background: #e6e1f4;
            padding: 10px 22px; border-radius: 6px; text-decoration: none; font-weight: 600; border: none; cursor:pointer; transition: background 0.2s;
            font-size: 1rem;
        }
        ul.menu > li > button:hover, ul.menu > li > a:hover { background: #510d96; color: #fff; }
        ul.dropdown-menu {
            display: none; position: absolute; top: 44px; left: 0; background: #fff;
            min-width: 200px; box-shadow: 0 4px 24px #0001; border-radius: 0 0 8px 8px; padding: 0; z-index: 100;
        }
        ul.menu > li.open > ul.dropdown-menu,
        ul.menu > li:hover > ul.dropdown-menu {
            display: block;
        }
        ul.dropdown-menu li { list-style: none; }
        ul.dropdown-menu a {
            display: block; padding: 10px 18px; color: #2c056e; text-decoration: none; border-radius: 0; transition: background 0.2s;
        }
        ul.dropdown-menu a:hover { background: #510d96; color: #fff; }
        @media (max-width: 700px) {
            ul.menu { flex-direction: column; gap: 0; }
            ul.menu > li { margin-bottom: 5px; }
            ul.dropdown-menu { position: static; box-shadow: none; border-radius:0; }
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
            <h2>Painel do <?=htmlspecialchars($nomeCargo ?: 'Usuário')?></h2>
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