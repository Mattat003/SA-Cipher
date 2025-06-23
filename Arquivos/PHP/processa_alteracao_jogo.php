<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;

if ($fk_cargo != 1 && $fk_cargo != 4) {
    echo "Acesso negado";
    exit;
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pk_jogo = $_POST['pk_jogo'] ?? null;
    $nome_jogo = trim($_POST['nome_jogo'] ?? '');
    $data_lanc = $_POST['data_lanc'] ?? '';
    $desenvolvedora = trim($_POST['desenvolvedora'] ?? '');
    $url_jogo = trim($_POST['url_jogo'] ?? '');
    $imagem_jogo = trim($_POST['imagem_jogo'] ?? '');

    if (!$pk_jogo || empty($nome_jogo) || empty($data_lanc) || empty($desenvolvedora)) {
        echo "<script>alert('Preencha todos os campos obrigatórios!'); window.history.back();</script>";
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            UPDATE jogo 
            SET nome_jogo = :nome_jogo,
                data_lanc = :data_lanc,
                desenvolvedora = :desenvolvedora,
                url_jogo = :url_jogo,
                imagem_jogo = :imagem_jogo
            WHERE pk_jogo = :pk_jogo
        ");

        $stmt->execute([
            ':nome_jogo' => $nome_jogo,
            ':data_lanc' => $data_lanc,
            ':desenvolvedora' => $desenvolvedora,
            ':url_jogo' => $url_jogo,
            ':imagem_jogo' => $imagem_jogo,
            ':pk_jogo' => $pk_jogo
        ]);

        echo "<script>alert('Jogo atualizado com sucesso!'); window.location.href='alterar_jogo.php';</script>";
        exit;
    } catch (PDOException $e) {
        echo "Erro ao atualizar o jogo: " . $e->getMessage();
    }
} else {
    echo "Acesso inválido.";
}
