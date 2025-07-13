<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;
if ($fk_cargo != 1 && $fk_cargo != 4) {
    echo "Acesso negado";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pk_jogo = $_POST['pk_jogo'];
    $nome_jogo = $_POST['nome_jogo'];
    $data_lanc = $_POST['data_lanc'];
    $url_jogo = $_POST['url_jogo'];
    $desenvolvedora = $_POST['desenvolvedora'];
    $generos = $_POST['generos'] ?? [];
    $estilos = $_POST['estilos'] ?? [];
    $plataformas = $_POST['plataformas'] ?? [];
    $idiomas = $_POST['idiomas'] ?? [];

    $jogo_id = $_POST['pk_jogo'];

    // Pega imagem atual do banco
    $stmt = $pdo->prepare("SELECT imagem_jogo FROM jogo WHERE pk_jogo = ?");
    $stmt->execute([$pk_jogo]);
    $imagem_atual = $stmt->fetchColumn();

    // Processar nova imagem, se houver
    $imagem_jogo = $imagem_atual;

    if (isset($_FILES['imagem_jogo']) && $_FILES['imagem_jogo']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['imagem_jogo']['name'], PATHINFO_EXTENSION);
        $novo_nome = uniqid('jogo_') . '.' . $ext;
        $caminho = 'uploads/' . $novo_nome;

        // Cria a pasta se não existir
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        if (move_uploaded_file($_FILES['imagem_jogo']['tmp_name'], $caminho)) {
            $imagem_jogo = $caminho;
        }
    }

    //Atualiza as Categorias
    // Atualiza GÊNEROS
    $stmt = $pdo->prepare("INSERT INTO jogo_genero (jogo_id, genero_id) VALUES (?, ?)");
    $stmt->execute([$jogo_id, $generos]);

    // Atualiza ESTILOS
    $stmt = $pdo->prepare("INSERT INTO jogo_estilo (jogo_id, estilo_id) VALUES (?, ?)");
    $stmt->execute([$jogo_id, $estilos]);

    // Atualiza PLATAFORMAS
    $stmt = $pdo->prepare("INSERT INTO jogo_plataforma (jogo_id, plataforma_id) VALUES (?, ?)");
    $stmt->execute([$jogo_id, $plataformas]);
    
    // Atualiza IDIOMAS
    $stmt = $pdo->prepare("INSERT INTO jogo_idioma (jogo_id, idioma_id) VALUES (?, ?)");
    $stmt->execute([$jogo_id, $idiomas]);

    // Atualizar no banco
    $stmt = $pdo->prepare("
        UPDATE jogo 
        SET nome_jogo = ?, data_lanc = ?, desenvolvedora = ?, url_jogo = ?, imagem_jogo = ?
        WHERE pk_jogo = ?
    ");
    $stmt->execute([$nome_jogo, $data_lanc, $desenvolvedora, $url_jogo, $imagem_jogo, $pk_jogo]);

    // Redirecionar com sucesso
    header("Location: alterar_jogo.php?sucesso=1&busca_jogo=$pk_jogo");
    exit;
}
?>
