<?php
require_once __DIR__ . '/../models/User.php';

class Auth {
    private static $instance = null;
    private $userModel;
    
    private function __construct() {
        $this->userModel = new User();
        
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            session_start();
        }
    }
    
    /**
     * Get Auth instance (singleton pattern)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Attempt user login
     */
    public function login($email, $password) {
        $user = $this->userModel->getByEmail($email);
        
        if (!$user) {
            return ['error' => 'Usuário não encontrado'];
        }
        
        if (!password_verify($password, $user['password'])) {
            return ['error' => 'Senha incorreta'];
        }
        
        // Set session data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['logged_in'] = true;
        
        return [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
    }
    
    /**
     * Log out the current user
     */
    public function logout() {
        // Unset all session variables
        $_SESSION = [];
        
        // Destroy the session
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        
        return true;
    }
    
    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    /**
     * Get current user ID
     */
    public function getUserId() {
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }
    
    /**
     * Get current user data
     */
    public function getUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'role' => $_SESSION['user_role']
        ];
    }
    
    /**
     * Check if current user has a specific role
     */
    public function hasRole($roles) {
        if (!$this->isLoggedIn()) {
            return false;
        }
        
        if (is_string($roles)) {
            $roles = [$roles];
        }
        
        return in_array($_SESSION['user_role'], $roles);
    }
    
    /**
     * Register a new user
     */
    public function register($name, $email, $password, $role = 'user') {
        return $this->userModel->create($name, $email, $password, $role);
    }
}