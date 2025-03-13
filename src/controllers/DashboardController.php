<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/UserProfile.php';
require_once __DIR__ . '/../models/NutritionistProfile.php';
require_once __DIR__ . '/../models/RestaurantProfile.php';
require_once __DIR__ . '/../models/DietPlan.php';
require_once __DIR__ . '/../models/Meal.php';
require_once __DIR__ . '/../models/Chat.php';
require_once __DIR__ . '/../utils/Auth.php';

class DashboardController {
    private $userModel;
    private $profileModel;
    private $nutritionistModel;
    private $restaurantModel;
    private $dietPlanModel;
    private $mealModel;
    private $chatModel;
    private $auth;
    
    public function __construct() {
        $this->userModel = new User();
        $this->profileModel = new UserProfile();
        $this->nutritionistModel = new NutritionistProfile();
        $this->restaurantModel = new RestaurantProfile();
        $this->dietPlanModel = new DietPlan();
        $this->mealModel = new Meal();
        $this->chatModel = new Chat();
        $this->auth = Auth::getInstance();
    }
    
    /**
     * User dashboard
     */
    public function user() {
        if (!$this->auth->hasRole('user')) {
            redirect('/403');
        }
        
        $userId = $this->auth->getUserId();
        $user = $this->userModel->getById($userId);
        $profile = $this->profileModel->getByUserId($userId);
        
        // Get active diet plans
        $dietPlans = $this->dietPlanModel->getByUserId($userId, 'active');
        
        // Get latest measurements
        $measurements = $this->profileModel->getMeasurements($userId, 10);
        
        // Calculate progress
        $progress = $this->profileModel->calculateProgress($userId);
        
        // Get unread messages
        $unreadCount = $this->chatModel->getUnreadCount($userId);
        
        // Get today's meals
        $today = date('Y-m-d');
        $todaysMeals = $this->dietPlanModel->getUserMealSelections($userId, $today);
        
        view('dashboard/user', [
            'user' => $user,
            'profile' => $profile,
            'dietPlans' => $dietPlans,
            'measurements' => $measurements,
            'progress' => $progress,
            'unreadCount' => $unreadCount,
            'todaysMeals' => $todaysMeals
        ]);
    }
    
    /**
     * Nutritionist dashboard
     */
    public function nutritionist() {
        if (!$this->auth->hasRole('nutritionist')) {
            redirect('/403');
        }
        
        $userId = $this->auth->getUserId();
        $nutritionist = $this->nutritionistModel->getByUserId($userId);
        
        // Get clients
        $clients = $this->nutritionistModel->getClients($nutritionist['id']);
        
        // Get active diet plans
        $dietPlans = $this->dietPlanModel->getByNutritionistId($nutritionist['id'], 'active');
        
        // Get unread messages
        $unreadCount = $this->chatModel->getUnreadCount($userId, true);
        
        view('dashboard/nutritionist', [
            'nutritionist' => $nutritionist,
            'clients' => $clients,
            'dietPlans' => $dietPlans,
            'unreadCount' => $unreadCount
        ]);
    }
    
    /**
     * Restaurant dashboard
     */
    public function restaurant() {
        if (!$this->auth->hasRole('restaurant')) {
            redirect('/403');
        }
        
        $userId = $this->auth->getUserId();
        $restaurant = $this->restaurantModel->getByUserId($userId);
        
        // Get meals
        $meals = $this->mealModel->getByRestaurantId($restaurant['id'], ['available' => true]);
        
        // Get popular meals - would need to extend the models to track this
        $popularMeals = [];
        
        view('dashboard/restaurant', [
            'restaurant' => $restaurant,
            'meals' => $meals,
            'popularMeals' => $popularMeals
        ]);
    }
}