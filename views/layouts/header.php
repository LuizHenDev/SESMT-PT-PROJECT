<?php
/**
 * Header Template
 * Navbar com Bootstrap 5, autenticação e menu
 */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_TITLE; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome (Ícones) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js (para gráficos) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/style.css">
    
    <style>
        :root {
            --sesmt-green: #2d6a3e;
            --sesmt-red: #d32f2f;
            --sesmt-light: #f5f5f5;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--sesmt-green);">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="<?php echo APP_URL; ?>/index.php">
                <i class="fas fa-shield-alt"></i> <?php echo APP_NAME; ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isLoggedIn()): 
                        $user = getCurrentUser();
                    ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($user['name']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/index.php?page=dashboard">
                                    <i class="fas fa-chart-line"></i> Dashboard
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <?php if (isAdmin()): ?>
                                <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/index.php?page=users">
                                    <i class="fas fa-users"></i> Gerenciar Usuários
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="<?php echo APP_URL; ?>/index.php?page=auth&action=logout">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo APP_URL; ?>/index.php?page=auth&action=login">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Messages -->
    <div class="container-fluid mt-3">
        <?php echo renderMessages(); ?>
    </div>

    <!-- Main Content -->
    <div class="d-flex" style="min-height: calc(100vh - 185px);">
        <!-- Sidebar (só aparece se logado) -->
        <?php if (isLoggedIn()): ?>
            <div class="sidebar bg-light border-end" style="width: 250px; overflow-y: auto;">
                <?php include VIEWS_PATH . '/layouts/sidebar.php'; ?>
            </div>
            <main class="flex-grow-1 p-4">
        <?php else: ?>
            <main class="container-fluid flex-grow-1 p-4">
        <?php endif; ?>
