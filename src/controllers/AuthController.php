<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/UserProfile.php';
require_once __DIR__ . '/../utils/Auth.php';

class AuthController {
    private $userModel;
    private $profileModel;
    private $auth;
    
    public function __construct() {
        $this->userModel = new User();
        $this->profileModel = new UserProfile();
        $this->auth = Auth::getInstance();
    }
    
    /**
     * Login page
     */
    public function login() {
        // If already logged in, redirect to dashboard
        if ($this->auth->isLoggedIn()) {
            $this->redirectToDashboard();
        }
        
        $error = '';
        
        // Process login form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $result = $this->auth->login($email, $password);
            
            if (isset($result['error'])) {
                $error = $result['error'];
            } else {
                $this->redirectToDashboard();
            }
        }
        
        view('auth/login', [
            'error' => $error
        ]);
    }
    
    /**
     * Register page
     */
    public function register() {
        // If already logged in, redirect to dashboard
        if ($this->auth->isLoggedIn()) {
            $this->redirectToDashboard();
        }
        
        $error = '';
        
        // Process registration form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
            
            // Validate form
            if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
                $error = 'Todos os campos são obrigatórios';
            } else if ($password !== $confirm) {
                $error = 'As senhas não coincidem';
            } else if (strlen($password) < 6) {
                $error = 'A senha deve ter pelo menos 6 caracteres';
            } else {
                // Create user
                $result = $this->auth->register($name, $email, $password, 'user');
                
                if (isset($result['error'])) {
                    $error = $result['error'];
                } else {
                    // Create empty profile
                    $this->profileModel->create($result['id'], []);
                    
                    // Auto login
                    $this->auth->login($email, $password);
                    
                    // Redirect to profile edit page to complete profile
                    redirect('/profile/edit');
                }
            }
        }
        
        view('auth/register', [
            'error' => $error
        ]);
    }
    
    /**
     * Logout
     */
    public function logout() {
        $this->auth->logout();
        redirect('/login');
    }
    
    /**
     * Redirect to appropriate dashboard based on user role
     */
    private function redirectToDashboard() {
        $user = $this->auth->getUser();
        
        switch ($user['role']) {
            case 'admin':
                redirect('/admin/dashboard');
                break;
            case 'nutritionist':
                redirect('/nutritionist/dashboard');
                break;
            case 'restaurant':
                redirect('/restaurant/dashboard');
                break;
            default:
                redirect('/dashboard');
                break;
        }
    }
}