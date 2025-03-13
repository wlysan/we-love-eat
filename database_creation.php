<?php
/**
 * Database Creation Script
 * 
 * This script initializes the database structure, clears any existing data,
 * and creates an admin user with credentials:
 * - Email: admin@admin.com
 * - Password: admin123
 */

// Load configuration
require_once __DIR__ . '/src/config/config.php';
require_once __DIR__ . '/src/config/database.php';

// File path for the SQLite database
$dbPath = __DIR__ . '/database/nutrition.db';
$dbDir = dirname($dbPath);

// Ensure database directory exists
if (!file_exists($dbDir)) {
    mkdir($dbDir, 0755, true);
    echo "Created database directory.\n";
}

// Remove existing database if it exists
if (file_exists($dbPath)) {
    unlink($dbPath);
    echo "Removed existing database.\n";
}

// Create a new database connection
try {
    $db = new SQLite3($dbPath);
    $db->enableExceptions(true);
    echo "Created new database file.\n";
} catch (Exception $e) {
    die("Failed to create database: " . $e->getMessage() . "\n");
}

// Load SQL schema from migration file
$sqlFile = __DIR__ . '/src/migrations/001_create_database.sql';
if (!file_exists($sqlFile)) {
    die("Migration file not found: $sqlFile\n");
}

$sql = file_get_contents($sqlFile);
$statements = explode(';', $sql);

// Execute each SQL statement
echo "Creating database tables...\n";
foreach ($statements as $statement) {
    $statement = trim($statement);
    if (!empty($statement)) {
        try {
            $db->exec($statement);
        } catch (Exception $e) {
            echo "Error executing SQL: " . $e->getMessage() . "\n";
            echo "Statement: " . $statement . "\n";
            // Continue despite errors
        }
    }
}

// Create admin user
$adminName = "Administrator";
$adminEmail = "admin@admin.com";
$adminPassword = "admin123";
$adminRole = "admin";

// Hash the password
$hashedPassword = password_hash($adminPassword, PASSWORD_BCRYPT, ['cost' => HASH_COST]);

// Insert admin user
$sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
$stmt = $db->prepare($sql);
$stmt->bindValue(':name', $adminName, SQLITE3_TEXT);
$stmt->bindValue(':email', $adminEmail, SQLITE3_TEXT);
$stmt->bindValue(':password', $hashedPassword, SQLITE3_TEXT);
$stmt->bindValue(':role', $adminRole, SQLITE3_TEXT);

try {
    $result = $stmt->execute();
    if ($result) {
        $userId = $db->lastInsertRowID();
        echo "Created admin user with ID: $userId\n";
        echo "Email: $adminEmail\n";
        echo "Password: $adminPassword\n";
    } else {
        echo "Failed to create admin user.\n";
    }
} catch (Exception $e) {
    echo "Error creating admin user: " . $e->getMessage() . "\n";
}

// Create some initial data for demonstration purposes

// Create a nutritionist user
$nutritionistName = "Nutricionista Demo";
$nutritionistEmail = "nutricionista@exemplo.com";
$nutritionistPassword = "nutri123";
$nutritionistRole = "nutritionist";

$hashedPassword = password_hash($nutritionistPassword, PASSWORD_BCRYPT, ['cost' => HASH_COST]);

$sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
$stmt = $db->prepare($sql);
$stmt->bindValue(':name', $nutritionistName, SQLITE3_TEXT);
$stmt->bindValue(':email', $nutritionistEmail, SQLITE3_TEXT);
$stmt->bindValue(':password', $hashedPassword, SQLITE3_TEXT);
$stmt->bindValue(':role', $nutritionistRole, SQLITE3_TEXT);

try {
    $result = $stmt->execute();
    if ($result) {
        $nutritionistUserId = $db->lastInsertRowID();
        echo "Created nutritionist user with ID: $nutritionistUserId\n";
        
        // Create nutritionist profile
        $sql = "INSERT INTO nutritionist_profiles (user_id, professional_id, specialties, bio) 
                VALUES (:user_id, :professional_id, :specialties, :bio)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $nutritionistUserId, SQLITE3_INTEGER);
        $stmt->bindValue(':professional_id', 'CRN-123456', SQLITE3_TEXT);
        $stmt->bindValue(':specialties', 'Emagrecimento, Nutrição Esportiva', SQLITE3_TEXT);
        $stmt->bindValue(':bio', 'Nutricionista especializado em emagrecimento e nutrição esportiva.', SQLITE3_TEXT);
        
        $result = $stmt->execute();
        if ($result) {
            $nutritionistProfileId = $db->lastInsertRowID();
            echo "Created nutritionist profile with ID: $nutritionistProfileId\n";
        }
    }
} catch (Exception $e) {
    echo "Error creating nutritionist: " . $e->getMessage() . "\n";
}

// Create a restaurant user
$restaurantName = "Restaurante Saudável";
$restaurantEmail = "restaurante@exemplo.com";
$restaurantPassword = "rest123";
$restaurantRole = "restaurant";

$hashedPassword = password_hash($restaurantPassword, PASSWORD_BCRYPT, ['cost' => HASH_COST]);

$sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
$stmt = $db->prepare($sql);
$stmt->bindValue(':name', $restaurantName, SQLITE3_TEXT);
$stmt->bindValue(':email', $restaurantEmail, SQLITE3_TEXT);
$stmt->bindValue(':password', $hashedPassword, SQLITE3_TEXT);
$stmt->bindValue(':role', $restaurantRole, SQLITE3_TEXT);

try {
    $result = $stmt->execute();
    if ($result) {
        $restaurantUserId = $db->lastInsertRowID();
        echo "Created restaurant user with ID: $restaurantUserId\n";
        
        // Create restaurant profile
        $sql = "INSERT INTO restaurant_profiles (user_id, cnpj, address, phone, description) 
                VALUES (:user_id, :cnpj, :address, :phone, :description)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $restaurantUserId, SQLITE3_INTEGER);
        $stmt->bindValue(':cnpj', '12.345.678/0001-90', SQLITE3_TEXT);
        $stmt->bindValue(':address', 'Av. Exemplo, 123, São Paulo, SP', SQLITE3_TEXT);
        $stmt->bindValue(':phone', '(11) 98765-4321', SQLITE3_TEXT);
        $stmt->bindValue(':description', 'Restaurante especializado em refeições saudáveis.', SQLITE3_TEXT);
        
        $result = $stmt->execute();
        if ($result) {
            $restaurantProfileId = $db->lastInsertRowID();
            echo "Created restaurant profile with ID: $restaurantProfileId\n";
        }
    }
} catch (Exception $e) {
    echo "Error creating restaurant: " . $e->getMessage() . "\n";
}

// Create a regular user
$userName = "Usuário Demo";
$userEmail = "usuario@exemplo.com";
$userPassword = "user123";
$userRole = "user";

$hashedPassword = password_hash($userPassword, PASSWORD_BCRYPT, ['cost' => HASH_COST]);

$sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
$stmt = $db->prepare($sql);
$stmt->bindValue(':name', $userName, SQLITE3_TEXT);
$stmt->bindValue(':email', $userEmail, SQLITE3_TEXT);
$stmt->bindValue(':password', $hashedPassword, SQLITE3_TEXT);
$stmt->bindValue(':role', $userRole, SQLITE3_TEXT);

try {
    $result = $stmt->execute();
    if ($result) {
        $regularUserId = $db->lastInsertRowID();
        echo "Created regular user with ID: $regularUserId\n";
        
        // Create user profile
        $sql = "INSERT INTO user_profiles (user_id, birth_date, gender, height, current_weight, goal_weight, activity_level) 
                VALUES (:user_id, :birth_date, :gender, :height, :current_weight, :goal_weight, :activity_level)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $regularUserId, SQLITE3_INTEGER);
        $stmt->bindValue(':birth_date', '1990-01-01', SQLITE3_TEXT);
        $stmt->bindValue(':gender', 'male', SQLITE3_TEXT);
        $stmt->bindValue(':height', 175, SQLITE3_FLOAT);
        $stmt->bindValue(':current_weight', 80, SQLITE3_FLOAT);
        $stmt->bindValue(':goal_weight', 75, SQLITE3_FLOAT);
        $stmt->bindValue(':activity_level', 'moderate', SQLITE3_TEXT);
        
        $result = $stmt->execute();
        if ($result) {
            echo "Created user profile\n";
        }
    }
} catch (Exception $e) {
    echo "Error creating user: " . $e->getMessage() . "\n";
}

// Close the database connection
$db->close();

echo "\nDatabase initialization completed successfully!\n";
echo "You can now log in with the following credentials:\n";
echo "Email: admin@admin.com\n";
echo "Password: admin123\n";
?>