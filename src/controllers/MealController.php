<?php
require_once __DIR__ . '/../models/Meal.php';
require_once __DIR__ . '/../models/RestaurantProfile.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../utils/Auth.php';

class MealController {
    private $mealModel;
    private $restaurantModel;
    private $orderModel;
    private $auth;
    
    public function __construct() {
        $this->mealModel = new Meal();
        $this->restaurantModel = new RestaurantProfile();
        $this->orderModel = new Order();
        $this->auth = Auth::getInstance();
        
        // Check if user is logged in
        if (!$this->auth->isLoggedIn()) {
            redirect('/login');
        }
    }
    
    /**
     * Meal catalog page
     */
    public function catalog() {
        // Default to user role
        if (!$this->auth->hasRole('user')) {
            redirect('/403');
        }
        
        // Pagination parameters
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $itemsPerPage = 9;
        $offset = ($currentPage - 1) * $itemsPerPage;
        
        // Build filter from query parameters
        $filter = [
            'restaurant_id' => $_GET['restaurant_id'] ?? '',
            'meal_type' => $_GET['meal_type'] ?? '',
            'max_calories' => $_GET['max_calories'] ?? '',
            'min_protein' => $_GET['min_protein'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];
        
        // Get all restaurants for filter dropdown
        $restaurants = $this->restaurantModel->getAll();
        
        // Get meals with pagination
        $meals = $this->mealModel->getAllAvailable($filter, $itemsPerPage, $offset);
        
        // Count total meals for pagination
        $totalMeals = $this->mealModel->countAllAvailable($filter);
        $totalPages = ceil($totalMeals / $itemsPerPage);
        
        view('meals/catalog', [
            'meals' => $meals,
            'restaurants' => $restaurants,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'filter' => $filter
        ]);
    }
    
    /**
     * Place an order
     */
    public function placeOrder() {
        // Check if request method is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/meals/catalog');
        }
        
        $userId = $this->auth->getUserId();
        
        // Get cart items from POST data
        $cartItems = json_decode($_POST['cart_items'] ?? '[]', true);
        
        if (empty($cartItems)) {
            redirect('/meals/catalog');
        }
        
        $deliveryAddress = $_POST['delivery_address'] ?? '';
        $deliveryDate = $_POST['delivery_date'] ?? '';
        $paymentMethod = $_POST['payment_method'] ?? '';
        $notes = $_POST['notes'] ?? '';
        
        // Validate required fields
        if (empty($deliveryAddress) || empty($deliveryDate) || empty($paymentMethod)) {
            redirect('/meals/catalog?error=missing_fields');
        }
        
        // Calculate total
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'];
        }
        
        // Create order
        $orderData = [
            'user_id' => $userId,
            'delivery_address' => $deliveryAddress,
            'delivery_date' => $deliveryDate,
            'payment_method' => $paymentMethod,
            'notes' => $notes,
            'total' => $total,
            'status' => 'pending'
        ];
        
        $orderId = $this->orderModel->create($orderData);
        
        if (!$orderId) {
            redirect('/meals/catalog?error=order_failed');
        }
        
        // Add order items
        foreach ($cartItems as $item) {
            $this->orderModel->addOrderItem($orderId, $item['id'], 1, $item['price']);
        }
        
        // Redirect to order success page
        redirect('/meals/orders?success=true&order_id=' . $orderId);
    }
    
    /**
     * List user orders
     */
    public function orders() {
        $userId = $this->auth->getUserId();
        
        // Get user orders
        $orders = $this->orderModel->getByUserId($userId);
        
        view('meals/orders', [
            'orders' => $orders,
            'success' => isset($_GET['success']),
            'orderId' => $_GET['order_id'] ?? null
        ]);
    }
    
    /**
     * View order details
     */
    public function viewOrder() {
        $userId = $this->auth->getUserId();
        $orderId = $_GET['id'] ?? 0;
        
        $order = $this->orderModel->getById($orderId);
        
        if (!$order || $order['user_id'] != $userId) {
            redirect('/meals/orders');
        }
        
        // Get order items
        $orderItems = $this->orderModel->getOrderItems($orderId);
        
        view('meals/view_order', [
            'order' => $order,
            'orderItems' => $orderItems
        ]);
    }
    
    /**
     * Cancel order
     */
    public function cancelOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/meals/orders');
        }
        
        $userId = $this->auth->getUserId();
        $orderId = $_POST['order_id'] ?? 0;
        
        $order = $this->orderModel->getById($orderId);
        
        if (!$order || $order['user_id'] != $userId) {
            redirect('/meals/orders');
        }
        
        // Only allow cancellation of pending orders
        if ($order['status'] !== 'pending') {
            redirect('/meals/orders?error=cannot_cancel');
        }
        
        // Update order status
        $this->orderModel->updateStatus($orderId, 'canceled');
        
        redirect('/meals/orders?success=canceled');
    }
}