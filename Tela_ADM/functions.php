<?php
// includes/functions.php

// Inicia a sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica se o usuário está logado.
 * @return bool True se o usuário estiver logado, false caso contrário.
 */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

/**
 * Redireciona o usuário para a página de login.
 */
function redirectToLogin(): void {
    header('Location: ../auth/login.php');
    exit;
}

/**
 * Redireciona o usuário para a página de acesso negado ou exibe uma mensagem de erro.
 * @param string $message Mensagem de erro a ser exibida.
 */
function accessDenied(string $message = 'Acesso negado. Você não tem permissão para esta ação.'): void {
    // Em um ambiente real, você pode ter uma página de erro 403 personalizada
    http_response_code(403); // Forbidden
    die($message); // Exibe a mensagem e encerra a execução
}

/**
 * Escapa strings para exibição em HTML para prevenir XSS.
 * @param string|null $string A string a ser escapada.
 * @return string A string escapada.
 */
function escapeHtml(?string $string): string {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Gera um token CSRF e o armazena na sessão.
 * @return string O token CSRF gerado.
 */
function generateCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Gera um token forte
    }
    return $_SESSION['csrf_token'];
}

/**
 * Valida um token CSRF.
 * @param string $token O token recebido da requisição.
 * @return bool True se o token for válido, false caso contrário.
 */
function validateCsrfToken(string $token): bool {
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        return false;
    }
    // Uma vez usado, o token deve ser invalidado para ataques de "replay"
    // No entanto, para formulários AJAX que podem ser enviados múltiplas vezes,
    // pode ser necessário gerar um novo token após cada uso ou usar um token por sessão.
    // Para simplificar, neste exemplo, manteremos um token por sessão.
    // Para maior segurança, considere tokens por formulário ou por requisição AJAX.
    return true;
}

/**
 * Registra um log de segurança.
 * @param PDO $pdo Objeto PDO para conexão com o banco de dados.
 * @param string $action_type Tipo de ação (ex: 'login_success', 'login_failure', 'user_created').
 * @param string $description Descrição detalhada da ação.
 * @param int|null $user_id ID do usuário (opcional, pode ser null para ações sem login).
 * @param string|null $ip_address Endereço IP do cliente (opcional).
 */
function logSecurityEvent(PDO $pdo, string $action_type, string $description, ?int $user_id = null, ?string $ip_address = null): void {
    // Obter o IP se não for fornecido
    if ($ip_address === null) {
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO security_logs (user_id, action_type, description, ip_address) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $action_type, $description, $ip_address]);
    } catch (PDOException $e) {
        // A falha ao registrar um log de segurança não deve derrubar a aplicação.
        // Apenas logue o erro internamente.
        error_log("Falha ao registrar log de segurança: " . $e->getMessage() . " - Detalhes: " . json_encode(['user_id' => $user_id, 'action_type' => $action_type, 'description' => $description, 'ip_address' => $ip_address]));
    }
}

/**
 * Registra um log de auditoria para ações de modificação de dados.
 * @param PDO $pdo Objeto PDO para conexão com o banco de dados.
 * @param string $action Tipo de ação (ex: 'User created', 'Product updated').
 * @param string|null $entity_type Tipo da entidade afetada (ex: 'users', 'products').
 * @param int|null $entity_id ID da entidade afetada.
 * @param string|null $old_value Valor antigo (para updates).
 * @param string|null $new_value Novo valor (para updates).
 */
function logAuditEvent(PDO $pdo, string $action, ?string $entity_type = null, ?int $entity_id = null, ?string $old_value = null, ?string $new_value = null): void {
    $user_id = $_SESSION['user_id'] ?? null;
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';

    try {
        $stmt = $pdo->prepare("INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_value, new_value, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $action, $entity_type, $entity_id, $old_value, $new_value, $ip_address, $user_agent]);
    } catch (PDOException $e) {
        error_log("Falha ao registrar log de auditoria: " . $e->getMessage() . " - Detalhes: " . json_encode(['user_id' => $user_id, 'action' => $action, 'entity_type' => $entity_type, 'entity_id' => $entity_id]));
    }
}

?>