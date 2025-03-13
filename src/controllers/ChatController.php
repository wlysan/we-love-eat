<?php
require_once __DIR__ . '/../models/Chat.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/NutritionistProfile.php';
require_once __DIR__ . '/../utils/Auth.php';

class ChatController {
    private $chatModel;
    private $userModel;
    private $nutritionistModel;
    private $auth;
    
    public function __construct() {
        $this->chatModel = new Chat();
        $this->userModel = new User();
        $this->nutritionistModel = new NutritionistProfile();
        $this->auth = Auth::getInstance();
    }
    
    /**
     * Chat list page
     */
    public function index() {
        $userId = $this->auth->getUserId();
        $userRole = $this->auth->getUser()['role'];
        
        if ($userRole === 'nutritionist') {
            $nutritionist = $this->nutritionistModel->getByUserId($userId);
            $chats = $this->chatModel->getByNutritionistId($nutritionist['id']);
        } else {
            $chats = $this->chatModel->getByUserId($userId);
        }
        
        view('chat/index', [
            'chats' => $chats,
            'userRole' => $userRole
        ]);
    }
    
    /**
     * View chat page
     */
    public function view() {
        $chatId = $_GET['id'] ?? 0;
        $chat = $this->chatModel->getById($chatId);
        
        if (!$chat) {
            redirect('/chats');
        }
        
        $userId = $this->auth->getUserId();
        $userRole = $this->auth->getUser()['role'];
        
        // Check if user has access to this chat
        if ($userRole === 'nutritionist') {
            $nutritionist = $this->nutritionistModel->getByUserId($userId);
            if ($chat['nutritionist_id'] != $nutritionist['id']) {
                redirect('/403');
            }
        } else if ($chat['user_id'] != $userId) {
            redirect('/403');
        }
        
        // Mark messages as read
        $this->chatModel->markAsRead($chatId, $userId);
        
        // Get messages
        $messages = $this->chatModel->getMessages($chatId);
        
        view('chat/view', [
            'chat' => $chat,
            'messages' => $messages,
            'userId' => $userId
        ]);
    }
    
    /**
     * Send message
     */
    public function send() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/chats');
        }
        
        $chatId = $_POST['chat_id'] ?? 0;
        $message = $_POST['message'] ?? '';
        
        if (empty($message)) {
            redirect("/chats/view?id=$chatId");
        }
        
        $userId = $this->auth->getUserId();
        $chat = $this->chatModel->getById($chatId);
        
        if (!$chat) {
            redirect('/chats');
        }
        
        $userRole = $this->auth->getUser()['role'];
        
        // Check if user has access to this chat
        if ($userRole === 'nutritionist') {
            $nutritionist = $this->nutritionistModel->getByUserId($userId);
            if ($chat['nutritionist_id'] != $nutritionist['id']) {
                redirect('/403');
            }
        } else if ($chat['user_id'] != $userId) {
            redirect('/403');
        }
        
        // Send message
        $this->chatModel->sendMessage($chatId, $userId, $message);
        
        redirect("/chats/view?id=$chatId");
    }
    
    /**
     * Create new chat
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/nutritionists');
        }
        
        $nutritionistId = $_POST['nutritionist_id'] ?? 0;
        
        if (empty($nutritionistId)) {
            redirect('/nutritionists');
        }
        
        $userId = $this->auth->getUserId();
        
        // Create chat
        $chatId = $this->chatModel->create($userId, $nutritionistId);
        
        if ($chatId) {
            // Send initial message if provided
            if (!empty($_POST['message'])) {
                $this->chatModel->sendMessage($chatId, $userId, $_POST['message']);
            }
            
            redirect("/chats/view?id=$chatId");
        } else {
            redirect('/nutritionists');
        }
    }
    
    /**
     * Toggle progress sharing
     */
    public function toggleProgress() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/chats');
        }
        
        $chatId = $_POST['chat_id'] ?? 0;
        $shareProgress = isset($_POST['share_progress']) ? (bool)$_POST['share_progress'] : false;
        
        $chat = $this->chatModel->getById($chatId);
        
        if (!$chat) {
            redirect('/chats');
        }
        
        $userId = $this->auth->getUserId();
        
        // Only the user can toggle progress sharing
        if ($chat['user_id'] != $userId) {
            redirect('/403');
        }
        
        // Update progress sharing setting
        $this->chatModel->updateShareProgress($chatId, $shareProgress);
        
        redirect("/chats/view?id=$chatId");
    }
}