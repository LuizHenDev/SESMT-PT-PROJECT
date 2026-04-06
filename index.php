<?php
/**
 * Index.php - Router Principal
 * Arquivo de entrada da aplicação
 * Todos os acessos são feitos via este arquivo
 */

// ========================================================
// INICIALIZAÇÃO
// ========================================================

// Iniciar sessão
session_start();

// Ativar buffer de saída para permitir redirecionamentos após views parcialmente geradas
ob_start();

// Carregar configurações e helpers
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers.php';

// Ativar exibição de erros se em modo DEBUG
if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

// ========================================================
// RODEO DA APLICAÇÃO
// ========================================================

try {
    // Obtém parâmetros da requisição
    $page = isset($_GET['page']) ? sanitize($_GET['page']) : 'dashboard';
    $action = isset($_GET['action']) ? sanitize($_GET['action']) : 'index';
    
    // ========================================================
    // ROTAS PÚBLICAS (sem autenticação)
    // ========================================================
    
    if ($page === 'auth') {
        // Página de autenticação (login/logout)
        require_once CONTROLLERS_PATH . '/auth.php';
        
        if ($action === 'logout') {
            AuthController::logout();
        } else {
            AuthController::login();
        }
        exit;
    }
    
    // ========================================================
    // ROTAS PROTEGIDAS (requer autenticação)
    // ========================================================
    
    // Proteger rotas que requerem login
    requireLogin();
    
    // Carregar controller de autenticação para validação de sessão
    require_once CONTROLLERS_PATH . '/auth.php';
    
    // Validar sessão (verificar timeout, etc)
    AuthController::validateSession();
    
    // Renderizar header
    include VIEWS_PATH . '/layouts/header.php';
    
    // ========================================================
    // ROTEADOR PRINCIPAL
    // ========================================================
    
    switch ($page) {
        case 'dashboard':
            require_once CONTROLLERS_PATH . '/dashboard.php';
            DashboardController::index();
            break;
            
        case 'users':
            // Apenas admin pode acessar
            requireAdmin();
            require_once CONTROLLERS_PATH . '/users.php';
            UsersController::handle($action);
            break;

        case 'cadastros':
            require_once CONTROLLERS_PATH . '/cadastros.php';
            CadastrosController::handle($action);
            break;
            
        case 'employees':
            require_once CONTROLLERS_PATH . '/employees.php';
            EmployeesController::handle($action);
            break;
            
        case 'permits':
            require_once CONTROLLERS_PATH . '/permits.php';
            PermitsController::handle($action);
            break;
            
        case 'epis':
            require_once CONTROLLERS_PATH . '/epis.php';
            EpisController::handle($action);
            break;
            
        case 'risks':
            require_once CONTROLLERS_PATH . '/risks.php';
            RisksController::handle($action);
            break;
            
        case 'accidents':
            require_once CONTROLLERS_PATH . '/accidents.php';
            AccidentsController::handle($action);
            break;
            
        case 'training':
            require_once CONTROLLERS_PATH . '/training.php';
            TrainingController::handle($action);
            break;
            
        default:
            // Página não encontrada
            http_response_code(404);
            echo '<div class="alert alert-warning">Página não encontrada.</div>';
            break;
    }
    
    // Renderizar footer
    include VIEWS_PATH . '/layouts/footer.php';
    
} catch (Exception $e) {
    // Tratamento de erro
    http_response_code(500);
    
    echo '<div class="container mt-5">';
    echo '<div class="alert alert-danger" role="alert">';
    echo '<h4 class="alert-heading">Erro!</h4>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    
    if (DEBUG_MODE) {
        echo '<hr>';
        echo '<small><pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre></small>';
    }
    
    echo '</div>';
    echo '</div>';
}
?>
