<?php
/**
 * Cadastros - Form View
 */

global $SETTING_TYPES;
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2><i class="fas fa-edit"></i> <?php echo $item ? 'Editar' : 'Novo'; ?> <?php echo htmlspecialchars($SETTING_TYPES[$type] ?? 'Cadastro'); ?></h2>
    </div>
</div>

<form method="POST" action="<?php echo APP_URL; ?>/index.php?page=cadastros&action=<?php echo $item ? 'update' : 'store'; ?>">
    <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
    <?php if ($item): ?>
        <input type="hidden" name="id" value="<?php echo intval($item['id']); ?>">
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="code" class="form-label">Código *</label>
            <input type="text" class="form-control" id="code" name="code" required value="<?php echo htmlspecialchars($item['code'] ?? ''); ?>">
            <div class="form-text">Por exemplo: altura, aberto, novo, ativo.</div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="label" class="form-label">Descrição *</label>
            <input type="text" class="form-control" id="label" name="label" required value="<?php echo htmlspecialchars($item['label'] ?? ''); ?>">
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sort_order" class="form-label">Ordem</label>
            <input type="number" class="form-control" id="sort_order" name="sort_order" value="<?php echo intval($item['sort_order'] ?? 0); ?>">
            <div class="form-text">Itens com menor ordem aparecem primeiro.</div>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Ativo</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="active" name="active" value="1" <?php echo isset($item['active']) && $item['active'] ? 'checked' : (!$item ? 'checked' : ''); ?>>
                <label class="form-check-label" for="active">Manter ativo</label>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Salvar
        </button>
        <a href="<?php echo APP_URL; ?>/index.php?page=cadastros&type=<?php echo urlencode($type); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
</form>
