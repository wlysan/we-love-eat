<?php
require_once __DIR__ . '/../config/database.php';

class DietPlan {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Create a new diet plan
     */
    public function create($nutritionistId, $userId, $name, $startDate, $endDate, $data = []) {
        $fields = ['nutritionist_id', 'user_id', 'name', 'start_date', 'end_date'];
        $placeholders = [':nutritionist_id', ':user_id', ':name', ':start_date', ':end_date'];
        $params = [
            ':nutritionist_id' => $nutritionistId,
            ':user_id' => $userId,
            ':name' => $name,
            ':start_date' => $startDate,
            ':end_date' => $endDate
        ];
        
        $allowedFields = ['daily_calories', 'daily_protein', 'daily_carbs', 'daily_fat', 'notes'];
        
        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $fields[] = $field;
                $placeholders[] = ":$field";
                $params[":$field"] = $value;
            }
        }
        
        $sql = "INSERT INTO diet_plans (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        try {
            $planId = $this->db->insert($sql, $params);
            return $planId;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get diet plan by ID
     */
    public function getById($id) {
        $sql = "SELECT dp.*, n.user_id as nutritionist_user_id, u.name as nutritionist_name,
                cu.name as client_name
                FROM diet_plans dp
                JOIN nutritionist_profiles n ON dp.nutritionist_id = n.id
                JOIN users u ON n.user_id = u.id
                JOIN users cu ON dp.user_id = cu.id
                WHERE dp.id = :id";
        
        return $this->db->fetchOne($sql, [':id' => $id]);
    }
    
    /**
     * Get diet plans for a user
     */
    public function getByUserId($userId, $status = null) {
        $sql = "SELECT dp.*, n.user_id as nutritionist_user_id, u.name as nutritionist_name
                FROM diet_plans dp
                JOIN nutritionist_profiles n ON dp.nutritionist_id = n.id
                JOIN users u ON n.user_id = u.id
                WHERE dp.user_id = :user_id";
        
        $params = [':user_id' => $userId];
        
        if ($status) {
            $sql .= " AND dp.status = :status";
            $params[':status'] = $status;
        }
        
        $sql .= " ORDER BY dp.start_date DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get diet plans created by a nutritionist
     */
    public function getByNutritionistId($nutritionistId, $status = null) {
        $sql = "SELECT dp.*, u.name as client_name
                FROM diet_plans dp
                JOIN users u ON dp.user_id = u.id
                WHERE dp.nutritionist_id = :nutritionist_id";
        
        $params = [':nutritionist_id' => $nutritionistId];
        
        if ($status) {
            $sql .= " AND dp.status = :status";
            $params[':status'] = $status;
        }
        
        $sql .= " ORDER BY dp.start_date DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Update diet plan
     */
    public function update($id, $data) {
        $allowedFields = ['name', 'start_date', 'end_date', 'daily_calories', 
                          'daily_protein', 'daily_carbs', 'daily_fat', 'notes', 'status'];
        
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
        $sql = "UPDATE diet_plans 
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
     * Add a meal to a diet plan
     */
    public function addMeal($dietPlanId, $mealType, $dayOfWeek, $timeOfDay, $data = []) {
        $fields = ['diet_plan_id', 'meal_type', 'day_of_week', 'time_of_day'];
        $placeholders = [':diet_plan_id', ':meal_type', ':day_of_week', ':time_of_day'];
        $params = [
            ':diet_plan_id' => $dietPlanId,
            ':meal_type' => $mealType,
            ':day_of_week' => $dayOfWeek,
            ':time_of_day' => $timeOfDay
        ];
        
        $allowedFields = ['calories_target', 'protein_target', 'carbs_target', 'fat_target', 'notes'];
        
        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $fields[] = $field;
                $placeholders[] = ":$field";
                $params[":$field"] = $value;
            }
        }
        
        $sql = "INSERT INTO diet_meals (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        try {
            $dietMealId = $this->db->insert($sql, $params);
            return $dietMealId;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Update a diet meal
     */
    public function updateMeal($dietMealId, $data) {
        $allowedFields = ['meal_type', 'day_of_week', 'time_of_day', 'calories_target', 
                          'protein_target', 'carbs_target', 'fat_target', 'notes'];
        
        $updates = [];
        $params = [':id' => $dietMealId];
        
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
        $sql = "UPDATE diet_meals 
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
     * Delete a diet meal
     */
    public function deleteMeal($dietMealId) {
        // First delete any meal selections for this diet meal
        $sql = "DELETE FROM user_meal_selections WHERE diet_meal_id = :diet_meal_id";
        $this->db->query($sql, [':diet_meal_id' => $dietMealId]);
        
        // Then delete the diet meal
        $sql = "DELETE FROM diet_meals WHERE id = :id";
        
        try {
            $this->db->query($sql, [':id' => $dietMealId]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get meals for a diet plan
     */
    public function getMeals($dietPlanId) {
        $sql = "SELECT * FROM diet_meals 
                WHERE diet_plan_id = :diet_plan_id 
                ORDER BY day_of_week ASC, time_of_day ASC";
        
        return $this->db->fetchAll($sql, [':diet_plan_id' => $dietPlanId]);
    }
    
    /**
     * Select a meal for a diet plan slot
     */
    public function selectMeal($userId, $dietMealId, $mealId, $date) {
        // Check if there's already a selection for this date and diet meal
        $sql = "SELECT id FROM user_meal_selections 
                WHERE user_id = :user_id AND diet_meal_id = :diet_meal_id AND date = :date";
        
        $existingSelection = $this->db->fetchOne($sql, [
            ':user_id' => $userId,
            ':diet_meal_id' => $dietMealId,
            ':date' => $date
        ]);
        
        if ($existingSelection) {
            // Update existing selection
            $sql = "UPDATE user_meal_selections 
                    SET meal_id = :meal_id, status = 'selected', updated_at = CURRENT_TIMESTAMP 
                    WHERE id = :id";
            
            try {
                $this->db->query($sql, [':id' => $existingSelection['id'], ':meal_id' => $mealId]);
                return $existingSelection['id'];
            } catch (Exception $e) {
                return false;
            }
        } else {
            // Create new selection
            $sql = "INSERT INTO user_meal_selections (user_id, diet_meal_id, meal_id, date) 
                    VALUES (:user_id, :diet_meal_id, :meal_id, :date)";
            
            try {
                $selectionId = $this->db->insert($sql, [
                    ':user_id' => $userId,
                    ':diet_meal_id' => $dietMealId,
                    ':meal_id' => $mealId,
                    ':date' => $date
                ]);
                return $selectionId;
            } catch (Exception $e) {
                return false;
            }
        }
    }
    
    /**
     * Update meal selection status (consumed or skipped)
     */
    public function updateMealStatus($selectionId, $status) {
        if (!in_array($status, ['selected', 'consumed', 'skipped'])) {
            return false;
        }
        
        $sql = "UPDATE user_meal_selections 
                SET status = :status, updated_at = CURRENT_TIMESTAMP 
                WHERE id = :id";
        
        try {
            $this->db->query($sql, [':id' => $selectionId, ':status' => $status]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get meal selections for a user on a specific date
     */
    public function getUserMealSelections($userId, $date) {
        $sql = "SELECT ums.id, ums.diet_meal_id, ums.meal_id, ums.status, 
                       dm.meal_type, dm.day_of_week, dm.time_of_day, 
                       dm.calories_target, dm.protein_target, dm.carbs_target, dm.fat_target,
                       m.name as meal_name, u.name as restaurant_name
                FROM user_meal_selections ums
                JOIN diet_meals dm ON ums.diet_meal_id = dm.id
                JOIN meals m ON ums.meal_id = m.id
                JOIN restaurant_profiles r ON m.restaurant_id = r.id
                JOIN users u ON r.user_id = u.id
                WHERE ums.user_id = :user_id AND ums.date = :date
                ORDER BY dm.time_of_day ASC";
        
        return $this->db->fetchAll($sql, [':user_id' => $userId, ':date' => $date]);
    }
    
    /**
     * Get user compliance with diet plan
     */
    public function getUserCompliance($userId, $dietPlanId, $startDate = null, $endDate = null) {
        // If dates not provided, use diet plan dates
        if (!$startDate || !$endDate) {
            $plan = $this->getById($dietPlanId);
            if (!$plan) {
                return false;
            }
            
            $startDate = $startDate ?: $plan['start_date'];
            $endDate = $endDate ?: $plan['end_date'];
        }
        
        // Get total number of diet meals for this plan
        $sql = "SELECT COUNT(*) as total FROM diet_meals WHERE diet_plan_id = :diet_plan_id";
        $totalMeals = $this->db->fetchOne($sql, [':diet_plan_id' => $dietPlanId]);
        $totalMeals = $totalMeals['total'] * (strtotime($endDate) - strtotime($startDate)) / (60*60*24);
        
        // Get number of consumed meals
        $sql = "SELECT COUNT(*) as consumed 
                FROM user_meal_selections ums
                JOIN diet_meals dm ON ums.diet_meal_id = dm.id
                WHERE ums.user_id = :user_id 
                AND dm.diet_plan_id = :diet_plan_id
                AND ums.date BETWEEN :start_date AND :end_date
                AND ums.status = 'consumed'";
        
        $consumedMeals = $this->db->fetchOne($sql, [
            ':user_id' => $userId,
            ':diet_plan_id' => $dietPlanId,
            ':start_date' => $startDate,
            ':end_date' => $endDate
        ]);
        
        // Calculate nutrition stats for consumed meals
        $sql = "SELECT 
                SUM(i.calories * mi.amount / 100) as total_calories,
                SUM(i.protein * mi.amount / 100) as total_protein,
                SUM(i.carbs * mi.amount / 100) as total_carbs,
                SUM(i.fat * mi.amount / 100) as total_fat
                FROM user_meal_selections ums
                JOIN diet_meals dm ON ums.diet_meal_id = dm.id
                JOIN meals m ON ums.meal_id = m.id
                JOIN meal_ingredients mi ON m.id = mi.meal_id
                JOIN ingredients i ON mi.ingredient_id = i.id
                WHERE ums.user_id = :user_id 
                AND dm.diet_plan_id = :diet_plan_id
                AND ums.date BETWEEN :start_date AND :end_date
                AND ums.status = 'consumed'";
        
        $nutritionStats = $this->db->fetchOne($sql, [
            ':user_id' => $userId,
            ':diet_plan_id' => $dietPlanId,
            ':start_date' => $startDate,
            ':end_date' => $endDate
        ]);
        
        // Get diet plan target values
        $plan = $this->getById($dietPlanId);
        
        // Calculate compliance percentages
        $daysInPeriod = max(1, (strtotime($endDate) - strtotime($startDate)) / (60*60*24) + 1);
        
        $complianceRate = $totalMeals > 0 ? ($consumedMeals['consumed'] / $totalMeals) * 100 : 0;
        
        $caloriesCompliance = $plan['daily_calories'] > 0 && $daysInPeriod > 0 ? 
            ($nutritionStats['total_calories'] / ($plan['daily_calories'] * $daysInPeriod)) * 100 : 0;
            
        $proteinCompliance = $plan['daily_protein'] > 0 && $daysInPeriod > 0 ? 
            ($nutritionStats['total_protein'] / ($plan['daily_protein'] * $daysInPeriod)) * 100 : 0;
            
        $carbsCompliance = $plan['daily_carbs'] > 0 && $daysInPeriod > 0 ? 
            ($nutritionStats['total_carbs'] / ($plan['daily_carbs'] * $daysInPeriod)) * 100 : 0;
            
        $fatCompliance = $plan['daily_fat'] > 0 && $daysInPeriod > 0 ? 
            ($nutritionStats['total_fat'] / ($plan['daily_fat'] * $daysInPeriod)) * 100 : 0;
        
        return [
            'compliance_rate' => round($complianceRate, 1),
            'calories_compliance' => round($caloriesCompliance, 1),
            'protein_compliance' => round($proteinCompliance, 1),
            'carbs_compliance' => round($carbsCompliance, 1),
            'fat_compliance' => round($fatCompliance, 1),
            'total_meals' => $totalMeals,
            'consumed_meals' => $consumedMeals['consumed'],
            'total_calories' => round($nutritionStats['total_calories'] ?: 0, 1),
            'total_protein' => round($nutritionStats['total_protein'] ?: 0, 1),
            'total_carbs' => round($nutritionStats['total_carbs'] ?: 0, 1),
            'total_fat' => round($nutritionStats['total_fat'] ?: 0, 1),
            'daily_calories_target' => $plan['daily_calories'],
            'daily_protein_target' => $plan['daily_protein'],
            'daily_carbs_target' => $plan['daily_carbs'],
            'daily_fat_target' => $plan['daily_fat']
        ];
    }
}