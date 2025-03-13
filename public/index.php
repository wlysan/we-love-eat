<?php

/**
 * Main entry point of the application
 */

// Load initialization
require_once __DIR__ . '/../src/init.php';

// Include all controller files
$controllerFiles = glob(__DIR__ . '/../src/controllers/*.php');
foreach ($controllerFiles as $controller) {
    require_once $controller;
}

// Simple router
$route = getCurrentRoute();
$auth = Auth::getInstance();

// Define routes and their handlers
$routes = [
    // Public routes
    '/' => 'HomeController::index',
    '/login' => 'AuthController::login',
    '/register' => 'AuthController::register',
    '/logout' => 'AuthController::logout',

    // User routes
    '/profile' => 'UserController::profile',
    '/profile/edit' => 'UserController::editProfile',
    '/profile/measurements' => 'UserController::measurements',
    '/profile/measurements/add' => 'UserController::addMeasurement',
    '/dashboard' => 'DashboardController::user',

    // Diet routes
    '/diets' => 'DietController::index',
    '/diets/view' => 'DietController::view',
    '/diets/meals' => 'DietController::meals',
    '/diets/select-meal' => 'DietController::selectMeal',
    '/diets/update-status' => 'DietController::updateStatus',

    // Nutritionist routes
    '/nutritionists' => 'NutritionistController::index',
    '/nutritionists/view' => 'NutritionistController::view',
    '/nutritionist/dashboard' => 'DashboardController::nutritionist',
    '/nutritionist/clients' => 'NutritionistController::clients',
    '/nutritionist/diets' => 'NutritionistController::diets',
    '/nutritionist/diets/create' => 'NutritionistController::createDiet',
    '/nutritionist/diets/edit' => 'NutritionistController::editDiet',
    '/nutritionist/diets/view' => 'NutritionistController::viewDiet',
    '/nutritionist/diets/add-meal' => 'NutritionistController::addMeal',
    '/nutritionist/diets/edit-meal' => 'NutritionistController::editMeal',

    // Restaurant routes
    '/restaurant/dashboard' => 'DashboardController::restaurant',
    '/restaurant/meals' => 'RestaurantController::meals',
    '/restaurant/meals/create' => 'RestaurantController::createMeal',
    '/restaurant/meals/edit' => 'RestaurantController::editMeal',
    '/restaurant/meals/view' => 'RestaurantController::viewMeal',
    '/restaurant/ingredients' => 'RestaurantController::ingredients',
    '/restaurant/ingredients/create' => 'RestaurantController::createIngredient',
    '/restaurant/ingredients/edit' => 'RestaurantController::editIngredient',
    '/restaurant/orders' => 'RestaurantController::orders',
    '/restaurant/orders/view' => 'RestaurantController::viewOrder',
    '/restaurant/orders/update-status' => 'RestaurantController::updateOrderStatus',

    // Chat routes
    '/chats' => 'ChatController::index',
    '/chats/view' => 'ChatController::view',
    '/chats/send' => 'ChatController::send',
    '/chats/create' => 'ChatController::create',
    '/chats/progress' => 'ChatController::toggleProgress',

    // Admin routes
    '/admin/dashboard' => 'AdminController::dashboard',
    '/admin/users' => 'AdminController::users',
    '/admin/users/create' => 'AdminController::createUser',
    '/admin/users/edit' => 'AdminController::editUser',
    '/admin/nutritionists' => 'AdminController::nutritionists',
    '/admin/nutritionists/create' => 'AdminController::createNutritionist',
    '/admin/nutritionists/edit' => 'AdminController::editNutritionist',
    '/admin/restaurants' => 'AdminController::restaurants',
    '/admin/restaurants/create' => 'AdminController::createRestaurant',
    '/admin/restaurants/edit' => 'AdminController::editRestaurant',

    // Meal catalog and ordering routes
    '/meals/catalog' => 'MealController::catalog',
    '/meals/order' => 'MealController::placeOrder',
    '/meals/orders' => 'MealController::orders',
    '/meals/orders/view' => 'MealController::viewOrder',
    '/meals/orders/cancel' => 'MealController::cancelOrder',

    // API routes
    '/api/meals' => 'ApiController::meals',
    '/api/meals/nutrition' => 'ApiController::mealNutrition',
    '/api/ingredients' => 'ApiController::ingredients',
    '/api/measurements' => 'ApiController::measurements',

    // Error routes
    '/404' => 'ErrorController::notFound',
    '/403' => 'ErrorController::forbidden'
];

// Authentication and role requirements for routes
$authRequirements = [
    'public' => ['/', '/login', '/register', '/logout', '/404', '/403'],
    'user' => [
        '/profile',
        '/profile/edit',
        '/profile/measurements',
        '/profile/measurements/add',
        '/dashboard',
        '/diets',
        '/diets/view',
        '/diets/meals',
        '/diets/select-meal',
        '/diets/update-status',
        '/nutritionists',
        '/nutritionists/view',
        '/chats',
        '/chats/view',
        '/chats/send',
        '/chats/create',
        '/chats/progress',
        '/meals/catalog',
        '/meals/order',
        '/meals/orders',
        '/meals/orders/view',
        '/meals/orders/cancel'
    ],
    'nutritionist' => [
        '/nutritionist/dashboard',
        '/nutritionist/clients',
        '/nutritionist/diets',
        '/nutritionist/diets/create',
        '/nutritionist/diets/edit',
        '/nutritionist/diets/view',
        '/nutritionist/diets/add-meal',
        '/nutritionist/diets/edit-meal'
    ],
    'restaurant' => [
        '/restaurant/dashboard',
        '/restaurant/meals',
        '/restaurant/meals/create',
        '/restaurant/meals/edit',
        '/restaurant/meals/view',
        '/restaurant/ingredients',
        '/restaurant/ingredients/create',
        '/restaurant/ingredients/edit',
        '/restaurant/orders',
        '/restaurant/orders/view',
        '/restaurant/orders/update-status'
    ],
    'admin' => [
        '/admin/dashboard',
        '/admin/users',
        '/admin/users/create',
        '/admin/users/edit',
        '/admin/nutritionists',
        '/admin/nutritionists/create',
        '/admin/nutritionists/edit',
        '/admin/restaurants',
        '/admin/restaurants/create',
        '/admin/restaurants/edit'
    ],


];

// Check if route exists
if (!isset($routes[$route])) {
    redirect('/404');
}

// Check authentication and role requirements
$requiresAuth = !in_array($route, $authRequirements['public']);

if ($requiresAuth && !$auth->isLoggedIn()) {
    redirect('/login');
}

if ($auth->isLoggedIn()) {
    $userRole = $auth->getUser()['role'];
    $allowedRoutes = array_merge($authRequirements['public'], $authRequirements['user']);

    if ($userRole === 'nutritionist') {
        $allowedRoutes = array_merge($allowedRoutes, $authRequirements['nutritionist']);
    } elseif ($userRole === 'restaurant') {
        $allowedRoutes = array_merge($allowedRoutes, $authRequirements['restaurant']);
    } elseif ($userRole === 'admin') {
        $allowedRoutes = array_merge(
            $allowedRoutes,
            $authRequirements['nutritionist'],
            $authRequirements['restaurant'],
            $authRequirements['admin']
        );
    }

    if (!in_array($route, $allowedRoutes)) {
        redirect('/403');
    }
}

// Call the route handler
list($controller, $method) = explode('::', $routes[$route]);
$controller = new $controller();
$controller->$method();
