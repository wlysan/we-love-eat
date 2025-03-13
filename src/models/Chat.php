<?php
require_once __DIR__ . '/../config/database.php';

class Chat {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Create a new chat
     */
    public function create($userId, $nutritionistId) {
        // Check if a chat already exists between these users
        $existingChat = $this->getChatByUsers($userId, $nutritionistId);
        if ($existingChat) {
            // If chat exists but is closed, reopen it
            if ($existingChat['status'] === 'closed') {
                $this->updateStatus($existingChat['id'], 'active');
            }
            return $existingChat['id'];
        }
        
        $sql = "INSERT INTO chats (user_id, nutritionist_id) VALUES (:user_id, :nutritionist_id)";
        
        try {
            $chatId = $this->db->insert($sql, [
                ':user_id' => $userId,
                ':nutritionist_id' => $nutritionistId
            ]);
            return $chatId;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get chat by ID
     */
    public function getById($chatId) {
        $sql = "SELECT c.*, 
                u.name as user_name,
                n.user_id as nutritionist_user_id,
                nu.name as nutritionist_name
                FROM chats c
                JOIN users u ON c.user_id = u.id
                JOIN nutritionist_profiles n ON c.nutritionist_id = n.id
                JOIN users nu ON n.user_id = nu.id
                WHERE c.id = :id";
        
        return $this->db->fetchOne($sql, [':id' => $chatId]);
    }
    
    /**
     * Get chat by user and nutritionist
     */
    public function getChatByUsers($userId, $nutritionistId) {
        $sql = "SELECT * FROM chats 
                WHERE user_id = :user_id AND nutritionist_id = :nutritionist_id";
        
        return $this->db->fetchOne($sql, [
            ':user_id' => $userId,
            ':nutritionist_id' => $nutritionistId
        ]);
    }
    
    /**
     * Get chats for a user
     */
    public function getByUserId($userId) {
        $sql = "SELECT c.*, 
                n.user_id as nutritionist_user_id,
                u.name as nutritionist_name,
                (SELECT COUNT(*) FROM chat_messages 
                 WHERE chat_id = c.id AND sender_id != :user_id AND read = 0) as unread_count
                FROM chats c
                JOIN nutritionist_profiles n ON c.nutritionist_id = n.id
                JOIN users u ON n.user_id = u.id
                WHERE c.user_id = :user_id
                ORDER BY c.updated_at DESC";
        
        return $this->db->fetchAll($sql, [':user_id' => $userId]);
    }
    
    /**
     * Get chats for a nutritionist
     */
    public function getByNutritionistId($nutritionistId) {
        $sql = "SELECT c.*, 
                u.name as user_name,
                (SELECT COUNT(*) FROM chat_messages 
                 WHERE chat_id = c.id AND sender_id != 
                 (SELECT user_id FROM nutritionist_profiles WHERE id = :nutritionist_id) 
                 AND read = 0) as unread_count
                FROM chats c
                JOIN users u ON c.user_id = u.id
                WHERE c.nutritionist_id = :nutritionist_id
                ORDER BY c.updated_at DESC";
        
        return $this->db->fetchAll($sql, [':nutritionist_id' => $nutritionistId]);
    }
    
    /**
     * Update chat status
     */
    public function updateStatus($chatId, $status) {
        if (!in_array($status, ['active', 'closed'])) {
            return false;
        }
        
        $sql = "UPDATE chats 
                SET status = :status, updated_at = CURRENT_TIMESTAMP 
                WHERE id = :id";
        
        try {
            $this->db->query($sql, [':id' => $chatId, ':status' => $status]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Update progress sharing setting
     */
    public function updateShareProgress($chatId, $shareProgress) {
        $sql = "UPDATE chats 
                SET share_progress = :share_progress, updated_at = CURRENT_TIMESTAMP 
                WHERE id = :id";
        
        try {
            $this->db->query($sql, [
                ':id' => $chatId, 
                ':share_progress' => $shareProgress ? 1 : 0
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Send a message
     */
    public function sendMessage($chatId, $senderId, $message) {
        $sql = "INSERT INTO chat_messages (chat_id, sender_id, message) 
                VALUES (:chat_id, :sender_id, :message)";
        
        try {
            $messageId = $this->db->insert($sql, [
                ':chat_id' => $chatId,
                ':sender_id' => $senderId,
                ':message' => $message
            ]);
            
            // Update chat updated_at timestamp
            $sql = "UPDATE chats SET updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            $this->db->query($sql, [':id' => $chatId]);
            
            return $messageId;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get messages for a chat
     */
    public function getMessages($chatId, $limit = 50, $offset = 0) {
        $sql = "SELECT cm.*, u.name as sender_name 
                FROM chat_messages cm
                JOIN users u ON cm.sender_id = u.id
                WHERE cm.chat_id = :chat_id
                ORDER BY cm.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        return $this->db->fetchAll($sql, [
            ':chat_id' => $chatId,
            ':limit' => $limit,
            ':offset' => $offset
        ]);
    }
    
    /**
     * Mark messages as read
     */
    public function markAsRead($chatId, $userId) {
        $sql = "UPDATE chat_messages 
                SET read = 1 
                WHERE chat_id = :chat_id AND sender_id != :user_id AND read = 0";
        
        try {
            $this->db->query($sql, [':chat_id' => $chatId, ':user_id' => $userId]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get unread message count for a user
     */
    public function getUnreadCount($userId, $isNutritionist = false) {
        if ($isNutritionist) {
            $sql = "SELECT COUNT(*) as count 
                    FROM chat_messages cm
                    JOIN chats c ON cm.chat_id = c.id
                    JOIN nutritionist_profiles np ON c.nutritionist_id = np.id
                    WHERE np.user_id = :user_id AND cm.sender_id != :user_id AND cm.read = 0";
        } else {
            $sql = "SELECT COUNT(*) as count 
                    FROM chat_messages cm
                    JOIN chats c ON cm.chat_id = c.id
                    WHERE c.user_id = :user_id AND cm.sender_id != :user_id AND cm.read = 0";
        }
        
        $result = $this->db->fetchOne($sql, [':user_id' => $userId]);
        return $result['count'];
    }
}