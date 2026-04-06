<?php
/**
 * EPIs - Form View (Create/Edit)
 */

$isEdit = isset($epi) && $epi;
$title = $isEdit ? 'Editar EPI' : 'Novo EPI';
$submitText = $isEdit ? 'Atualizar' : 'Criar';
$formAction = $isEdit ? 'update' : 'store';
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <h2><i class="fas fa-hard-hat"></i> <?php echo $title; ?></h2>

        <form method="POST" action="<?php echo APP_URL; ?>/index.php?page=epis&action=<?php echo $formAction; ?>">
            
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?php echo $epi['id']; ?>">
            <?php endif; ?>

            <div class="row">
                <!-- Nome -->
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nome *</label>
                    <input type="text" class="form-control" id="name" name="name" required 
                           value="<?php echo htmlspecialchars($epi['name'] ?? ''); ?>">
                </div>

                <!-- Tipo -->
                <div class="col-md-6 mb-3">
                    <label for="type" class="form-label">Tipo *</label>
                    <input type="text" class="form-control" id="type" name="type" required 
                           value="<?php echo htmlspecialchars($epi['type'] ?? ''); ?>">
                </div>
            </div>

            <!-- Descrição -->
            <div class="mb-3">
                <label for="description" class="form-label">Descrição</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($epi['description'] ?? ''); ?></textarea>
            </div>

            <div class="row">
                <!-- Data Validade -->
                <div class="col-md-4 mb-3">
                    <label for="expiry_date" class="form-label">Data Validade</label>
                    <input type="date" class="form-control" id="expiry_date" name="expiry_date" 
                           value="<?php echo isset($epi['expiry_date']) && $epi['expiry_date'] ? $epi['expiry_date'] : ''; ?>">
                </div>

                <!-- Quantidade -->
                <div class="col-md-4 mb-3">
                    <label for="quantity" class="form-label">Quantidade</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" min="0" 
                           value="<?php echo $epi['quantity'] ?? 0; ?>">
                </div>

                <!-- Valor Unitário -->
                <div class="col-md-4 mb-3">
                    <label for="unit_cost" class="form-label">Valor Unitário (R$)</label>
                    <input type="number" class="form-control" id="unit_cost" name="unit_cost" step="0.01" min="0" 
                           value="<?php echo $epi['unit_cost'] ?? 0; ?>">
                </div>
            </div>

            <!-- Botões -->
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i> <?php echo $submitText; ?>
                </button>
                <a href="<?php echo APP_URL; ?>/index.php?page=epis" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
