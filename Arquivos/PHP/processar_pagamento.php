<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['pk_usuario'])) {
    die("Acesso negado.");
}

$mensagem = '';

if (isset($_POST['locacao_id'], $_POST['usuario_id'], $_POST['jogo_id'], $_POST['nome_jogo'], $_POST['nome_user'], $_POST['data_inicio'], $_POST['data_expiracao'])) {
    $locacao_id = $_POST['locacao_id'];
    $usuario_id = $_POST['usuario_id'];
    $jogo_id = $_POST['jogo_id'];
    $nome_jogo = $_POST['nome_jogo'];
    $nome_user = $_POST['nome_user'];
    $data_inicio_str = $_POST['data_inicio'];
    $data_expiracao_str = $_POST['data_expiracao'];

    // Converte para objetos DateTime
    $data_inicio = new DateTime($data_inicio_str);
    $data_expiracao = new DateTime($data_expiracao_str);

    // Calcula a diferença em horas
    $interval = $data_inicio->diff($data_expiracao);
    $horas = $interval->days * 24 + $interval->h + ($interval->i / 60); // Considera minutos
    $valor_por_hora = 2.00; // R$2.00 por hora
    $valor_total = round($horas * $valor_por_hora, 2);

    // Formata as datas para o MySQL
    $data_inicio_mysql = $data_inicio->format('Y-m-d H:i:s');
    $data_expiracao_mysql = $data_expiracao->format('Y-m-d H:i:s');

    if (isset($_POST['confirmar_pagamento'])) {
        // Simula o processamento do pagamento
        // Em um sistema real, aqui você integraria com um gateway de pagamento.
        // Por simplicidade, assumimos que o pagamento é "bem-sucedido" aqui.

        try {
            $pdo->beginTransaction();

            // Atualiza o status da locação para 'liberado'
            $stmt = $pdo->prepare("
                UPDATE locacoes_pendentes
                SET status = 'liberado',
                    data_liberacao = NOW(),
                    data_inicio = :data_inicio,
                    data_expiracao = :data_expiracao,
                    valor_total = :valor_total
                WHERE id = :locacao_id AND status = 'pendente'
            ");
            $stmt->execute([
                ':data_inicio' => $data_inicio_mysql,
                ':data_expiracao' => $data_expiracao_mysql,
                ':valor_total' => $valor_total,
                ':locacao_id' => $locacao_id
            ]);

            // Adiciona o jogo à biblioteca do usuário
            $stmt = $pdo->prepare("SELECT nome_jogo, imagem_jogo, url_jogo FROM jogo WHERE pk_jogo = ?");
            $stmt->execute([$jogo_id]);
            $jogo_info = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($jogo_info) {
                $stmt = $pdo->prepare("
                    INSERT IGNORE INTO biblioteca_usuario (usuario_id, jogo_id, nome_jogo, imagem_jogo, url_jogo)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $usuario_id,
                    $jogo_id,
                    $jogo_info['nome_jogo'],
                    $jogo_info['imagem_jogo'],
                    $jogo_info['url_jogo']
                ]);
            }
            $pdo->commit();
            $mensagem = "Locação liberada com sucesso e pagamento processado!";
            header('Location: liberar_locacoes.php?msg=' . urlencode($mensagem));
            exit();

        } catch (PDOException $e) {
            $pdo->rollBack();
            $mensagem = "Erro ao processar a locação: " . $e->getMessage();
        }
    }

} else {
    $mensagem = "Dados da locação não fornecidos.";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Processar Pagamento</title>
    <style>
        body {
            font-family: 'Motiva Sans', sans-serif;
            background-color: #12002b;
            color: #f0e6ff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: #1e1b2e;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6);
            border: 1px solid #5d3bad;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        h2 {
            color: #c084fc;
            margin-bottom: 25px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }
        p {
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        .total-value {
            font-size: 1.6rem;
            color: #9cff57;
            font-weight: bold;
            margin: 20px 0;
        }
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
            margin: 0 10px;
        }
        .btn-success {
            background: #510d96;
        }
        .btn-success:hover {
            background: #7a5af5;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            transform: translateY(-1px);
        }
        .btn-danger {
            background: #b42348;
        }
        .btn-danger:hover {
            background: #e11d48;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            transform: translateY(-1px);
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 8px;
            background-color: #2e2152;
            border: 1px solid #7a5af5;
            color: #e9d5ff;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!empty($mensagem) && !isset($_POST['confirmar_pagamento'])): // Display initial payment screen ?>
            <h2>Confirmar Pagamento da Locação</h2>
            <p><strong>Usuário:</strong> <?= htmlspecialchars($nome_user) ?></p>
            <p><strong>Jogo:</strong> <?= htmlspecialchars($nome_jogo) ?></p>
            <p><strong>Início da Locação:</strong> <?= htmlspecialchars($data_inicio->format('d/m/Y H:i')) ?></p>
            <p><strong>Fim da Locação:</strong> <?= htmlspecialchars($data_expiracao->format('d/m/Y H:i')) ?></p>
            <p><strong>Duração Estimada:</strong> <?= number_format($horas, 2) ?> horas</p>
            <p class="total-value">Total a Pagar: R$ <?= number_format($valor_total, 2, ',', '.') ?></p>

            <form method="post" action="">
                <input type="hidden" name="locacao_id" value="<?= htmlspecialchars($locacao_id) ?>">
                <input type="hidden" name="usuario_id" value="<?= htmlspecialchars($usuario_id) ?>">
                <input type="hidden" name="jogo_id" value="<?= htmlspecialchars($jogo_id) ?>">
                <input type="hidden" name="nome_jogo" value="<?= htmlspecialchars($nome_jogo) ?>">
                <input type="hidden" name="nome_user" value="<?= htmlspecialchars($nome_user) ?>">
                <input type="hidden" name="data_inicio" value="<?= htmlspecialchars($data_inicio_str) ?>">
                <input type="hidden" name="data_expiracao" value="<?= htmlspecialchars($data_expiracao_str) ?>">
                <button type="submit" name="confirmar_pagamento" value="1" class="btn btn-success">Confirmar Pagamento</button>
                <a href="liberar_locacoes.php" class="btn btn-danger">Cancelar</a>
            </form>
        <?php else: // Display messages after processing or if data is missing ?>
            <p class="message"><?= htmlspecialchars($mensagem) ?></p>
            <a href="liberar_locacoes.php" class="btn btn-success">Voltar</a>
        <?php endif; ?>
    </div>
</body>
</html>