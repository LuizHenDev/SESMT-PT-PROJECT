<?php
/**
 * Users - Form View (Create/Edit)
 */

global $ROLES;

$isEdit = isset($user) && $user;
$title = $isEdit ? 'Editar Usuário' : 'Novo Usuário';
$submitText = $isEdit ? 'Atualizar' : 'Criar';
$formAction = $isEdit ? 'update' : 'store';
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <h2><i class="fas fa-user-plus"></i> <?php echo $title; ?></h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo APP_URL; ?>/index.php?page=users&action=<?php echo $formAction; ?>" id="userForm">
            
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <?php endif; ?>

            <!-- Nome -->
            <div class="mb-3">
                <label for="name" class="form-label">Nome Completo *</label>
                <input type="text" class="form-control" id="name" name="name" required 
                       value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>">
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email *</label>
                <input type="email" class="form-control" id="email" name="email" required 
                       value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
            </div>

            <!-- Senha -->
            <div class="mb-3">
                <label for="password" class="form-label">
                    Senha <?php echo $isEdit ? '(deixe em branco para manter)' : '*'; ?>
                </label>
                <input type="password" class="form-control" id="password" name="password" 
                       <?php echo !$isEdit ? 'required' : ''; ?> 
                       minlength="6">
                <small class="form-text text-muted">Mínimo 6 caracteres</small>
            </div>

            <!-- Confirmação Senha -->
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmar Senha</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                       <?php echo !$isEdit ? 'required' : ''; ?>>
            </div>

            <!-- Função -->
            <div class="mb-3">
                <label for="role" class="form-label">Função *</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="">-- Selecione --</option>
                    <?php foreach ($ROLES as $key => $name): ?>
                        <option value="<?php echo $key; ?>" <?php echo (isset($user['role']) && $user['role'] === $key) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Botões -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i> <?php echo $submitText; ?>
                </button>
                <a href="<?php echo APP_URL; ?>/index.php?page=users" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Validação de senhas
    document.getElementById('userForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('confirm_password').value;
        
        if (password !== confirm) {
            e.preventDefault();
            alert('Senhas não correspondem!');
        }
    });
</script>
