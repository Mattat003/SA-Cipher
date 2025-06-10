<?php
session_start();
header('Content-Type: application/json'); // Sempre retorna JSON

require_once 'db.php';
require_once 'functions.php';

// Redireciona ou retorna erro se não estiver logado
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Não autorizado. Por favor, faça login.']);
    http_response_code(401); // Unauthorized
    exit;
}

// Opcional: Verifique se o usuário tem a role de 'admin' para todas as ações
// É uma boa prática ter granularidade, mas para um painel admin, 'admin' total faz sentido.
if ($_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Acesso negado. Você não tem permissão para esta ação.']);
    http_response_code(403); // Forbidden
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Lógica para buscar usuários
        try {
            $stmt = $pdo->query("SELECT id, username, email, role, status, created_at, last_login_at, last_login_ip FROM users ORDER BY created_at DESC");
            $users = $stmt->fetchAll();
            // Escapar dados antes de enviar para o frontend para prevenir XSS
            foreach ($users as &$user) {
                $user['username'] = escapeHtml($user['username']);
                $user['email'] = escapeHtml($user['email']);
                $user['role'] = escapeHtml($user['role']);
                $user['status'] = escapeHtml($user['status']);
                $user['last_login_ip'] = escapeHtml($user['last_login_ip']);
                // datas não precisam de escape HTML
            }
            echo json_encode(['success' => true, 'data' => $users]);
        } catch (PDOException $e) {
            error_log("Erro ao buscar usuários (GET): " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Ocorreu um erro ao buscar usuários.']);
            http_response_code(500); // Internal Server Error
        }
        break;

    case 'POST':
        // Lógica para criar um novo usuário
        $data = json_decode(file_get_contents('php://input'), true);

        // 1. Validação CSRF para requisições POST
        $csrf_token = $data['csrf_token'] ?? '';
        if (!validateCsrfToken($csrf_token)) {
            echo json_encode(['success' => false, 'message' => 'Erro de segurança: Requisição inválida.']);
            logSecurityEvent($pdo, 'csrf_attack', 'Tentativa de criação de usuário com token CSRF inválido.', $_SESSION['user_id'] ?? null, $_SERVER['REMOTE_ADDR']);
            http_response_code(403); // Forbidden
            exit;
        }

        $username = trim($data['username'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $role = $data['role'] ?? 'user';
        $status = $data['status'] ?? 'active';

        // Validação de entrada
        if (empty($username) || empty($email) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Nome de usuário, e-mail e senha são obrigatórios.']);
            http_response_code(400); // Bad Request
            exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Formato de e-mail inválido.']);
            http_response_code(400);
            exit;
        }
        if (strlen($password) < 8) {
            echo json_encode(['success' => false, 'message' => 'A senha deve ter pelo menos 8 caracteres.']);
            http_response_code(400);
            exit;
        }
        // Validar role e status contra os ENUMs do banco de dados
        $allowed_roles = ['admin', 'editor', 'user'];
        $allowed_statuses = ['active', 'inactive', 'suspended', 'banned'];
        if (!in_array($role, $allowed_roles) || !in_array($status, $allowed_statuses)) {
            echo json_encode(['success' => false, 'message' => 'Role ou status inválido.']);
            http_response_code(400);
            exit;
        }


        try {
            // Verificar se o nome de usuário ou e-mail já existem
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
            $stmt_check->execute([$username, $email]);
            if ($stmt_check->fetchColumn() > 0) {
                echo json_encode(['success' => false, 'message' => 'Nome de usuário ou e-mail já existem.']);
                http_response_code(409); // Conflict
                exit;
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, status) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed_password, $role, $status])) {
                $new_user_id = $pdo->lastInsertId();
                logAuditEvent($pdo, 'User created', 'users', $new_user_id, null, json_encode(['username' => $username, 'email' => $email, 'role' => $role, 'status' => $status]));
                echo json_encode(['success' => true, 'message' => 'Usuário criado com sucesso!', 'user_id' => $new_user_id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao criar usuário.']);
                http_response_code(500);
            }
        } catch (PDOException $e) {
            error_log("Erro no banco de dados ao criar usuário (POST): " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erro no banco de dados.']);
            http_response_code(500);
        }
        break;

    case 'PUT':
        // Lógica para atualizar um usuário existente
        $data = json_decode(file_get_contents('php://input'), true);

        // 1. Validação CSRF para requisições PUT
        $csrf_token = $data['csrf_token'] ?? '';
        if (!validateCsrfToken($csrf_token)) {
            echo json_encode(['success' => false, 'message' => 'Erro de segurança: Requisição inválida.']);
            logSecurityEvent($pdo, 'csrf_attack', 'Tentativa de atualização de usuário com token CSRF inválido.', $_SESSION['user_id'] ?? null, $_SERVER['REMOTE_ADDR']);
            http_response_code(403); // Forbidden
            exit;
        }

        $user_id = $data['id'] ?? null;
        $username = trim($data['username'] ?? '');
        $email = trim($data['email'] ?? '');
        $role = $data['role'] ?? null;
        $status = $data['status'] ?? null;
        $password = $data['password'] ?? null; // Senha é opcional na atualização

        if (!$user_id) {
            echo json_encode(['success' => false, 'message' => 'ID do usuário não fornecido para atualização.']);
            http_response_code(400);
            exit;
        }

        // Validação de entrada
        if (empty($username) || empty($email) || empty($role) || empty($status)) {
            echo json_encode(['success' => false, 'message' => 'Nome de usuário, e-mail, role e status são obrigatórios.']);
            http_response_code(400);
            exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Formato de e-mail inválido.']);
            http_response_code(400);
            exit;
        }
        $allowed_roles = ['admin', 'editor', 'user'];
        $allowed_statuses = ['active', 'inactive', 'suspended', 'banned'];
        if (!in_array($role, $allowed_roles) || !in_array($status, $allowed_statuses)) {
            echo json_encode(['success' => false, 'message' => 'Role ou status inválido.']);
            http_response_code(400);
            exit;
        }
        if ($password !== null && strlen($password) < 8 && $password !== '') {
             echo json_encode(['success' => false, 'message' => 'A nova senha deve ter pelo menos 8 caracteres (se for alterada).']);
             http_response_code(400);
             exit;
        }

        try {
            // Obter dados antigos do usuário para log de auditoria
            $stmt_old_data = $pdo->prepare("SELECT username, email, role, status FROM users WHERE id = ?");
            $stmt_old_data->execute([$user_id]);
            $old_user_data = $stmt_old_data->fetch(PDO::FETCH_ASSOC);

            if (!$old_user_data) {
                echo json_encode(['success' => false, 'message' => 'Usuário não encontrado.']);
                http_response_code(404); // Not Found
                exit;
            }
            $old_value_json = json_encode($old_user_data);

            // Preparar a query de atualização
            $sql = "UPDATE users SET username = ?, email = ?, role = ?, status = ?";
            $params = [$username, $email, $role, $status];

            if ($password !== null && $password !== '') {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql .= ", password = ?";
                $params[] = $hashed_password;
            }
            $sql .= " WHERE id = ?";
            $params[] = $user_id;

            $stmt = $pdo->prepare($sql);
            if ($stmt->execute($params)) {
                // Obter novos dados do usuário para log de auditoria
                $stmt_new_data = $pdo->prepare("SELECT username, email, role, status FROM users WHERE id = ?");
                $stmt_new_data->execute([$user_id]);
                $new_user_data = $stmt_new_data->fetch(PDO::FETCH_ASSOC);
                $new_value_json = json_encode($new_user_data);

                logAuditEvent($pdo, 'User updated', 'users', $user_id, $old_value_json, $new_value_json);
                echo json_encode(['success' => true, 'message' => 'Usuário atualizado com sucesso!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar usuário.']);
                http_response_code(500);
            }
        } catch (PDOException $e) {
            error_log("Erro no banco de dados ao atualizar usuário (PUT): " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erro no banco de dados.']);
            http_response_code(500);
        }
        break;

    case 'DELETE':
        // Lógica para deletar um usuário
        $data = json_decode(file_get_contents('php://input'), true);

        // 1. Validação CSRF para requisições DELETE
        $csrf_token = $data['csrf_token'] ?? '';
        if (!validateCsrfToken($csrf_token)) {
            echo json_encode(['success' => false, 'message' => 'Erro de segurança: Requisição inválida.']);
            logSecurityEvent($pdo, 'csrf_attack', 'Tentativa de exclusão de usuário com token CSRF inválido.', $_SESSION['user_id'] ?? null, $_SERVER['REMOTE_ADDR']);
            http_response_code(403); // Forbidden
            exit;
        }

        $user_id = $data['id'] ?? null;

        if (!$user_id) {
            echo json_encode(['success' => false, 'message' => 'ID do usuário não fornecido para exclusão.']);
            http_response_code(400);
            exit;
        }

        // Não permitir que um admin delete a si mesmo (se for o último admin, por exemplo)
        if ($user_id == $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Você não pode excluir sua própria conta enquanto estiver logado.']);
            http_response_code(403);
            exit;
        }

        try {
            // Obter dados do usuário a ser deletado para log de auditoria
            $stmt_old_data = $pdo->prepare("SELECT username, email, role, status FROM users WHERE id = ?");
            $stmt_old_data->execute([$user_id]);
            $deleted_user_data = $stmt_old_data->fetch(PDO::FETCH_ASSOC);

            if (!$deleted_user_data) {
                echo json_encode(['success' => false, 'message' => 'Usuário não encontrado para exclusão.']);
                http_response_code(404);
                exit;
            }

            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            if ($stmt->execute([$user_id])) {
                logAuditEvent($pdo, 'User deleted', 'users', $user_id, json_encode($deleted_user_data), null);
                echo json_encode(['success' => true, 'message' => 'Usuário excluído com sucesso!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao excluir usuário.']);
                http_response_code(500);
            }
        } catch (PDOException $e) {
            error_log("Erro no banco de dados ao deletar usuário (DELETE): " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erro no banco de dados.']);
            http_response_code(500);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Método de requisição não suportado.']);
        http_response_code(405); // Method Not Allowed
        break;
}
?>