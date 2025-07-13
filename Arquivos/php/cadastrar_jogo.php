<?php
session_start();
require_once 'conexao.php';

// Recupera o cargo do usuário logado
$fk_cargo = $_SESSION['fk_cargo'] ?? null;
$nomeCargo = '';
$nome = $_SESSION['adm'] ?? 'Funcionário';

// Carrega listas de categorias do banco para os selects do formulário
$generos = $pdo->query("SELECT pk_genero, nome_gen FROM genero ORDER BY nome_gen")->fetchAll(PDO::FETCH_ASSOC);
$estilos = $pdo->query("SELECT pk_estilo, nome_estilo FROM estilo ORDER BY nome_estilo")->fetchAll(PDO::FETCH_ASSOC);
$plataformas = $pdo->query("SELECT pk_plataforma, nome_plat FROM plataforma ORDER BY nome_plat")->fetchAll(PDO::FETCH_ASSOC);

// Variável para indicar erro de nome duplicado
$erro_nome = false;

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera e sanitiza os dados do formulário
    $nome_jogo = trim($_POST['nome_jogo']);
    $data_lanc = $_POST['data_lanc'];
    $url_jogo = $_POST['url_jogo'];
    $imagem_jogo = null;

    // Verificação de duplicidade de nome de jogo (não diferencia maiúsculas de minúsculas)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM jogo WHERE LOWER(nome_jogo) = LOWER(?)");
    $stmt->execute([$nome_jogo]);
    if ($stmt->fetchColumn() > 0) {
        // Se já existe um jogo com esse nome, define o erro para exibir o alert
        $erro_nome = true;
    } else {
        // Cria a pasta uploads se não existir para armazenar as imagens enviadas
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        // Faz o upload da imagem, se fornecida
        if (isset($_FILES['imagem_jogo']) && $_FILES['imagem_jogo']['error'] == 0) {
            $ext = pathinfo($_FILES['imagem_jogo']['name'], PATHINFO_EXTENSION);
            $imagem_jogo = 'uploads/' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['imagem_jogo']['tmp_name'], $imagem_jogo);
        }

        // Insere o novo jogo na tabela jogo
        $stmt = $pdo->prepare(
            "INSERT INTO jogo (nome_jogo, data_lanc, imagem_jogo, url_jogo, disponivel_locacao)
            VALUES (?, ?, ?, ?, 1)"
        );
        $stmt->execute([$nome_jogo, $data_lanc,  $imagem_jogo, $url_jogo]);
        $jogo_id = $pdo->lastInsertId();

        // Associa os gêneros selecionados ao jogo (relacionamento muitos para muitos)
        $generos_sel = $_POST['generos'] ?? [];
        foreach ($generos_sel as $g) {
            $stmt = $pdo->prepare("INSERT INTO jogo_genero (jogo_id, genero_id) VALUES (?, ?)");
            $stmt->execute([$jogo_id, $g]);
        }

        // Associa os estilos selecionados ao jogo (relacionamento muitos para muitos)
        $estilos_sel = $_POST['estilos'] ?? [];
        foreach ($estilos_sel as $e) {
            $stmt = $pdo->prepare("INSERT INTO jogo_estilo (jogo_id, estilo_id) VALUES (?, ?)");
            $stmt->execute([$jogo_id, $e]);
        }

        // Associa as plataformas selecionadas ao jogo (relacionamento muitos para muitos)
        $plataformas_sel = $_POST['plataformas'] ?? [];
        foreach ($plataformas_sel as $p) {
            $stmt = $pdo->prepare("INSERT INTO jogo_plataforma (jogo_id, plataforma_id) VALUES (?, ?)");
            $stmt->execute([$jogo_id, $p]);
        }

        // Redireciona para a mesma página com parâmetro de sucesso
        header('Location: cadastrar_jogo.php?sucesso=1');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Cadastrar Jogo</title>
    <style>
        body {
            background-color: #12002b;
            font-family: 'Motiva Sans', 'Segoe UI', sans-serif;
            color: #f0e6ff;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        form {
            max-width: 400px;
            background: #1e1b2e;
            margin: 60px auto;
            border-radius: 12px;
            padding: 28px 25px 20px 25px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6);
            border: 1px solid #5d3bad;
        }
        h2 {
            text-align: center;
            color: #c084fc;
            margin-top: 20px;
            margin-bottom: 30px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }
        label {
            color: #e9d5ff;
            font-weight: 500;
            display: block;
            margin-top: 12px;
            margin-bottom: 6px;
        }
        input, select {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #2a2540;
            background: #252836;
            color: #f0e6ff;
            margin-bottom: 18px;
            font-size: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            box-sizing: border-box;
        }
        input:focus, select:focus {
            border-color: #7a5af5;
            background-color: #2a2e3c;
            outline: none;
            box-shadow: 0 0 0 3px rgba(122, 90, 245, 0.3);
        }
        select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L6 6L11 1' stroke='%23c084fc' stroke-width='2'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 12px 8px;
            cursor: pointer;
        }
        option {
            background-color: #252836;
            color: #f0e6ff;
        }
        button[type="submit"] {
            display: block;
            text-align: center;
            background: #510d96;
            color: #fff;
            font-weight: 600;
            padding: 12px 25px;
            border: 1px solid #510d96;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.2s;
            width: 100%;
            margin-bottom: 10px;
        }
        button:hover {
            background: #7a5af5;
            color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            transform: translateY(-1px);
        }
        button:active {
            transform: translateY(0);
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        .alert-success {
            text-align: center;
            color: #c084fc;
            background: #2e2152;
            border: 1px solid #7a5af5;
            padding: 12px;
            border-radius: 8px;
            margin: 20px auto;
            max-width: 400px;
        }
        .alert-error {
            text-align: center;
            color: #fff;
            background: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: -25px;
            color: #fff;
            background: #510d96;
            text-decoration: none;
            font-weight: 600;
            padding: 12px 25px;
            border: 1px solid #510d96;
            border-radius: 8px;
            max-width: 200px;
            margin-left: auto;
            margin-right: auto;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .back-link:hover {
            background: #7a5af5;
            color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            transform: translateY(-1px);
        }
        .back-link:active {
            transform: translateY(0);
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        /* Responsivo */
        @media (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 20px;
            }
            table th, table td {
                padding: 10px;
                font-size: 0.85em;
            }
            table a {
                margin-right: 5px;
            }
        }
        @media (max-width: 480px) {
            h2 {
                font-size: 1.8em;
            }
            .back-link {
                max-width: 100%;
            }
        }
        </style>
</head>
<body class="bg-dark text-light">
<?php
// Exibe um alert de sucesso caso o jogo seja cadastrado
if (isset($_GET['sucesso'])): ?>
<script>
    alert("Jogo cadastrado com sucesso!");
</script>
<?php
endif;
// Exibe um alert de erro caso tente cadastrar um nome já existente
if ($erro_nome): ?>
<script>
    alert("Já existe um jogo cadastrado com esse nome!");
</script>
<?php endif; ?>
<div class="container mt-5">
    <form method="post" enctype="multipart/form-data">
        <h2>Cadastrar Novo Jogo</h2>
        <!-- Campo para nome do jogo -->
        <div class="mb-3">
            <label class="form-label">Nome do Jogo</label>
            <input type="text" name="nome_jogo" class="form-control" required>
        </div>
        <!-- Campo para data de lançamento -->
        <div class="mb-3">
            <label class="form-label">Data de Lançamento</label>
            <input type="date" name="data_lanc" class="form-control" required>
        </div>
        <!-- Campo para link do jogo -->
        <div class="mb-3">
            <label class="form-label">Link do Jogo</label>
            <input type="text" name="url_jogo" class="form-control" required>
        </div>
        <!-- Campo para upload de imagem -->
        <div class="mb-3">
            <label class="form-label">Imagem do Jogo</label>
            <input type="file" name="imagem_jogo" class="form-control" accept="image/*" required>
        </div>
        <!-- Select múltiplo para gêneros -->
        <div class="mb-3">
            <label class="form-label">Gênero(s)</label>
            <select name="generos[]" class="form-control" multiple size="5" required>
                <?php foreach ($generos as $g): ?>
                    <option value="<?= $g['pk_genero'] ?>"><?= htmlspecialchars($g['nome_gen']) ?></option>
                <?php endforeach; ?>
            </select>
            <small style="color:#c084fc">Segure Ctrl (Windows) ou Command (Mac) para selecionar mais de um.</small>
        </div>
        <!-- Select múltiplo para estilos -->
        <div class="mb-3">
            <label class="form-label">Estilo(s)</label>
            <select name="estilos[]" class="form-control" multiple size="4">
                <?php foreach ($estilos as $e): ?>
                    <option value="<?= $e['pk_estilo'] ?>"><?= htmlspecialchars($e['nome_estilo']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <!-- Select múltiplo para plataformas -->
        <div class="mb-3">
            <label class="form-label">Plataforma(s)</label>
            <select name="plataformas[]" class="form-control" multiple size="5">
                <?php foreach ($plataformas as $p): ?>
                    <option value="<?= $p['pk_plataforma'] ?>"><?= htmlspecialchars($p['nome_plat']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <!-- Botão de envio -->
        <button type="submit" class="btn btn-primary">Cadastrar Jogo</button>
    </form>
</div>
<!-- Link para voltar para a página administrativa -->
<a href="adm.php" class="back-link">Voltar</a>
</body>
</html>