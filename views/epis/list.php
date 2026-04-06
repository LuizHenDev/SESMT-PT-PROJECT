<?php
/**
 * EPIs - List View
 */
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-hard-hat"></i> Equipamentos de Proteção Individual (EPIs)</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo APP_URL; ?>/index.php?page=epis&action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo EPI
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Quantidade</th>
                <th>Valor Unit.</th>
                <th>Validade</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($epis)): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">Nenhum EPI cadastrado</td>
                </tr>
            <?php else: ?>
                <?php foreach ($epis as $e): ?>
                    <tr>
                        <td><?php echo $e['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($e['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($e['type']); ?></td>
                        <td><span class="badge bg-info"><?php echo $e['quantity']; ?></span></td>
                        <td><?php echo formatMoney($e['unit_cost']); ?></td>
                        <td>
                            <?php 
                            if (isset($e['expiry_date']) && $e['expiry_date']) {
                                echo formatDate($e['expiry_date']);
                                if (isExpired($e['expiry_date'])) {
                                    echo ' <span class="badge bg-danger">Vencido</span>';
                                } elseif (isExpiringSoon($e['expiry_date'], 30)) {
                                    echo ' <span class="badge bg-warning">Vencendo</span>';
                                }
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td>
                            <?php if ($e['quantity'] <= 5): ?>
                                <span class="badge bg-danger">Em Falta</span>
                            <?php else: ?>
                                <span class="badge bg-success">OK</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo APP_URL; ?>/index.php?page=epis&action=delivery&id=<?php echo $e['id']; ?>" 
                               class="btn btn-sm btn-info" title="Entregar EPI">
                                <i class="fas fa-box"></i>
                            </a>
                            <a href="<?php echo APP_URL; ?>/index.php?page=epis&action=edit&id=<?php echo $e['id']; ?>" 
                               class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" 
                                    onclick="deleteItem(<?php echo $e['id']; ?>, 'EPI')" 
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
                    <a class="page-link" href="<?php echo APP_URL; ?>/index.php?page=epis&page_num=1">Primeira</a>
                </li>
            <?php endif; ?>
            <li class="page-item active">
                <span class="page-link"><?php echo $pagination['current_page']; ?>/<?php echo $pagination['total_pages']; ?></span>
            </li>
            <?php if ($pagination['has_next']): ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo APP_URL; ?>/index.php?page=epis&page_num=<?php echo $pagination['next_page']; ?>">Próxima</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
