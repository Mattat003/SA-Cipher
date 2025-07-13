<?php
session_start();
require_once 'conexao.php'; // Certifique-se de que conexao.php está configurado corretamente

// Verificar se o usuário está logado e tem permissão de administrador/funcionário
if (!isset($_SESSION['tipo']) || ($_SESSION['tipo'] !== 'adm' && $_SESSION['tipo'] !== 'funcionario')) {
    $_SESSION['mensagem'] = "Acesso negado. Você não tem permissão para esta ação.";
    $_SESSION['tipo_mensagem'] = "erro";
    header("Location: login.php"); // Ou para uma página de erro/dashboard do admin
    exit();
}

if (isset($_POST['locacao_id'], $_POST['acao'])) {
    $locacao_id = $_POST['locacao_id'];
    $acao = $_POST['acao'];
    $mensagem = '';
    $tipo_mensagem = '';

    try {
        $pdo->beginTransaction(); // Inicia a transação

        // Busca a locação para garantir que ela exista
        $stmt = $pdo->prepare("SELECT * FROM locacoes_pendentes WHERE id = :id");
        $stmt->execute([':id' => $locacao_id]);
        $locacao = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$locacao) {
            $mensagem = "Locação não encontrada.";
            $tipo_mensagem = "erro";
            $pdo->rollBack();
        } else {
            if ($acao === 'liberar') {
                // Ao liberar, apenas atualizamos o status e a data de liberação.
                // As datas de início, expiração, duração e valor já estão corretas
                // na tabela locacoes_pendentes, definidas pelo usuário em pagar_locacao.php.
                $stmt = $pdo->prepare("
                    UPDATE locacoes_pendentes
                    SET status = 'liberado',
                        data_liberacao = NOW()
                    WHERE id = :id
                ");
                $stmt->execute([
                    ':id' => $locacao_id
                ]);

                // Busca dados do jogo para adicionar à biblioteca do usuário
                $stmt = $pdo->prepare("SELECT nome_jogo, imagem_jogo, url_jogo FROM jogo WHERE pk_jogo = :jogo_id");
                $stmt->execute([':jogo_id' => $locacao['jogo_id']]);
                $jogo = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($jogo) {
                    // Adiciona o jogo à biblioteca do usuário, se ainda não estiver (INSERT IGNORE evita duplicatas)
                    $stmt = $pdo->prepare("
                        INSERT IGNORE INTO biblioteca_usuario (usuario_id, jogo_id, nome_jogo, imagem_jogo, url_jogo)
                        VALUES (:usuario_id, :jogo_id, :nome_jogo, :imagem_jogo, :url_jogo)
                    ");
                    $stmt->execute([
                        ':usuario_id' => $locacao['usuario_id'],
                        ':jogo_id' => $locacao['jogo_id'],
                        ':nome_jogo' => $jogo['nome_jogo'],
                        ':imagem_jogo' => $jogo['imagem_jogo'],
                        ':url_jogo' => $jogo['url_jogo'] ?? null // url_jogo pode ser nulo
                    ]);
                }

                $mensagem = "Locação ID {$locacao_id} liberada com sucesso e jogo adicionado à biblioteca do usuário!";
                $tipo_mensagem = "sucesso";
                $pdo->commit(); // Confirma a transação

            } elseif ($acao === 'recusar') {
                // Ação de recusar locação
                $stmt = $pdo->prepare("UPDATE locacoes_pendentes SET status = 'recusado' WHERE id = :id");
                $stmt->execute([':id' => $locacao_id]);

                $mensagem = "Locação ID {$locacao_id} recusada.";
                $tipo_mensagem = "sucesso";
                $pdo->commit(); // Confirma a transação

            } elseif ($acao === 'alterar_tempo') {
                // Esta é a lógica existente para alterar a data de expiração,
                // que pode ser usada se o administrador precisar ajustar o tempo.
                $nova_data_expiracao = $_POST['data_expiracao'] ?? null;

                if (!$nova_data_expiracao) {
                    $mensagem = "Nova data de expiração não fornecida para alteração.";
                    $tipo_mensagem = "erro";
                    $pdo->rollBack();
                } else {
                    // Converte formato datetime-local para MySQL datetime
                    $nova_data_expiracao_mysql = str_replace("T", " ", $nova_data_expiracao) . ":00";

                    // Recalcula a duração baseada na data de início original da locação e a nova data de expiração
                    try {
                        $inicio_dt_original = new DateTime($locacao['data_inicio']); // Usa a data de início original
                        $fim_dt_nova = new DateTime($nova_data_expiracao_mysql);
                        $intervalo_nova = $inicio_dt_original->diff($fim_dt_nova);
                        $diff_segundos_nova = $intervalo_nova->days * 86400 + $intervalo_nova->h * 3600 + $intervalo_nova->i * 60 + $intervalo_nova->s;
                        $duracao_horas_recalculada_nova = $diff_segundos_nova / 3600;
                    } catch (Exception $e) {
                        $mensagem = "Erro ao recalcular duração (alterar tempo): " . $e->getMessage();
                        $tipo_mensagem = "erro";
                        $pdo->rollBack();
                        goto end_processing;
                    }

                    $stmt = $pdo->prepare("
                        UPDATE locacoes_pendentes
                        SET data_expiracao = :nova_data_expiracao,
                            duracao_horas = :duracao_horas_recalc
                        WHERE id = :id AND status = 'liberado'
                    ");
                    $stmt->execute([
                        ':nova_data_expiracao' => $nova_data_expiracao_mysql,
                        ':duracao_horas_recalc' => $duracao_horas_recalculada_nova,
                        ':id' => $locacao_id
                    ]);

                    if ($stmt->rowCount() > 0) {
                        $mensagem = "Tempo da locação ID {$locacao_id} alterado com sucesso!";
                        $tipo_mensagem = "sucesso";
                        $pdo->commit(); // Confirma a transação
                    } else {
                        $mensagem = "Não foi possível alterar o tempo da locação. Verifique se a locação está liberada.";
                        $tipo_mensagem = "erro";
                        $pdo->rollBack();
                    }
                }
            } else {
                $mensagem = "Ação desconhecida.";
                $tipo_mensagem = "erro";
                $pdo->rollBack();
            }
        }

    } catch (PDOException $e) {
        $pdo->rollBack(); // Em caso de exceção, desfaz a transação
        $mensagem = "Erro no banco de dados: " . $e->getMessage();
        $tipo_mensagem = "erro";
    }

    end_processing: // Label para o goto
    // Armazena a mensagem na sessão para exibir na página de origem
    $_SESSION['mensagem'] = $mensagem;
    $_SESSION['tipo_mensagem'] = $tipo_mensagem;
    header("Location: liberar_locacoes.php");
    exit();
} else {
    // Se a requisição não for POST ou não tiver os parâmetros necessários
    $_SESSION['mensagem'] = "Requisição inválida.";
    $_SESSION['tipo_mensagem'] = "erro";
    header("Location: liberar_locacoes.php");
    exit();
}
?>