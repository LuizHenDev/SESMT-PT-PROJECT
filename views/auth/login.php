<?php
/**
 * Login View
 */

if (!isset($error)) {
    $error = '';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_TITLE; ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --sesmt-green: #2d6a3e;
        }

        body {
            background: linear-gradient(135deg, var(--sesmt-green) 0%, #1d4620 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }

        .login-header {
            background-color: var(--sesmt-green);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .login-header h1 {
            font-size: 1.8rem;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .login-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .login-body {
            padding: 2rem;
        }

        .form-control {
            padding: 0.75rem;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 1rem;
        }

        .form-control:focus {
            border-color: var(--sesmt-green);
            box-shadow: 0 0 0 0.2rem rgba(45, 106, 62, 0.25);
        }

        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .btn-login {
            background-color: var(--sesmt-green);
            border-color: var(--sesmt-green);
            color: white;
            padding: 0.75rem;
            font-weight: 600;
            width: 100%;
            margin-top: 1rem;
            border-radius: 5px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-login:hover {
            background-color: #1d4620;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(45, 106, 62, 0.3);
        }

        .alert {
            border-radius: 5px;
            border: none;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background-color: #ffebee;
            color: #b71c1c;
            padding: 1rem;
            border-left: 4px solid #f44336;
        }

        .input-group {
            position: relative;
            margin-bottom: 1rem;
        }

        .input-group-text {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            color: var(--sesmt-green);
        }

        .form-check {
            margin-bottom: 1rem;
        }

        .form-check-input:checked {
            background-color: var(--sesmt-green);
            border-color: var(--sesmt-green);
        }

        .form-check-label {
            margin-bottom: 0;
            color: #666;
        }

        .login-footer {
            text-align: center;
            padding: 1rem;
            background-color: #f5f5f5;
            font-size: 0.9rem;
            color: #666;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            margin-right: 0.5rem;
        }

        @media (max-width: 480px) {
            .login-container {
                margin: 1rem;
            }

            .login-header h1 {
                font-size: 1.5rem;
            }

            .login-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container fade-in">
        <!-- Header -->
        <div class="login-header">
            <h1>
                <i class="fas fa-shield-alt"></i> SESMT
            </h1>
            <p>Gestão de Saúde e Segurança do Trabalho</p>
        </div>

        <!-- Body -->
        <div class="login-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo APP_URL; ?>/index.php?page=auth&action=login" id="loginForm">
                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <input 
                        type="email" 
                        class="form-control" 
                        id="email" 
                        name="email" 
                        placeholder="seu@email.com"
                        required
                        autofocus
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    >
                </div>

                <!-- Senha -->
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i> Senha
                    </label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="password" 
                        name="password" 
                        placeholder="Sua senha"
                        required
                    >
                </div>

                <!-- Remember Me -->
                <div class="form-check mb-3">
                    <input 
                        type="checkbox" 
                        class="form-check-input" 
                        id="rememberMe" 
                        name="remember"
                    >
                    <label class="form-check-label" for="rememberMe">
                        Lembrar-me neste dispositivo
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-login" id="submitBtn">
                    <i class="fas fa-sign-in-alt"></i> Entrar
                </button>
            </form>

            <!-- Info Box -->
            <div class="alert alert-info mt-3" role="alert">
                <small>
                    <strong>Credenciais de teste:</strong><br>
                    Email: <code>admin@sesmt.com</code><br>
                    Senha: <code>admin123</code>
                </small>
            </div>
        </div>

        <!-- Footer -->
        <div class="login-footer">
            <small>© 2024-2026 SESMT System | Versão 1.0.0</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Prevenir submit duplo
        document.getElementById('loginForm').addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Entrando...';
        });
    </script>
</body>
</html>
