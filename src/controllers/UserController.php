<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/UserProfile.php';
require_once __DIR__ . '/../utils/Auth.php';
require_once __DIR__ . '/../utils/Formatter.php';

class UserController {
    private $userModel;
    private $profileModel;
    private $auth;
    
    public function __construct() {
        $this->userModel = new User();
        $this->profileModel = new UserProfile();
        $this->auth = Auth::getInstance();
    }
    
    /**
     * User profile page
     */
    public function profile() {
        $userId = $this->auth->getUserId();
        $user = $this->userModel->getById($userId);
        $profile = $this->profileModel->getByUserId($userId);
        
        view('user/profile', [
            'user' => $user,
            'profile' => $profile
        ]);
    }
    
    /**
     * Edit profile page
     */
    public function editProfile() {
        $userId = $this->auth->getUserId();
        $user = $this->userModel->getById($userId);
        $profile = $this->profileModel->getByUserId($userId);
        
        $success = '';
        $error = '';
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Update user data
            $userData = [
                'name' => $_POST['name'] ?? $user['name']
            ];
            
            $this->userModel->update($userId, $userData);
            
            // Update profile data
            $profileData = [
                'birth_date' => Formatter::dateToDatabase($_POST['birth_date'] ?? ''),
                'gender' => $_POST['gender'] ?? '',
                'height' => $_POST['height'] ?? null,
                'current_weight' => $_POST['current_weight'] ?? null,
                'goal_weight' => $_POST['goal_weight'] ?? null,
                'activity_level' => $_POST['activity_level'] ?? '',
                'health_conditions' => $_POST['health_conditions'] ?? '',
                'dietary_restrictions' => $_POST['dietary_restrictions'] ?? ''
            ];
            
            if ($profile) {
                $result = $this->profileModel->update($userId, $profileData);
            } else {
                $result = $this->profileModel->create($userId, $profileData);
            }
            
            if ($result) {
                $success = 'Perfil atualizado com sucesso';
                
                // Refresh data
                $user = $this->userModel->getById($userId);
                $profile = $this->profileModel->getByUserId($userId);
            } else {
                $error = 'Erro ao atualizar perfil';
            }
        }
        
        view('user/edit_profile', [
            'user' => $user,
            'profile' => $profile,
            'success' => $success,
            'error' => $error
        ]);
    }
    
    /**
     * User measurements page
     */
    public function measurements() {
        $userId = $this->auth->getUserId();
        $measurements = $this->profileModel->getMeasurements($userId);
        
        view('user/measurements', [
            'measurements' => $measurements
        ]);
    }
    
    /**
     * Add measurement page
     */
    public function addMeasurement() {
        $userId = $this->auth->getUserId();
        $profile = $this->profileModel->getByUserId($userId);
        
        $success = '';
        $error = '';
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $measurementData = [
                'date' => Formatter::dateToDatabase($_POST['date'] ?? date('d/m/Y')),
                'weight' => $_POST['weight'] ?? null,
                'body_fat_percentage' => $_POST['body_fat_percentage'] ?? null,
                'waist' => $_POST['waist'] ?? null,
                'chest' => $_POST['chest'] ?? null,
                'arms' => $_POST['arms'] ?? null,
                'legs' => $_POST['legs'] ?? null,
                'notes' => $_POST['notes'] ?? ''
            ];
            
            $result = $this->profileModel->addMeasurement($userId, $measurementData);
            
            if ($result) {
                $success = 'Medida adicionada com sucesso';
                
                // Update current weight in profile
                if (isset($_POST['weight']) && !empty($_POST['weight'])) {
                    $this->profileModel->update($userId, [
                        'current_weight' => $_POST['weight']
                    ]);
                }
                
                // Redirect to measurements page
                redirect('/profile/measurements');
            } else {
                $error = 'Erro ao adicionar medida';
            }
        }
        
        view('user/add_measurement', [
            'profile' => $profile,
            'success' => $success,
            'error' => $error
        ]);
    }
}