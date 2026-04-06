<?php
/**
 * Risk Model
 * Gestão de Riscos (PGR/GRO)
 */

class Risk {
    
    public function findById($id) {
        $query = "SELECT * FROM risks WHERE id = ? LIMIT 1";
        return Database::getInstance()->fetchOne($query, [$id], 'i');
    }
    
    public function getAll($limit = null, $offset = 0, $level = null) {
        $query = "SELECT * FROM risks";
        $params = [];
        $types = '';
        
        if ($level) {
            $query .= " WHERE level = ?";
            $params[] = $level;
            $types = 's';
        }
        
        $query .= " ORDER BY level DESC, description ASC";
        
        if ($limit) {
            $query .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            $types .= 'ii';
        }
        
        return Database::getInstance()->fetchAll($query, $params, $types);
    }
    
    public function count() {
        $query = "SELECT COUNT(*) as total FROM risks";
        $result = Database::getInstance()->fetchOne($query);
        return $result ? $result['total'] : 0;
    }
    
    public function getCountByLevel() {
        $query = "SELECT level, COUNT(*) as count FROM risks GROUP BY level";
        $result = Database::getInstance()->fetchAll($query);
        $data = [];
        foreach ($result as $item) {
            $data[$item['level']] = $item['count'];
        }
        return $data;
    }
    
    public function create($data) {
        if (empty($data['description']) || empty($data['level'])) {
            throw new Exception("Descrição e nível do risco são obrigatórios.");
        }
        
        $query = "INSERT INTO risks (description, level, department, control_measures, responsible_person) 
                  VALUES (?, ?, ?, ?, ?)";
        
        $values = [
            $data['description'],
            $data['level'],
            $data['department'] ?? null,
            $data['control_measures'] ?? null,
            $data['responsible_person'] ?? null
        ];
        
        Database::getInstance()->query($query, $values, 'sssss');
        return Database::getInstance()->lastInsertId();
    }
    
    public function update($id, $data) {
        $allowed = ['description', 'level', 'department', 'control_measures', 'responsible_person'];
        $updates = [];
        $values = [];
        $types = '';
        
        foreach ($data as $key => $value) {
            if (in_array($key, $allowed)) {
                $updates[] = "$key = ?";
                $values[] = $value;
                $types .= 's';
            }
        }
        
        if (empty($updates)) {
            return 0;
        }
        
        $values[] = $id;
        $types .= 'i';
        
        $query = "UPDATE risks SET " . implode(', ', $updates) . ", updated_at = NOW() WHERE id = ?";
        return Database::getInstance()->query($query, $values, $types);
    }
    
    public function delete($id) {
        $query = "DELETE FROM risks WHERE id = ?";
        return Database::getInstance()->query($query, [$id], 'i');
    }
}
?>
