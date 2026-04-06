<?php
/**
 * EPI Model
 * Equipamentos de Proteção Individual
 */

class EPI {
    
    public function findById($id) {
        $query = "SELECT * FROM epis WHERE id = ? LIMIT 1";
        return Database::getInstance()->fetchOne($query, [$id], 'i');
    }
    
    public function getAll($limit = null, $offset = 0) {
        $query = "SELECT * FROM epis ORDER BY name ASC";
        
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
        $query = "SELECT COUNT(*) as total FROM epis";
        $result = Database::getInstance()->fetchOne($query);
        return $result ? $result['total'] : 0;
    }
    
    public function create($data) {
        if (empty($data['name']) || empty($data['type'])) {
            throw new Exception("Nome e tipo do EPI são obrigatórios.");
        }
        
        $query = "INSERT INTO epis (name, type, description, expiry_date, quantity, unit_cost) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        
        $values = [
            $data['name'],
            $data['type'],
            $data['description'] ?? null,
            isset($data['expiry_date']) && !empty($data['expiry_date']) ? convertDateFormat($data['expiry_date']) : null,
            $data['quantity'] ?? 0,
            $data['unit_cost'] ?? 0
        ];
        
        Database::getInstance()->query($query, $values, 'sssids');
        return Database::getInstance()->lastInsertId();
    }
    
    public function update($id, $data) {
        $allowed = ['name', 'type', 'description', 'expiry_date', 'quantity', 'unit_cost'];
        $updates = [];
        $values = [];
        $types = '';
        
        foreach ($data as $key => $value) {
            if (in_array($key, $allowed)) {
                if ($key === 'expiry_date') {
                    $value = convertDateFormat($value);
                }
                $updates[] = "$key = ?";
                $values[] = $value;
                $types .= (in_array($key, ['quantity']) ? 'i' : ($key === 'unit_cost' ? 'd' : 's'));
            }
        }
        
        if (empty($updates)) {
            return 0;
        }
        
        $values[] = $id;
        $types .= 'i';
        
        $query = "UPDATE epis SET " . implode(', ', $updates) . ", updated_at = NOW() WHERE id = ?";
        return Database::getInstance()->query($query, $values, $types);
    }
    
    public function delete($id) {
        $query = "DELETE FROM epis WHERE id = ?";
        return Database::getInstance()->query($query, [$id], 'i');
    }
}

/**
 * EPIDelivery Model
 * Histórico de entrega de EPIs
 */
class EPIDelivery {
    
    public function getByEmployee($employeeId) {
        $query = "SELECT ed.*, e.name as epi_name, e.type as epi_type, emp.name as employee_name
                  FROM epi_deliveries ed
                  LEFT JOIN epis e ON ed.epi_id = e.id
                  LEFT JOIN employees emp ON ed.employee_id = emp.id
                  WHERE ed.employee_id = ?
                  ORDER BY ed.delivery_date DESC";
        
        return Database::getInstance()->fetchAll($query, [$employeeId], 'i');
    }
    
    public function create($data) {
        if (empty($data['epi_id']) || empty($data['employee_id']) || empty($data['delivery_date'])) {
            throw new Exception("Dados obrigatórios: EPI, colaborador, data entrega.");
        }
        
        $query = "INSERT INTO epi_deliveries (epi_id, employee_id, delivery_date, quantity, delivery_condition, expiry_date, notes) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $values = [
            $data['epi_id'],
            $data['employee_id'],
            convertDateFormat($data['delivery_date']),
            $data['quantity'] ?? 1,
            $data['condition'] ?? 'novo',
            isset($data['expiry_date']) && !empty($data['expiry_date']) ? convertDateFormat($data['expiry_date']) : null,
            $data['notes'] ?? null
        ];
        
        Database::getInstance()->query($query, $values, 'iisssss');
        return Database::getInstance()->lastInsertId();
    }
}
?>
