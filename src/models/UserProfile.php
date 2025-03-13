<?php
require_once __DIR__ . '/../config/database.php';

class UserProfile {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Create a new user profile
     */
    public function create($userId, $data) {
        $allowedFields = [
            'birth_date', 'gender', 'height', 'current_weight', 
            'goal_weight', 'activity_level', 'health_conditions', 'dietary_restrictions'
        ];
        
        $fields = [];
        $placeholders = [];
        $params = [':user_id' => $userId];
        
        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $fields[] = $field;
                $placeholders[] = ":$field";
                $params[":$field"] = $value;
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $fields[] = 'user_id';
        $placeholders[] = ':user_id';
        
        $sql = "INSERT INTO user_profiles (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        try {
            $profileId = $this->db->insert($sql, $params);
            return $profileId;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get user profile by user ID
     */
    public function getByUserId($userId) {
        $sql = "SELECT * FROM user_profiles WHERE user_id = :user_id";
        return $this->db->fetchOne($sql, [':user_id' => $userId]);
    }
    
    /**
     * Update user profile
     */
    public function update($userId, $data) {
        $allowedFields = [
            'birth_date', 'gender', 'height', 'current_weight', 
            'goal_weight', 'activity_level', 'health_conditions', 'dietary_restrictions'
        ];
        
        $updates = [];
        $params = [':user_id' => $userId];
        
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
        $sql = "UPDATE user_profiles 
                SET $updateClause, updated_at = CURRENT_TIMESTAMP 
                WHERE user_id = :user_id";
        
        try {
            $this->db->query($sql, $params);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get user measurements history
     */
    public function getMeasurements($userId, $limit = null) {
        $sql = "SELECT * FROM user_measurements 
                WHERE user_id = :user_id 
                ORDER BY date DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        return $this->db->fetchAll($sql, [':user_id' => $userId]);
    }
    
    /**
     * Add a new measurement record
     */
    public function addMeasurement($userId, $data) {
        $allowedFields = [
            'date', 'weight', 'body_fat_percentage', 'waist', 
            'chest', 'arms', 'legs', 'notes'
        ];
        
        $fields = ['user_id'];
        $placeholders = [':user_id'];
        $params = [':user_id' => $userId];
        
        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $fields[] = $field;
                $placeholders[] = ":$field";
                $params[":$field"] = $value;
            }
        }
        
        if (count($fields) <= 1) {
            return false;
        }
        
        $sql = "INSERT INTO user_measurements (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        try {
            $measurementId = $this->db->insert($sql, $params);
            return $measurementId;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Calculate progress metrics for a user
     */
    public function calculateProgress($userId, $startDate = null, $endDate = null) {
        // If dates not provided, use last 30 days
        if (!$startDate) {
            $startDate = date('Y-m-d', strtotime('-30 days'));
        }
        
        if (!$endDate) {
            $endDate = date('Y-m-d');
        }
        
        $sql = "SELECT * FROM user_measurements 
                WHERE user_id = :user_id 
                AND date BETWEEN :start_date AND :end_date 
                ORDER BY date ASC";
        
        $params = [
            ':user_id' => $userId,
            ':start_date' => $startDate,
            ':end_date' => $endDate
        ];
        
        $measurements = $this->db->fetchAll($sql, $params);
        
        // If no measurements found, return empty result
        if (empty($measurements)) {
            return [
                'weight_change' => 0,
                'body_fat_change' => 0,
                'waist_change' => 0,
                'measurements' => []
            ];
        }
        
        // Calculate changes from first to last measurement
        $first = $measurements[0];
        $last = end($measurements);
        
        $weightChange = isset($last['weight'], $first['weight']) ? 
            $last['weight'] - $first['weight'] : 0;
            
        $bodyFatChange = isset($last['body_fat_percentage'], $first['body_fat_percentage']) ? 
            $last['body_fat_percentage'] - $first['body_fat_percentage'] : 0;
            
        $waistChange = isset($last['waist'], $first['waist']) ? 
            $last['waist'] - $first['waist'] : 0;
        
        return [
            'weight_change' => $weightChange,
            'body_fat_change' => $bodyFatChange,
            'waist_change' => $waistChange,
            'measurements' => $measurements
        ];
    }
}