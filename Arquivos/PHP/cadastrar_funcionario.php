<?php
session_start();
require_once 'conexao.php';

$fk_cargo = $_SESSION['fk_cargo'] ?? null;

// Apenas quem tem cargo de Adm pode cadastrar funcionário
if ($fk_cargo != 1) {
    echo "Acesso negado";
    exit;
}

$mensagem = "";

// Busca cargos disponíveis para seleção
$stmt_cargo = $pdo->query("SELECT pk_cargo, nome_cargo FROM cargo");
$cargos = $stmt_cargo->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome_adm'] ?? '');
    $email = trim($_POST['email_adm'] ?? '');
    $senha = $_POST['senha_user'] ?? '';
    $fk_cargo_form = $_POST['fk_cargo'] ?? null;

    if (empty($nome) || empty($email) || empty($senha) || empty($fk_cargo_form)) {
        $mensagem = "Todos os campos são obrigatórios.";
    } else {
        // Verifica e-mail duplicado
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM adm WHERE email_adm = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            $mensagem = "E-mail já cadastrado!";
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "INSERT INTO adm (nome_adm, email_adm, senha_user, fk_cargo) VALUES (:nome, :email, :senha, :fk_cargo)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha_hash);
            $stmt->bindParam(':fk_cargo', $fk_cargo_form);

            if ($stmt->execute()) {
                $mensagem = "Funcionário cadastrado com sucesso!";
            } else {
                $mensagem = "Erro ao cadastrar funcionário!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Funcionário</title>
    <style>
        body { background: #f8f8fc; font-family: Arial, sans-serif; }
        form { max-width: 350px; background: #fff; margin: 40px auto; border-radius: 10px; padding: 28px 25px 18px 25px; box-shadow: 0 4px 24px #0001; }
        h2 { text-align: center; color: #510d96; }
        label { color: #222; font-weight: 500; }
        input, select { width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #bbb; margin-bottom: 18px; }
        button { background: #510d96; color: #fff; border: none; padding: 8px 20px; border-radius: 6px; font-size: 1rem; margin-right: 8px; cursor: pointer; }
        button[type="reset"] { background: #bbb; color: #222; }
        .mensagem { text-align: center; color: #510d96; margin-bottom: 12px; font-weight: bold; }
        a { display: block; text-align: center; margin-top: 14px; color: #510d96; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <form action="cadastrar_funcionario.php" method="POST" autocomplete="off">
        <h2>Cadastrar Funcionário</h2>
        <?php if (!empty($mensagem)): ?>
            <div class="mensagem"><?= htmlspecialchars($mensagem); ?></div>
        <?php endif; ?>
        <label for="nome_adm">Nome:</label>
        <input type="text" id="nome_adm" name="nome_adm" required>

        <label for="email_adm">E-mail:</label>
        <input type="email" id="email_adm" name="email_adm" required>

        <label for="senha_user">Senha:</label>
        <input type="password" id="senha_user" name="senha_user" required>
        
        <label for="fk_cargo">Cargo:</label>
        <select id="fk_cargo" name="fk_cargo" required>
            <option value="">Selecione o cargo</option>
            <?php foreach ($cargos as $cargo): ?>
                <option value="<?= htmlspecialchars($cargo['pk_cargo']) ?>">
                    <?= htmlspecialchars($cargo['nome_cargo']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Cadastrar</button>
        <button type="reset">Cancelar</button>
        <a href="adm.php">Voltar</a>
    </form>
</body>
</html>