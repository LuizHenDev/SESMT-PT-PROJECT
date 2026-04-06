<?php
/**
 * TrainingEmployee Model
 * Controla alocação e progresso de treinamentos dos colaboradores
 */

class TrainingEmployee {
    
    public function getByEmployee($employeeId) {
        $query = "SELECT te.*, t.name as training_name, t.is_mandatory, t.duration_hours
                  FROM training_employees te
                  JOIN trainings t ON te.training_id = t.id
                  WHERE te.employee_id = ?
                  ORDER BY te.completion_date DESC";
        
        return Database::getInstance()->fetchAll($query, [$employeeId], 'i');
    }
    
    public function getByTraining($trainingId) {
        $query = "SELECT te.*, emp.name as employee_name, emp.cpf
                  FROM training_employees te
                  JOIN employees emp ON te.employee_id = emp.id
                  WHERE te.training_id = ?
                  ORDER BY te.completion_date DESC";
        
        return Database::getInstance()->fetchAll($query, [$trainingId], 'i');
    }
    
    public function findById($id) {
        $query = "SELECT * FROM training_employees WHERE id = ? LIMIT 1";
        return Database::getInstance()->fetchOne($query, [$id], 'i');
    }
    
    public function findByTrainingAndEmployee($trainingId, $employeeId) {
        $query = "SELECT * FROM training_employees 
                  WHERE training_id = ? AND employee_id = ? 
                  LIMIT 1";
        return Database::getInstance()->fetchOne($query, [$trainingId, $employeeId], 'ii');
    }
    
    public function assignEmployee($trainingId, $employeeId) {
        // Verificar se já existe
        if ($this->findByTrainingAndEmployee($trainingId, $employeeId)) {
            throw new Exception("Colaborador já foi alocado a este treinamento");
        }
        
        $query = "INSERT INTO training_employees (training_id, employee_id, status)
                  VALUES (?, ?, 'pendente')";
        
        return Database::getInstance()->query($query, [$trainingId, $employeeId], 'ii');
    }
    
    public function updateStatus($id, $status, $completionDate = null) {
        $query = "UPDATE training_employees 
                  SET status = ?";
        
        $params = [$status];
        $types = 's';
        
        if ($completionDate) {
            $query .= ", completion_date = ?";
            $params[] = $completionDate;
            $types .= 's';
        }
        
        $query .= " WHERE id = ?";
        $params[] = $id;
        $types .= 'i';
        
        return Database::getInstance()->query($query, $params, $types);
    }
    
    public function delete($id) {
        $query = "DELETE FROM training_employees WHERE id = ?";
        return Database::getInstance()->query($query, [$id], 'i');
    }
    
    public function getPendingByEmployee($employeeId) {
        $query = "SELECT te.*, t.name as training_name, t.is_mandatory
                  FROM training_employees te
                  JOIN trainings t ON te.training_id = t.id
                  WHERE te.employee_id = ? AND te.status = 'pendente'
                  ORDER BY t.name";
        
        return Database::getInstance()->fetchAll($query, [$employeeId], 'i');
    }
    
    public function getOverdueByEmployee($employeeId) {
        $query = "SELECT te.*, t.name as training_name, t.is_mandatory
                  FROM training_employees te
                  JOIN trainings t ON te.training_id = t.id
                  WHERE te.employee_id = ? AND te.status = 'vencido'
                  ORDER BY te.expiry_date DESC";
        
        return Database::getInstance()->fetchAll($query, [$employeeId], 'i');
    }
}
