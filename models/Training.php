<?php
/**
 * Training Model
 * Treinamentos Obrigatórios
 */

class Training {
    
    public function findById($id) {
        $query = "SELECT * FROM trainings WHERE id = ? LIMIT 1";
        return Database::getInstance()->fetchOne($query, [$id], 'i');
    }
    
    public function getAll($limit = null, $offset = 0) {
        $query = "SELECT * FROM trainings ORDER BY name ASC";
        
        $params = [];
        $types = '';
        
        if ($limit) {
            $query .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            $types = 'ii';
        }
        
        return Database::getInstance()->fetchAll($query, $params, $types);
    }
    
    public function count() {
        $query = "SELECT COUNT(*) as total FROM trainings";
        $result = Database::getInstance()->fetchOne($query);
        return $result ? $result['total'] : 0;
    }
    
    public function create($data) {
        if (empty($data['name'])) {
            throw new Exception("Nome do treinamento é obrigatório.");
        }
        
        $query = "INSERT INTO trainings (name, description, is_mandatory, duration_hours, content) 
                  VALUES (?, ?, ?, ?, ?)";
        
        $values = [
            $data['name'],
            $data['description'] ?? null,
            isset($data['is_mandatory']) ? 1 : 0,
            $data['duration_hours'] ?? null,
            $data['content'] ?? null
        ];
        
        Database::getInstance()->query($query, $values, 'ssiss');
        return Database::getInstance()->lastInsertId();
    }
    
    public function update($id, $data) {
        $allowed = ['name', 'description', 'is_mandatory', 'duration_hours', 'content'];
        $updates = [];
        $values = [];
        $types = '';
        
        foreach ($data as $key => $value) {
            if (in_array($key, $allowed)) {
                if ($key === 'is_mandatory') {
                    $value = $value ? 1 : 0;
                }
                $updates[] = "$key = ?";
                $values[] = $value;
                $types .= ($key === 'is_mandatory' || $key === 'duration_hours' ? 'i' : 's');
            }
        }
        
        if (empty($updates)) {
            return 0;
        }
        
        $values[] = $id;
        $types .= 'i';
        
        $query = "UPDATE trainings SET " . implode(', ', $updates) . ", updated_at = NOW() WHERE id = ?";
        return Database::getInstance()->query($query, $values, $types);
    }
    
    public function delete($id) {
        $query = "DELETE FROM trainings WHERE id = ?";
        return Database::getInstance()->query($query, [$id], 'i');
    }
}

/**
 * TrainingEmployee Model
 * Controle de participação em treinamentos
 */
class TrainingEmployee {
    
    public function getByEmployee($employeeId) {
        $query = "SELECT te.*, t.name as training_name, t.is_mandatory
                  FROM training_employees te
                  LEFT JOIN trainings t ON te.training_id = t.id
                  WHERE te.employee_id = ?
                  ORDER BY te.created_at DESC";
        
        return Database::getInstance()->fetchAll($query, [$employeeId], 'i');
    }
    
    public function getByTraining($trainingId) {
        $query = "SELECT te.*, e.name as employee_name, e.cpf
                  FROM training_employees te
                  LEFT JOIN employees e ON te.employee_id = e.id
                  WHERE te.training_id = ?
                  ORDER BY e.name ASC";
        
        return Database::getInstance()->fetchAll($query, [$trainingId], 'i');
    }
    
    public function assignEmployee($trainingId, $employeeId) {
        // Verificar se já existe
        $query = "SELECT COUNT(*) as count FROM training_employees WHERE training_id = ? AND employee_id = ?";
        $result = Database::getInstance()->fetchOne($query, [$trainingId, $employeeId], 'ii');
        
        if ($result['count'] > 0) {
            throw new Exception("Colaborador já atribuído a este treinamento.");
        }
        
        $query = "INSERT INTO training_employees (training_id, employee_id, status) VALUES (?, ?, ?)";
        Database::getInstance()->query($query, [$trainingId, $employeeId, 'pendente'], 'iis');
        return Database::getInstance()->lastInsertId();
    }
    
    public function updateStatus($id, $status, $completionDate = null) {
        $query = "UPDATE training_employees SET status = ?, completion_date = ?, updated_at = NOW() WHERE id = ?";
        
        $values = [$status, $completionDate, $id];
        $types = 'ssi';
        
        return Database::getInstance()->query($query, $values, $types);
    }
    
    public function delete($id) {
        $query = "DELETE FROM training_employees WHERE id = ?";
        return Database::getInstance()->query($query, [$id], 'i');
    }
}
?>
