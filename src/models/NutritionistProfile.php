<?php
require_once __DIR__ . '/../config/database.php';

class NutritionistProfile {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Create a new nutritionist profile
     */
    public function create($userId, $professionalId, $data = []) {
        $fields = ['user_id', 'professional_id'];
        $placeholders = [':user_id', ':professional_id'];
        $params = [
            ':user_id' => $userId,
            ':professional_id' => $professionalId
        ];
        
        $allowedFields = ['specialties', 'bio', 'education', 'experience'];
        
        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $fields[] = $field;
                $placeholders[] = ":$field";
                $params[":$field"] = $value;
            }
        }
        
        $sql = "INSERT INTO nutritionist_profiles (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        try {
            $profileId = $this->db->insert($sql, $params);
            return $profileId;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get nutritionist profile by user ID
     */
    public function getByUserId($userId) {
        $sql = "SELECT np.*, u.name, u.email 
                FROM nutritionist_profiles np
                JOIN users u ON np.user_id = u.id
                WHERE np.user_id = :user_id";
        
        return $this->db->fetchOne($sql, [':user_id' => $userId]);
    }
    
    /**
     * Get nutritionist profile by ID
     */
    public function getById($id) {
        $sql = "SELECT np.*, u.name, u.email 
                FROM nutritionist_profiles np
                JOIN users u ON np.user_id = u.id
                WHERE np.id = :id";
        
        return $this->db->fetchOne($sql, [':id' => $id]);
    }
    
    /**
     * Update nutritionist profile
     */
    public function update($id, $data) {
        $allowedFields = ['professional_id', 'specialties', 'bio', 'education', 'experience'];
        
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
        $sql = "UPDATE nutritionist_profiles 
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
     * Get all nutritionists
     */
    public function getAll() {
        $sql = "SELECT np.*, u.name, u.email 
                FROM nutritionist_profiles np
                JOIN users u ON np.user_id = u.id
                ORDER BY u.name ASC";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get nutritionists by specialty
     */
    public function getBySpecialty($specialty) {
        $sql = "SELECT np.*, u.name, u.email 
                FROM nutritionist_profiles np
                JOIN users u ON np.user_id = u.id
                WHERE np.specialties LIKE :specialty
                ORDER BY u.name ASC";
        
        return $this->db->fetchAll($sql, [':specialty' => "%$specialty%"]);
    }
    
    /**
     * Get clients for a nutritionist
     */
    public function getClients($nutritionistId) {
        $sql = "SELECT DISTINCT u.id, u.name, u.email, dp.id as diet_plan_id, c.id as chat_id, c.share_progress
                FROM users u
                LEFT JOIN diet_plans dp ON u.id = dp.user_id AND dp.nutritionist_id = :nutritionist_id
                LEFT JOIN chats c ON u.id = c.user_id AND c.nutritionist_id = :nutritionist_id
                WHERE dp.id IS NOT NULL OR c.id IS NOT NULL
                ORDER BY u.name ASC";
        
        return $this->db->fetchAll($sql, [':nutritionist_id' => $nutritionistId]);
    }
}