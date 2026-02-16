<?php
// Simple database connection test
echo "<h2>Database Connection Test</h2>";

// Test 1: Check if MySQL extension is loaded
if (!extension_loaded('pdo_mysql')) {
    echo "<p style='color: red;'>❌ PDO MySQL extension is not loaded!</p>";
    echo "<p>Please enable pdo_mysql extension in php.ini</p>";
} else {
    echo "<p style='color: green;'>✅ PDO MySQL extension is loaded</p>";
}

// Test 2: Try to connect without database first
try {
    $conn = new PDO("mysql:host=localhost", "root", "");
    echo "<p style='color: green;'>✅ Connected to MySQL server successfully</p>";
    
    // Test 3: Check if database exists
    $result = $conn->query("SHOW DATABASES LIKE 'risingeast'");
    if ($result->rowCount() > 0) {
        echo "<p style='color: green;'>✅ Database 'risingeast' exists</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Database 'risingeast' does not exist</p>";
        echo "<p>Creating database...</p>";
        $conn->exec("CREATE DATABASE risingeast CHARACTER SET utf8 COLLATE utf8_unicode_ci");
        echo "<p style='color: green;'>✅ Database 'risingeast' created</p>";
    }
    
    // Test 4: Try to connect to the specific database
    $conn = new PDO("mysql:host=localhost;dbname=risingeast", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✅ Connected to 'risingeast' database successfully</p>";
    
    // Test 5: Check if tables exist
    $tables = $conn->query("SHOW TABLES");
    if ($tables->rowCount() > 0) {
        echo "<p style='color: green;'>✅ Found " . $tables->rowCount() . " tables in database</p>";
        echo "<ul>";
        while ($row = $tables->fetch(PDO::FETCH_NUM)) {
            echo "<li>" . $row[0] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: orange;'>⚠️ No tables found in database</p>";
    }
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>❌ Connection failed: " . $e->getMessage() . "</p>";
    echo "<p><strong>Possible solutions:</strong></p>";
    echo "<ul>";
    echo "<li>1. Make sure XAMPP MySQL/MariaDB is running</li>";
    echo "<li>2. Check if MySQL has a password set</li>";
    echo "<li>3. Verify database name exists</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<p><a href='index.html'>← Back to Website</a></p>";
?>
