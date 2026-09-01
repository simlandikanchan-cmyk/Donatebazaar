<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=donatebazaar_final', 'root', '');
    echo "Connected successfully\n";
    
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables found: " . count($tables) . "\n";
    
    if (in_array('campaigns', $tables)) {
        echo "campaigns table EXISTS\n";
    } else {
        echo "campaigns table MISSING\n";
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}