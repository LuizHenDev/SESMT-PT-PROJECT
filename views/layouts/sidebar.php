<?php
/**
 * Sidebar Template
 * Menu lateral com links para todos os módulos
 */
?>

<nav class="nav flex-column p-3">
    <div class="mb-3">
        <h6 class="text-uppercase text-muted small fw-bold">Módulos</h6>
    </div>

    <!-- Dashboard -->
    <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] == 'dashboard') ? 'active' : ''; ?>" 
       href="<?php echo APP_URL; ?>/index.php?page=dashboard">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>

    <!-- Colaboradores -->
    <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] == 'employees') ? 'active' : ''; ?>" 
       href="<?php echo APP_URL; ?>/index.php?page=employees">
        <i class="fas fa-users"></i> Colaboradores
    </a>

    <!-- Permissão de Trabalho -->
    <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] == 'permits') ? 'active' : ''; ?>" 
       href="<?php echo APP_URL; ?>/index.php?page=permits">
        <i class="fas fa-file-contract"></i> Permissão de Trabalho
    </a>

    <!-- EPIs -->
    <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] == 'epis') ? 'active' : ''; ?>" 
       href="<?php echo APP_URL; ?>/index.php?page=epis">
        <i class="fas fa-hard-hat"></i> EPIs
    </a>

    <!-- Riscos -->
    <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] == 'risks') ? 'active' : ''; ?>" 
       href="<?php echo APP_URL; ?>/index.php?page=risks">
        <i class="fas fa-exclamation-triangle"></i> Riscos
    </a>

    <!-- Acidentes -->
    <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] == 'accidents') ? 'active' : ''; ?>" 
       href="<?php echo APP_URL; ?>/index.php?page=accidents">
        <i class="fas fa-ambulance"></i> Acidentes
    </a>

    <!-- Treinamentos -->
    <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] == 'training') ? 'active' : ''; ?>" 
       href="<?php echo APP_URL; ?>/index.php?page=training">
        <i class="fas fa-graduation-cap"></i> Treinamentos
    </a>

    <!-- Admin Only -->
    <?php if (isAdmin()): ?>
        <hr class="my-3">
        <div class="mb-3">
            <h6 class="text-uppercase text-muted small fw-bold">Administração</h6>
        </div>

        <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] == 'users') ? 'active' : ''; ?>" 
           href="<?php echo APP_URL; ?>/index.php?page=users">
            <i class="fas fa-user-shield"></i> Usuários do Sistema
        </a>
        <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] == 'cadastros') ? 'active' : ''; ?>" 
           href="<?php echo APP_URL; ?>/index.php?page=cadastros">
            <i class="fas fa-list"></i> Cadastros
        </a>
    <?php endif; ?>
</nav>

<style>
.sidebar {
    min-height: 100%;
    position: sticky;
    top: 0;
}

.nav-link {
    color: #333;
    padding: 0.75rem 1rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.nav-link:hover {
    color: var(--sesmt-green);
    background-color: rgba(45, 106, 62, 0.1);
}

.nav-link.active {
    color: white;
    background-color: var(--sesmt-green);
    border-radius: 5px;
}

.nav-link i {
    width: 20px;
    margin-right: 10px;
    text-align: center;
}
</style>
