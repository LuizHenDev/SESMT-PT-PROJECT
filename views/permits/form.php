<?php
/**
 * Work Permits - Form View (Create/Edit)
 */

global $PT_TYPES;

$isEdit = isset($permit) && $permit;
$title = $isEdit ? 'Editar PT' : 'Nova Permissão de Trabalho';
$submitText = $isEdit ? 'Atualizar' : 'Criar';
$formAction = $isEdit ? 'update' : 'store';
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <h2><i class="fas fa-file-contract"></i> <?php echo $title; ?></h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo APP_URL; ?>/index.php?page=permits&action=<?php echo $formAction; ?>">
            
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?php echo $permit['id']; ?>">
            <?php endif; ?>

            <div class="row">
                <!-- Colaborador -->
                <div class="col-md-6 mb-3">
                    <label for="employee_id" class="form-label">Colaborador *</label>
                    <select class="form-select" id="employee_id" name="employee_id" required>
                        <option value="">-- Selecione --</option>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?php echo $emp['id']; ?>" 
                                    <?php echo (isset($permit['employee_id']) && $permit['employee_id'] == $emp['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($emp['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tipo -->
                <div class="col-md-6 mb-3">
                    <label for="type" class="form-label">Tipo de PT *</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="">-- Selecione --</option>
                        <?php foreach ($PT_TYPES as $key => $name): ?>
                            <option value="<?php echo $key; ?>" 
                                    <?php echo (isset($permit['type']) && $permit['type'] === $key) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <!-- Setor -->
                <div class="col-md-6 mb-3">
                    <label for="department" class="form-label">Setor</label>
                    <input type="text" class="form-control" id="department" name="department" 
                           value="<?php echo htmlspecialchars($permit['department'] ?? ''); ?>">
                </div>

                <!-- Data Emissão -->
                <div class="col-md-6 mb-3">
                    <label for="issue_date" class="form-label">Data Emissão *</label>
                    <input type="date" class="form-control" id="issue_date" name="issue_date" required 
                           value="<?php echo isset($permit['issue_date']) ? $permit['issue_date'] : date('Y-m-d'); ?>">
                </div>
            </div>

            <div class="row">
                <!-- Data Validade -->
                <div class="col-md-6 mb-3">
                    <label for="expiry_date" class="form-label">Data Validade</label>
                    <input type="date" class="form-control" id="expiry_date" name="expiry_date" 
                           value="<?php echo isset($permit['expiry_date']) && $permit['expiry_date'] ? $permit['expiry_date'] : ''; ?>">
                </div>

                <!-- Status -->
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="ativa" <?php echo (isset($permit['status']) && $permit['status'] === 'ativa') ? 'selected' : ''; ?>>Ativa</option>
                        <option value="expirada" <?php echo (isset($permit['status']) && $permit['status'] === 'expirada') ? 'selected' : ''; ?>>Expirada</option>
                        <option value="cancelada" <?php echo (isset($permit['status']) && $permit['status'] === 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                    </select>
                </div>
            </div>

            <!-- Descrição -->
            <div class="mb-3">
                <label for="description" class="form-label">Descrição</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($permit['description'] ?? ''); ?></textarea>
            </div>

            <!-- Validado por -->
            <div class="mb-3">
                <label for="validated_by" class="form-label">Validado por</label>
                <input type="text" class="form-control" id="validated_by" name="validated_by" 
                       value="<?php echo htmlspecialchars($permit['validated_by'] ?? ''); ?>">
            </div>

            <!-- Botões -->
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i> <?php echo $submitText; ?>
                </button>
                <a href="<?php echo APP_URL; ?>/index.php?page=permits" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
