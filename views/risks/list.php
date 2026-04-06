<?php
/**
 * Risks - List View
 */

global $RISK_LEVELS, $RISK_COLORS;
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-exclamation-triangle"></i> Riscos (GRO)</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo APP_URL; ?>/index.php?page=risks&action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo Risco
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="mb-3">
    <a href="<?php echo APP_URL; ?>/index.php?page=risks" class="btn btn-outline-secondary btn-sm">Todos</a>
    <?php foreach ($RISK_LEVELS as $key => $name): ?>
        <a href="<?php echo APP_URL; ?>/index.php?page=risks&level=<?php echo $key; ?>" 
           class="btn btn-outline-<?php echo $RISK_COLORS[$key]; ?> btn-sm">
            <?php echo htmlspecialchars($name); ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Descrição</th>
                <th>Nível</th>
                <th>Setor</th>
                <th>Medidas de Controle</th>
                <th>Responsável</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($risks)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Nenhum risco encontrado</td>
                </tr>
            <?php else: ?>
                <?php foreach ($risks as $r): ?>
                    <tr>
                        <td><?php echo $r['id']; ?></td>
                        <td><?php echo htmlspecialchars(truncate($r['description'], 50)); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $RISK_COLORS[$r['level']]; ?>">
                                <?php echo htmlspecialchars($RISK_LEVELS[$r['level']]); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($r['department'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars(truncate($r['control_measures'] ?? '', 40)); ?></td>
                        <td><?php echo htmlspecialchars($r['responsible_person'] ?? '-'); ?></td>
                        <td>
                            <a href="<?php echo APP_URL; ?>/index.php?page=risks&action=edit&id=<?php echo $r['id']; ?>" 
                               class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" 
                                    onclick="deleteItem(<?php echo $r['id']; ?>, 'risco')" 
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
                    <a class="page-link" href="<?php echo APP_URL; ?>/index.php?page=risks&page_num=1">Primeira</a>
                </li>
            <?php endif; ?>
            <li class="page-item active">
                <span class="page-link"><?php echo $pagination['current_page']; ?>/<?php echo $pagination['total_pages']; ?></span>
            </li>
            <?php if ($pagination['has_next']): ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo APP_URL; ?>/index.php?page=risks&page_num=<?php echo $pagination['next_page']; ?>">Próxima</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
