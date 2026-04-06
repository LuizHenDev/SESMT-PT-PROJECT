<?php
/**
 * Risks - Form View (Create/Edit)
 */

global $RISK_LEVELS;

$isEdit = isset($risk) && $risk;
$title = $isEdit ? 'Editar Risco' : 'Novo Risco';
$submitText = $isEdit ? 'Atualizar' : 'Criar';
$formAction = $isEdit ? 'update' : 'store';
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <h2><i class="fas fa-exclamation-triangle"></i> <?php echo $title; ?></h2>

        <form method="POST" action="<?php echo APP_URL; ?>/index.php?page=risks&action=<?php echo $formAction; ?>">
            
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?php echo $risk['id']; ?>">
            <?php endif; ?>

            <!-- Descrição -->
            <div class="mb-3">
                <label for="description" class="form-label">Descrição do Risco *</label>
                <textarea class="form-control" id="description" name="description" required rows="4"><?php echo htmlspecialchars($risk['description'] ?? ''); ?></textarea>
            </div>

            <div class="row">
                <!-- Nível -->
                <div class="col-md-4 mb-3">
                    <label for="level" class="form-label">Nível *</label>
                    <select class="form-select" id="level" name="level" required>
                        <option value="">-- Selecione --</option>
                        <?php foreach ($RISK_LEVELS as $key => $name): ?>
                            <option value="<?php echo $key; ?>" <?php echo (isset($risk['level']) && $risk['level'] === $key) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Setor -->
                <div class="col-md-8 mb-3">
                    <label for="department" class="form-label">Setor</label>
                    <input type="text" class="form-control" id="department" name="department" 
                           value="<?php echo htmlspecialchars($risk['department'] ?? ''); ?>">
                </div>
            </div>

            <!-- Medidas de Controle -->
            <div class="mb-3">
                <label for="control_measures" class="form-label">Medidas de Controle</label>
                <textarea class="form-control" id="control_measures" name="control_measures" rows="3"><?php echo htmlspecialchars($risk['control_measures'] ?? ''); ?></textarea>
            </div>

            <!-- Responsável -->
            <div class="mb-3">
                <label for="responsible_person" class="form-label">Responsável</label>
                <input type="text" class="form-control" id="responsible_person" name="responsible_person" 
                       value="<?php echo htmlspecialchars($risk['responsible_person'] ?? ''); ?>">
            </div>

            <!-- Botões -->
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i> <?php echo $submitText; ?>
                </button>
                <a href="<?php echo APP_URL; ?>/index.php?page=risks" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
