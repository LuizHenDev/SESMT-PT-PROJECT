<?php
/**
 * WorkPermit Model
 * Permissão de Trabalho (PT)
 */

class WorkPermit {
    
    public function findById($id) {
        $query = "SELECT * FROM work_permits WHERE id = ? LIMIT 1";
        return Database::getInstance()->fetchOne($query, [$id], 'i');
    }
    
    public function getAll($limit = null, $offset = 0) {
        $query = "SELECT wp.*, e.name as employee_name, e.job_title 
                  FROM work_permits wp
                  LEFT JOIN employees e ON wp.employee_id = e.id
                  ORDER BY wp.issue_date DESC";
        
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
        $query = "SELECT COUNT(*) as total FROM work_permits";
        $result = Database::getInstance()->fetchOne($query);
        return $result['total'];
    }
    
    public function create($data) {
        if (empty($data['employee_id']) || empty($data['type']) || empty($data['issue_date'])) {
            throw new Exception("Dados obrigatórios: colaborador, tipo, data emissão.");
        }
        
        $query = "INSERT INTO work_permits (employee_id, type, department, description, issue_date, expiry_date, validated_by, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $values = [
            $data['employee_id'],
            $data['type'],
            $data['department'] ?? null,
            $data['description'] ?? null,
            convertDateFormat($data['issue_date']),
            isset($data['expiry_date']) && !empty($data['expiry_date']) ? convertDateFormat($data['expiry_date']) : null,
            $data['validated_by'] ?? null,
            $data['status'] ?? 'ativa'
        ];
        
        Database::getInstance()->query($query, $values, 'isssssss');
        return Database::getInstance()->lastInsertId();
    }
    
    public function update($id, $data) {
        $allowed = ['type', 'department', 'description', 'issue_date', 'expiry_date', 'validated_by', 'status'];
        $updates = [];
        $values = [];
        $types = '';
        
        foreach ($data as $key => $value) {
            if (in_array($key, $allowed)) {
                if ($key === 'issue_date' || $key === 'expiry_date') {
                    $value = convertDateFormat($value);
                }
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
        
        $query = "UPDATE work_permits SET " . implode(', ', $updates) . ", updated_at = NOW() WHERE id = ?";
        return Database::getInstance()->query($query, $values, $types);
    }
    
    public function delete($id) {
        $query = "DELETE FROM work_permits WHERE id = ?";
        return Database::getInstance()->query($query, [$id], 'i');
    }
}
?>
