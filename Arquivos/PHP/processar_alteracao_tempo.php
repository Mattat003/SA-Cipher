<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;
$nomeCargo = '';
$nome = $_SESSION['adm'] ?? 'Funcionário';

if (isset($_POST['locacao_id'], $_POST['nova_data_inicio'], $_POST['nova_data_expiracao'])) {
    $locacao_id = $_POST['locacao_id'];
    $nova_data_inicio_str = $_POST['nova_data_inicio'];
    $nova_data_expiracao_str = $_POST['nova_data_expiracao'];

    // Converte formato datetime-local para objetos DateTime
    $nova_data_inicio = new DateTime(str_replace("T", " ", $nova_data_inicio_str));
    $nova_data_expiracao = new DateTime(str_replace("T", " ", $nova_data_expiracao_str));

    // Calcular a diferença em horas
    $interval = $nova_data_inicio->diff($nova_data_expiracao);
    $horas = $interval->days * 24 + $interval->h + ($interval->i / 60); // Considera minutos para precisão
    $valor_por_hora = 2.00; // R$2.00 por hora
    $novo_valor_total = round($horas * $valor_por_hora, 2);

    $stmt = $pdo->prepare("
        UPDATE locacoes_pendentes
        SET data_inicio = :nova_data_inicio,
            data_expiracao = :nova_data_expiracao,
            valor_total = :novo_valor_total
        WHERE id = :locacao_id
    ");
    $stmt->execute([
        ':nova_data_inicio' => $nova_data_inicio->format('Y-m-d H:i:s'),
        ':nova_data_expiracao' => $nova_data_expiracao->format('Y-m-d H:i:s'),
        ':novo_valor_total' => $novo_valor_total,
        ':locacao_id' => $locacao_id
    ]);
}

header('Location: liberar_locacoes.php');
exit;
?>