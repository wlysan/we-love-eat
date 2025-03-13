<?php
require_once __DIR__ . '/../config/database.php';

class Ingredient {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Create a new ingredient
     */
    public function create($name, $calories, $protein, $carbs, $fat, $fiber = null) {
        $sql = "INSERT INTO ingredients (name, calories, protein, carbs, fat, fiber) 
                VALUES (:name, :calories, :protein, :carbs, :fat, :fiber)";
        
        $params = [
            ':name' => $name,
            ':calories' => $calories,
            ':protein' => $protein,
            ':carbs' => $carbs,
            ':fat' => $fat,
            ':fiber' => $fiber
        ];
        
        try {
            $ingredientId = $this->db->insert($sql, $params);
            return $ingredientId;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get ingredient by ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM ingredients WHERE id = :id";
        return $this->db->fetchOne($sql, [':id' => $id]);
    }
    
    /**
     * Update ingredient
     */
    public function update($id, $data) {
        $allowedFields = ['name', 'calories', 'protein', 'carbs', 'fat', 'fiber'];
        
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
        $sql = "UPDATE ingredients 
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
     * Delete ingredient
     */
    public function delete($id) {
        // First check if ingredient is used in any meals
        $sql = "SELECT COUNT(*) as count FROM meal_ingredients WHERE ingredient_id = :id";
        $result = $this->db->fetchOne($sql, [':id' => $id]);
        
        if ($result && $result['count'] > 0) {
            return ['error' => 'Este ingrediente está sendo usado em refeições e não pode ser excluído.'];
        }
        
        $sql = "DELETE FROM ingredients WHERE id = :id";
        
        try {
            $this->db->query($sql, [':id' => $id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get all ingredients
     */
    public function getAll($search = null) {
        $sql = "SELECT * FROM ingredients";
        $params = [];
        
        if ($search) {
            $sql .= " WHERE name LIKE :search";
            $params[':search'] = "%$search%";
        }
        
        $sql .= " ORDER BY name ASC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Search ingredients by name
     */
    public function search($term) {
        $sql = "SELECT * FROM ingredients WHERE name LIKE :term ORDER BY name ASC";
        return $this->db->fetchAll($sql, [':term' => "%$term%"]);
    }
}