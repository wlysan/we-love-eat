<?php
require_once __DIR__ . '/../utils/Auth.php';

class HomeController {
    private $auth;
    
    public function __construct() {
        $this->auth = Auth::getInstance();
    }
    
    /**
     * Home page
     */
    public function index() {
        // If user is logged in, redirect to dashboard
        if ($this->auth->isLoggedIn()) {
            $this->redirectToDashboard();
        }
        
        view('home/index');
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