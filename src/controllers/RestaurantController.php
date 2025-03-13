<?php
require_once __DIR__ . '/../models/RestaurantProfile.php';
require_once __DIR__ . '/../models/Meal.php';
require_once __DIR__ . '/../models/Ingredient.php';
require_once __DIR__ . '/../utils/Auth.php';

class RestaurantController {
    private $restaurantModel;
    private $mealModel;
    private $ingredientModel;
    private $auth;
    
    public function __construct() {
        $this->restaurantModel = new RestaurantProfile();
        $this->mealModel = new Meal();
        $this->ingredientModel = new Ingredient();
        $this->auth = Auth::getInstance();
        
        // Check if user is logged in and has restaurant role
        if (!$this->auth->hasRole(['restaurant', 'admin'])) {
            redirect('/403');
        }
    }
    
    /**
     * Meals page
     */
    public function meals() {
        $userId = $this->auth->getUserId();
        $restaurant = $this->restaurantModel->getByUserId($userId);
        
        if (!$restaurant) {
            redirect('/403');
        }
        
        $filter = [
            'meal_type' => $_GET['meal_type'] ?? '',
            'available' => isset($_GET['available']) ? (bool)$_GET['available'] : null,
            'search' => $_GET['search'] ?? ''
        ];
        
        $meals = $this->mealModel->getByRestaurantId($restaurant['id'], $filter);
        
        view('restaurant/meals', [
            'restaurant' => $restaurant,
            'meals' => $meals,
            'filter' => $filter
        ]);
    }
    
    /**
     * View meal details
     */
    public function viewMeal() {
        $userId = $this->auth->getUserId();
        $restaurant = $this->restaurantModel->getByUserId($userId);
        
        if (!$restaurant) {
            redirect('/403');
        }
        
        $mealId = $_GET['id'] ?? 0;
        $meal = $this->mealModel->getById($mealId);
        
        if (!$meal || $meal['restaurant_id'] != $restaurant['id']) {
            redirect('/restaurant/meals');
        }
        
        $nutritionFacts = $this->mealModel->getNutritionFacts($mealId);
        
        view('restaurant/view_meal', [
            'restaurant' => $restaurant,
            'meal' => $meal,
            'nutritionFacts' => $nutritionFacts
        ]);
    }
    
    /**
     * Create new meal
     */
    public function createMeal() {
        $userId = $this->auth->getUserId();
        $restaurant = $this->restaurantModel->getByUserId($userId);
        
        if (!$restaurant) {
            redirect('/403');
        }
        
        $success = '';
        $error = '';
        
        // Get all ingredients
        $ingredients = $this->ingredientModel->getAll();
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $mealType = $_POST['meal_type'] ?? '';
            $available = isset($_POST['available']) ? 1 : 0;
            
            // Validate form
            if (empty($name) || empty($mealType) || $price <= 0) {
                $error = 'Os campos Nome, Tipo de Refeição e Preço são obrigatórios';
            } else {
                // Create meal
                $mealId = $this->mealModel->create(
                    $restaurant['id'],
                    $name,
                    $description,
                    $price,
                    $mealType,
                    $available
                );
                
                if ($mealId) {
                    // Add ingredients if provided
                    if (isset($_POST['ingredient_id']) && is_array($_POST['ingredient_id'])) {
                        foreach ($_POST['ingredient_id'] as $index => $ingredientId) {
                            $amount = $_POST['amount'][$index] ?? 0;
                            
                            if ($ingredientId > 0 && $amount > 0) {
                                $this->mealModel->addIngredient($mealId, $ingredientId, $amount);
                            }
                        }
                    }
                    
                    redirect("/restaurant/meals");
                } else {
                    $error = 'Erro ao criar refeição';
                }
            }
        }
        
        view('restaurant/create_meal', [
            'restaurant' => $restaurant,
            'ingredients' => $ingredients,
            'success' => $success,
            'error' => $error
        ]);
    }
    
    /**
     * Edit meal
     */
    public function editMeal() {
        $userId = $this->auth->getUserId();
        $restaurant = $this->restaurantModel->getByUserId($userId);
        
        if (!$restaurant) {
            redirect('/403');
        }
        
        $mealId = $_GET['id'] ?? 0;
        $meal = $this->mealModel->getById($mealId);
        
        if (!$meal || $meal['restaurant_id'] != $restaurant['id']) {
            redirect('/restaurant/meals');
        }
        
        $success = '';
        $error = '';
        
        // Get all ingredients
        $ingredients = $this->ingredientModel->getAll();
        
        // Get meal ingredients
        $mealIngredients = $this->mealModel->getIngredients($mealId);
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $mealType = $_POST['meal_type'] ?? '';
            $available = isset($_POST['available']) ? 1 : 0;
            
            // Validate form
            if (empty($name) || empty($mealType) || $price <= 0) {
                $error = 'Os campos Nome, Tipo de Refeição e Preço são obrigatórios';
            } else {
                // Update meal
                $result = $this->mealModel->update($mealId, [
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'meal_type' => $mealType,
                    'available' => $available
                ]);
                
                if ($result) {
                    // Update ingredients
                    if (isset($_POST['ingredient_id']) && is_array($_POST['ingredient_id'])) {
                        // Remove all existing ingredients
                        foreach ($mealIngredients as $ingredient) {
                            $this->mealModel->removeIngredient($mealId, $ingredient['ingredient_id']);
                        }
                        
                        // Add new ingredients
                        foreach ($_POST['ingredient_id'] as $index => $ingredientId) {
                            $amount = $_POST['amount'][$index] ?? 0;
                            
                            if ($ingredientId > 0 && $amount > 0) {
                                $this->mealModel->addIngredient($mealId, $ingredientId, $amount);
                            }
                        }
                    }
                    
                    $success = 'Refeição atualizada com sucesso';
                    
                    // Refresh data
                    $meal = $this->mealModel->getById($mealId);
                    $mealIngredients = $this->mealModel->getIngredients($mealId);
                } else {
                    $error = 'Erro ao atualizar refeição';
                }
            }
        }
        
        view('restaurant/edit_meal', [
            'restaurant' => $restaurant,
            'meal' => $meal,
            'ingredients' => $ingredients,
            'mealIngredients' => $mealIngredients,
            'success' => $success,
            'error' => $error
        ]);
    }
    
    /**
     * Ingredients page
     */
    public function ingredients() {
        $userId = $this->auth->getUserId();
        $restaurant = $this->restaurantModel->getByUserId($userId);
        
        if (!$restaurant) {
            redirect('/403');
        }
        
        $search = $_GET['search'] ?? '';
        $ingredients = $this->ingredientModel->getAll($search);
        
        view('restaurant/ingredients', [
            'restaurant' => $restaurant,
            'ingredients' => $ingredients,
            'search' => $search
        ]);
    }
    
    /**
     * Create new ingredient
     */
    public function createIngredient() {
        $userId = $this->auth->getUserId();
        $restaurant = $this->restaurantModel->getByUserId($userId);
        
        if (!$restaurant) {
            redirect('/403');
        }
        
        $success = '';
        $error = '';
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $calories = $_POST['calories'] ?? 0;
            $protein = $_POST['protein'] ?? 0;
            $carbs = $_POST['carbs'] ?? 0;
            $fat = $_POST['fat'] ?? 0;
            $fiber = $_POST['fiber'] ?? null;
            
            // Validate form
            if (empty($name) || $calories < 0 || $protein < 0 || $carbs < 0 || $fat < 0) {
                $error = 'Os campos Nome, Calorias, Proteínas, Carboidratos e Gorduras são obrigatórios e devem ser positivos';
            } else {
                // Create ingredient
                $ingredientId = $this->ingredientModel->create(
                    $name,
                    $calories,
                    $protein,
                    $carbs,
                    $fat,
                    $fiber
                );
                
                if ($ingredientId) {
                    redirect("/restaurant/ingredients");
                } else {
                    $error = 'Erro ao criar ingrediente';
                }
            }
        }
        
        view('restaurant/create_ingredient', [
            'restaurant' => $restaurant,
            'success' => $success,
            'error' => $error
        ]);
    }
    
    /**
     * Edit ingredient
     */
    public function editIngredient() {
        $userId = $this->auth->getUserId();
        $restaurant = $this->restaurantModel->getByUserId($userId);
        
        if (!$restaurant) {
            redirect('/403');
        }
        
        $ingredientId = $_GET['id'] ?? 0;
        $ingredient = $this->ingredientModel->getById($ingredientId);
        
        if (!$ingredient) {
            redirect('/restaurant/ingredients');
        }
        
        $success = '';
        $error = '';
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $calories = $_POST['calories'] ?? 0;
            $protein = $_POST['protein'] ?? 0;
            $carbs = $_POST['carbs'] ?? 0;
            $fat = $_POST['fat'] ?? 0;
            $fiber = $_POST['fiber'] ?? null;
            
            // Validate form
            if (empty($name) || $calories < 0 || $protein < 0 || $carbs < 0 || $fat < 0) {
                $error = 'Os campos Nome, Calorias, Proteínas, Carboidratos e Gorduras são obrigatórios e devem ser positivos';
            } else {
                // Update ingredient
                $result = $this->ingredientModel->update($ingredientId, [
                    'name' => $name,
                    'calories' => $calories,
                    'protein' => $protein,
                    'carbs' => $carbs,
                    'fat' => $fat,
                    'fiber' => $fiber
                ]);
                
                if ($result) {
                    $success = 'Ingrediente atualizado com sucesso';
                    
                    // Refresh data
                    $ingredient = $this->ingredientModel->getById($ingredientId);
                } else {
                    $error = 'Erro ao atualizar ingrediente';
                }
            }
        }
        
        view('restaurant/edit_ingredient', [
            'restaurant' => $restaurant,
            'ingredient' => $ingredient,
            'success' => $success,
            'error' => $error
        ]);
    }
}