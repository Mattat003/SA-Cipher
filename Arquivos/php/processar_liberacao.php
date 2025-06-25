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

    if ($locacao && $locacao['status'] === 'pendente') {
        if ($acao === 'liberar') {
            $data_expiracao = $_POST['data_expiracao'] ?? null;

            if (!$data_expiracao) {
                die("Data de expiração não fornecida.");
            }

            // Converte formato datetime-local (ex: 2025-06-25T15:30) para MySQL datetime
            $data_expiracao = str_replace("T", " ", $data_expiracao);

            // Atualiza status e datas da locação
            $stmt = $pdo->prepare("
                UPDATE locacoes_pendentes
                SET status = 'liberado',
                    data_liberacao = NOW(),
                    data_expiracao = :expiracao
                WHERE id = :id
            ");
            $stmt->execute([
                ':expiracao' => $data_expiracao,
                ':id' => $locacao_id
            ]);

            // Busca dados do jogo
            $stmt = $pdo->prepare("SELECT nome_jogo, imagem_jogo, url_jogo FROM jogo WHERE pk_jogo = ?");
            $stmt->execute([$locacao['jogo_id']]);
            $jogo = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($jogo) {
                // Adiciona à biblioteca, se ainda não estiver
                $stmt = $pdo->prepare("
                    INSERT IGNORE INTO biblioteca_usuario (usuario_id, nome_jogo, imagem_jogo, url_jogo, jogo_id)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $locacao['usuario_id'],
                    $jogo['nome_jogo'],
                    $jogo['imagem_jogo'],
                    $jogo['url_jogo'],
                    $locacao['jogo_id']
                ]);
            }

        } elseif ($acao === 'recusar') {
            // Apenas recusa o pedido
            $stmt = $pdo->prepare("UPDATE locacoes_pendentes SET status = 'recusado' WHERE id = ?");
            $stmt->execute([$locacao_id]);
        }
    }
}

// Redireciona de volta
header('Location: liberar_locacoes.php');
exit;
