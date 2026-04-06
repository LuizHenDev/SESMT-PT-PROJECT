<?php
/**
 * Accident Model
 * Registro de Acidentes
 */

class Accident {
    
    public function findById($id) {
        $query = "SELECT a.*, e.name as employee_name, e.cpf, e.job_title 
                  FROM accidents a
                  LEFT JOIN employees e ON a.employee_id = e.id
                  WHERE a.id = ?
                  LIMIT 1";
        
        return Database::getInstance()->fetchOne($query, [$id], 'i');
    }
    
    public function getAll($limit = null, $offset = 0) {
        $query = "SELECT a.*, e.name as employee_name, e.cpf
                  FROM accidents a
                  LEFT JOIN employees e ON a.employee_id = e.id
                  ORDER BY a.accident_date DESC, a.accident_time DESC";
        
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
        $query = "SELECT COUNT(*) as total FROM accidents";
        $result = Database::getInstance()->fetchOne($query);
        return $result ? $result['total'] : 0;
    }
    
    public function countByStatus() {
        $query = "SELECT status, COUNT(*) as count FROM accidents GROUP BY status";
        $result = Database::getInstance()->fetchAll($query);
        $data = [];
        foreach ($result as $item) {
            $data[$item['status']] = $item['count'];
        }
        return $data;
    }
    
    public function getRecentCount($days = 30) {
        $query = "SELECT COUNT(*) as total FROM accidents WHERE accident_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)";
        $result = Database::getInstance()->fetchOne($query, [$days], 'i');
        return $result ? $result['total'] : 0;
    }
    
    public function create($data) {
        if (empty($data['employee_id']) || empty($data['accident_date']) || empty($data['description'])) {
            throw new Exception("Dados obrigatórios: colaborador, data, descrição.");
        }
        
        $query = "INSERT INTO accidents (employee_id, accident_date, accident_time, description, injury_type, body_part, status, action_plan, investigation_responsible) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $values = [
            $data['employee_id'],
            convertDateFormat($data['accident_date']),
            $data['accident_time'] ?? null,
            $data['description'],
            $data['injury_type'] ?? null,
            $data['body_part'] ?? null,
            $data['status'] ?? 'aberto',
            $data['action_plan'] ?? null,
            $data['investigation_responsible'] ?? null
        ];
        
        Database::getInstance()->query($query, $values, 'issssssss');
        return Database::getInstance()->lastInsertId();
    }
    
    public function update($id, $data) {
        $allowed = ['employee_id', 'accident_date', 'accident_time', 'description', 'injury_type', 'body_part', 'status', 'action_plan', 'investigation_responsible'];
        $updates = [];
        $values = [];
        $types = '';
        
        foreach ($data as $key => $value) {
            if (in_array($key, $allowed)) {
                if ($key === 'accident_date') {
                    $value = convertDateFormat($value);
                }
                if ($key === 'employee_id') {
                    $types .= 'i';
                } else {
                    $types .= 's';
                }
                $updates[] = "$key = ?";
                $values[] = $value;
            }
        }
        
        if (empty($updates)) {
            return 0;
        }
        
        $values[] = $id;
        $types .= 'i';
        
        $query = "UPDATE accidents SET " . implode(', ', $updates) . ", updated_at = NOW() WHERE id = ?";
        return Database::getInstance()->query($query, $values, $types);
    }
    
    public function delete($id) {
        $query = "DELETE FROM accidents WHERE id = ?";
        return Database::getInstance()->query($query, [$id], 'i');
    }
}
?>
