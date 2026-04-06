<?php
/**
 * EPIs - Delivery Form
 */

global $EPI_CONDITIONS;
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <h2><i class="fas fa-box"></i> Entregar EPI: <?php echo htmlspecialchars($epi['name']); ?></h2>

        <form method="POST" action="<?php echo APP_URL; ?>/index.php?page=epis&action=storeDelivery">
            
            <input type="hidden" name="epi_id" value="<?php echo $epi['id']; ?>">

            <div class="row">
                <!-- Colaborador -->
                <div class="col-md-6 mb-3">
                    <label for="employee_id" class="form-label">Colaborador *</label>
                    <select class="form-select" id="employee_id" name="employee_id" required>
                        <option value="">-- Selecione --</option>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?php echo $emp['id']; ?>">
                                <?php echo htmlspecialchars($emp['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Data Entrega -->
                <div class="col-md-6 mb-3">
                    <label for="delivery_date" class="form-label">Data Entrega *</label>
                    <input type="date" class="form-control" id="delivery_date" name="delivery_date" required 
                           value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>

            <div class="row">
                <!-- Quantidade -->
                <div class="col-md-4 mb-3">
                    <label for="quantity" class="form-label">Quantidade *</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" required min="1" value="1">
                </div>

                <!-- Condição -->
                <div class="col-md-4 mb-3">
                    <label for="condition" class="form-label">Condição</label>
                    <select class="form-select" id="condition" name="condition">
                        <?php foreach ($EPI_CONDITIONS as $key => $name): ?>
                            <option value="<?php echo $key; ?>" <?php echo $key === 'novo' ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Validade -->
                <div class="col-md-4 mb-3">
                    <label for="expiry_date" class="form-label">Validade</label>
                    <input type="date" class="form-control" id="expiry_date" name="expiry_date" 
                           value="<?php echo isset($epi['expiry_date']) && $epi['expiry_date'] ? $epi['expiry_date'] : ''; ?>">
                </div>
            </div>

            <!-- Observações -->
            <div class="mb-3">
                <label for="notes" class="form-label">Observações</label>
                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
            </div>

            <!-- Botões -->
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-check"></i> Registrar Entrega
                </button>
                <a href="<?php echo APP_URL; ?>/index.php?page=epis" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
