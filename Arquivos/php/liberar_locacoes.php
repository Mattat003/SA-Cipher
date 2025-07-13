<?php
session_start();
require_once 'conexao.php'; // Certifique-se de que conexao.php está configurado corretamente

// Verificar se o usuário está logado e tem permissão de administrador/funcionário
if (!isset($_SESSION['tipo']) || ($_SESSION['tipo'] !== 'adm' && $_SESSION['tipo'] !== 'funcionario')) {
    $_SESSION['mensagem'] = "Acesso negado. Você não tem permissão para esta ação.";
    $_SESSION['tipo_mensagem'] = "erro";
    header("Location: login.php");
    exit();
}

$fk_cargo = $_SESSION['fk_cargo'] ?? null;
$nomeCargo = '';
$nome = $_SESSION['adm'] ?? $_SESSION['usuario']; // Pega o nome do admin/func ou usuário comum

// Busca o nome do cargo na tabela cargo
if ($fk_cargo) {
    $stmt = $pdo->prepare("SELECT nome_cargo FROM cargo WHERE pk_cargo = :id");
    $stmt->bindParam(":id", $fk_cargo, PDO::PARAM_INT);
    $stmt->execute();
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $nomeCargo = $row['nome_cargo'];
    }
}

// Deletar locações liberadas expiradas há mais de 1 hora
// Isso ajuda a limpar a tabela de locações antigas e expiradas
$pdo->prepare("
    DELETE FROM locacoes_pendentes
    WHERE status = 'liberado'
      AND data_expiracao IS NOT NULL
      AND data_expiracao < NOW() - INTERVAL 1 HOUR
")->execute();


// Buscar locações pendentes (aquelas com status 'pago_aguardando_admin' ou 'pendente')
// Adicionado data_inicio e data_expiracao para exibir no admin
$stmt = $pdo->prepare("
    SELECT l.id, u.nome_user, j.nome_jogo, l.data_pedido, l.valor_total, l.duracao_horas, l.data_inicio, l.data_expiracao
    FROM locacoes_pendentes l
    JOIN usuario u ON l.usuario_id = u.pk_usuario
    JOIN jogo j ON l.jogo_id = j.pk_jogo
    WHERE l.status IN ('pendente', 'pago_aguardando_admin')
    ORDER BY l.data_pedido ASC
");
$stmt->execute();
$locacoes_pendentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta para trazer locações liberadas, usuários e jogos
$stmt = $pdo->prepare("
    SELECT l.id AS locacao_id, u.pk_usuario, u.nome_user, j.pk_jogo, j.nome_jogo, l.data_expiracao, l.duracao_horas
    FROM locacoes_pendentes l
    JOIN usuario u ON l.usuario_id = u.pk_usuario
    JOIN jogo j ON l.jogo_id = j.pk_jogo
    WHERE l.status = 'liberado'
    ORDER BY l.data_expiracao DESC
");
$stmt->execute();
$locacoes_liberadas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$mensagem = $_SESSION['mensagem'] ?? '';
$tipo_mensagem = $_SESSION['tipo_mensagem'] ?? '';
unset($_SESSION['mensagem']); // Limpa a mensagem após exibir
unset($_SESSION['tipo_mensagem']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Locações - Painel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Motiva Sans', sans-serif;
            background-color: #12002b; /* Fundo escuro */
            color: #f0e6ff; /* Texto claro */
            margin: 0;
            padding-top: 60px; /* Espaço para o header */
        }
        header {
            background-color: #1e1b2e; /* Header mais escuro */
            color: #c084fc; /* Título no header */
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        header h2 {
            margin: 0;
            color: #c084fc;
        }
        .logout-btn {
            background-color: #a060e0;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .logout-btn:hover {
            background-color: #be84ff;
        }
        /* Estilo para o botão Voltar */
        .back-btn {
            background-color: #5a6268; /* Um cinza neutro */
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none; /* Para o <a> tag */
            display: inline-block; /* Para o <a> tag */
            line-height: normal; /* Alinha o texto verticalmente */
        }
        .back-btn:hover {
            background-color: #6c757d;
        }
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: #1e1b2e; /* Fundo do container */
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6);
            border: 1px solid #5d3bad;
        }
        h3 {
            color: #c084fc;
            margin-top: 30px;
            margin-bottom: 20px;
            text-align: center;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .table {
            color: #f0e6ff; /* Cor do texto da tabela */
            border-color: #5d3bad; /* Cor das bordas da tabela */
        }
        .table th, .table td {
            border-color: #5d3bad; /* Cor das bordas das células */
            vertical-align: middle;
        }
        .table thead th {
            background-color: #2e2152; /* Fundo do cabeçalho da tabela */
            color: #d0c0f0;
        }
        .table tbody tr:nth-child(even) {
            background-color: #261a40; /* Fundo das linhas pares */
        }
        .table tbody tr:nth-child(odd) {
            background-color: #1e1b2e; /* Fundo das linhas ímpares */
        }
        .btn-action {
            padding: 5px 10px;
            font-size: 0.9em;
            margin-right: 5px;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
        }
        .alert {
            margin-top: 20px;
        }

        /* Estilos do Modal */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1001; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.7); /* Black w/ opacity */
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background-color: #2e2152;
            margin: auto;
            padding: 30px;
            border: 1px solid #5d3bad;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.6);
            color: #f0e6ff;
        }
        .modal-content h4 {
            color: #c084fc;
            margin-bottom: 20px;
            text-align: center;
        }
        .modal-content label {
            display: block;
            margin-bottom: 8px;
            color: #d0c0f0;
        }
        .modal-content input[type="datetime-local"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #5d3bad;
            background-color: #1e1b2e;
            color: #f0e6ff;
            box-sizing: border-box;
        }
        .modal-buttons {
            display: flex;
            justify-content: space-around;
            gap: 10px;
            margin-top: 20px;
        }
        .modal-buttons button {
            flex: 1;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }
        .modal-buttons .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .modal-buttons .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .modal-buttons .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .modal-buttons .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>
</head>
<body>
    <header>
        <div>
            <h2>Painel de Gerenciamento de Locações</h2>
            <span>Bem-vindo, <strong><?= htmlspecialchars($nome) ?></strong>! (<?= htmlspecialchars($nomeCargo ?: 'N/A') ?>)</span>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="adm.php" class="back-btn">Voltar</a> <form action="logout.php" method="post" style="margin:0;">
                <button class="logout-btn" type="submit">Sair</button>
            </form>
        </div>
    </header>

    <div class="container">
        <?php if (!empty($mensagem)): ?>
            <div class="alert alert-<?= $tipo_mensagem === 'sucesso' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($mensagem) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <h3>Locações Pendentes (Aguardando Ação do Admin)</h3>
        <?php if (count($locacoes_pendentes) === 0): ?>
            <p class="text-center text-muted">Não há locações pendentes no momento.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID Locação</th>
                            <th>Usuário</th>
                            <th>Jogo</th>
                            <th>Data do Pedido</th>
                            <th>Valor Total</th>
                            <th>Duração (Horas)</th>
                            <th>Início (Proposto)</th>
                            <th>Fim (Proposto)</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($locacoes_pendentes as $locacao): ?>
                            <tr>
                                <td><?= htmlspecialchars($locacao['id']) ?></td>
                                <td><?= htmlspecialchars($locacao['nome_user']) ?></td>
                                <td><?= htmlspecialchars($locacao['nome_jogo']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($locacao['data_pedido'])) ?></td>
                                <td>R$ <?= number_format($locacao['valor_total'], 2, ',', '.') ?></td>
                                <td><?= number_format($locacao['duracao_horas'], 2) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($locacao['data_inicio'])) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($locacao['data_expiracao'])) ?></td>
                                <td>
                                    <form action="processar_liberacao.php" method="POST" style="display:inline-block;">
                                        <input type="hidden" name="locacao_id" value="<?= $locacao['id'] ?>">
                                        <button type="button" class="btn btn-success btn-action btn-liberar-modal"
                                            data-locacao-id="<?= $locacao['id'] ?>"
                                            data-usuario="<?= htmlspecialchars($locacao['nome_user']) ?>"
                                            data-jogo="<?= htmlspecialchars($locacao['nome_jogo']) ?>"
                                            data-data-inicio="<?= htmlspecialchars($locacao['data_inicio']) ?>"
                                            data-data-fim="<?= htmlspecialchars($locacao['data_expiracao']) ?>">
                                            <i class="fas fa-check"></i> Liberar
                                        </button>
                                        <button type="submit" name="acao" value="recusar" class="btn btn-danger btn-action">
                                            <i class="fas fa-times"></i> Recusar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <h3>Locações Liberadas (Ativas)</h3>
        <?php if (count($locacoes_liberadas) === 0): ?>
            <p class="text-center text-muted">Não há locações liberadas no momento.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID Locação</th>
                            <th>Usuário</th>
                            <th>Jogo</th>
                            <th>Data de Expiração</th>
                            <th>Duração (Horas)</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($locacoes_liberadas as $locacao): ?>
                            <tr>
                                <td><?= htmlspecialchars($locacao['locacao_id']) ?></td>
                                <td><?= htmlspecialchars($locacao['nome_user']) ?></td>
                                <td><?= htmlspecialchars($locacao['nome_jogo']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($locacao['data_expiracao'])) ?></td>
                                <td><?= number_format($locacao['duracao_horas'], 2) ?></td>
                                <td>
                                    <button type="button" class="btn btn-info btn-action btn-alterar-tempo"
                                        data-locacao-id="<?= $locacao['locacao_id'] ?>"
                                        data-usuario="<?= htmlspecialchars($locacao['nome_user']) ?>"
                                        data-jogo="<?= htmlspecialchars($locacao['nome_jogo']) ?>"
                                        data-expiracao="<?= htmlspecialchars($locacao['data_expiracao']) ?>">
                                        <i class="fas fa-clock"></i> Alterar Tempo
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div id="modalLiberarLocacao" class="modal">
        <div class="modal-content">
            <h4>Liberar Locação</h4>
            <p><strong>Usuário:</strong> <span id="modalLiberarUsuario"></span></p>
            <p><strong>Jogo:</strong> <span id="modalLiberarJogo"></span></p>
            <p><strong>Data Início Proposta:</strong> <span id="modalLiberarDataInicioProposta"></span></p>
            <p><strong>Data Fim Proposta:</strong> <span id="modalLiberarDataFimProposta"></span></p>

            <form action="processar_liberacao.php" method="POST">
                <input type="hidden" name="locacao_id" id="modalLiberarLocacaoId">
                <input type="hidden" name="acao" value="liberar">
                <div class="form-group">
                    <label for="data_inicio_liberar">Data e Hora de Início (Admin):</label>
                    <input type="datetime-local" id="data_inicio_liberar" name="data_inicio" required>
                </div>
                <div class="form-group">
                    <label for="data_expiracao_liberar">Data e Hora de Expiração (Admin):</label>
                    <input type="datetime-local" id="data_expiracao_liberar" name="data_expiracao" required>
                </div>
                <div class="modal-buttons">
                    <button type="submit" class="btn btn-primary">Confirmar Liberação</button>
                    <button type="button" id="btnCancelarLiberacao" class="btn btn-danger">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalAlterarTempo" class="modal">
        <div class="modal-content">
            <h4>Alterar Tempo de Locação</h4>
            <p><strong>Usuário:</strong> <span id="modalUsuario"></span></p>
            <p><strong>Jogo:</strong> <span id="modalJogo"></span></p>
            <form action="processar_liberacao.php" method="POST">
                <input type="hidden" name="locacao_id" id="locacao_id">
                <input type="hidden" name="acao" value="alterar_tempo">
                <div class="form-group">
                    <label for="nova_data_expiracao">Nova Data e Hora de Expiração:</label>
                    <input type="datetime-local" id="nova_data_expiracao" name="data_expiracao" required>
                </div>
                <div class="modal-buttons">
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    <button type="button" id="btnCancelar" class="btn btn-danger">Cancelar</button>
                </div>
            </form>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Lógica para abrir o modal de Liberar Locação
        document.querySelectorAll('.btn-liberar-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                const locacaoId = btn.getAttribute('data-locacao-id');
                const usuario = btn.getAttribute('data-usuario');
                const jogo = btn.getAttribute('data-jogo');
                const dataInicioProposta = btn.getAttribute('data-data-inicio'); // Data de início do cliente
                const dataFimProposta = btn.getAttribute('data-data-fim');     // Data de fim do cliente

                document.getElementById('modalLiberarLocacaoId').value = locacaoId;
                document.getElementById('modalLiberarUsuario').textContent = usuario;
                document.getElementById('modalLiberarJogo').textContent = jogo;
                
                // Exibe as datas propostas pelo cliente em formato legível
                document.getElementById('modalLiberarDataInicioProposta').textContent = new Date(dataInicioProposta).toLocaleString('pt-BR', { dateStyle: 'short', timeStyle: 'short' });
                document.getElementById('modalLiberarDataFimProposta').textContent = new Date(dataFimProposta).toLocaleString('pt-BR', { dateStyle: 'short', timeStyle: 'short' });

                // Preenche os campos do modal com as datas propostas pelo cliente, formatando para datetime-local
                document.getElementById('data_inicio_liberar').value = dataInicioProposta.slice(0, 16); // Remove segundos se houver
                document.getElementById('data_expiracao_liberar').value = dataFimProposta.slice(0, 16); // Remove segundos se houver
                
                document.getElementById('modalLiberarLocacao').style.display = 'flex';
            });
        });

        // Lógica para fechar o modal de Liberar Locação
        document.getElementById('btnCancelarLiberacao').addEventListener('click', () => {
            document.getElementById('modalLiberarLocacao').style.display = 'none';
        });

        // Fecha modal de Liberar Locação clicando fora da área do form
        document.getElementById('modalLiberarLocacao').addEventListener('click', e => {
            if (e.target === document.getElementById('modalLiberarLocacao')) {
                document.getElementById('modalLiberarLocacao').style.display = 'none';
            }
        });


        // Lógica para abrir o modal de Alterar Tempo
        document.querySelectorAll('.btn-alterar-tempo').forEach(btn => {
            btn.addEventListener('click', () => {
                const locacaoId = btn.getAttribute('data-locacao-id');
                const usuario = btn.getAttribute('data-usuario');
                const jogo = btn.getAttribute('data-jogo');
                const expiracao = btn.getAttribute('data-expiracao'); // Já vem no formato do DB (YYYY-MM-DD HH:MM:SS)

                document.getElementById('locacao_id').value = locacaoId;
                document.getElementById('modalUsuario').textContent = usuario;
                document.getElementById('modalJogo').textContent = jogo;

                // Ajusta formato para datetime-local (YYYY-MM-DDTHH:mm)
                // Remove os segundos e substitui espaço por 'T'
                const dtLocal = expiracao.slice(0, 16).replace(' ', 'T');
                document.getElementById('nova_data_expiracao').value = dtLocal;

                document.getElementById('modalAlterarTempo').style.display = 'flex';
            });
        });

        // Lógica para fechar o modal de Alterar Tempo
        document.getElementById('btnCancelar').addEventListener('click', () => {
            document.getElementById('modalAlterarTempo').style.display = 'none';
        });

        // Fecha modal de Alterar Tempo clicando fora da área do form
        document.getElementById('modalAlterarTempo').addEventListener('click', e => {
            if (e.target === document.getElementById('modalAlterarTempo')) {
                document.getElementById('modalAlterarTempo').style.display = 'none';
            }
        });
    </script>
</body>
</html>