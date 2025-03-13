<?php
require_once __DIR__ . '/../models/Meal.php';
require_once __DIR__ . '/../models/Ingredient.php';
require_once __DIR__ . '/../models/UserProfile.php';
require_once __DIR__ . '/../utils/Auth.php';

class ApiController {
    private $mealModel;
    private $ingredientModel;
    private $profileModel;
    private $auth;
    
    public function __construct() {
        $this->mealModel = new Meal();
        $this->ingredientModel = new Ingredient();
        $this->profileModel = new UserProfile();
        $this->auth = Auth::getInstance();
        
        // Check if user is logged in
        if (!$this->auth->isLoggedIn()) {
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
            exit;
        }
        
        // Set content type to JSON
        header('Content-Type: application/json');
    }
    
    /**
     * Get meals JSON endpoint
     */
    public function meals() {
        $filter = [
            'meal_type' => $_GET['meal_type'] ?? '',
            'restaurant_id' => $_GET['restaurant_id'] ?? '',
            'search' => $_GET['search'] ?? '',
            'max_calories' => $_GET['max_calories'] ?? '',
            'min_protein' => $_GET['min_protein'] ?? '',
            'max_carbs' => $_GET['max_carbs'] ?? '',
            'max_fat' => $_GET['max_fat'] ?? ''
        ];
        
        $meals = $this->mealModel->getAllAvailable($filter);
        
        // Add nutrition facts to each meal
        foreach ($meals as &$meal) {
            $meal['nutrition'] = $this->mealModel->getNutritionFacts($meal['id']);
            unset($meal['nutrition']['ingredients']);  // Remove detailed ingredients to reduce payload size
        }
        
        $this->jsonResponse($meals);
    }
    
    /**
     * Get meal nutrition facts JSON endpoint
     */
    public function mealNutrition() {
        $mealId = $_GET['id'] ?? 0;
        
        if (empty($mealId)) {
            $this->jsonResponse(['error' => 'Meal ID is required'], 400);
            exit;
        }
        
        $nutrition = $this->mealModel->getNutritionFacts($mealId);
        
        $this->jsonResponse($nutrition);
    }
    
    /**
     * Get ingredients JSON endpoint
     */
    public function ingredients() {
        $search = $_GET['search'] ?? '';
        
        if (empty($search)) {
            $ingredients = $this->ingredientModel->getAll();
        } else {
            $ingredients = $this->ingredientModel->search($search);
        }
        
        $this->jsonResponse($ingredients);
    }
    
    /**
     * Get user measurements JSON endpoint
     */
    public function measurements() {
        $userId = $this->auth->getUserId();
        
        // Get measurements
        $measurements = $this->profileModel->getMeasurements($userId);
        
        // Calculate progress
        $progress = $this->profileModel->calculateProgress($userId);
        
        $data = [
            'measurements' => $measurements,
            'progress' => $progress
        ];
        
        $this->jsonResponse($data);
    }
    
    /**
     * Send JSON response
     */
    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}