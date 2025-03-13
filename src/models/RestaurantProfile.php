<?php
require_once __DIR__ . '/../config/database.php';

class RestaurantProfile {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Create a new restaurant profile
     */
    public function create($userId, $cnpj, $address, $phone, $data = []) {
        $fields = ['user_id', 'cnpj', 'address', 'phone'];
        $placeholders = [':user_id', ':cnpj', ':address', ':phone'];
        $params = [
            ':user_id' => $userId,
            ':cnpj' => $cnpj,
            ':address' => $address,
            ':phone' => $phone
        ];
        
        $allowedFields = ['description', 'delivery_areas'];
        
        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $fields[] = $field;
                $placeholders[] = ":$field";
                $params[":$field"] = $value;
            }
        }
        
        $sql = "INSERT INTO restaurant_profiles (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        try {
            $profileId = $this->db->insert($sql, $params);
            return $profileId;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get restaurant profile by user ID
     */
    public function getByUserId($userId) {
        $sql = "SELECT rp.*, u.name, u.email 
                FROM restaurant_profiles rp
                JOIN users u ON rp.user_id = u.id
                WHERE rp.user_id = :user_id";
        
        return $this->db->fetchOne($sql, [':user_id' => $userId]);
    }
    
    /**
     * Get restaurant profile by ID
     */
    public function getById($id) {
        $sql = "SELECT rp.*, u.name, u.email 
                FROM restaurant_profiles rp
                JOIN users u ON rp.user_id = u.id
                WHERE rp.id = :id";
        
        return $this->db->fetchOne($sql, [':id' => $id]);
    }
    
    /**
     * Update restaurant profile
     */
    public function update($id, $data) {
        $allowedFields = ['cnpj', 'address', 'phone', 'description', 'delivery_areas'];
        
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
        $sql = "UPDATE restaurant_profiles 
                SET $updateClause, updated_at = CURRENT_TIMESTAMP 
                WHERE id = :id";
        
        try {
            $this->db->query($sql, $params);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get all restaurants
     */
    public function getAll() {
        $sql = "SELECT rp.*, u.name, u.email 
                FROM restaurant_profiles rp
                JOIN users u ON rp.user_id = u.id
                ORDER BY u.name ASC";
        
        return $this->db->fetchAll($sql);
    }
}