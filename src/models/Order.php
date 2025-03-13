<?php
require_once __DIR__ . '/../config/database.php';

class Order
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Create a new order
     */
    public function create($data)
    {
        try {
            // Debug
            file_put_contents('order_create_log.txt', "Iniciando criação de pedido: " . print_r($data, true) . "\n", FILE_APPEND);

            $fields = [
                'user_id',
                'delivery_address',
                'delivery_date',
                'payment_method',
                'total',
                'status'
            ];
            $placeholders = [
                ':user_id',
                ':delivery_address',
                ':delivery_date',
                ':payment_method',
                ':total',
                ':status'
            ];
            $params = [
                ':user_id' => $data['user_id'],
                ':delivery_address' => $data['delivery_address'],
                ':delivery_date' => $data['delivery_date'],
                ':payment_method' => $data['payment_method'],
                ':total' => $data['total'],
                ':status' => $data['status']
            ];

            if (isset($data['notes'])) {
                $fields[] = 'notes';
                $placeholders[] = ':notes';
                $params[':notes'] = $data['notes'];
            }

            $sql = "INSERT INTO meal_orders (" . implode(', ', $fields) . ") 
                    VALUES (" . implode(', ', $placeholders) . ")";

            file_put_contents('order_create_log.txt', "SQL: $sql\n", FILE_APPEND);
            file_put_contents('order_create_log.txt', "Params: " . print_r($params, true) . "\n", FILE_APPEND);

            $orderId = $this->db->insert($sql, $params);
            file_put_contents('order_create_log.txt', "Order ID: " . ($orderId ? $orderId : "false") . "\n", FILE_APPEND);

            return $orderId;
        } catch (Exception $e) {
            file_put_contents('order_create_log.txt', "Erro: " . $e->getMessage() . "\n", FILE_APPEND);
            return false;
        }
    }

    /**
     * Add an item to an order
     */
    public function addOrderItem($orderId, $mealId, $quantity, $price)
    {
        $sql = "INSERT INTO meal_order_items (order_id, meal_id, quantity, price) 
                VALUES (:order_id, :meal_id, :quantity, :price)";

        $params = [
            ':order_id' => $orderId,
            ':meal_id' => $mealId,
            ':quantity' => $quantity,
            ':price' => $price
        ];

        try {
            $itemId = $this->db->insert($sql, $params);
            return $itemId;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get order by ID
     */
    public function getById($id)
    {
        $sql = "SELECT mo.*, u.name as user_name, u.email as user_email
                FROM meal_orders mo
                JOIN users u ON mo.user_id = u.id
                WHERE mo.id = :id";

        return $this->db->fetchOne($sql, [':id' => $id]);
    }

    /**
     * Get orders for a user
     */
    public function getByUserId($userId)
    {
        $sql = "SELECT mo.*, 
                (SELECT COUNT(*) FROM meal_order_items WHERE order_id = mo.id) as item_count
                FROM meal_orders mo
                WHERE mo.user_id = :user_id
                ORDER BY mo.created_at DESC";

        return $this->db->fetchAll($sql, [':user_id' => $userId]);
    }

    /**
     * Get items for an order
     */
    public function getOrderItems($orderId)
    {
        $sql = "SELECT moi.*, m.name as meal_name, m.meal_type,
                r.id as restaurant_id, u.name as restaurant_name
                FROM meal_order_items moi
                JOIN meals m ON moi.meal_id = m.id
                JOIN restaurant_profiles r ON m.restaurant_id = r.id
                JOIN users u ON r.user_id = u.id
                WHERE moi.order_id = :order_id
                ORDER BY m.meal_type";

        return $this->db->fetchAll($sql, [':order_id' => $orderId]);
    }

    /**
     * Update order status
     */
    public function updateStatus($orderId, $status, $notes = '')
    {
        $sql = "UPDATE meal_orders 
                SET status = :status, updated_at = CURRENT_TIMESTAMP 
                WHERE id = :id";

        try {
            $this->db->query($sql, [':id' => $orderId, ':status' => $status]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Add status history entry
     */
    public function addStatusHistory($orderId, $status, $notes = '')
    {
        $sql = "INSERT INTO meal_order_status_history (order_id, status, notes) 
            VALUES (:order_id, :status, :notes)";

        try {
            $this->db->insert($sql, [
                ':order_id' => $orderId,
                ':status' => $status,
                ':notes' => $notes
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get status history for an order
     */
    public function getStatusHistory($orderId)
    {
        $sql = "SELECT * FROM meal_order_status_history 
                WHERE order_id = :order_id 
                ORDER BY created_at DESC";

        return $this->db->fetchAll($sql, [':order_id' => $orderId]);
    }

    /**
     * Get orders for a restaurant
     */
    public function getByRestaurantId($restaurantId, $limit = null)
    {
        $sql = "SELECT DISTINCT mo.*, u.name as user_name, u.email as user_email,
                (SELECT COUNT(*) FROM meal_order_items WHERE order_id = mo.id) as item_count
                FROM meal_orders mo
                JOIN meal_order_items moi ON mo.id = moi.order_id
                JOIN meals m ON moi.meal_id = m.id
                JOIN users u ON mo.user_id = u.id
                WHERE m.restaurant_id = :restaurant_id
                ORDER BY mo.created_at DESC";

        $params = [':restaurant_id' => $restaurantId];

        if ($limit !== null) {
            $sql .= " LIMIT :limit";
            $params[':limit'] = $limit;
        }

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Count pending orders for a restaurant
     */
    public function countPendingByRestaurantId($restaurantId)
    {
        $sql = "SELECT COUNT(DISTINCT mo.id) as count
                FROM meal_orders mo
                JOIN meal_order_items moi ON mo.id = moi.order_id
                JOIN meals m ON moi.meal_id = m.id
                WHERE m.restaurant_id = :restaurant_id AND mo.status = 'pending'";

        $result = $this->db->fetchOne($sql, [':restaurant_id' => $restaurantId]);
        return $result ? $result['count'] : 0;
    }
}
