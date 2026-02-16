<?php
// Test database connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=risingeast", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connection successful!<br>";
    
    // Test if tables exist
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables found: " . implode(", ", $tables);
    
} catch(PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>
