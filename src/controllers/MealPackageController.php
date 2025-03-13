<?php
require_once __DIR__ . '/../models/Meal.php';
require_once __DIR__ . '/../models/MealPackage.php';
require_once __DIR__ . '/../models/RestaurantProfile.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../utils/Auth.php';

class MealPackageController {
    private $mealModel;
    private $packageModel;
    private $restaurantModel;
    private $orderModel;
    private $auth;
    
    public function __construct() {
        $this->mealModel = new Meal();
        $this->packageModel = new MealPackage();
        $this->restaurantModel = new RestaurantProfile();
        $this->orderModel = new Order();
        $this->auth = Auth::getInstance();
        
        // Check if user is logged in
        if (!$this->auth->isLoggedIn()) {
            redirect('/login');
        }
        
        // Only users can access packages
        if (!$this->auth->hasRole('user')) {
            redirect('/403');
        }
    }
    
    /**
     * Display package creation page
     */
    public function index() {
        // Get available meals by type for selections
        $filter = ['available' => true];
        
        // Get breakfast meals
        $filter['meal_type'] = 'breakfast';
        $breakfastMeals = $this->mealModel->getAllAvailable($filter);
        
        // Get lunch meals
        $filter['meal_type'] = 'lunch';
        $lunchMeals = $this->mealModel->getAllAvailable($filter);
        
        // Get dinner meals
        $filter['meal_type'] = 'dinner';
        $dinnerMeals = $this->mealModel->getAllAvailable($filter);
        
        // Get snack meals
        $filter['meal_type'] = 'snack';
        $snackMeals = $this->mealModel->getAllAvailable($filter);
        
        // Get all restaurants for filter dropdown
        $restaurants = $this->restaurantModel->getAll();
        
        view('meals/packages', [
            'breakfastMeals' => $breakfastMeals,
            'lunchMeals' => $lunchMeals,
            'dinnerMeals' => $dinnerMeals,
            'snackMeals' => $snackMeals,
            'restaurants' => $restaurants
        ]);
    }
    
    /**
     * Process package creation
     */
    public function createPackage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/meals/packages');
        }
        
        $userId = $this->auth->getUserId();
        $packageType = $_POST['package_type'] ?? '';
        
        if (!in_array($packageType, ['day', 'week', 'month'])) {
            redirect('/meals/packages?error=invalid_package_type');
        }
        
        // Process based on package type
        if ($packageType === 'day') {
            return $this->processDayPackage($userId);
        } elseif ($packageType === 'week') {
            return $this->processWeekPackage($userId);
        } elseif ($packageType === 'month') {
            return $this->processMonthPackage($userId);
        }
    }
    
    /**
     * Process day package
     */
    private function processDayPackage($userId) {
        $packageName = $_POST['package_name'] ?? '';
        $deliveryDate = $_POST['delivery_date'] ?? '';
        $mealTypes = $_POST['meal_types'] ?? [];
        $meals = $_POST['meals'] ?? [];
        
        if (empty($packageName) || empty($deliveryDate) || empty($mealTypes) || empty($meals)) {
            redirect('/meals/packages?error=missing_fields');
        }
        
        // Create package
        $packageData = [
            'user_id' => $userId,
            'name' => $packageName,
            'type' => 'day',
            'start_date' => $deliveryDate,
            'end_date' => $deliveryDate, // Same day for day package
            'status' => 'pending'
        ];
        
        $packageId = $this->packageModel->create($packageData);
        
        if (!$packageId) {
            redirect('/meals/packages?error=package_creation_failed');
        }
        
        // Add meals to package
        $totalPrice = 0;
        $mealCount = 0;
        
        foreach ($mealTypes as $mealType) {
            if (isset($meals[$mealType]) && !empty($meals[$mealType])) {
                $mealId = $meals[$mealType];
                $meal = $this->mealModel->getById($mealId);
                
                if ($meal) {
                    $this->packageModel->addPackageMeal($packageId, $mealId, $deliveryDate, $mealType);
                    $totalPrice += $meal['price'];
                    $mealCount++;
                }
            }
        }
        
        // Update package total price
        $this->packageModel->updatePackage($packageId, [
            'total_price' => $totalPrice,
            'meal_count' => $mealCount
        ]);
        
        // Create order for the package
        $orderData = [
            'user_id' => $userId,
            'delivery_address' => $_POST['delivery_address'] ?? 'A ser confirmado',
            'delivery_date' => $deliveryDate,
            'payment_method' => 'pending', // Will be set during checkout
            'notes' => 'Pacote de refeições para o dia ' . $deliveryDate,
            'total' => $totalPrice,
            'status' => 'pending',
            'package_id' => $packageId
        ];
        
        $orderId = $this->orderModel->create($orderData);
        
        // Add order items
        foreach ($mealTypes as $mealType) {
            if (isset($meals[$mealType]) && !empty($meals[$mealType])) {
                $mealId = $meals[$mealType];
                $meal = $this->mealModel->getById($mealId);
                
                if ($meal) {
                    $this->orderModel->addOrderItem($orderId, $mealId, 1, $meal['price']);
                }
            }
        }
        
        redirect('/meals/packages/view?id=' . $packageId);
    }
    
    /**
     * Process week package
     */
    private function processWeekPackage($userId) {
        $packageName = $_POST['package_name'] ?? '';
        $startDate = $_POST['start_date'] ?? '';
        $days = $_POST['days'] ?? [];
        $mealTypes = $_POST['meal_types'] ?? [];
        $maxCalories = $_POST['max_calories'] ?? 2000;
        $minProtein = $_POST['min_protein'] ?? 75;
        $preferredRestaurant = $_POST['preferred_restaurant'] ?? null;
        
        if (empty($packageName) || empty($startDate) || empty($days) || empty($mealTypes)) {
            redirect('/meals/packages?error=missing_fields');
        }
        
        // Calculate end date (start date + 6 days)
        $endDate = date('Y-m-d', strtotime($startDate . ' + 6 days'));
        
        // Create package
        $packageData = [
            'user_id' => $userId,
            'name' => $packageName,
            'type' => 'week',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'pending',
            'preferences' => json_encode([
                'days' => $days,
                'meal_types' => $mealTypes,
                'max_calories' => $maxCalories,
                'min_protein' => $minProtein,
                'preferred_restaurant' => $preferredRestaurant
            ])
        ];
        
        $packageId = $this->packageModel->create($packageData);
        
        if (!$packageId) {
            redirect('/meals/packages?error=package_creation_failed');
        }
        
        // Get suitable meals based on preferences
        $mealFilter = [
            'available' => true
        ];
        
        if (!empty($maxCalories)) {
            $mealFilter['max_calories'] = $maxCalories;
        }
        
        if (!empty($minProtein)) {
            $mealFilter['min_protein'] = $minProtein;
        }
        
        if (!empty($preferredRestaurant)) {
            $mealFilter['restaurant_id'] = $preferredRestaurant;
        }
        
        // Estimate total price based on meal count
        $totalDays = count($days);
        $totalMealTypes = count($mealTypes);
        $estimatedMealCount = $totalDays * $totalMealTypes;
        
        // Estimate average price of R$35 per meal
        $estimatedPrice = $estimatedMealCount * 35;
        
        // Update package with estimated values
        $this->packageModel->updatePackage($packageId, [
            'meal_count' => $estimatedMealCount,
            'total_price' => $estimatedPrice
        ]);
        
        redirect('/meals/packages/view?id=' . $packageId);
    }
    
    /**
     * Process month package
     */
    private function processMonthPackage($userId) {
        $packageName = $_POST['package_name'] ?? '';
        $startMonth = $_POST['start_month'] ?? '';
        $days = $_POST['days'] ?? [];
        $mealTypes = $_POST['meal_types'] ?? [];
        $nutritionPlan = $_POST['nutrition_plan'] ?? 'balance';
        $dietaryRestrictions = $_POST['dietary_restrictions'] ?? '';
        $additionalNotes = $_POST['additional_notes'] ?? '';
        
        if (empty($packageName) || empty($startMonth) || empty($days) || empty($mealTypes)) {
            redirect('/meals/packages?error=missing_fields');
        }
        
        // Calculate start and end dates
        $startDate = $startMonth . '-01'; // First day of the month
        $endDate = date('Y-m-t', strtotime($startDate)); // Last day of the month
        
        // Create package
        $packageData = [
            'user_id' => $userId,
            'name' => $packageName,
            'type' => 'month',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'pending',
            'preferences' => json_encode([
                'days' => $days,
                'meal_types' => $mealTypes,
                'nutrition_plan' => $nutritionPlan,
                'dietary_restrictions' => $dietaryRestrictions,
                'additional_notes' => $additionalNotes
            ])
        ];
        
        $packageId = $this->packageModel->create($packageData);
        
        if (!$packageId) {
            redirect('/meals/packages?error=package_creation_failed');
        }
        
        // Estimate total price based on meal count
        $totalDays = count($days);
        $totalMealTypes = count($mealTypes);
        $weeksInMonth = 4;
        $estimatedMealCount = $totalDays * $totalMealTypes * $weeksInMonth;
        
        // Estimate average price of R$35 per meal with 10% monthly discount
        $estimatedPrice = $estimatedMealCount * 35 * 0.9;
        
        // Update package with estimated values
        $this->packageModel->updatePackage($packageId, [
            'meal_count' => $estimatedMealCount,
            'total_price' => $estimatedPrice
        ]);
        
        redirect('/meals/packages/view?id=' . $packageId);
    }
    
    /**
     * View package details
     */
    public function viewPackage() {
        $packageId = $_GET['id'] ?? 0;
        $userId = $this->auth->getUserId();
        
        $package = $this->packageModel->getById($packageId);
        
        if (!$package || $package['user_id'] != $userId) {
            redirect('/meals/packages');
        }
        
        // Get package meals
        $packageMeals = $this->packageModel->getPackageMeals($packageId);
        
        // Get related orders
        $orders = $this->orderModel->getByPackageId($packageId);
        
        view('meals/view_package', [
            'package' => $package,
            'packageMeals' => $packageMeals,
            'orders' => $orders
        ]);
    }
    
    /**
     * List user packages
     */
    public function listPackages() {
        $userId = $this->auth->getUserId();
        
        // Get active packages
        $activePackages = $this->packageModel->getUserPackages($userId, 'active');
        
        // Get pending packages
        $pendingPackages = $this->packageModel->getUserPackages($userId, 'pending');
        
        // Get completed packages
        $completedPackages = $this->packageModel->getUserPackages($userId, 'completed');
        
        view('meals/my_packages', [
            'activePackages' => $activePackages,
            'pendingPackages' => $pendingPackages,
            'completedPackages' => $completedPackages
        ]);
    }
    
    /**
     * Cancel package
     */
    public function cancelPackage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/meals/packages/list');
        }
        
        $packageId = $_POST['package_id'] ?? 0;
        $userId = $this->auth->getUserId();
        
        $package = $this->packageModel->getById($packageId);
        
        if (!$package || $package['user_id'] != $userId) {
            redirect('/meals/packages/list');
        }
        
        // Only allow cancellation of pending or active packages
        if (!in_array($package['status'], ['pending', 'active'])) {
            redirect('/meals/packages/list?error=cannot_cancel');
        }
        
        // Update package status
        $this->packageModel->updatePackage($packageId, [
            'status' => 'canceled'
        ]);
        
        // Cancel related orders that are still pending
        $orders = $this->orderModel->getByPackageId($packageId);
        foreach ($orders as $order) {
            if ($order['status'] === 'pending') {
                $this->orderModel->updateStatus($order['id'], 'canceled');
            }
        }
        
        redirect('/meals/packages/list?success=canceled');
    }
}