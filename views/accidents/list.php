<?php
/**
 * Accidents - List View
 */

global $ACCIDENT_STATUS, $ACCIDENT_COLORS;
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-ambulance"></i> Acidentes de Trabalho</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo APP_URL; ?>/index.php?page=accidents&action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Registrar Acidente
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="mb-3">
    <a href="<?php echo APP_URL; ?>/index.php?page=accidents" class="btn btn-outline-secondary btn-sm">Todos</a>
    <?php foreach ($ACCIDENT_STATUS as $key => $name): ?>
        <?php $buttonColor = $ACCIDENT_COLORS[$key] ?? 'secondary'; ?>
        <a href="<?php echo APP_URL; ?>/index.php?page=accidents&status=<?php echo $key; ?>" 
           class="btn btn-outline-<?php echo $buttonColor; ?> btn-sm">
            <?php echo htmlspecialchars($name); ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Colaborador</th>
                <th>Data/Hora</th>
                <th>Descrição</th>
                <th>Tipo Lesão</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($accidents)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Nenhum acidente registrado</td>
                </tr>
            <?php else: ?>
                <?php foreach ($accidents as $ac): ?>
                    <tr>
                        <td><?php echo $ac['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($ac['employee_name']); ?></strong></td>
                        <td><?php echo formatDate($ac['accident_date']); ?> <?php echo $ac['accident_time'] ? substr($ac['accident_time'], 0, 5) : ''; ?></td>
                        <td><?php echo htmlspecialchars(truncate($ac['description'], 40)); ?></td>
                        <td><?php echo htmlspecialchars($ac['injury_type'] ?? '-'); ?></td>
                        <td>
                            <?php $badgeColor = $ACCIDENT_COLORS[$ac['status']] ?? 'secondary'; ?>
                            <span class="badge bg-<?php echo $badgeColor; ?>">
                                <?php echo htmlspecialchars($ACCIDENT_STATUS[$ac['status']] ?? $ac['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?php echo APP_URL; ?>/index.php?page=accidents&action=edit&id=<?php echo $ac['id']; ?>" 
                               class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" 
                                    onclick="deleteItem(<?php echo $ac['id']; ?>, 'acidente')" 
                                    title="Deletar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<?php if ($pagination['total_pages'] > 1): ?>
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php if ($pagination['has_prev']): ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo APP_URL; ?>/index.php?page=accidents&page_num=1">Primeira</a>
                </li>
            <?php endif; ?>
            <li class="page-item active">
                <span class="page-link"><?php echo $pagination['current_page']; ?>/<?php echo $pagination['total_pages']; ?></span>
            </li>
            <?php if ($pagination['has_next']): ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo APP_URL; ?>/index.php?page=accidents&page_num=<?php echo $pagination['next_page']; ?>">Próxima</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
