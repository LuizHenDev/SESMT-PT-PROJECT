<?php
/**
 * User Model
 * Classe para gerenciar usuários no banco de dados
 */

require_once __DIR__ . '/../config/database.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Obtém usuário por email
     */
    public function findByEmail($email) {
        $query = "SELECT * FROM users WHERE email = ? LIMIT 1";
        return Database::getInstance()->fetchOne($query, [$email], 's');
    }
    
    /**
     * Obtém usuário por ID
     */
    public function findById($id) {
        $query = "SELECT * FROM users WHERE id = ? LIMIT 1";
        return Database::getInstance()->fetchOne($query, [$id], 'i');
    }
    
    /**
     * Autentica usuário (login)
     */
    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);
        
        if (!$user) {
            return false;
        }
        
        if (!$user['active']) {
            return false;
        }
        
        if (!verifyPassword($password, $user['password'])) {
            return false;
        }
        
        return $user;
    }
    
    /**
     * Cria novo usuário
     */
    public function create($name, $email, $password, $role = 'comum') {
        // Validar dados
        if (empty($name) || empty($email) || empty($password)) {
            throw new Exception("Nome, email e senha são obrigatórios.");
        }
        
        if (!validateEmail($email)) {
            throw new Exception("Email inválido.");
        }
        
        // Verificar se email já existe
        if ($this->findByEmail($email)) {
            throw new Exception("Email já cadastrado no sistema.");
        }
        
        // Hash de senha
        $hash = hashPassword($password);
        
        $query = "INSERT INTO users (name, email, password, role, active) VALUES (?, ?, ?, ?, 1)";
        $result = Database::getInstance()->query($query, [$name, $email, $hash, $role], 'ssss');
        
        if ($result === false) {
            throw new Exception("Erro ao criar usuário.");
        }
        
        return Database::getInstance()->lastInsertId();
    }
    
    /**
     * Atualiza usuário
     */
    public function update($id, $data) {
        $allowed = ['name', 'email', 'role', 'active'];
        $updates = [];
        $values = [];
        $types = '';
        
        foreach ($data as $key => $value) {
            if (in_array($key, $allowed)) {
                $updates[] = "$key = ?";
                $values[] = $value;
                $types .= (is_int($value)) ? 'i' : 's';
            }
        }
        
        if (empty($updates)) {
            return 0;
        }
        
        $values[] = $id;
        $types .= 'i';
        
        $query = "UPDATE users SET " . implode(', ', $updates) . ", updated_at = NOW() WHERE id = ?";
        return Database::getInstance()->query($query, $values, $types);
    }
    
    /**
     * Atualiza senha
     */
    public function updatePassword($id, $newPassword) {
        $hash = hashPassword($newPassword);
        $query = "UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?";
        return Database::getInstance()->query($query, [$hash, $id], 'si');
    }
    
    /**
     * Delete usuário
     */
    public function delete($id) {
        $query = "DELETE FROM users WHERE id = ?";
        return Database::getInstance()->query($query, [$id], 'i');
    }
    
    /**
     * Obtém todos os usuários
     */
    public function getAll($limit = null, $offset = 0) {
        $query = "SELECT id, name, email, role, active, created_at FROM users ORDER BY created_at DESC";
        
        if ($limit) {
            $query .= " LIMIT ? OFFSET ?";
            return Database::getInstance()->fetchAll($query, [$limit, $offset], 'ii');
        }
        
        return Database::getInstance()->fetchAll($query);
    }
    
    /**
     * Conta total de usuários
     */
    public function count() {
        $query = "SELECT COUNT(*) as total FROM users";
        $result = Database::getInstance()->fetchOne($query);
        return $result['total'];
    }
    
    /**
     * Valida email único (para atualização)
     */
    public function isEmailUnique($email, $excludeId = null) {
        $query = "SELECT COUNT(*) as count FROM users WHERE email = ?";
        $params = [$email];
        $types = 's';
        
        if ($excludeId) {
            $query .= " AND id != ?";
            $params[] = $excludeId;
            $types .= 'i';
        }
        
        $result = Database::getInstance()->fetchOne($query, $params, $types);
        return $result['count'] === 0;
    }
}
?>
