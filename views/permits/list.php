<?php
/**
 * Work Permits - List View
 */

global $PT_TYPES;
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-file-contract"></i> Permissão de Trabalho</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo APP_URL; ?>/index.php?page=permits&action=create" class="btn btn-primary me-2">
            <i class="fas fa-plus"></i> Nova PT
        </a>
        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
            <i class="fas fa-print"></i> Imprimir
        </button>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Colaborador</th>
                <th>Tipo</th>
                <th>Setor</th>
                <th>Data Emissão</th>
                <th>Validade</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($permits)): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">Nenhuma permissão encontrada</td>
                </tr>
            <?php else: ?>
                <?php foreach ($permits as $permit): ?>
                    <tr>
                        <td><?php echo $permit['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($permit['employee_name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($PT_TYPES[$permit['type']] ?? $permit['type']); ?></td>
                        <td><?php echo htmlspecialchars($permit['department'] ?? '-'); ?></td>
                        <td><?php echo formatDate($permit['issue_date']); ?></td>
                        <td>
                            <?php 
                            if (isset($permit['expiry_date']) && $permit['expiry_date']) {
                                echo formatDate($permit['expiry_date']);
                                if (isExpired($permit['expiry_date'])) {
                                    echo ' <span class="badge bg-danger">Vencida</span>';
                                } elseif (isExpiringSoon($permit['expiry_date'], 60)) {
                                    echo ' <span class="badge bg-warning">Vencendo</span>';
                                }
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td>
                            <span class="badge bg-info"><?php echo htmlspecialchars($permit['status']); ?></span>
                        </td>
                        <td>
                            <a href="<?php echo APP_URL; ?>/index.php?page=permits&action=edit&id=<?php echo $permit['id']; ?>" 
                               class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" 
                                    onclick="deleteItem(<?php echo $permit['id']; ?>, 'permissão')" 
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
                    <a class="page-link" href="<?php echo APP_URL; ?>/index.php?page=permits&page_num=1">Primeira</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="<?php echo APP_URL; ?>/index.php?page=permits&page_num=<?php echo $pagination['prev_page']; ?>">Anterior</a>
                </li>
            <?php endif; ?>
            
            <li class="page-item active">
                <span class="page-link"><?php echo $pagination['current_page']; ?>/<?php echo $pagination['total_pages']; ?></span>
            </li>
            
            <?php if ($pagination['has_next']): ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo APP_URL; ?>/index.php?page=permits&page_num=<?php echo $pagination['next_page']; ?>">Próxima</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="<?php echo APP_URL; ?>/index.php?page=permits&page_num=<?php echo $pagination['total_pages']; ?>">Última</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
