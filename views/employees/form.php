<?php
/**
 * Employees - Form View (Create/Edit)
 */

global $EMPLOYEE_STATUS;

$isEdit = isset($employee) && $employee;
$title = $isEdit ? 'Editar Colaborador' : 'Novo Colaborador';
$submitText = $isEdit ? 'Atualizar' : 'Criar';
$formAction = $isEdit ? 'update' : 'store';
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <h2><i class="fas fa-user-plus"></i> <?php echo $title; ?></h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo APP_URL; ?>/index.php?page=employees&action=<?php echo $formAction; ?>" id="empForm">
            
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?php echo $employee['id']; ?>">
            <?php endif; ?>

            <div class="row">
                <!-- Nome -->
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nome Completo *</label>
                    <input type="text" class="form-control" id="name" name="name" required 
                           value="<?php echo htmlspecialchars($employee['name'] ?? ''); ?>">
                </div>

                <!-- CPF -->
                <div class="col-md-6 mb-3">
                    <label for="cpf" class="form-label">CPF *</label>
                    <input type="text" class="form-control cpf-input" id="cpf" name="cpf" required 
                           <?php echo $isEdit ? 'readonly' : ''; ?>
                           value="<?php echo htmlspecialchars($employee['cpf'] ?? ''); ?>"
                           placeholder="000.000.000-00">
                </div>
            </div>

            <div class="row">
                <!-- Email -->
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?php echo htmlspecialchars($employee['email'] ?? ''); ?>">
                </div>

                <!-- Telefone -->
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Telefone</label>
                    <input type="text" class="form-control phone-input" id="phone" name="phone" 
                           value="<?php echo htmlspecialchars($employee['phone'] ?? ''); ?>"
                           placeholder="(00) 99999-9999">
                </div>
            </div>

            <div class="row">
                <!-- Cargo -->
                <div class="col-md-6 mb-3">
                    <label for="job_title" class="form-label">Cargo *</label>
                    <input type="text" class="form-control" id="job_title" name="job_title" required 
                           value="<?php echo htmlspecialchars($employee['job_title'] ?? ''); ?>">
                </div>

                <!-- Setor -->
                <div class="col-md-6 mb-3">
                    <label for="department" class="form-label">Setor *</label>
                    <input type="text" class="form-control" id="department" name="department" required 
                           value="<?php echo htmlspecialchars($employee['department'] ?? ''); ?>">
                </div>
            </div>

            <div class="row">
                <!-- Data Admissão -->
                <div class="col-md-6 mb-3">
                    <label for="hire_date" class="form-label">Data Admissão *</label>
                    <input type="date" class="form-control" id="hire_date" name="hire_date" required 
                           value="<?php echo isset($employee['hire_date']) ? $employee['hire_date'] : ''; ?>">
                </div>

                <!-- Status -->
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status *</label>
                    <select class="form-select" id="status" name="status" required>
                        <?php foreach ($EMPLOYEE_STATUS as $key => $name): ?>
                            <option value="<?php echo $key; ?>" <?php echo (isset($employee['status']) && $employee['status'] === $key) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Botões -->
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i> <?php echo $submitText; ?>
                </button>
                <a href="<?php echo APP_URL; ?>/index.php?page=employees" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
