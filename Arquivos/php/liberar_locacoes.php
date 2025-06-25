<?php
session_start();
require_once 'conexao.php';

// Deletar locações liberadas expiradas há mais de 1 hora
$pdo->prepare("
    DELETE FROM locacoes_pendentes
    WHERE status = 'liberado'
      AND data_expiracao IS NOT NULL
      AND data_expiracao < NOW() - INTERVAL 1 HOUR
")->execute();

$fk_cargo = $_SESSION['fk_cargo'] ?? null;
$nomeCargo = '';
$nome = $_SESSION['adm'] ?? 'Funcionário';

// Buscar locações pendentes
$stmt = $pdo->prepare("
    SELECT l.id, u.nome_user, j.nome_jogo, l.data_pedido 
    FROM locacoes_pendentes l
    JOIN usuario u ON l.usuario_id = u.pk_usuario
    JOIN jogo j ON l.jogo_id = j.pk_jogo
    WHERE l.status = 'pendente'
    ORDER BY l.data_pedido ASC
");
$stmt->execute();
$locacoes_pendentes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta para trazer locações liberadas, usuários e jogos
$stmt = $pdo->prepare("
    SELECT l.id AS locacao_id, u.pk_usuario, u.nome_user, j.pk_jogo, j.nome_jogo, l.data_expiracao
    FROM locacoes_pendentes l
    JOIN usuario u ON l.usuario_id = u.pk_usuario
    JOIN jogo j ON l.jogo_id = j.pk_jogo
    WHERE l.status = 'liberado'
    ORDER BY l.data_expiracao ASC
");
$stmt->execute();
$locacoes_liberadas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Liberação de Locações</title>
    <style>
            body {
                background-color: #12002b;
                font-family: 'Motiva Sans', 'Segoe UI', sans-serif;
                color: #f0e6ff;
                margin: 0;
                padding: 20px;
                min-height: 100vh;
            }

            h2 {
                text-align: center;
                color: #c084fc;
                margin-bottom: 30px;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            }

            table {
                width: 100%;
                border-collapse: collapse;
                background: #1e1b2e;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6);
                border: 1px solid #5d3bad;
                border-radius: 12px;
                overflow: hidden;
            }

            th, td {
                padding: 14px 16px;
                text-align: left;
            }

            th {
                background-color: #2e2152;
                color: #c084fc;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            tr:nth-child(even) {
                background-color: #252836;
            }

            tr:hover {
                background-color: #322f4c;
            }

            .btn {
                padding: 8px 16px;
                border: none;
                border-radius: 8px;
                color: #fff;
                font-weight: 600;
                cursor: pointer;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
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

            .alert {
                background: #2e2152;
                border: 1px solid #7a5af5;
                color: #e9d5ff;
                padding: 15px;
                border-radius: 8px;
                max-width: 500px;
                margin: 20px auto;
                text-align: center;
            }

            form {
                display: inline;
            }

            .back-link {
                display: block;
                text-align: center;
                margin-top: 30px;
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

            /* Modal estilos */
            #modalAlterarTempo {
                display: none;
                position: fixed;
                top:0; left:0; width:100%; height:100%;
                background: rgba(0,0,0,0.7);
                justify-content: center;
                align-items: center;
                z-index: 9999;
            }

            #modalAlterarTempo > div {
                background: #1e1b2e;
                padding: 20px;
                border-radius: 12px;
                max-width: 400px;
                width: 90%;
                color: #f0e6ff;
                position: relative;
            }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Pedidos de Locação Pendentes</h2>
        <?php if (count($locacoes_pendentes) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Jogo</th>
                    <th>Data do Pedido</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($locacoes_pendentes as $loc): ?>
                <tr>
                    <td><?= htmlspecialchars($loc['nome_user']) ?></td>
                    <td><?= htmlspecialchars($loc['nome_jogo']) ?></td>
                    <td><?= htmlspecialchars($loc['data_pedido']) ?></td>
                    <td>
                        <form method="post" action="processar_liberacao.php" style="display:inline;">
                            <input type="hidden" name="locacao_id" value="<?= $loc['id'] ?>">
                            <input type="datetime-local" name="data_expiracao" required style="margin-right: 8px;" />
                            <button type="submit" name="acao" value="liberar" class="btn btn-success btn-sm">Liberar</button>
                        </form>
                        <form method="post" action="processar_liberacao.php" style="display:inline;">
                            <input type="hidden" name="locacao_id" value="<?= $loc['id'] ?>">
                            <button type="submit" name="acao" value="recusar" class="btn btn-danger btn-sm">Recusar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="alert alert-info">Nenhum pedido de locação pendente.</div>
        <?php endif; ?>
        <a href="adm.php" class="back-link">Voltar</a>
    </div>
    
    <h2>Jogos Locados e Usuários</h2>
    <?php if (count($locacoes_liberadas) > 0): ?>
    <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse; margin-bottom: 2rem;">
        <thead>
            <tr style="background-color:#5d3bad; color:#fff;">
                <th>Usuário</th>
                <th>Jogo</th>
                <th>Data de Expiração</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($locacoes_liberadas as $loc): ?>
                <tr>
                    <td><?= htmlspecialchars($loc['nome_user']) ?></td>
                    <td><?= htmlspecialchars($loc['nome_jogo']) ?></td>
                    <td><?= htmlspecialchars($loc['data_expiracao']) ?></td>
                    <td>
                        <button 
                            class="btn-alterar-tempo" 
                            data-locacao-id="<?= $loc['locacao_id'] ?>" 
                            data-usuario="<?= htmlspecialchars($loc['nome_user']) ?>"
                            data-jogo="<?= htmlspecialchars($loc['nome_jogo']) ?>"
                            data-expiracao="<?= $loc['data_expiracao'] ?>"
                        >Alterar Tempo</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p style="text-align: center;">Nenhum jogo liberado encontrado.</p>
    <?php endif; ?>

    <!-- Modal para alterar tempo -->
    <div id="modalAlterarTempo">
        <div>
            <h3>Alterar Tempo de Locação</h3>
            <form id="formAlterarTempo" method="post" action="processar_alteracao_tempo.php">
                <input type="hidden" name="locacao_id" id="locacao_id" />
                <p><strong>Usuário:</strong> <span id="modalUsuario"></span></p>
                <p><strong>Jogo:</strong> <span id="modalJogo"></span></p>
                <label for="nova_data_expiracao">Nova data e hora de expiração:</label>
                <input type="datetime-local" name="nova_data_expiracao" id="nova_data_expiracao" required style="width:100%; padding:8px; margin-top:6px; border-radius:6px; border:none;" />
                <div style="margin-top:15px; display:flex; justify-content:flex-end; gap:10px;">
                    <button type="submit" class="btn btn-success">Salvar</button>
                    <button type="button" id="btnCancelar" class="btn btn-danger">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

<script>
document.querySelectorAll('.btn-alterar-tempo').forEach(btn => {
    btn.addEventListener('click', () => {
        const locacaoId = btn.getAttribute('data-locacao-id');
        const usuario = btn.getAttribute('data-usuario');
        const jogo = btn.getAttribute('data-jogo');
        const expiracao = btn.getAttribute('data-expiracao');

        document.getElementById('locacao_id').value = locacaoId;
        document.getElementById('modalUsuario').textContent = usuario;
        document.getElementById('modalJogo').textContent = jogo;

        // Ajusta formato para datetime-local (YYYY-MM-DDTHH:mm)
        const dtLocal = expiracao.replace(' ', 'T').slice(0,16);
        document.getElementById('nova_data_expiracao').value = dtLocal;

        document.getElementById('modalAlterarTempo').style.display = 'flex';
    });
});

document.getElementById('btnCancelar').addEventListener('click', () => {
    document.getElementById('modalAlterarTempo').style.display = 'none';
});

// Fecha modal clicando fora da área do form
document.getElementById('modalAlterarTempo').addEventListener('click', e => {
    if (e.target === document.getElementById('modalAlterarTempo')) {
        document.getElementById('modalAlterarTempo').style.display = 'none';
    }
});
</script>
</body>
</html>
