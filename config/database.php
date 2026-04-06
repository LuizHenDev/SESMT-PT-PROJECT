<?php
/**
 * Database Connection Class
 * Configuração de conexão com MySQL usando MySQLi
 */

class Database {
    private static $instance = null;
    private $connection;
    
    // Credenciais do banco de dados
    private $host = 'localhost';
    private $db_name = 'sesmt_db';
    private $db_user = 'root';
    private $db_pass = ''; // XAMPP MySQL padrão não tem senha
    private $charset = 'utf8mb4';
    
    /**
     * Construtor privado (Singleton Pattern)
     */
    private function __construct() {
        $this->connect();
    }
    
    /**
     * Conecta ao banco de dados
     */
    private function connect() {
        // Desabilitar erros de relative paths
        error_reporting(E_ALL & ~E_WARNING);
        
        try {
            $this->connection = new mysqli(
                $this->host,
                $this->db_user,
                $this->db_pass,
                $this->db_name
            );
            
            // Verificar conexão
            if ($this->connection->connect_error) {
                throw new Exception("Erro ao conectar ao banco de dados: " . $this->connection->connect_error);
            }
            
            // Definir charset
            $this->connection->set_charset($this->charset);
            
        } catch (Exception $e) {
            die("Erro de conexão: " . $e->getMessage() . 
                "<br><br>Verifique se o MySQL está rodando e se o banco 'sesmt_db' foi criado.<br>" .
                "Importe o arquivo database.sql via phpMyAdmin.");
        }
    }
    
    /**
     * Retorna instância única da conexão (Singleton)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Retorna a conexão MySQLi
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Executa uma query preparada com segurança contra SQL Injection
     * 
     * @param string $query - SQL query com placeholders (?)
     * @param array $params - Parâmetros para bind
     * @param string $types - Tipos dos parâmetros (s=string, i=int, d=double, b=blob)
     * @return mysqli_result|bool
     */
    public function execute($query, $params = [], $types = '') {
        $stmt = $this->connection->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Erro ao preparar query: " . $this->connection->error);
        }
        
        // Fazer bind dos parâmetros se houver
        if (!empty($params)) {
            if (empty($types)) {
                // Auto-detectar tipos
                $types = '';
                foreach ($params as $param) {
                    if (is_int($param)) {
                        $types .= 'i';
                    } elseif (is_float($param)) {
                        $types .= 'd';
                    } else {
                        $types .= 's';
                    }
                }
            }
            
            $stmt->bind_param($types, ...$params);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar query: " . $stmt->error);
        }
        
        return $stmt;
    }
    
    /**
     * Executa SELECT e retorna todos os resultados
     */
    public function fetchAll($query, $params = [], $types = '') {
        $stmt = $this->execute($query, $params, $types);
        $result = $stmt->get_result();
        $data = [];
        
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        $stmt->close();
        return $data;
    }
    
    /**
     * Executa SELECT e retorna uma única linha
     */
    public function fetchOne($query, $params = [], $types = '') {
        $stmt = $this->execute($query, $params, $types);
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data;
    }
    
    /**
     * Executa INSERT/UPDATE/DELETE, retorna número de linhas afetadas
     */
    public function query($query, $params = [], $types = '') {
        $stmt = $this->execute($query, $params, $types);
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }
    
    /**
     * Retorna ID da last insert
     */
    public function lastInsertId() {
        return $this->connection->insert_id;
    }
    
    /**
     * Inicia transação
     */
    public function beginTransaction() {
        $this->connection->begin_transaction();
    }
    
    /**
     * Faz commit
     */
    public function commit() {
        $this->connection->commit();
    }
    
    /**
     * Faz rollback
     */
    public function rollback() {
        $this->connection->rollback();
    }
    
    /**
     * Fecha conexão
     */
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}

// Instanciar banco globalmente para facilitar uso
$db = Database::getInstance();
?>
