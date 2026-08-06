<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    $stmt = $pdo->query("SHOW DATABASES LIKE 'donatebazaar_test'");
    echo "Database exists: " . ($stmt->rowCount() > 0 ? "YES" : "NO") . "\n";
    
    $stmt = $pdo->query("SHOW TABLES FROM donatebazaar_test");
    echo "Tables in donatebazaar_test: " . $stmt->rowCount() . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
