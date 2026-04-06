<?php
/**
 * Authentication Controller
 * Gerencia login, logout e autenticação de sesão
 */

require_once MODELS_PATH . '/User.php';

class AuthController {
    
    /**
     * Exibe página de login
     */
    public static function login() {
        // Se já está logado, redireciona ao dashboard
        if (isLoggedIn()) {
            redirectTo('dashboard');
        }
        
        $error = '';
        
        // Processa POST do formulário de login
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitize($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            // Validações
            if (empty($email) || empty($password)) {
                $error = 'Email e senha são obrigatórios.';
            } elseif (!validateEmail($email)) {
                $error = 'Email inválido.';
            } else {
                // Tenta autenticar
                $userModel = new User();
                $user = $userModel->authenticate($email, $password);
                
                if ($user) {
                    // Login bem-sucedido, inicia sessão
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['login_time'] = time();
                    
                    // Redireciona para dashboard ou URL anterior
                    $redirect = $_SESSION['redirect_after_login'] ?? 'index.php?page=dashboard';
                    unset($_SESSION['redirect_after_login']);
                    
                    setMessage('Bem-vindo, ' . $user['name'] . '!', 'success');
                    redirect(APP_URL . '/' . $redirect);
                } else {
                    $error = 'Email ou senha incorretos.';
                }
            }
        }
        
        // Renderizar view de login
        include VIEWS_PATH . '/auth/login.php';
    }
    
    /**
     * Faz logout
     */
    public static function logout() {
        // Destroi sessão
        session_destroy();
        
        // Limpar cookies de sessão
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        // Redireciona ao login
        setMessage('Logout realizado com sucesso.', 'success');
        redirect(APP_URL . '/index.php?page=auth&action=login');
    }
    
    /**
     * Verifica se sessão ainda é válida
     */
    public static function validateSession() {
        if (!isLoggedIn()) {
            return false;
        }
        
        // Verifica timeout de sessão (1 hora)
        $loginTime = $_SESSION['login_time'] ?? 0;
        if (time() - $loginTime > SESSION_TIMEOUT) {
            self::logout();
            return false;
        }
        
        // Atualiza tempo de atividade
        $_SESSION['login_time'] = time();
        
        return true;
    }
}
?>
