<?php
require_once __DIR__ . '/../models/NutritionistProfile.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/DietPlan.php';
require_once __DIR__ . '/../utils/Auth.php';

class NutritionistController {
    private $nutritionistModel;
    private $userModel;
    private $dietPlanModel;
    private $auth;
    
    public function __construct() {
        $this->nutritionistModel = new NutritionistProfile();
        $this->userModel = new User();
        $this->dietPlanModel = new DietPlan();
        $this->auth = Auth::getInstance();
    }
    
    /**
     * Nutritionists list for users
     */
    public function index() {
        // Check if user is logged in and has user role
        if (!$this->auth->hasRole('user')) {
            redirect('/403');
        }
        
        $nutritionists = $this->nutritionistModel->getAll();
        
        view('nutritionist/index', [
            'nutritionists' => $nutritionists
        ]);
    }
    
    /**
     * View nutritionist profile for users
     */
    public function view() {
        // Check if user is logged in and has user role
        if (!$this->auth->hasRole('user')) {
            redirect('/403');
        }
        
        $id = $_GET['id'] ?? 0;
        $nutritionist = $this->nutritionistModel->getById($id);
        
        if (!$nutritionist) {
            redirect('/nutritionists');
        }
        
        view('nutritionist/view', [
            'nutritionist' => $nutritionist
        ]);
    }
    
    /**
     * Clients list for nutritionists
     */
    public function clients() {
        // Check if user is logged in and has nutritionist role
        if (!$this->auth->hasRole(['nutritionist', 'admin'])) {
            redirect('/403');
        }
        
        $userId = $this->auth->getUserId();
        $nutritionist = $this->nutritionistModel->getByUserId($userId);
        
        if (!$nutritionist) {
            redirect('/403');
        }
        
        $clients = $this->nutritionistModel->getClients($nutritionist['id']);
        
        view('nutritionist/clients', [
            'nutritionist' => $nutritionist,
            'clients' => $clients
        ]);
    }
    
    /**
     * Diet plans for nutritionists
     */
    public function diets() {
        // Check if user is logged in and has nutritionist role
        if (!$this->auth->hasRole(['nutritionist', 'admin'])) {
            redirect('/403');
        }
        
        $userId = $this->auth->getUserId();
        $nutritionist = $this->nutritionistModel->getByUserId($userId);
        
        if (!$nutritionist) {
            redirect('/403');
        }
        
        $dietPlans = $this->dietPlanModel->getByNutritionistId($nutritionist['id']);
        
        view('nutritionist/diets', [
            'nutritionist' => $nutritionist,
            'dietPlans' => $dietPlans
        ]);
    }
    
    /**
     * Create diet plan
     */
    public function createDiet() {
        // Check if user is logged in and has nutritionist role
        if (!$this->auth->hasRole(['nutritionist', 'admin'])) {
            redirect('/403');
        }
        
        $userId = $this->auth->getUserId();
        $nutritionist = $this->nutritionistModel->getByUserId($userId);
        
        if (!$nutritionist) {
            redirect('/403');
        }
        
        $success = '';
        $error = '';
        
        // Get clients
        $clients = $this->nutritionistModel->getClients($nutritionist['id']);
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clientId = $_POST['client_id'] ?? 0;
            $name = $_POST['name'] ?? '';
            $startDate = Formatter::dateToDatabase($_POST['start_date'] ?? '');
            $endDate = Formatter::dateToDatabase($_POST['end_date'] ?? '');
            $dailyCalories = $_POST['daily_calories'] ?? null;
            $dailyProtein = $_POST['daily_protein'] ?? null;
            $dailyCarbs = $_POST['daily_carbs'] ?? null;
            $dailyFat = $_POST['daily_fat'] ?? null;
            $notes = $_POST['notes'] ?? '';
            
            // Validate form
            if (empty($clientId) || empty($name) || empty($startDate) || empty($endDate)) {
                $error = 'Os campos Cliente, Nome, Data Inicial e Data Final são obrigatórios';
            } else {
                // Create diet plan
                $dietData = [
                    'daily_calories' => $dailyCalories,
                    'daily_protein' => $dailyProtein,
                    'daily_carbs' => $dailyCarbs,
                    'daily_fat' => $dailyFat,
                    'notes' => $notes
                ];
                
                $planId = $this->dietPlanModel->create(
                    $nutritionist['id'],
                    $clientId,
                    $name,
                    $startDate,
                    $endDate,
                    $dietData
                );
                
                if ($planId) {
                    redirect("/nutritionist/diets/edit?id=$planId");
                } else {
                    $error = 'Erro ao criar plano de dieta';
                }
            }
        }
        
        view('nutritionist/create_diet', [
            'nutritionist' => $nutritionist,
            'clients' => $clients,
            'success' => $success,
            'error' => $error
        ]);
    }
    
    /**
     * Edit diet plan
     */
    public function editDiet() {
        // Check if user is logged in and has nutritionist role
        if (!$this->auth->hasRole(['nutritionist', 'admin'])) {
            redirect('/403');
        }
        
        $userId = $this->auth->getUserId();
        $nutritionist = $this->nutritionistModel->getByUserId($userId);
        
        if (!$nutritionist) {
            redirect('/403');
        }
        
        $planId = $_GET['id'] ?? 0;
        $plan = $this->dietPlanModel->getById($planId);
        
        if (!$plan || $plan['nutritionist_id'] != $nutritionist['id']) {
            redirect('/nutritionist/diets');
        }
        
        $success = '';
        $error = '';
        
        // Get meals
        $meals = $this->dietPlanModel->getMeals($planId);
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $startDate = Formatter::dateToDatabase($_POST['start_date'] ?? '');
            $endDate = Formatter::dateToDatabase($_POST['end_date'] ?? '');
            $dailyCalories = $_POST['daily_calories'] ?? null;
            $dailyProtein = $_POST['daily_protein'] ?? null;
            $dailyCarbs = $_POST['daily_carbs'] ?? null;
            $dailyFat = $_POST['daily_fat'] ?? null;
            $notes = $_POST['notes'] ?? '';
            $status = $_POST['status'] ?? 'active';
            
            // Validate form
            if (empty($name) || empty($startDate) || empty($endDate)) {
                $error = 'Os campos Nome, Data Inicial e Data Final são obrigatórios';
            } else {
                // Update diet plan
                $dietData = [
                    'name' => $name,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'daily_calories' => $dailyCalories,
                    'daily_protein' => $dailyProtein,
                    'daily_carbs' => $dailyCarbs,
                    'daily_fat' => $dailyFat,
                    'notes' => $notes,
                    'status' => $status
                ];
                
                $result = $this->dietPlanModel->update($planId, $dietData);
                
                if ($result) {
                    $success = 'Plano de dieta atualizado com sucesso';
                    
                    // Refresh data
                    $plan = $this->dietPlanModel->getById($planId);
                } else {
                    $error = 'Erro ao atualizar plano de dieta';
                }
            }
        }
        
        view('nutritionist/edit_diet', [
            'nutritionist' => $nutritionist,
            'plan' => $plan,
            'meals' => $meals,
            'success' => $success,
            'error' => $error
        ]);
    }
    
    /**
     * View diet plan for nutritionist
     */
    public function viewDiet() {
        // Check if user is logged in and has nutritionist role
        if (!$this->auth->hasRole(['nutritionist', 'admin'])) {
            redirect('/403');
        }
        
        $userId = $this->auth->getUserId();
        $nutritionist = $this->nutritionistModel->getByUserId($userId);
        
        if (!$nutritionist) {
            redirect('/403');
        }
        
        $planId = $_GET['id'] ?? 0;
        $plan = $this->dietPlanModel->getById($planId);
        
        if (!$plan || $plan['nutritionist_id'] != $nutritionist['id']) {
            redirect('/nutritionist/diets');
        }
        
        $meals = $this->dietPlanModel->getMeals($planId);
        
        // Calculate compliance
        $compliance = $this->dietPlanModel->getUserCompliance($plan['user_id'], $planId);
        
        view('nutritionist/view_diet', [
            'nutritionist' => $nutritionist,
            'plan' => $plan,
            'meals' => $meals,
            'compliance' => $compliance
        ]);
    }
    
    /**
     * Add meal to diet plan
     */
    public function addMeal() {
        // Check if user is logged in and has nutritionist role
        if (!$this->auth->hasRole(['nutritionist', 'admin'])) {
            redirect('/403');
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/nutritionist/diets');
        }
        
        $userId = $this->auth->getUserId();
        $nutritionist = $this->nutritionistModel->getByUserId($userId);
        
        if (!$nutritionist) {
            redirect('/403');
        }
        
        $planId = $_POST['plan_id'] ?? 0;
        $plan = $this->dietPlanModel->getById($planId);
        
        if (!$plan || $plan['nutritionist_id'] != $nutritionist['id']) {
            redirect('/nutritionist/diets');
        }
        
        $mealType = $_POST['meal_type'] ?? '';
        $dayOfWeek = $_POST['day_of_week'] ?? 0;
        $timeOfDay = $_POST['time_of_day'] ?? '';
        $caloriesTarget = $_POST['calories_target'] ?? null;
        $proteinTarget = $_POST['protein_target'] ?? null;
        $carbsTarget = $_POST['carbs_target'] ?? null;
        $fatTarget = $_POST['fat_target'] ?? null;
        $notes = $_POST['notes'] ?? '';
        
        // Validate form
        if (empty($mealType) || empty($timeOfDay)) {
            redirect("/nutritionist/diets/edit?id=$planId&error=meal_required");
        }
        
        // Add meal
        $mealData = [
            'calories_target' => $caloriesTarget,
            'protein_target' => $proteinTarget,
            'carbs_target' => $carbsTarget,
            'fat_target' => $fatTarget,
            'notes' => $notes
        ];
        
        $result = $this->dietPlanModel->addMeal($planId, $mealType, $dayOfWeek, $timeOfDay, $mealData);
        
        redirect("/nutritionist/diets/edit?id=$planId");
    }
    
    /**
     * Edit meal in diet plan
     */
    public function editMeal() {
        // Check if user is logged in and has nutritionist role
        if (!$this->auth->hasRole(['nutritionist', 'admin'])) {
            redirect('/403');
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/nutritionist/diets');
        }
        
        $userId = $this->auth->getUserId();
        $nutritionist = $this->nutritionistModel->getByUserId($userId);
        
        if (!$nutritionist) {
            redirect('/403');
        }
        
        $planId = $_POST['plan_id'] ?? 0;
        $plan = $this->dietPlanModel->getById($planId);
        
        if (!$plan || $plan['nutritionist_id'] != $nutritionist['id']) {
            redirect('/nutritionist/diets');
        }
        
        $mealId = $_POST['meal_id'] ?? 0;
        $mealType = $_POST['meal_type'] ?? '';
        $dayOfWeek = $_POST['day_of_week'] ?? 0;
        $timeOfDay = $_POST['time_of_day'] ?? '';
        $caloriesTarget = $_POST['calories_target'] ?? null;
        $proteinTarget = $_POST['protein_target'] ?? null;
        $carbsTarget = $_POST['carbs_target'] ?? null;
        $fatTarget = $_POST['fat_target'] ?? null;
        $notes = $_POST['notes'] ?? '';
        
        // Validate form
        if (empty($mealType) || empty($timeOfDay)) {
            redirect("/nutritionist/diets/edit?id=$planId&error=meal_required");
        }
        
        // Update meal
        $mealData = [
            'meal_type' => $mealType,
            'day_of_week' => $dayOfWeek,
            'time_of_day' => $timeOfDay,
            'calories_target' => $caloriesTarget,
            'protein_target' => $proteinTarget,
            'carbs_target' => $carbsTarget,
            'fat_target' => $fatTarget,
            'notes' => $notes
        ];
        
        $result = $this->dietPlanModel->updateMeal($mealId, $mealData);
        
        // Check if delete operation
        if (isset($_POST['delete']) && $_POST['delete'] === '1') {
            $this->dietPlanModel->deleteMeal($mealId);
        }
        
        redirect("/nutritionist/diets/edit?id=$planId");
    }
}