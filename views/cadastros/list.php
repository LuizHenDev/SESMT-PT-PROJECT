<?php
/**
 * Cadastros - List View
 */

global $SETTING_TYPES;
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-list"></i> Cadastros</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo APP_URL; ?>/index.php?page=cadastros&action=create&type=<?php echo urlencode($type); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo item
        </a>
    </div>
</div>

<ul class="nav nav-tabs mb-4">
    <?php foreach ($SETTING_TYPES as $key => $label): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo $key === $type ? 'active' : ''; ?>" href="<?php echo APP_URL; ?>/index.php?page=cadastros&type=<?php echo urlencode($key); ?>">
                <?php echo htmlspecialchars($label); ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Código</th>
                <th>Descrição</th>
                <th>Ativo</th>
                <th>Ordem</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($items)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Nenhum item cadastrado para este tipo.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo $item['id']; ?></td>
                        <td><?php echo htmlspecialchars($item['code']); ?></td>
                        <td><?php echo htmlspecialchars($item['label']); ?></td>
                        <td>
                            <span class="badge <?php echo $item['active'] ? 'bg-success' : 'bg-secondary'; ?>">
                                <?php echo $item['active'] ? 'Sim' : 'Não'; ?>
                            </span>
                        </td>
                        <td><?php echo intval($item['sort_order']); ?></td>
                        <td>
                            <a href="<?php echo APP_URL; ?>/index.php?page=cadastros&action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-warning" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" onclick="if(confirm('Excluir este item?')) location.href='<?php echo APP_URL; ?>/index.php?page=cadastros&action=delete&id=<?php echo $item['id']; ?>';" title="Excluir">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
