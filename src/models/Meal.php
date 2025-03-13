<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Ingredient.php';

class Meal
{
    private $db;
    private $ingredientModel;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->ingredientModel = new Ingredient();
    }

    /**
     * Create a new meal
     */
    public function create($restaurantId, $name, $description, $price, $mealType, $available = true)
    {
        $sql = "INSERT INTO meals (restaurant_id, name, description, price, meal_type, available) 
                VALUES (:restaurant_id, :name, :description, :price, :meal_type, :available)";

        $params = [
            ':restaurant_id' => $restaurantId,
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':meal_type' => $mealType,
            ':available' => $available ? 1 : 0
        ];

        try {
            $mealId = $this->db->insert($sql, $params);
            return $mealId;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get meal by ID
     */
    public function getById($id)
    {
        $sql = "SELECT m.*, u.name as restaurant_name 
                FROM meals m
                JOIN restaurant_profiles r ON m.restaurant_id = r.id
                JOIN users u ON r.user_id = u.id
                WHERE m.id = :id";

        return $this->db->fetchOne($sql, [':id' => $id]);
    }

    /**
     * Update meal
     */
    public function update($id, $data)
    {
        $allowedFields = ['name', 'description', 'price', 'meal_type', 'available'];

        $updates = [];
        $params = [':id' => $id];

        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                if ($field === 'available') {
                    $value = $value ? 1 : 0;
                }
                $updates[] = "$field = :$field";
                $params[":$field"] = $value;
            }
        }

        if (empty($updates)) {
            return false;
        }

        $updateClause = implode(', ', $updates);
        $sql = "UPDATE meals 
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
     * Delete meal
     */
    public function delete($id)
    {
        // First check if meal is used in any diet plans
        $sql = "SELECT COUNT(*) as count FROM user_meal_selections WHERE meal_id = :id";
        $result = $this->db->fetchOne($sql, [':id' => $id]);

        if ($result && $result['count'] > 0) {
            return ['error' => 'Esta refeição está sendo usada em planos de dieta e não pode ser excluída.'];
        }

        // Delete meal ingredients first
        $sql = "DELETE FROM meal_ingredients WHERE meal_id = :id";
        $this->db->query($sql, [':id' => $id]);

        // Then delete the meal
        $sql = "DELETE FROM meals WHERE id = :id";

        try {
            $this->db->query($sql, [':id' => $id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get all meals for a restaurant
     */
    public function getByRestaurantId($restaurantId, $filter = null)
    {
        $sql = "SELECT * FROM meals WHERE restaurant_id = :restaurant_id";
        $params = [':restaurant_id' => $restaurantId];

        if ($filter) {
            if (isset($filter['meal_type']) && !empty($filter['meal_type'])) {
                $sql .= " AND meal_type = :meal_type";
                $params[':meal_type'] = $filter['meal_type'];
            }

            if (isset($filter['available'])) {
                $sql .= " AND available = :available";
                $params[':available'] = $filter['available'] ? 1 : 0;
            }

            if (isset($filter['search']) && !empty($filter['search'])) {
                $sql .= " AND (name LIKE :search OR description LIKE :search)";
                $params[':search'] = "%{$filter['search']}%";
            }
        }

        $sql .= " ORDER BY name ASC";

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Get all available meals
     */
    public function getAllAvailable($filter = null, $limit = null, $offset = 0)
    {
        $sql = "SELECT m.*, u.name as restaurant_name 
        FROM meals m
        JOIN restaurant_profiles r ON m.restaurant_id = r.id
        JOIN users u ON r.user_id = u.id
        WHERE m.available = 1";
        $params = [];

        if ($filter) {
            if (isset($filter['meal_type']) && !empty($filter['meal_type'])) {
                $sql .= " AND m.meal_type = :meal_type";
                $params[':meal_type'] = $filter['meal_type'];
            }

            if (isset($filter['restaurant_id']) && !empty($filter['restaurant_id'])) {
                $sql .= " AND m.restaurant_id = :restaurant_id";
                $params[':restaurant_id'] = $filter['restaurant_id'];
            }

            if (isset($filter['search']) && !empty($filter['search'])) {
                $sql .= " AND (m.name LIKE :search OR m.description LIKE :search)";
                $params[':search'] = "%{$filter['search']}%";
            }

            // Filter by nutrient requirements
            if (isset($filter['max_calories']) && !empty($filter['max_calories'])) {
                $sql .= " AND (
                    SELECT SUM(i.calories * mi.amount / 100)
                    FROM meal_ingredients mi
                    JOIN ingredients i ON mi.ingredient_id = i.id
                    WHERE mi.meal_id = m.id
                ) <= :max_calories";
                $params[':max_calories'] = $filter['max_calories'];
            }

            if (isset($filter['min_protein']) && !empty($filter['min_protein'])) {
                $sql .= " AND (
                    SELECT SUM(i.protein * mi.amount / 100)
                    FROM meal_ingredients mi
                    JOIN ingredients i ON mi.ingredient_id = i.id
                    WHERE mi.meal_id = m.id
                ) >= :min_protein";
                $params[':min_protein'] = $filter['min_protein'];
            }

            if (isset($filter['max_carbs']) && !empty($filter['max_carbs'])) {
                $sql .= " AND (
                    SELECT SUM(i.carbs * mi.amount / 100)
                    FROM meal_ingredients mi
                    JOIN ingredients i ON mi.ingredient_id = i.id
                    WHERE mi.meal_id = m.id
                ) <= :max_carbs";
                $params[':max_carbs'] = $filter['max_carbs'];
            }

            if (isset($filter['max_fat']) && !empty($filter['max_fat'])) {
                $sql .= " AND (
                    SELECT SUM(i.fat * mi.amount / 100)
                    FROM meal_ingredients mi
                    JOIN ingredients i ON mi.ingredient_id = i.id
                    WHERE mi.meal_id = m.id
                ) <= :max_fat";
                $params[':max_fat'] = $filter['max_fat'];
            }
        }

        $sql .= " ORDER BY m.name ASC";

        if ($limit !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
            $params[':limit'] = $limit;
            $params[':offset'] = $offset;
        }

        $meals = $this->db->fetchAll($sql, $params);

        // Add nutrition facts to each meal
        foreach ($meals as &$meal) {
            $meal['nutrition'] = $this->getNutritionFacts($meal['id']);
            unset($meal['nutrition']['ingredients']);  // Remove detailed ingredients to reduce payload size
        }

        return $meals;
    }

    /**
     * Add ingredient to a meal
     */
    public function addIngredient($mealId, $ingredientId, $amount)
    {
        $sql = "INSERT INTO meal_ingredients (meal_id, ingredient_id, amount) 
                VALUES (:meal_id, :ingredient_id, :amount)";

        $params = [
            ':meal_id' => $mealId,
            ':ingredient_id' => $ingredientId,
            ':amount' => $amount
        ];

        try {
            $id = $this->db->insert($sql, $params);
            return $id;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Update meal ingredient amount
     */
    public function updateIngredientAmount($mealId, $ingredientId, $amount)
    {
        $sql = "UPDATE meal_ingredients 
                SET amount = :amount, updated_at = CURRENT_TIMESTAMP 
                WHERE meal_id = :meal_id AND ingredient_id = :ingredient_id";

        $params = [
            ':meal_id' => $mealId,
            ':ingredient_id' => $ingredientId,
            ':amount' => $amount
        ];

        try {
            $this->db->query($sql, $params);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Remove ingredient from a meal
     */
    public function removeIngredient($mealId, $ingredientId)
    {
        $sql = "DELETE FROM meal_ingredients 
                WHERE meal_id = :meal_id AND ingredient_id = :ingredient_id";

        $params = [
            ':meal_id' => $mealId,
            ':ingredient_id' => $ingredientId
        ];

        try {
            $this->db->query($sql, $params);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get ingredients for a meal
     */
    public function getIngredients($mealId)
    {
        $sql = "SELECT mi.ingredient_id, mi.amount, i.name, i.calories, i.protein, i.carbs, i.fat, i.fiber
                FROM meal_ingredients mi
                JOIN ingredients i ON mi.ingredient_id = i.id
                WHERE mi.meal_id = :meal_id
                ORDER BY i.name ASC";

        return $this->db->fetchAll($sql, [':meal_id' => $mealId]);
    }

    /**
     * Calculate meal nutrition facts
     */
    public function getNutritionFacts($mealId)
    {
        $ingredients = $this->getIngredients($mealId);

        $totalCalories = 0;
        $totalProtein = 0;
        $totalCarbs = 0;
        $totalFat = 0;
        $totalFiber = 0;

        foreach ($ingredients as $ingredient) {
            // Calculate based on amount (converting from per 100g to actual amount)
            $factor = $ingredient['amount'] / 100;
            $totalCalories += $ingredient['calories'] * $factor;
            $totalProtein += $ingredient['protein'] * $factor;
            $totalCarbs += $ingredient['carbs'] * $factor;
            $totalFat += $ingredient['fat'] * $factor;

            if ($ingredient['fiber'] !== null) {
                $totalFiber += $ingredient['fiber'] * $factor;
            }
        }

        return [
            'calories' => round($totalCalories, 1),
            'protein' => round($totalProtein, 1),
            'carbs' => round($totalCarbs, 1),
            'fat' => round($totalFat, 1),
            'fiber' => round($totalFiber, 1),
            'ingredients' => $ingredients
        ];
    }

    /**
     * Count total available meals with filters
     */
    public function countAllAvailable($filter = null)
    {
        $sql = "SELECT COUNT(*) as count 
            FROM meals m
            JOIN restaurant_profiles r ON m.restaurant_id = r.id
            JOIN users u ON r.user_id = u.id
            WHERE m.available = 1";
        $params = [];

        if ($filter) {
            if (isset($filter['restaurant_id']) && !empty($filter['restaurant_id'])) {
                $sql .= " AND m.restaurant_id = :restaurant_id";
                $params[':restaurant_id'] = $filter['restaurant_id'];
            }

            if (isset($filter['meal_type']) && !empty($filter['meal_type'])) {
                $sql .= " AND m.meal_type = :meal_type";
                $params[':meal_type'] = $filter['meal_type'];
            }

            if (isset($filter['search']) && !empty($filter['search'])) {
                $sql .= " AND (m.name LIKE :search OR m.description LIKE :search)";
                $params[':search'] = "%{$filter['search']}%";
            }

            // Filter by nutrient requirements
            if (isset($filter['max_calories']) && !empty($filter['max_calories'])) {
                $sql .= " AND (
                SELECT SUM(i.calories * mi.amount / 100)
                FROM meal_ingredients mi
                JOIN ingredients i ON mi.ingredient_id = i.id
                WHERE mi.meal_id = m.id
            ) <= :max_calories";
                $params[':max_calories'] = $filter['max_calories'];
            }

            if (isset($filter['min_protein']) && !empty($filter['min_protein'])) {
                $sql .= " AND (
                SELECT SUM(i.protein * mi.amount / 100)
                FROM meal_ingredients mi
                JOIN ingredients i ON mi.ingredient_id = i.id
                WHERE mi.meal_id = m.id
            ) >= :min_protein";
                $params[':min_protein'] = $filter['min_protein'];
            }
        }

        $result = $this->db->fetchOne($sql, $params);
        return $result ? $result['count'] : 0;
    }


    /**
     * Get popular meals
     */
    public function getPopularMeals($limit = 5)
    {
        $sql = "SELECT m.*, u.name as restaurant_name, COUNT(moi.id) as order_count
            FROM meals m
            JOIN restaurant_profiles r ON m.restaurant_id = r.id
            JOIN users u ON r.user_id = u.id
            LEFT JOIN meal_order_items moi ON moi.meal_id = m.id
            WHERE m.available = 1
            GROUP BY m.id
            ORDER BY order_count DESC
            LIMIT :limit";

        $meals = $this->db->fetchAll($sql, [':limit' => $limit]);

        // Add nutrition facts to each meal
        foreach ($meals as &$meal) {
            $meal['nutrition'] = $this->getNutritionFacts($meal['id']);
            unset($meal['nutrition']['ingredients']);  // Remove detailed ingredients to reduce payload size
        }

        return $meals;
    }
}
