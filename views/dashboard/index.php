<?php
/**
 * Dashboard - Main Page
 */
?>

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12">
            <h1><i class="fas fa-chart-line"></i> Dashboard SESMT</h1>
            <p class="text-muted">Visão geral do sistema de saúde e segurança do trabalho</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-5">
        <!-- Total de Acidentes -->
        <div class="col-md-3 mb-4">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <p class="card-text">Acidentes</p>
                            <h3 class="card-title"><?php echo $stats['total_accidents']; ?></h3>
                            <small>Últimos 30 dias: <?php echo $stats['recent_accidents_30d']; ?></small>
                        </div>
                        <div class="col-4 text-center">
                            <i class="fas fa-ambulance fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total de Colaboradores -->
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <p class="card-text">Colaboradores</p>
                            <h3 class="card-title"><?php echo $stats['total_employees']; ?></h3>
                            <small>Ativos no sistema</small>
                        </div>
                        <div class="col-4 text-center">
                            <i class="fas fa-users fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total de EPIs -->
        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <p class="card-text">EPIs</p>
                            <h3 class="card-title"><?php echo $stats['total_epis']; ?></h3>
                            <small>Tipos cadastrados</small>
                        </div>
                        <div class="col-4 text-center">
                            <i class="fas fa-hard-hat fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total de Riscos -->
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <p class="card-text">Riscos</p>
                            <h3 class="card-title"><?php echo $stats['total_risks']; ?></h3>
                            <small>Identificados</small>
                        </div>
                        <div class="col-4 text-center">
                            <i class="fas fa-exclamation-triangle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <!-- Acidentes por Status -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-chart-pie"></i> Acidentes por Status</h5>
                </div>
                <div class="card-body">
                    <canvas id="accidentStatusChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Riscos por Nível -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-chart-doughnut"></i> Riscos por Nível</h5>
                </div>
                <div class="card-body">
                    <canvas id="riskLevelChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Accidents -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-list"></i> Últimos Acidentes</h5>
                    <a href="<?php echo APP_URL; ?>/index.php?page=accidents" class="btn btn-sm btn-primary">
                        Ver todos <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Colaborador</th>
                                <th>Data</th>
                                <th>Descrição</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($stats['last_accidents'])): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">Nenhum acidente registrado</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($stats['last_accidents'] as $ac): ?>
                                    <tr>
                                        <td><?php echo $ac['id']; ?></td>
                                        <td><strong><?php echo htmlspecialchars($ac['employee_name']); ?></strong></td>
                                        <td><?php echo formatDate($ac['accident_date']); ?></td>
                                        <td><?php echo htmlspecialchars(truncate($ac['description'], 50)); ?></td>
                                        <td>
                                            <span class="badge 
                                                <?php 
                                                if ($ac['status'] === 'aberto') echo 'bg-danger';
                                                elseif ($ac['status'] === 'investigado') echo 'bg-warning';
                                                else echo 'bg-success';
                                                ?>
                                            ">
                                                <?php echo htmlspecialchars($ac['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?php echo APP_URL; ?>/index.php?page=accidents&action=edit&id=<?php echo $ac['id']; ?>" 
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Dados do Backend (PHP)
    const accidentsByStatus = <?php echo json_encode($stats['accidents_by_status'] ?? []); ?>;
    const risksByLevel = <?php echo json_encode($stats['risks_by_level'] ?? []); ?>;

    // Chart 1: Acidentes por Status
    if (document.getElementById('accidentStatusChart')) {
        new Chart(document.getElementById('accidentStatusChart'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(accidentsByStatus),
                datasets: [{
                    data: Object.values(accidentsByStatus),
                    backgroundColor: [
                        '#dc3545',  // Aberto - Red
                        '#ff9800',  // Investigado - Orange
                        '#28a745'   // Fechado - Green
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Chart 2: Riscos por Nível
    if (document.getElementById('riskLevelChart')) {
        new Chart(document.getElementById('riskLevelChart'), {
            type: 'pie',
            data: {
                labels: Object.keys(risksByLevel),
                datasets: [{
                    data: Object.values(risksByLevel),
                    backgroundColor: [
                        '#28a745',  // Baixo - Green
                        '#ff9800',  // Médio - Orange
                        '#dc3545'   // Alto - Red
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
</script>
