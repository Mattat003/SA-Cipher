<?php
session_start();
require_once 'conexao.php'; // Certifique-se de que conexao.php está configurado corretamente

if (!isset($_SESSION['pk_usuario'])) {
    header('Location: login.php');
    exit();
}

$pk_usuario = $_SESSION['pk_usuario'];
$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jogo_id = $_POST['jogo_id'] ?? null;
    $nome_jogo = $_POST['nome_jogo'] ?? null;
    $imagem_jogo = $_POST['imagem_jogo'] ?? null;
    $url_jogo = $_POST['url_jogo'] ?? null;

    $data_inicio_str = $_POST['data_inicio'] ?? null; // datetime-local string
    $data_fim_str = $_POST['data_fim'] ?? null;     // datetime-local string

    // Dados de Cartão (para simulação)
    $card_number = $_POST['card_number'] ?? '';
    $card_name = $_POST['card_name'] ?? '';
    $card_expiry = $_POST['card_expiry'] ?? '';
    $card_cvv = $_POST['card_cvv'] ?? '';

    if ($jogo_id && $data_inicio_str && $data_fim_str) {
        // Converte as strings para objetos DateTime para cálculo
        try {
            $data_inicio = new DateTime($data_inicio_str);
            $data_fim = new DateTime($data_fim_str);
        } catch (Exception $e) {
            $mensagem = "Erro nas datas fornecidas: " . $e->getMessage();
            $tipo_mensagem = "erro";
            goto end_processing;
        }

        // Calcula a diferença em horas
        $interval = $data_inicio->diff($data_fim);
        $horas = $interval->days * 24 + $interval->h + ($interval->i / 60); // Considera minutos
        $valor_por_hora = 2.00; // R$2.00 por hora
        $valor_total = round($horas * $valor_por_hora, 2);

        // Verifica se a duração é válida (maior que zero)
        if ($horas <= 0) {
            $mensagem = "A duração da locação deve ser de pelo menos alguns minutos.";
            $tipo_mensagem = "erro";
            goto end_processing;
        }

        try {
            $pdo->beginTransaction();

            // Insere a locação na tabela locacoes_pendentes
            // O status inicial é 'pago_aguardando_admin' para que o admin possa liberar
            $stmt = $pdo->prepare("
                INSERT INTO locacoes_pendentes (usuario_id, jogo_id, data_inicio, data_expiracao, duracao_horas, valor_total, status)
                VALUES (:usuario_id, :jogo_id, :data_inicio, :data_expiracao, :duracao_horas, :valor_total, 'pago_aguardando_admin')
            ");
            $stmt->execute([
                ':usuario_id' => $pk_usuario,
                ':jogo_id' => $jogo_id,
                ':data_inicio' => $data_inicio->format('Y-m-d H:i:s'),
                ':data_expiracao' => $data_fim->format('Y-m-d H:i:s'),
                ':duracao_horas' => $horas,
                ':valor_total' => $valor_total
            ]);

            $pdo->commit();
            $mensagem = "Pagamento simulado realizado com sucesso! Sua locação está aguardando liberação do administrador.";
            $tipo_mensagem = "sucesso";

        } catch (PDOException $e) {
            $pdo->rollBack();
            $mensagem = "Erro ao processar o pagamento: " . $e->getMessage();
            $tipo_mensagem = "erro";
        }
    } else {
        $mensagem = "Dados de locação incompletos.";
        $tipo_mensagem = "erro";
    }
} else {
    $mensagem = "Método de requisição inválido.";
    $tipo_mensagem = "erro";
}

end_processing:
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Status do Pagamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #12002b; /* Roxo escuro */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .container-custom {
            background-color: #1e1b2e; /* Roxo mais claro para o container */
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6); /* Sombra mais intensa */
            padding: 30px;
            max-width: 600px;
            width: 100%;
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 20px;
            color: #f0e6ff; /* Cor do texto claro */
            border: 1px solid #5d3bad; /* Borda roxa */
        }
        .message-box {
            padding: 25px;
            border-radius: 12px;
            font-size: 1.1em;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
            animation: fadeIn 0.5s ease-out;
        }
        .message-box.sucesso {
            background-color: #e9d5ff; /* Roxo claro para sucesso */
            color: #510d96; /* Roxo escuro para texto */
            border: 1px solid #7a5af5; /* Borda roxa vibrante */
        }
        .message-box.erro {
            background-color: #ffcccc; /* Vermelho claro para erro */
            color: #dc3545; /* Vermelho escuro para texto */
            border: 1px solid #dc3545;
        }
        .message-box .icon {
            font-size: 2em;
        }
        .game-details {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-top: 20px;
            padding: 15px;
            background-color: #2e2152; /* Fundo da informação do jogo */
            border-radius: 10px;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.3);
            text-align: left;
            border: 1px solid #7a5af5; /* Borda roxa */
        }
        .game-details img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        .game-details div {
            flex-grow: 1;
        }
        .game-details h3 {
            margin: 0;
            color: #e9d5ff; /* Título do jogo claro */
            font-weight: 500;
        }
        .game-details p {
            margin: 5px 0 0;
            color: #f0e6ff; /* Texto da informação do jogo */
            font-size: 0.9em;
        }
        .total-value {
            font-size: 1.4em;
            font-weight: 700;
            color: #c084fc; /* Roxo vibrante para o valor total */
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px dashed #5d3bad; /* Borda tracejada roxa */
        }
        .btn-return {
            background-color: #510d96; /* Roxo principal para botões de ação */
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            margin: 0 5px; /* Espaçamento entre os botões */
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .btn-return:hover {
            background-color: #7a5af5; /* Roxo mais claro no hover */
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(81, 13, 150, 0.3);
        }
        /* Estilo específico para o botão "Alugar Outro Jogo" */
        .btn-return.secondary-button {
            background-color: #7a5af5; /* Roxo mais claro */
        }
        .btn-return.secondary-button:hover {
            background-color: #510d96; /* Roxo mais escuro no hover */
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 576px) {
            .container-custom {
                padding: 20px;
            }
            .game-details {
                flex-direction: column;
                text-align: center;
            }
            .btn-group {
                flex-direction: column;
                gap: 10px;
            }
            .btn-return {
                margin: 5px 0;
            }
        }
    </style>
    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container-custom">
        <?php if ($mensagem): ?>
            <div class="message-box <?= htmlspecialchars($tipo_mensagem) ?>">
                <?php if ($tipo_mensagem === 'sucesso'): ?>
                    <span class="icon"><i class="fas fa-check-circle"></i></span>
                <?php else: ?>
                    <span class="icon"><i class="fas fa-times-circle"></i></span>
                <?php endif; ?>
                <p><?= htmlspecialchars($mensagem) ?></p>
            </div>
        <?php endif; ?>

        <?php if ($tipo_mensagem === 'sucesso' && isset($jogo_id)): // Exibe os detalhes da locação apenas em caso de sucesso ?>
            <div class="game-details">
                <img src="<?= htmlspecialchars($imagem_jogo ?? 'https://placehold.co/80x80/cccccc/333333?text=Sem+Imagem') ?>" alt="<?= htmlspecialchars($nome_jogo) ?>">
                <div>
                    <h3><?= htmlspecialchars($nome_jogo) ?></h3>
                    <p>Início da Locação: <?= htmlspecialchars($data_inicio->format('d/m/Y H:i')) ?></p>
                    <p>Fim da Locação: <?= htmlspecialchars($data_fim->format('d/m/Y H:i')) ?></p>
                    <p>Duração Estimada: <?= number_format($horas, 2) ?> horas</p>
                    <p class="total-value">Total Pago: R$ <?= number_format($valor_total, 2, ',', '.') ?></p>
                </div>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-center mt-4 btn-group">
            <a href="index.php" class="btn-return">Voltar para Minha Biblioteca</a>
            <a href="jogos.php" class="btn-return secondary-button">Alugar Outro Jogo</a>
        </div>
    </div>
</body>
</html>
