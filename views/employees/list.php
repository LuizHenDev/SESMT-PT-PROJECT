<?php
/**
 * Employees - List View
 */

global $EMPLOYEE_STATUS;
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-users"></i> Colaboradores</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo APP_URL; ?>/index.php?page=employees&action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo Colaborador
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="mb-3">
    <a href="<?php echo APP_URL; ?>/index.php?page=employees" class="btn btn-outline-secondary btn-sm">
        Todos (<?php echo isset($pagination['total_items']) ? $pagination['total_items'] : '0'; ?>)
    </a>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>Cargo</th>
                <th>Setor</th>
                <th>Data Admissão</th>
                <th>Email</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($employees)): ?>
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">Nenhum colaborador encontrado</td>
                </tr>
            <?php else: ?>
                <?php foreach ($employees as $emp): ?>
                    <tr>
                        <td><?php echo $emp['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($emp['name']); ?></strong></td>
                        <td><?php echo formatCPF($emp['cpf']); ?></td>
                        <td><?php echo htmlspecialchars($emp['job_title']); ?></td>
                        <td><?php echo htmlspecialchars($emp['department']); ?></td>
                        <td><?php echo formatDate($emp['hire_date']); ?></td>
                        <td><small><?php echo htmlspecialchars($emp['email'] ?? '-'); ?></small></td>
                        <td>
                            <span class="badge <?php echo $emp['status'] === 'ativo' ? 'bg-success' : 'bg-danger'; ?>">
                                <?php echo htmlspecialchars($EMPLOYEE_STATUS[$emp['status']] ?? $emp['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?php echo APP_URL; ?>/index.php?page=employees&action=edit&id=<?php echo $emp['id']; ?>" 
                               class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" 
                                    onclick="deleteItem(<?php echo $emp['id']; ?>, 'colaborador')" 
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
                    <a class="page-link" href="<?php echo APP_URL; ?>/index.php?page=employees&page_num=1">Primeira</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="<?php echo APP_URL; ?>/index.php?page=employees&page_num=<?php echo $pagination['prev_page']; ?>">Anterior</a>
                </li>
            <?php endif; ?>
            
            <li class="page-item active">
                <span class="page-link"><?php echo $pagination['current_page']; ?>/<?php echo $pagination['total_pages']; ?></span>
            </li>
            
            <?php if ($pagination['has_next']): ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo APP_URL; ?>/index.php?page=employees&page_num=<?php echo $pagination['next_page']; ?>">Próxima</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="<?php echo APP_URL; ?>/index.php?page=employees&page_num=<?php echo $pagination['total_pages']; ?>">Última</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
