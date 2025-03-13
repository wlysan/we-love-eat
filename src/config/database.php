<?php

/**
 * Database configuration and connection
 */
class Database
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $dbPath = __DIR__ . '/../../database/nutrition.db';
        $dbDir = dirname($dbPath);

        // Create database directory if it doesn't exist
        if (!file_exists($dbDir)) {
            mkdir($dbDir, 0755, true);
        }

        try {
            $this->conn = new SQLite3($dbPath);
            $this->conn->enableExceptions(true);
        } catch (Exception $e) {
            die("Database connection error: " . $e->getMessage());
        }
    }

    /**
     * Get database connection instance (singleton pattern)
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get database connection
     */
    public function getConnection()
    {
        return $this->conn;
    }

    /**
     * Initialize database schema
     */
    public function initSchema()
    {
        try {
            $sqlFile = __DIR__ . '/../migrations/001_create_database.sql';
            if (!file_exists($sqlFile)) {
                die("Migration file not found: $sqlFile");
            }

            $sql = file_get_contents($sqlFile);
            $statements = explode(';', $sql);

            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    $this->conn->exec($statement);
                }
            }

            return true;
        } catch (Exception $e) {
            die("Database initialization error: " . $e->getMessage());
        }
    }

    /**
     * Execute a query and return the result
     */
    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($sql);

            // Bind parameters
            if (!empty($params)) {
                foreach ($params as $param => $value) {
                    if (is_int($value)) {
                        $stmt->bindValue($param, $value, SQLITE3_INTEGER);
                    } else if (is_float($value)) {
                        $stmt->bindValue($param, $value, SQLITE3_FLOAT);
                    } else if (is_null($value)) {
                        $stmt->bindValue($param, null, SQLITE3_NULL);
                    } else {
                        $stmt->bindValue($param, $value, SQLITE3_TEXT);
                    }
                }
            }

            $result = $stmt->execute();
            return $result;
        } catch (Exception $e) {
            die("Query execution error: " . $e->getMessage());
        }
    }

    /**
     * Execute a query and return all rows as an array
     */
    public function fetchAll($sql, $params = [])
    {
        $result = $this->query($sql, $params);
        $rows = [];

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Execute a query and return a single row
     */
    public function fetchOne($sql, $params = [])
    {
        $result = $this->query($sql, $params);
        return $result->fetchArray(SQLITE3_ASSOC);
    }

    /**
     * Execute a query and return the last inserted ID
     */
    /**
     * Execute a query and return the last inserted ID
     */
    public function insert($sql, $params = [])
    {
        try {
            // Debug
            file_put_contents('db_insert_log.txt', "SQL: $sql\n", FILE_APPEND);
            file_put_contents('db_insert_log.txt', "Params: " . print_r($params, true) . "\n", FILE_APPEND);

            $this->query($sql, $params);
            $lastId = $this->conn->lastInsertRowID();

            file_put_contents('db_insert_log.txt', "Last Insert ID: $lastId\n", FILE_APPEND);

            return $lastId;
        } catch (Exception $e) {
            file_put_contents('db_insert_log.txt', "Erro: " . $e->getMessage() . "\n", FILE_APPEND);
            return false;
        }
    }
}
