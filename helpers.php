<?php
/**
 * Helper Functions
 * Funções auxiliares globais usadas em toda a aplicação
 */

require_once __DIR__ . '/config/constants.php';

// ========================================================
// SESSION & AUTHENTICATION
// ========================================================

/**
 * Verifica se usuário está autenticado
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_email']);
}

/**
 * Retorna dados do usuário autenticado
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'],
        'email' => $_SESSION['user_email'],
        'role' => $_SESSION['user_role']
    ];
}

/**
 * Verifica se usuário é admin
 */
function isAdmin() {
    return isLoggedIn() && $_SESSION['user_role'] === 'admin';
}

/**
 * Requer autenticação, redireciona se não logado
 */
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        redirect('index.php?page=auth&action=login');
    }
}

/**
 * Requer role admin
 */
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        die('<div class="alert alert-danger">Acesso negado. Apenas administradores podem acessar esta página.</div>');
    }
}

// ========================================================
// REDIRECT & NAVIGATION
// ========================================================

/**
 * Redireciona para URL
 */
function redirect($url, $delay = 0) {
    // Limpa qualquer saída já gerada antes de enviar cabeçalhos
    if (ob_get_level() > 0) {
        ob_clean();
    }

    // Previne cache e reenvio de POST
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");
    
    if ($delay > 0) {
        header("Refresh: $delay; url=$url");
    } else {
        header("Location: $url");
    }
    exit;
}

/**
 * Redireciona para página do app
 */
function redirectTo($page, $action = '', $params = []) {
    $url = 'index.php?page=' . $page;
    
    if (!empty($action)) {
        $url .= '&action=' . $action;
    }
    
    foreach ($params as $key => $value) {
        $url .= '&' . urlencode($key) . '=' . urlencode($value);
    }
    
    redirect($url);
}

// ========================================================
// INPUT VALIDATION & SANITIZATION
// ========================================================

/**
 * Sanitiza string input
 */
function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return trim(htmlspecialchars($input, ENT_QUOTES, 'UTF-8'));
}

/**
 * Valida email
 */
function validateEmail($email) {
    return filter_var(sanitize($email), FILTER_VALIDATE_EMAIL);
}

/**
 * Valida CPF (simples - apenas formato)
 */
function validateCPF($cpf) {
    $cpf = preg_replace('/\D/', '', $cpf);
    return strlen($cpf) === 11;
}

/**
 * Valida data (formato dd/mm/yyyy ou yyyy-mm-dd)
 */
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

/**
 * Valida se é data válida no futuro
 */
function validateFutureDate($date, $format = 'Y-m-d') {
    if (!validateDate($date, $format)) {
        return false;
    }
    return strtotime($date) > time();
}

// ========================================================
// PASSWORD & SECURITY
// ========================================================

/**
 * Hash de senha usando bcrypt
 */
function hashPassword($password) {
    if (strlen($password) < PASSWORD_MIN_LENGTH) {
        throw new Exception("Senha deve ter no mínimo " . PASSWORD_MIN_LENGTH . " caracteres.");
    }
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
}

/**
 * Verifica se senha está correta
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Gera token aleatório
 */
function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

// ========================================================
// MESSAGES & ALERTS
// ========================================================

/**
 * Define mensagem de sessão
 */
function setMessage($message, $type = 'success') {
    if (!isset($_SESSION['messages'])) {
        $_SESSION['messages'] = [];
    }
    $_SESSION['messages'][] = [
        'text' => $message,
        'type' => $type
    ];
}

/**
 * Obtém mensagens pendentes
 */
function getMessages() {
    if (!isset($_SESSION['messages'])) {
        return [];
    }
    $messages = $_SESSION['messages'];
    unset($_SESSION['messages']);
    return $messages;
}

/**
 * Renderiza mensagens em HTML
 */
function renderMessages() {
    $messages = getMessages();
    $html = '';
    
    foreach ($messages as $message) {
        $class = 'alert alert-' . htmlspecialchars($message['type']);
        $html .= '<div class="' . $class . ' alert-dismissible fade show" role="alert">';
        $html .= htmlspecialchars($message['text']);
        $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        $html .= '</div>';
    }
    
    return $html;
}

/**
 * Flash message com redirecionamento
 */
function flashAndRedirect($message, $type, $redirect_url) {
    setMessage($message, $type);
    redirect($redirect_url);
}

// ========================================================
// DATA FORMATTING
// ========================================================

/**
 * Formata data para exibição (dd/mm/yyyy)
 */
function formatDate($date, $format = DATE_FORMAT) {
    if (empty($date) || $date === '0000-00-00') {
        return '-';
    }
    try {
        $dateObj = new DateTime($date);
        return $dateObj->format($format);
    } catch (Exception $e) {
        return $date;
    }
}

/**
 * Formata data/hora para exibição
 */
function formatDateTime($datetime, $format = DATETIME_FORMAT) {
    if (empty($datetime)) {
        return '-';
    }
    try {
        $dateObj = new DateTime($datetime);
        return $dateObj->format($format);
    } catch (Exception $e) {
        return $datetime;
    }
}

/**
 * Converte data de dd/mm/yyyy para yyyy-mm-dd ou aceita yyyy-mm-dd diretamente
 */
function convertDateFormat($date) {
    if (empty($date)) {
        return null;
    }
    try {
        // Tenta formato Y-m-d primeiro (de inputs type="date")
        $dateObj = DateTime::createFromFormat('Y-m-d', $date);
        if ($dateObj !== false) {
            return $dateObj->format('Y-m-d');
        }
        // Tenta formato d/m/Y
        $dateObj = DateTime::createFromFormat('d/m/Y', $date);
        if ($dateObj !== false) {
            return $dateObj->format('Y-m-d');
        }
        // Retorna original se falhar
        return $date;
    } catch (Exception $e) {
        return $date;
    }
}

/**
 * Formata CPF
 */
function formatCPF($cpf) {
    $cpf = preg_replace('/\D/', '', $cpf);
    return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
}

/**
 * Formata telefone
 */
function formatPhone($phone) {
    $phone = preg_replace('/\D/', '', $phone);
    if (strlen($phone) === 11) {
        return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 5) . '-' . substr($phone, 7);
    }
    return $phone;
}

/**
 * Formata valor monetário
 */
function formatMoney($value) {
    return 'R$ ' . number_format($value, 2, ',', '.');
}

// ========================================================
// ARRAY & UTILITIES
// ========================================================

/**
 * Obtém valor de array com default
 */
function arrayGet($array, $key, $default = null) {
    return isset($array[$key]) ? $array[$key] : $default;
}

/**
 * Cria select options em HTML
 */
function getSelectOptions($options, $selected = '', $addEmpty = true) {
    $html = '';
    
    if ($addEmpty) {
        $html .= '<option value="">-- Selecione --</option>';
    }
    
    foreach ($options as $key => $value) {
        $selectedAttr = ($key === $selected) ? 'selected' : '';
        $html .= '<option value="' . htmlspecialchars($key) . '" ' . $selectedAttr . '>' . htmlspecialchars($value) . '</option>';
    }
    
    return $html;
}

/**
 * Formata texto curto (trunca)
 */
function truncate($text, $length = 100, $suffix = '...') {
    if (strlen($text) > $length) {
        return substr($text, 0, $length) . $suffix;
    }
    return $text;
}

/**
 * Calcula dias até data
 */
function daysUntil($date) {
    $today = new DateTime('today');
    $targetDate = new DateTime($date);
    $interval = $today->diff($targetDate);
    return $interval->days * ($interval->invert ? -1 : 1);
}

/**
 * Verifica se data está expirada
 */
function isExpired($date) {
    return daysUntil($date) < 0;
}

/**
 * Verifica se data está próxima de expirar (30 dias)
 */
function isExpiringSoon($date, $days = 30) {
    $daysLeft = daysUntil($date);
    return $daysLeft >= 0 && $daysLeft <= $days;
}

// ========================================================
// LOGGING & DEBUG
// ========================================================

/**
 * Log simples de erro/informação
 */
function logMessage($message, $type = 'info') {
    $logFile = ROOT_PATH . '/logs/system.log';
    $dir = dirname($logFile);
    
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    $timestamp = date(DATETIME_FORMAT);
    $logMessage = "[$timestamp] [$type] $message\n";
    
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

/**
 * Debug print
 */
function debug($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

// ========================================================
// PAGINATION HELPERS
// ========================================================

/**
 * Retorna offset para LIMIT SQL
 */
function getPaginationOffset($page = 1, $perPage = ITEMS_PER_PAGE) {
    $page = max(1, intval($page));
    return ($page - 1) * $perPage;
}

/**
 * Cria array de paginação
 */
function getPagination($currentPage, $totalItems, $perPage = ITEMS_PER_PAGE) {
    $totalPages = ceil($totalItems / $perPage);
    
    return [
        'current_page' => max(1, intval($currentPage)),
        'total_pages' => $totalPages,
        'total_items' => $totalItems,
        'items_per_page' => $perPage,
        'offset' => getPaginationOffset($currentPage, $perPage),
        'has_prev' => $currentPage > 1,
        'has_next' => $currentPage < $totalPages,
        'prev_page' => $currentPage - 1,
        'next_page' => $currentPage + 1
    ];
}

?>
