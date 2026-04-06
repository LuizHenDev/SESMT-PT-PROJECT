<?php
/**
 * Training - Form View (Create/Edit)
 */

$isEdit = isset($training) && $training;
$title = $isEdit ? 'Editar Treinamento' : 'Novo Treinamento';
$submitText = $isEdit ? 'Atualizar' : 'Criar';
$formAction = $isEdit ? 'update' : 'store';
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <h2><i class="fas fa-graduation-cap"></i> <?php echo $title; ?></h2>

        <form method="POST" action="<?php echo APP_URL; ?>/index.php?page=training&action=<?php echo $formAction; ?>">
            
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?php echo $training['id']; ?>">
            <?php endif; ?>

            <!-- Nome -->
            <div class="mb-3">
                <label for="name" class="form-label">Nome do Treinamento *</label>
                <input type="text" class="form-control" id="name" name="name" required 
                       value="<?php echo htmlspecialchars($training['name'] ?? ''); ?>">
            </div>

            <!-- Descrição -->
            <div class="mb-3">
                <label for="description" class="form-label">Descrição</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($training['description'] ?? ''); ?></textarea>
            </div>

            <div class="row">
                <!-- Duração -->
                <div class="col-md-6 mb-3">
                    <label for="duration_hours" class="form-label">Duração (horas)</label>
                    <input type="number" class="form-control" id="duration_hours" name="duration_hours" min="0" 
                           value="<?php echo $training['duration_hours'] ?? ''; ?>">
                </div>

                <!-- Obrigatório -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Treinamento Obrigatório</label>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_mandatory" name="is_mandatory" 
                               value="1" <?php echo (isset($training['is_mandatory']) && $training['is_mandatory']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="is_mandatory">
                            Sim, este é um treinamento obrigatório
                        </label>
                    </div>
                </div>
            </div>

            <!-- Conteúdo -->
            <div class="mb-3">
                <label for="content" class="form-label">Conteúdo do Treinamento</label>
                <textarea class="form-control" id="content" name="content" rows="4"><?php echo htmlspecialchars($training['content'] ?? ''); ?></textarea>
            </div>

            <!-- Botões -->
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i> <?php echo $submitText; ?>
                </button>
                <a href="<?php echo APP_URL; ?>/index.php?page=training" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
