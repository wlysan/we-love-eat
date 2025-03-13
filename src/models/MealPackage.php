<?php
require_once __DIR__ . '/../config/database.php';

class MealPackage {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Create a new meal package
     */
    public function create($data) {
        $fields = ['user_id', 'name', 'type', 'start_date', 'end_date', 'status'];
        $placeholders = [':user_id', ':name', ':type', ':start_date', ':end_date', ':status'];
        $params = [
            ':user_id' => $data['user_id'],
            ':name' => $data['name'],
            ':type' => $data['type'],
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date'],
            ':status' => $data['status']
        ];
        
        // Optional fields
        if (isset($data['preferences'])) {
            $fields[] = 'preferences';
            $placeholders[] = ':preferences';
            $params[':preferences'] = $data['preferences'];
        }
        
        if (isset($data['total_price'])) {
            $fields[] = 'total_price';
            $placeholders[] = ':total_price';
            $params[':total_price'] = $data['total_price'];
        }
        
        if (isset($data['meal_count'])) {
            $fields[] = 'meal_count';
            $placeholders[] = ':meal_count';
            $params[':meal_count'] = $data['meal_count'];
        }
        
        $sql = "INSERT INTO meal_packages (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        try {
            $packageId = $this->db->insert($sql, $params);
            return $packageId;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get package by ID
     */
    public function getById($id) {
        $sql = "SELECT p.*, u.name as user_name 
                FROM meal_packages p
                JOIN users u ON p.user_id = u.id
                WHERE p.id = :id";
        
        return $this->db->fetchOne($sql, [':id' => $id]);
    }
    
    /**
     * Update package
     */
    public function updatePackage($id, $data) {
        $allowedFields = ['name', 'status', 'total_price', 'meal_count', 'preferences'];
        
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
        $sql = "UPDATE meal_packages 
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
     * Add meal to package
     */
    public function addPackageMeal($packageId, $mealId, $date, $mealType) {
        $sql = "INSERT INTO package_meals (package_id, meal_id, delivery_date, meal_type) 
                VALUES (:package_id, :meal_id, :delivery_date, :meal_type)";
        
        $params = [
            ':package_id' => $packageId,
            ':meal_id' => $mealId,
            ':delivery_date' => $date,
            ':meal_type' => $mealType
        ];
        
        try {
            $id = $this->db->insert($sql, $params);
            return $id;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get meals for a package
     */
    public function getPackageMeals($packageId) {
        $sql = "SELECT pm.*, m.name as meal_name, m.price, m.meal_type as original_meal_type,
                r.id as restaurant_id, u.name as restaurant_name
                FROM package_meals pm
                JOIN meals m ON pm.meal_id = m.id
                JOIN restaurant_profiles r ON m.restaurant_id = r.id
                JOIN users u ON r.user_id = u.id
                WHERE pm.package_id = :package_id
                ORDER BY pm.delivery_date, 
                CASE pm.meal_type 
                    WHEN 'breakfast' THEN 1 
                    WHEN 'lunch' THEN 2 
                    WHEN 'dinner' THEN 3 
                    WHEN 'snack' THEN 4 
                    ELSE 5 
                END";
        
        return $this->db->fetchAll($sql, [':package_id' => $packageId]);
    }
    
    /**
     * Get user packages with filtering by status
     */
    public function getUserPackages($userId, $status = null) {
        $sql = "SELECT p.* 
                FROM meal_packages p
                WHERE p.user_id = :user_id";
        
        $params = [':user_id' => $userId];
        
        if ($status !== null) {
            $sql .= " AND p.status = :status";
            $params[':status'] = $status;
        }
        
        $sql .= " ORDER BY p.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get all packages with filters
     */
    public function getAllPackages($filters = []) {
        $sql = "SELECT p.*, u.name as user_name 
                FROM meal_packages p
                JOIN users u ON p.user_id = u.id
                WHERE 1=1";
        
        $params = [];
        
        if (isset($filters['status']) && !empty($filters['status'])) {
            $sql .= " AND p.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (isset($filters['type']) && !empty($filters['type'])) {
            $sql .= " AND p.type = :type";
            $params[':type'] = $filters['type'];
        }
        
        $sql .= " ORDER BY p.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Calculate total price for a package
     */
    public function calculatePackageTotal($packageId) {
        $sql = "SELECT SUM(m.price) as total_price 
                FROM package_meals pm
                JOIN meals m ON pm.meal_id = m.id
                WHERE pm.package_id = :package_id";
        
        $result = $this->db->fetchOne($sql, [':package_id' => $packageId]);
        return $result ? $result['total_price'] : 0;
    }
    
    /**
     * Get counts of package types
     */
    public function getPackageCounts() {
        $sql = "SELECT 
                    COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_count,
                    COUNT(CASE WHEN status = 'active' THEN 1 END) as active_count,
                    COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_count,
                    COUNT(CASE WHEN status = 'canceled' THEN 1 END) as canceled_count
                FROM meal_packages";
        
        return $this->db->fetchOne($sql);
    }
}