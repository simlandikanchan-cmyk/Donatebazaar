<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    $stmt = $pdo->query("SHOW DATABASES LIKE 'donatebazaar_test'");
    echo $stmt->rowCount() > 0 ? "EXISTS\n" : "MISSING\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
