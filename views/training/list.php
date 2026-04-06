<?php
/**
 * Training - List View
 */
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-graduation-cap"></i> Treinamentos</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo APP_URL; ?>/index.php?page=training&action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo Treinamento
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Duração (horas)</th>
                <th>Obrigatório</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($trainings)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Nenhum treinamento cadastrado</td>
                </tr>
            <?php else: ?>
                <?php foreach ($trainings as $t): ?>
                    <tr>
                        <td><?php echo $t['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($t['name']); ?></strong></td>
                        <td><?php echo $t['duration_hours'] ? $t['duration_hours'] . 'h' : '-'; ?></td>
                        <td>
                            <?php if ($t['is_mandatory']): ?>
                                <span class="badge bg-danger">Sim</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Não</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars(truncate($t['description'] ?? '', 50)); ?></td>
                        <td>
                            <a href="<?php echo APP_URL; ?>/index.php?page=training&action=edit&id=<?php echo $t['id']; ?>" 
                               class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" 
                                    onclick="deleteItem(<?php echo $t['id']; ?>, 'treinamento')" 
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
                    <a class="page-link" href="<?php echo APP_URL; ?>/index.php?page=training&page_num=1">Primeira</a>
                </li>
            <?php endif; ?>
            <li class="page-item active">
                <span class="page-link"><?php echo $pagination['current_page']; ?>/<?php echo $pagination['total_pages']; ?></span>
            </li>
            <?php if ($pagination['has_next']): ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo APP_URL; ?>/index.php?page=training&page_num=<?php echo $pagination['next_page']; ?>">Próxima</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
