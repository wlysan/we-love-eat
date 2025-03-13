<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/UserProfile.php';
require_once __DIR__ . '/../models/NutritionistProfile.php';
require_once __DIR__ . '/../models/RestaurantProfile.php';
require_once __DIR__ . '/../utils/Auth.php';

class AdminController {
    private $userModel;
    private $profileModel;
    private $nutritionistModel;
    private $restaurantModel;
    private $auth;
    
    public function __construct() {
        $this->userModel = new User();
        $this->profileModel = new UserProfile();
        $this->nutritionistModel = new NutritionistProfile();
        $this->restaurantModel = new RestaurantProfile();
        $this->auth = Auth::getInstance();
        
        // Check if user is admin
        if (!$this->auth->hasRole('admin')) {
            redirect('/403');
        }
    }
    
    /**
     * Admin dashboard
     */
    public function dashboard() {
        // Get counts
        $users = $this->userModel->getAll(['role' => 'user']);
        $nutritionists = $this->userModel->getAll(['role' => 'nutritionist']);
        $restaurants = $this->userModel->getAll(['role' => 'restaurant']);
        
        $userCount = count($users);
        $nutritionistCount = count($nutritionists);
        $restaurantCount = count($restaurants);
        
        view('admin/dashboard', [
            'userCount' => $userCount,
            'nutritionistCount' => $nutritionistCount,
            'restaurantCount' => $restaurantCount
        ]);
    }
    
    /**
     * Manage users
     */
    public function users() {
        $users = $this->userModel->getAll(['role' => 'user']);
        
        view('admin/users', [
            'users' => $users
        ]);
    }
    
    /**
     * Create new user
     */
    public function createUser() {
        $success = '';
        $error = '';
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Validate form
            if (empty($name) || empty($email) || empty($password)) {
                $error = 'Todos os campos são obrigatórios';
            } else if (strlen($password) < 6) {
                $error = 'A senha deve ter pelo menos 6 caracteres';
            } else {
                // Create user
                $result = $this->userModel->create($name, $email, $password, 'user');
                
                if (isset($result['error'])) {
                    $error = $result['error'];
                } else {
                    // Create empty profile
                    $this->profileModel->create($result['id'], []);
                    
                    $success = 'Usuário criado com sucesso';
                }
            }
        }
        
        view('admin/create_user', [
            'success' => $success,
            'error' => $error
        ]);
    }
    
    /**
     * Edit user
     */
    public function editUser() {
        $id = $_GET['id'] ?? 0;
        $user = $this->userModel->getById($id);
        
        if (!$user || $user['role'] !== 'user') {
            redirect('/admin/users');
        }
        
        $profile = $this->profileModel->getByUserId($id);
        $success = '';
        $error = '';
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Update user data
            $userData = [
                'name' => $_POST['name'] ?? $user['name'],
                'email' => $_POST['email'] ?? $user['email']
            ];
            
            $this->userModel->update($id, $userData);
            
            // Update password if provided
            if (!empty($_POST['password'])) {
                $this->userModel->updatePassword($id, $_POST['password']);
            }
            
            $success = 'Usuário atualizado com sucesso';
            
            // Refresh data
            $user = $this->userModel->getById($id);
        }
        
        view('admin/edit_user', [
            'user' => $user,
            'profile' => $profile,
            'success' => $success,
            'error' => $error
        ]);
    }
    
    /**
     * Manage nutritionists
     */
    public function nutritionists() {
        $nutritionists = $this->nutritionistModel->getAll();
        
        view('admin/nutritionists', [
            'nutritionists' => $nutritionists
        ]);
    }
    
    /**
     * Create new nutritionist
     */
    public function createNutritionist() {
        $success = '';
        $error = '';
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $professionalId = $_POST['professional_id'] ?? '';
            $specialties = $_POST['specialties'] ?? '';
            $bio = $_POST['bio'] ?? '';
            $education = $_POST['education'] ?? '';
            $experience = $_POST['experience'] ?? '';
            
            // Validate form
            if (empty($name) || empty($email) || empty($password) || empty($professionalId)) {
                $error = 'Os campos Nome, Email, Senha e Número CRN são obrigatórios';
            } else if (strlen($password) < 6) {
                $error = 'A senha deve ter pelo menos 6 caracteres';
            } else {
                // Create user
                $result = $this->userModel->create($name, $email, $password, 'nutritionist');
                
                if (isset($result['error'])) {
                    $error = $result['error'];
                } else {
                    // Create nutritionist profile
                    $profileData = [
                        'specialties' => $specialties,
                        'bio' => $bio,
                        'education' => $education,
                        'experience' => $experience
                    ];
                    
                    $this->nutritionistModel->create($result['id'], $professionalId, $profileData);
                    
                    $success = 'Nutricionista criado com sucesso';
                }
            }
        }
        
        view('admin/create_nutritionist', [
            'success' => $success,
            'error' => $error
        ]);
    }
    
    /**
     * Edit nutritionist
     */
    public function editNutritionist() {
        $id = $_GET['id'] ?? 0;
        $nutritionist = $this->nutritionistModel->getById($id);
        
        if (!$nutritionist) {
            redirect('/admin/nutritionists');
        }
        
        $user = $this->userModel->getById($nutritionist['user_id']);
        $success = '';
        $error = '';
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Update user data
            $userData = [
                'name' => $_POST['name'] ?? $user['name'],
                'email' => $_POST['email'] ?? $user['email']
            ];
            
            $this->userModel->update($nutritionist['user_id'], $userData);
            
            // Update password if provided
            if (!empty($_POST['password'])) {
                $this->userModel->updatePassword($nutritionist['user_id'], $_POST['password']);
            }
            
            // Update nutritionist profile
            $profileData = [
                'professional_id' => $_POST['professional_id'] ?? $nutritionist['professional_id'],
                'specialties' => $_POST['specialties'] ?? $nutritionist['specialties'],
                'bio' => $_POST['bio'] ?? $nutritionist['bio'],
                'education' => $_POST['education'] ?? $nutritionist['education'],
                'experience' => $_POST['experience'] ?? $nutritionist['experience']
            ];
            
            $this->nutritionistModel->update($id, $profileData);
            
            $success = 'Nutricionista atualizado com sucesso';
            
            // Refresh data
            $nutritionist = $this->nutritionistModel->getById($id);
            $user = $this->userModel->getById($nutritionist['user_id']);
        }
        
        view('admin/edit_nutritionist', [
            'nutritionist' => $nutritionist,
            'user' => $user,
            'success' => $success,
            'error' => $error
        ]);
    }
    
    /**
     * Manage restaurants
     */
    public function restaurants() {
        $restaurants = $this->restaurantModel->getAll();
        
        view('admin/restaurants', [
            'restaurants' => $restaurants
        ]);
    }
    
    /**
     * Create new restaurant
     */
    public function createRestaurant() {
        $success = '';
        $error = '';
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $cnpj = $_POST['cnpj'] ?? '';
            $address = $_POST['address'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $description = $_POST['description'] ?? '';
            $deliveryAreas = $_POST['delivery_areas'] ?? '';
            
            // Validate form
            if (empty($name) || empty($email) || empty($password) || empty($cnpj) || empty($address) || empty($phone)) {
                $error = 'Os campos Nome, Email, Senha, CNPJ, Endereço e Telefone são obrigatórios';
            } else if (strlen($password) < 6) {
                $error = 'A senha deve ter pelo menos 6 caracteres';
            } else {
                // Create user
                $result = $this->userModel->create($name, $email, $password, 'restaurant');
                
                if (isset($result['error'])) {
                    $error = $result['error'];
                } else {
                    // Create restaurant profile
                    $profileData = [
                        'description' => $description,
                        'delivery_areas' => $deliveryAreas
                    ];
                    
                    $this->restaurantModel->create($result['id'], $cnpj, $address, $phone, $profileData);
                    
                    $success = 'Restaurante criado com sucesso';
                }
            }
        }
        
        view('admin/create_restaurant', [
            'success' => $success,
            'error' => $error
        ]);
    }
    
    /**
     * Edit restaurant
     */
    public function editRestaurant() {
        $id = $_GET['id'] ?? 0;
        $restaurant = $this->restaurantModel->getById($id);
        
        if (!$restaurant) {
            redirect('/admin/restaurants');
        }
        
        $user = $this->userModel->getById($restaurant['user_id']);
        $success = '';
        $error = '';
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Update user data
            $userData = [
                'name' => $_POST['name'] ?? $user['name'],
                'email' => $_POST['email'] ?? $user['email']
            ];
            
            $this->userModel->update($restaurant['user_id'], $userData);
            
            // Update password if provided
            if (!empty($_POST['password'])) {
                $this->userModel->updatePassword($restaurant['user_id'], $_POST['password']);
            }
            
            // Update restaurant profile
            $profileData = [
                'cnpj' => $_POST['cnpj'] ?? $restaurant['cnpj'],
                'address' => $_POST['address'] ?? $restaurant['address'],
                'phone' => $_POST['phone'] ?? $restaurant['phone'],
                'description' => $_POST['description'] ?? $restaurant['description'],
                'delivery_areas' => $_POST['delivery_areas'] ?? $restaurant['delivery_areas']
            ];
            
            $this->restaurantModel->update($id, $profileData);
            
            $success = 'Restaurante atualizado com sucesso';
            
            // Refresh data
            $restaurant = $this->restaurantModel->getById($id);
            $user = $this->userModel->getById($restaurant['user_id']);
        }
        
        view('admin/edit_restaurant', [
            'restaurant' => $restaurant,
            'user' => $user,
            'success' => $success,
            'error' => $error
        ]);
    }
}