<?php
/**
 * Users - List View
 */

global $ROLES;
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-users"></i> Usuários do Sistema</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo APP_URL; ?>/index.php?page=users&action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo Usuário
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Função</th>
                <th>Status</th>
                <th>Data Criação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Nenhum usuário encontrado</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($user['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="badge bg-info">
                                <?php echo htmlspecialchars($ROLES[$user['role']] ?? $user['role']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($user['active']): ?>
                                <span class="badge bg-success">Ativo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inativo</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo formatDateTime($user['created_at']); ?></td>
                        <td>
                            <a href="<?php echo APP_URL; ?>/index.php?page=users&action=edit&id=<?php echo $user['id']; ?>" 
                               class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" 
                                    onclick="deleteItem(<?php echo $user['id']; ?>, 'usuário')" 
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
                    <a class="page-link" href="<?php echo APP_URL; ?>/index.php?page=users&page_num=1">Primeira</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="<?php echo APP_URL; ?>/index.php?page=users&page_num=<?php echo $pagination['prev_page']; ?>">Anterior</a>
                </li>
            <?php endif; ?>
            
            <li class="page-item active">
                <span class="page-link"><?php echo $pagination['current_page']; ?> de <?php echo $pagination['total_pages']; ?></span>
            </li>
            
            <?php if ($pagination['has_next']): ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo APP_URL; ?>/index.php?page=users&page_num=<?php echo $pagination['next_page']; ?>">Próxima</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="<?php echo APP_URL; ?>/index.php?page=users&page_num=<?php echo $pagination['total_pages']; ?>">Última</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
