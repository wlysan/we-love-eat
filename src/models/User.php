<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Create a new user
     */
    public function create($name, $email, $password, $role = 'user') {
        // Validate inputs
        if (empty($name) || empty($email) || empty($password)) {
            return ['error' => 'Todos os campos são obrigatórios'];
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['error' => 'Email inválido'];
        }
        
        // Check if email already exists
        $user = $this->getByEmail($email);
        if ($user) {
            return ['error' => 'Este email já está cadastrado'];
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => HASH_COST]);
        
        // Insert new user
        $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
        $params = [
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':role' => $role
        ];
        
        try {
            $userId = $this->db->insert($sql, $params);
            return [
                'id' => $userId,
                'name' => $name,
                'email' => $email,
                'role' => $role
            ];
        } catch (Exception $e) {
            return ['error' => 'Erro ao criar usuário: ' . $e->getMessage()];
        }
    }
    
    /**
     * Get user by ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        return $this->db->fetchOne($sql, [':id' => $id]);
    }
    
    /**
     * Get user by email
     */
    public function getByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        return $this->db->fetchOne($sql, [':email' => $email]);
    }
    
    /**
     * Update user
     */
    public function update($id, $data) {
        $allowedFields = ['name', 'email', 'role'];
        $updates = [];
        $params = [':id' => $id];
        
        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $updates[] = "$field = :$field";
                $params[":$field"] = $value;
            }
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $updateClause = implode(', ', $updates);
        $sql = "UPDATE users SET $updateClause, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        
        try {
            $this->db->query($sql, $params);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Update user password
     */
    public function updatePassword($id, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => HASH_COST]);
        $sql = "UPDATE users SET password = :password, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        
        try {
            $this->db->query($sql, [':id' => $id, ':password' => $hashedPassword]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Delete user
     */
    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        
        try {
            $this->db->query($sql, [':id' => $id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get all users
     */
    public function getAll($filter = null) {
        $sql = "SELECT * FROM users";
        $params = [];
        
        if ($filter && isset($filter['role'])) {
            $sql .= " WHERE role = :role";
            $params[':role'] = $filter['role'];
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Verify user password
     */
    public function verifyPassword($userId, $password) {
        $user = $this->getById($userId);
        if (!$user) {
            return false;
        }
        
        return password_verify($password, $user['password']);
    }
}