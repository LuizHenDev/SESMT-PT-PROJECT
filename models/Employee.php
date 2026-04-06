<?php
/**
 * Employee Model
 * Gestão de colaboradores
 */

class Employee {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Obtém colaborador por ID
     */
    public function findById($id) {
        $query = "SELECT * FROM employees WHERE id = ? LIMIT 1";
        return Database::getInstance()->fetchOne($query, [$id], 'i');
    }
    
    /**
     * Obtém todos os colaboradores
     */
    public function getAll($limit = null, $offset = 0, $status = null) {
        $query = "SELECT * FROM employees";
        $params = [];
        $types = '';
        
        if ($status) {
            $query .= " WHERE status = ?";
            $params[] = $status;
            $types = 's';
        }
        
        $query .= " ORDER BY name ASC";
        
        if ($limit) {
            $query .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            $types .= 'ii';
        }
        
        return Database::getInstance()->fetchAll($query, $params, $types);
    }
    
    /**
     * Conta total de colaboradores
     */
    public function count() {
        $query = "SELECT COUNT(*) as total FROM employees";
        $result = Database::getInstance()->fetchOne($query);
        return $result ? $result['total'] : 0;
    }
    
    /**
     * Cria novo colaborador
     */
    public function create($data) {
        if (empty($data['name']) || empty($data['cpf']) || empty($data['job_title']) || empty($data['department']) || empty($data['hire_date'])) {
            throw new Exception("Dados obrigatórios: nome, CPF, cargo, setor, data admissão.");
        }
        
        // Verificar CPF único
        if ($this->findByCPF($data['cpf'])) {
            throw new Exception("CPF já cadastrado.");
        }
        
        $query = "INSERT INTO employees (name, cpf, email, job_title, department, hire_date, phone, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $values = [
            $data['name'],
            preg_replace('/\D/', '', $data['cpf']),
            $data['email'] ?? null,
            $data['job_title'],
            $data['department'],
            convertDateFormat($data['hire_date']),
            $data['phone'] ?? null,
            $data['status'] ?? 'ativo'
        ];
        
        Database::getInstance()->query($query, $values, 'ssssssss');
        return Database::getInstance()->lastInsertId();
    }
    
    /**
     * Atualiza colaborador
     */
    public function update($id, $data) {
        $allowed = ['name', 'email', 'job_title', 'department', 'phone', 'status'];
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
        
        $query = "UPDATE employees SET " . implode(', ', $updates) . ", updated_at = NOW() WHERE id = ?";
        return Database::getInstance()->query($query, $values, $types);
    }
    
    /**
     * Delete colaborador
     */
    public function delete($id) {
        $query = "DELETE FROM employees WHERE id = ?";
        return Database::getInstance()->query($query, [$id], 'i');
    }
    
    /**
     * Encontra colaborador por CPF
     */
    public function findByCPF($cpf, $excludeId = null) {
        $cpf = preg_replace('/\D/', '', $cpf);
        $query = "SELECT * FROM employees WHERE cpf = ?";
        $params = [$cpf];
        $types = 's';
        
        if ($excludeId) {
            $query .= " AND id != ?";
            $params[] = $excludeId;
            $types .= 'i';
        }
        
        return Database::getInstance()->fetchOne($query, $params, $types);
    }
}
?>
