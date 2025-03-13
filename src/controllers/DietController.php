<?php
require_once __DIR__ . '/../models/DietPlan.php';
require_once __DIR__ . '/../models/Meal.php';
require_once __DIR__ . '/../utils/Auth.php';
require_once __DIR__ . '/../utils/Formatter.php';

class DietController {
    private $dietPlanModel;
    private $mealModel;
    private $auth;
    
    public function __construct() {
        $this->dietPlanModel = new DietPlan();
        $this->mealModel = new Meal();
        $this->auth = Auth::getInstance();
        
        // Check if user is logged in and has user role
        if (!$this->auth->hasRole('user')) {
            redirect('/403');
        }
    }
    
    /**
     * Diet plans list
     */
    public function index() {
        $userId = $this->auth->getUserId();
        $dietPlans = $this->dietPlanModel->getByUserId($userId);
        
        view('diet/index', [
            'dietPlans' => $dietPlans
        ]);
    }
    
    /**
     * View diet plan
     */
    public function view() {
        $planId = $_GET['id'] ?? 0;
        $userId = $this->auth->getUserId();
        
        $plan = $this->dietPlanModel->getById($planId);
        
        if (!$plan || $plan['user_id'] != $userId) {
            redirect('/diets');
        }
        
        $meals = $this->dietPlanModel->getMeals($planId);
        
        // Calculate compliance
        $compliance = $this->dietPlanModel->getUserCompliance($userId, $planId);
        
        view('diet/view', [
            'plan' => $plan,
            'meals' => $meals,
            'compliance' => $compliance
        ]);
    }
    
    /**
     * Meals selection for a specific date
     */
    public function meals() {
        $userId = $this->auth->getUserId();
        $date = $_GET['date'] ?? date('Y-m-d');
        
        // Get active diet plans
        $dietPlans = $this->dietPlanModel->getByUserId($userId, 'active');
        
        if (empty($dietPlans)) {
            redirect('/diets');
        }
        
        // Get diet meals for each day of the week
        $dayOfWeek = date('w', strtotime($date)); // 0 (Sunday) to 6 (Saturday)
        
        $dietMeals = [];
        foreach ($dietPlans as $plan) {
            $meals = $this->dietPlanModel->getMeals($plan['id']);
            foreach ($meals as $meal) {
                if ($meal['day_of_week'] == $dayOfWeek) {
                    $dietMeals[] = [
                        'diet_meal' => $meal,
                        'plan' => $plan
                    ];
                }
            }
        }
        
        // Get user selections for this date
        $selections = $this->dietPlanModel->getUserMealSelections($userId, $date);
        
        // Get available restaurant meals
        $availableMeals = $this->mealModel->getAllAvailable();
        
        view('diet/meals', [
            'date' => $date,
            'dietMeals' => $dietMeals,
            'selections' => $selections,
            'availableMeals' => $availableMeals,
            'dayOfWeek' => $dayOfWeek
        ]);
    }
    
    /**
     * Select a meal for a diet plan slot
     */
    public function selectMeal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/diets');
        }
        
        $userId = $this->auth->getUserId();
        $dietMealId = $_POST['diet_meal_id'] ?? 0;
        $mealId = $_POST['meal_id'] ?? 0;
        $date = $_POST['date'] ?? date('Y-m-d');
        
        if (empty($dietMealId) || empty($mealId)) {
            redirect("/diets/meals?date=$date");
        }
        
        // Select meal
        $result = $this->dietPlanModel->selectMeal($userId, $dietMealId, $mealId, $date);
        
        redirect("/diets/meals?date=$date");
    }
    
    /**
     * Update meal status (consumed or skipped)
     */
    public function updateStatus() {
        $selectionId = $_GET['id'] ?? 0;
        $status = $_GET['status'] ?? '';
        $date = $_GET['date'] ?? date('Y-m-d');
        
        if (empty($selectionId) || !in_array($status, ['consumed', 'skipped'])) {
            redirect("/diets/meals?date=$date");
        }
        
        // Update status
        $this->dietPlanModel->updateMealStatus($selectionId, $status);
        
        // Redirect back
        $referrer = $_SERVER['HTTP_REFERER'] ?? "/diets/meals?date=$date";
        redirect($referrer);
    }
}