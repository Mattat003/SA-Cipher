<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;
$nomeCargo = '';
$nome = $_SESSION['adm'] ?? 'Funcionário';

if (isset($_POST['locacao_id'], $_POST['acao'])) {
    $locacao_id = $_POST['locacao_id'];
    $acao = $_POST['acao'];

    // Busca a locação
    $stmt = $pdo->prepare("SELECT * FROM locacoes_pendentes WHERE id = ?");
    $stmt->execute([$locacao_id]);
    $locacao = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($locacao && $locacao['status'] == 'pendente') {
        if ($acao == 'liberar') {
            // Busca dados completos do jogo
            $stmt = $pdo->prepare("SELECT nome_jogo, imagem_jogo, url_jogo FROM jogo WHERE pk_jogo = ?");
            $stmt->execute([$locacao['jogo_id']]);
            $jogo = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($jogo) {
                // Adiciona na biblioteca do usuário com imagem e url
                $stmt = $pdo->prepare(
                    "INSERT IGNORE INTO biblioteca_usuario (usuario_id, nome_jogo, imagem_jogo, url_jogo) VALUES (?, ?, ?, ?)"
                );
                $stmt->execute([
                    $locacao['usuario_id'],
                    $jogo['nome_jogo'],
                    $jogo['imagem_jogo'],
                    $jogo['url_jogo']
                ]);
            }
            // Atualiza status da locação
            $stmt = $pdo->prepare("UPDATE locacoes_pendentes SET status = 'liberado' WHERE id = ?");
            $stmt->execute([$locacao_id]);
        } elseif ($acao == 'recusar') {
            $stmt = $pdo->prepare("UPDATE locacoes_pendentes SET status = 'recusado' WHERE id = ?");
            $stmt->execute([$locacao_id]);
        }
    }
}

header('Location: liberar_locacoes.php');
exit();