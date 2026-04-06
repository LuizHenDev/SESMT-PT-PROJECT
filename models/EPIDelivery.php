<?php
/**
 * EPIDelivery Model
 * Controla entregas de EPIs aos colaboradores
 */

class EPIDelivery {
    
    public function getByEmployee($employeeId, $limit = null, $offset = null) {
        $query = "SELECT ed.*, e.name as epi_name, e.type as epi_type 
                  FROM epi_deliveries ed
                  JOIN epis e ON ed.epi_id = e.id
                  WHERE ed.employee_id = ?
                  ORDER BY ed.delivery_date DESC";
        
        if ($limit) {
            $query .= " LIMIT ? OFFSET ?";
            return Database::getInstance()->fetchAll($query, [$employeeId, $limit, $offset], 'iii');
        }
        
        return Database::getInstance()->fetchAll($query, [$employeeId], 'i');
    }
    
    public function getByEPI($epiId) {
        $query = "SELECT ed.*, emp.name as employee_name 
                  FROM epi_deliveries ed
                  JOIN employees emp ON ed.employee_id = emp.id
                  WHERE ed.epi_id = ?
                  ORDER BY ed.delivery_date DESC";
        
        return Database::getInstance()->fetchAll($query, [$epiId], 'i');
    }
    
    public function findById($id) {
        $query = "SELECT * FROM epi_deliveries WHERE id = ? LIMIT 1";
        return Database::getInstance()->fetchOne($query, [$id], 'i');
    }
    
    public function create($data) {
        $query = "INSERT INTO epi_deliveries (
                    epi_id, employee_id, delivery_date, quantity, 
                    delivery_condition, expiry_date, notes
                  ) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $types = 'iisssss';
        $params = [
            $data['epi_id'],
            $data['employee_id'],
            $data['delivery_date'],
            $data['quantity'],
            $data['condition'] ?? 'novo',
            $data['expiry_date'] ?? null,
            $data['notes'] ?? null
        ];
        
        return Database::getInstance()->query($query, $params, $types);
    }
    
    public function update($id, $data) {
        $updates = [];
        $params = [];
        $types = '';
        
        foreach ($data as $key => $value) {
            $column = $key === 'condition' ? 'delivery_condition' : $key;
            $updates[] = "$column = ?";
            $params[] = $value;
            $types .= is_int($value) ? 'i' : 's';
        }
        
        $params[] = $id;
        $types .= 'i';
        
        $query = "UPDATE epi_deliveries SET " . implode(', ', $updates) . " WHERE id = ?";
        
        return Database::getInstance()->query($query, $params, $types);
    }
    
    public function delete($id) {
        $query = "DELETE FROM epi_deliveries WHERE id = ?";
        return Database::getInstance()->query($query, [$id], 'i');
    }
}
