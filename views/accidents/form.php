<?php
/**
 * Accidents - Form View (Create/Edit)
 */

global $ACCIDENT_STATUS;

$isEdit = isset($accident) && $accident;
$title = $isEdit ? 'Editar Acidente' : 'Registrar Acidente';
$submitText = $isEdit ? 'Atualizar' : 'Registrar';
$formAction = $isEdit ? 'update' : 'store';
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <h2><i class="fas fa-ambulance"></i> <?php echo $title; ?></h2>

        <form method="POST" action="<?php echo APP_URL; ?>/index.php?page=accidents&action=<?php echo $formAction; ?>">
            
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?php echo $accident['id']; ?>">
            <?php endif; ?>

            <!-- Colaborador -->
            <div class="mb-3">
                <label for="employee_id" class="form-label">Colaborador *</label>
                <select class="form-select" id="employee_id" name="employee_id" required>
                    <option value="">-- Selecione --</option>
                    <?php foreach ($employees as $emp): ?>
                        <option value="<?php echo $emp['id']; ?>" 
                                <?php echo (isset($accident['employee_id']) && $accident['employee_id'] == $emp['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($emp['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row">
                <!-- Data do Acidente -->
                <div class="col-md-6 mb-3">
                    <label for="accident_date" class="form-label">Data do Acidente *</label>
                    <input type="date" class="form-control" id="accident_date" name="accident_date" required 
                           value="<?php echo isset($accident['accident_date']) ? $accident['accident_date'] : date('Y-m-d'); ?>">
                </div>

                <!-- Hora do Acidente -->
                <div class="col-md-6 mb-3">
                    <label for="accident_time" class="form-label">Hora do Acidente</label>
                    <input type="time" class="form-control" id="accident_time" name="accident_time" 
                           value="<?php echo htmlspecialchars($accident['accident_time'] ?? ''); ?>">
                </div>
            </div>

            <!-- Descrição -->
            <div class="mb-3">
                <label for="description" class="form-label">Descrição do Acidente *</label>
                <textarea class="form-control" id="description" name="description" required rows="4"><?php echo htmlspecialchars($accident['description'] ?? ''); ?></textarea>
            </div>

            <div class="row">
                <!-- Tipo de Lesão -->
                <div class="col-md-6 mb-3">
                    <label for="injury_type" class="form-label">Tipo de Lesão</label>
                    <input type="text" class="form-control" id="injury_type" name="injury_type" 
                           placeholder="Ex: Queimadura, Fratura, etc"
                           value="<?php echo htmlspecialchars($accident['injury_type'] ?? ''); ?>">
                </div>

                <!-- Parte do Corpo -->
                <div class="col-md-6 mb-3">
                    <label for="body_part" class="form-label">Parte do Corpo Atingida</label>
                    <input type="text" class="form-control" id="body_part" name="body_part" 
                           placeholder="Ex: Braço esquerdo"
                           value="<?php echo htmlspecialchars($accident['body_part'] ?? ''); ?>">
                </div>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status" class="form-label">Status *</label>
                <select class="form-select" id="status" name="status" required>
                    <?php foreach ($ACCIDENT_STATUS as $key => $name): ?>
                        <option value="<?php echo $key; ?>" <?php echo (isset($accident['status']) && $accident['status'] === $key) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Plano de Ação -->
            <div class="mb-3">
                <label for="action_plan" class="form-label">Plano de Ação / Medidas</label>
                <textarea class="form-control" id="action_plan" name="action_plan" rows="3"><?php echo htmlspecialchars($accident['action_plan'] ?? ''); ?></textarea>
            </div>

            <!-- Investigado por -->
            <div class="mb-3">
                <label for="investigation_responsible" class="form-label">Responsável pela Investigação</label>
                <input type="text" class="form-control" id="investigation_responsible" name="investigation_responsible" 
                       value="<?php echo htmlspecialchars($accident['investigation_responsible'] ?? ''); ?>">
            </div>

            <!-- Botões -->
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i> <?php echo $submitText; ?>
                </button>
                <a href="<?php echo APP_URL; ?>/index.php?page=accidents" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
