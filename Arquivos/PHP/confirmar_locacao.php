<?php
session_start();
require_once 'conexao.php'; // Certifique-se de que conexao.php está configurado corretamente

if (!isset($_SESSION['pk_usuario'])) {
    header('Location: login.php');
    exit();
}

$jogo_id = $_GET['jogo_id'] ?? null;
$jogo = null;

if ($jogo_id) {
    // Busca informações do jogo no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM jogo WHERE pk_jogo = :jogo_id");
    $stmt->execute([':jogo_id' => $jogo_id]);
    $jogo = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$jogo) {
    // Redireciona ou mostra erro se o jogo não for encontrado ou ID não fornecido
    header('Location: jogos.php');
    exit();
}

$valor_por_hora = 2.00; // Define o valor da locação por hora

// Define o fuso horário para garantir que as datas e horas sejam manipuladas corretamente
// Use o fuso horário do seu servidor ou defina um específico (ex: America/Sao_Paulo)
date_default_timezone_set('America/Sao_Paulo'); 

// Calcula a data e hora mínima de início (agora)
$min_inicio = date('Y-m-d\\TH:i');

// Calcula a data e hora mínima para o fim (1 hora após o início, como sugestão mínima)
$min_fim = date('Y-m-d\\TH:i', strtotime('+1 hour'));

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Confirmar Locação - <?= htmlspecialchars($jogo['nome_jogo']) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" />
    <style>
        body {
            font-family: 'Inter', sans-serif; /* Mantendo Inter, mas você pode mudar para 'Motiva Sans' se tiver */
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
            max-width: 700px;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 25px;
            color: #f0e6ff; /* Cor do texto claro */
            border: 1px solid #5d3bad; /* Borda roxa */
        }
        .header-section {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #5d3bad; /* Borda roxa */
        }
        .header-section h2 {
            color: #c084fc; /* Roxo vibrante para o título */
            font-weight: 600;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }
        .header-section p {
            color: #e9d5ff; /* Texto de parágrafo suave */
        }
        .game-info {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #2e2152; /* Fundo da informação do jogo */
            border-radius: 10px;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.3);
            border: 1px solid #7a5af5; /* Borda roxa */
        }
        .game-info img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        .game-info div h3 {
            margin: 0;
            color: #e9d5ff; /* Título do jogo claro */
            font-weight: 500;
        }
        .game-info div p {
            margin: 5px 0 0;
            color: #f0e6ff; /* Texto da informação do jogo */
            font-size: 0.9em;
        }
        .form-section .form-label {
            font-weight: 500;
            color: #f0e6ff; /* Labels do formulário claras */
            margin-bottom: 5px;
        }
        .form-section .form-control {
            border-radius: 8px;
            border: 1px solid #7a5af5; /* Borda do input */
            padding: 10px 15px;
            background-color: #2e2152; /* Fundo do input */
            color: #f0e6ff; /* Cor do texto do input */
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.2);
        }
        .form-section .form-control:focus {
            border-color: #c084fc; /* Roxo vibrante para foco */
            box-shadow: 0 0 0 0.25rem rgba(192, 132, 252, 0.25);
            background-color: #2e2152; /* Mantém o fundo no foco */
            color: #f0e6ff; /* Mantém a cor do texto no foco */
        }
        .summary-section {
            background-color: #2e2152; /* Fundo da seção de resumo */
            border: 1px solid #7a5af5; /* Borda roxa vibrante */
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            color: #f0e6ff; /* Texto claro */
        }
        .summary-section p {
            margin-bottom: 8px;
            font-size: 1.05em;
        }
        .summary-section strong {
            color: #c084fc; /* Roxo vibrante para valores importantes */
        }
        .total-value {
            font-size: 1.4em;
            font-weight: 700;
            color: #c084fc; /* Roxo vibrante para o valor total */
            text-align: right;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px dashed #5d3bad; /* Borda tracejada roxa */
        }
        .btn-primary, .btn-secondary {
            border-radius: 8px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white; /* Texto branco para contraste nos botões */
            border: none; /* Remove borda padrão do bootstrap */
        }
        .btn-primary {
            background-color: #510d96; /* Roxo principal para o botão */
        }
        .btn-primary:hover {
            background-color: #7a5af5; /* Roxo mais claro no hover */
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(81, 13, 150, 0.3);
        }
        .btn-secondary {
            background-color: #7a5af5; /* Roxo mais claro para o botão secundário */
        }
        .btn-secondary:hover {
            background-color: #510d96; /* Roxo mais escuro no hover */
            transform: translateY(-2px);
            box_shadow: 0 4px 8px rgba(122, 90, 245, 0.3);
        }
        .card-input-group {
            margin-bottom: 15px;
        }
        .card-input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #f0e6ff;
        }
        .card-input-group input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #7a5af5;
            border-radius: 8px;
            box-sizing: border-box;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.2);
            background-color: #2e2152;
            color: #f0e6ff;
        }
        .card-input-group input:focus {
            border-color: #c084fc;
            box-shadow: 0 0 0 0.25rem rgba(192, 132, 252, 0.25);
        }
        .card-details-row {
            display: flex;
            gap: 15px;
        }
        .card-details-row > div {
            flex: 1;
        }
        @media (max-width: 576px) {
            .container-custom {
                padding: 20px;
            }
            .game-info {
                flex-direction: column;
                text-align: center;
            }
            .card-details-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container-custom">
        <div class="header-section">
            <h2>Confirmar Locação</h2>
            <p>Preencha os detalhes para alugar "<?= htmlspecialchars($jogo['nome_jogo']) ?>"</p>
        </div>

        <div class="game-info">
            <img src="<?= htmlspecialchars($jogo['imagem_jogo'] ?? 'https://placehold.co/100x100/cccccc/333333?text=Sem+Imagem') ?>" alt="<?= htmlspecialchars($jogo['nome_jogo']) ?>">
            <div>
                <h3><?= htmlspecialchars($jogo['nome_jogo']) ?></h3>
                <p>Desenvolvedora: <?= htmlspecialchars($jogo['desenvolvedora']) ?></p>
                <p>Valor por hora: R$ <?= number_format($valor_por_hora, 2, ',', '.') ?></p>
            </div>
        </div>

        <form action="pagar_locacao.php" method="post" class="form-section">
            <input type="hidden" name="jogo_id" value="<?= htmlspecialchars($jogo['pk_jogo']) ?>">
            <input type="hidden" name="nome_jogo" value="<?= htmlspecialchars($jogo['nome_jogo']) ?>">
            <input type="hidden" name="imagem_jogo" value="<?= htmlspecialchars($jogo['imagem_jogo']) ?>">
            <input type="hidden" name="url_jogo" value="<?= htmlspecialchars($jogo['url_jogo']) ?>">

            <div class="mb-3">
                <label for="data_inicio" class="form-label">Data e Hora de Início:</label>
                <input type="datetime-local" class="form-control" id="data_inicio" name="data_inicio" required min="<?= $min_inicio ?>">
            </div>

            <div class="mb-3">
                <label for="data_fim" class="form-label">Data e Hora de Fim:</label>
                <input type="datetime-local" class="form-control" id="data_fim" name="data_fim" required min="<?= $min_fim ?>">
            </div>

            <div class="summary-section">
                <p>Duração Estimada: <strong id="duracao_estimada">0.00</strong> horas</p>
                <p class="total-value">Total a Pagar: R$ <span id="valor_total">0,00</span></p>
            </div>

            <div class="card-details-section mt-4">
                <h4 class="text-center mb-3" style="color: #c084fc; font-weight: 600;">Detalhes do Pagamento (Simulação)</h4>
                <div class="card-input-group">
                    <label for="card_number">Número do Cartão:</label>
                    <input type="text" id="card_number" name="card_number" class="form-control" placeholder="XXXX XXXX XXXX XXXX" maxlength="19" required>
                </div>
                <div class="card-input-group">
                    <label for="card_name">Nome no Cartão:</label>
                    <input type="text" id="card_name" name="card_name" class="form-control" placeholder="Nome Completo" required>
                </div>
                <div class="card-details-row">
                    <div class="card-input-group">
                        <label for="card_expiry">Validade (MM/AA):</label>
                        <input type="text" id="card_expiry" name="card_expiry" class="form-control" placeholder="MM/AA" maxlength="5" required>
                    </div>
                    <div class="card-input-group">
                        <label for="card_cvv">CVV:</label>
                        <input type="text" id="card_cvv" name="card_cvv" class="form-control" placeholder="XXX" maxlength="4" required>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Alugar e Pagar</button>
                <a href="jogos.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        const dataInicioInput = document.getElementById('data_inicio');
        const dataFimInput = document.getElementById('data_fim');
        const duracaoEstimadaSpan = document.getElementById('duracao_estimada');
        const valorTotalSpan = document.getElementById('valor_total');
        const valorPorHora = <?= json_encode($valor_por_hora) ?>;

        function calcularDuracaoEValor() {
            const inicioStr = dataInicioInput.value;
            const fimStr = dataFimInput.value;

            if (inicioStr && fimStr) {
                const inicio = new Date(inicioStr);
                const fim = new Date(fimStr);

                if (fim <= inicio) {
                    duracaoEstimadaSpan.textContent = '0.00';
                    valorTotalSpan.textContent = '0,00';
                    return; // Sai da função se a data fim for inválida
                }

                const diffMs = fim - inicio;
                const diffHours = diffMs / (1000 * 60 * 60);

                duracaoEstimadaSpan.textContent = diffHours.toFixed(2);
                valorTotalSpan.textContent = (diffHours * valorPorHora).toFixed(2).replace('.', ',');
            } else {
                duracaoEstimadaSpan.textContent = '0.00';
                valorTotalSpan.textContent = '0,00';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Define a data e hora mínima para o campo de início como o tempo real
            const now = new Date();
            // Ajusta para o fuso horário local para evitar problemas de offset
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            const nowFormatted = now.toISOString().slice(0, 16);
            dataInicioInput.setAttribute('min', nowFormatted);
            
            // Define o valor padrão para a data de início como "agora" se já não estiver preenchido
            if (!dataInicioInput.value) {
                dataInicioInput.value = nowFormatted;
            }

            // Calcula a data de fim padrão (1 hora após a data de início)
            const defaultFim = new Date(new Date(dataInicioInput.value).getTime() + (1 * 60 * 60 * 1000));
            const defaultFimFormatted = defaultFim.toISOString().slice(0, 16);
            if (!dataFimInput.value || new Date(dataFimInput.value) <= new Date(dataInicioInput.value)) {
                dataFimInput.value = defaultFimFormatted;
            }

            calcularDuracaoEValor(); // Calcula valores iniciais
        });

        dataInicioInput.addEventListener('change', () => {
            // Garante que a data de fim seja sempre maior que a data de início
            const inicio = new Date(dataInicioInput.value);
            const fim = new Date(dataFimInput.value);
            if (fim <= inicio) {
                const newFim = new Date(inicio.getTime() + (1 * 60 * 60 * 1000)); // 1 hora depois
                dataFimInput.value = newFim.toISOString().slice(0, 16);
            }
            dataFimInput.setAttribute('min', dataInicioInput.value); // Define o min do fim como o início
            calcularDuracaoEValor();
        });

        dataFimInput.addEventListener('change', calcularDuracaoEValor);


        // Card number formatting
        document.getElementById('card_number').addEventListener('input', function (e) {
            e.target.value = e.target.value.replace(/\D/g, '').replace(/(\d{4})(?=\d)/g, '$1 ');
        });

        // Card expiry formatting MM/AA
        document.getElementById('card_expiry').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 2) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4);
            }
            e.target.value = value;
        });
    </script>
</body>
</html>
