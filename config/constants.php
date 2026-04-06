<?php
/**
 * Application Constants
 * Define todas as constantes e configurações globais do sistema
 */

// ========================================================
// APPLICATION INFO
// ========================================================
define('APP_NAME', 'SESMT Label Packing');
define('APP_CREATOR', 'Luiz Henrique');
define('APP_URL', 'http://localhost/Sesmt');
define('SITE_TITLE', 'SESMT - Gestão de Saúde e Segurança do Trabalho');

// ========================================================
// PATH CONSTANTS
// ========================================================
define('ROOT_PATH', dirname(dirname(__FILE__)));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('MODELS_PATH', ROOT_PATH . '/models');
define('CONTROLLERS_PATH', ROOT_PATH . '/controllers');
define('VIEWS_PATH', ROOT_PATH . '/views');
define('ASSETS_PATH', ROOT_PATH . '/assets');

// ========================================================
// USER ROLES
// ========================================================
define('ROLE_ADMIN', 'admin');
define('ROLE_COMUM', 'comum');

$ROLES = [
    'admin' => 'Administrador',
    'comum' => 'Usuário Comum'
];

// ========================================================
// WORK PERMIT TYPES (Permissão de Trabalho)
// ========================================================
$PT_TYPES = [
    'altura' => 'Trabalho em Altura',
    'eletricidade' => 'Trabalho com Eletricidade',
    'espaco_confinado' => 'Espaço Confinado',
    'trabalho_quente' => 'Trabalho a Quente'
];

// ========================================================
// RISK LEVELS
// ========================================================
$RISK_LEVELS = [
    'baixo' => 'Baixo',
    'medio' => 'Médio',
    'alto' => 'Alto'
];

// CSS classes para risk levels
$RISK_COLORS = [
    'baixo' => 'success',    // Verde
    'medio' => 'warning',    // Amarelo
    'alto' => 'danger'       // Vermelho
];

// ========================================================
// ACCIDENT STATUS
// ========================================================
$ACCIDENT_STATUS = [
    'aberto' => 'Aberto',
    'investigado' => 'Investigado',
    'fechado' => 'Fechado'
];

$ACCIDENT_COLORS = [
    'aberto' => 'danger',
    'investigado' => 'warning',
    'fechado' => 'success'
];

// ========================================================
// TRAINING STATUS
// ========================================================
$TRAINING_STATUS = [
    'pendente' => 'Pendente',
    'concluido' => 'Concluído',
    'vencido' => 'Vencido',
    'cancelado' => 'Cancelado'
];

$TRAINING_COLORS = [
    'pendente' => 'warning',
    'concluido' => 'success',
    'vencido' => 'danger',
    'cancelado' => 'secondary'
];

// ========================================================
// EPI CONDITION
// ========================================================
$EPI_CONDITIONS = [
    'novo' => 'Novo',
    'usado' => 'Usado',
    'danificado' => 'Danificado'
];

// ========================================================
// EMPLOYEE STATUS
// ========================================================
$EMPLOYEE_STATUS = [
    'ativo' => 'Ativo',
    'inativo' => 'Inativo'
];

// ========================================================
// SETTINGS TYPES
// ========================================================
$SETTING_TYPES = [
    'pt_type' => 'Tipos de Permissão de Trabalho',
    'accident_status' => 'Status de Acidente',
    'training_status' => 'Status de Treinamento',
    'epi_condition' => 'Condições de EPI',
    'employee_status' => 'Status do Colaborador'
];

// ========================================================
// PAGINATION
// ========================================================
define('ITEMS_PER_PAGE', 10);

// ========================================================
// DATE & TIME
// ========================================================
define('DATE_FORMAT', 'd/m/Y');
define('DATETIME_FORMAT', 'd/m/Y H:i:s');
define('TIME_FORMAT', 'H:i:s');

// Set PHP timezone
date_default_timezone_set('America/Sao_Paulo');

// ========================================================
// SESSION CONFIG
// ========================================================
define('SESSION_TIMEOUT', 3600); // 1 hora em segundos
define('REMEMBER_ME_DURATION', 86400 * 7); // 7 dias

// ========================================================
// SECURITY
// ========================================================
define('PASSWORD_MIN_LENGTH', 6);
define('PASSWORD_HASH_ALGO', 'bcrypt');

// ========================================================
// ERROR HANDLING
// ========================================================
define('DEBUG_MODE', true); // Mudar para false em produção
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ERROR | E_WARNING);
    ini_set('display_errors', 0);
}

// ========================================================
// MESSAGES TYPES
// ========================================================
$MESSAGE_TYPES = [
    'success' => 'success',
    'error' => 'danger',
    'warning' => 'warning',
    'info' => 'info'
];
?>
