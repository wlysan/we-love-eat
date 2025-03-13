<?php
/**
 * Database Migration Runner
 * 
 * This script automatically runs all migration files in the src/migrations directory
 * in numerical order. It creates a migrations table to track which migrations have
 * already been applied.
 */

// Load configuration
require_once __DIR__ . '/src/config/config.php';
require_once __DIR__ . '/src/config/database.php';

// Initialize
echo "=== NutriMenu Database Migration Tool ===\n\n";

// Get database instance
$db = Database::getInstance();
$conn = $db->getConnection();

// Make sure the database directory exists
$dbDir = __DIR__ . '/database';
if (!file_exists($dbDir)) {
    mkdir($dbDir, 0755, true);
    echo "Created database directory.\n";
}

// Path to SQLite database
$dbPath = __DIR__ . '/database/nutrition.db';

// Create migrations table if it doesn't exist
echo "Checking migrations table...\n";
$conn->exec("
    CREATE TABLE IF NOT EXISTS migrations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        migration TEXT NOT NULL,
        batch INTEGER NOT NULL,
        executed_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
");
echo "✓ Migrations table verified.\n\n";

// Get list of already applied migrations
$appliedMigrations = $db->fetchAll("SELECT migration FROM migrations", []);
$appliedMigrationNames = array_column($appliedMigrations, 'migration');

// Get current batch number
$batchResult = $db->fetchOne("SELECT MAX(batch) as max_batch FROM migrations", []);
$currentBatch = ($batchResult && isset($batchResult['max_batch'])) ? $batchResult['max_batch'] + 1 : 1;

// Get migration files
$migrationsDir = __DIR__ . '/src/migrations';
$migrationFiles = glob("$migrationsDir/*.sql");
sort($migrationFiles); // Sort by filename

if (empty($migrationFiles)) {
    echo "No migration files found in $migrationsDir\n";
    exit(1);
}

echo "Found " . count($migrationFiles) . " migration files.\n";
echo "Current migration batch: $currentBatch\n\n";

// Apply migrations
$appliedCount = 0;
$skippedCount = 0;
$errorCount = 0;

foreach ($migrationFiles as $migrationFile) {
    $filename = basename($migrationFile);
    
    echo "Processing $filename... ";
    
    // Skip if already applied
    if (in_array($filename, $appliedMigrationNames)) {
        echo "SKIPPED (already applied)\n";
        $skippedCount++;
        continue;
    }
    
    // Read migration file
    $sql = file_get_contents($migrationFile);
    
    // Split into statements
    $statements = explode(';', $sql);
    
    try {
        // Begin transaction
        $conn->exec('BEGIN TRANSACTION');
        
        // Execute each statement
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (empty($statement)) {
                continue;
            }
            
            $conn->exec($statement);
        }
        
        // Record the migration
        $insertSql = "INSERT INTO migrations (migration, batch) VALUES (:migration, :batch)";
        $db->query($insertSql, [
            ':migration' => $filename,
            ':batch' => $currentBatch
        ]);
        
        // Commit transaction
        $conn->exec('COMMIT');
        
        echo "SUCCESS\n";
        $appliedCount++;
    } catch (Exception $e) {
        // Rollback transaction
        $conn->exec('ROLLBACK');
        
        echo "ERROR\n";
        echo "Error details: " . $e->getMessage() . "\n";
        $errorCount++;
    }
}

echo "\n=== Migration Summary ===\n";
echo "Total migration files: " . count($migrationFiles) . "\n";
echo "Applied: $appliedCount\n";
echo "Skipped: $skippedCount\n";
echo "Errors: $errorCount\n";

// Create default admin user if database is new
if ($appliedCount > 0) {
    echo "\nChecking for default admin user...\n";
    
    // Check if admin exists
    $adminCheck = $db->fetchOne("SELECT COUNT(*) as count FROM users WHERE email = 'admin@nutrimenu.com.br'", []);
    
    if (!$adminCheck || $adminCheck['count'] == 0) {
        echo "Creating default admin user...\n";
        
        require_once __DIR__ . '/src/models/User.php';
        $userModel = new User();
        
        $adminData = $userModel->create(
            'Administrador', 
            'admin@nutrimenu.com.br', 
            'admin123', 
            'admin'
        );
        
        if (isset($adminData['id'])) {
            echo "✓ Default admin user created successfully.\n";
            echo "  Email: admin@nutrimenu.com.br\n";
            echo "  Password: admin123\n";
        } else {
            echo "✗ Failed to create default admin user.\n";
        }
    } else {
        echo "✓ Default admin user already exists.\n";
    }
    
    // Load sample data if needed
    // Uncomment the following line if you want to load sample data
    // include __DIR__ . '/src/seeds/sample_data.php';
}

echo "\nMigration completed!\n";