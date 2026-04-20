<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'smart-store';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== Checking ORDER #30 in detail ===\n";
    $stmt = $pdo->query("SELECT * FROM orders WHERE id = 30");
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($order) {
        echo "Order #30:\n";
        foreach ($order as $key => $value) {
            echo "  $key: " . ($value ?? 'NULL') . "\n";
        }
    }
    
    echo "\n=== Testing Route for Shipper #84 ===\n";
    $stmt = $pdo->query("SELECT id, delivery_user_id, delivery_status FROM orders WHERE delivery_user_id = 84 AND delivery_status = 'assigned' LIMIT 1");
    $testOrder = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($testOrder) {
        echo "Test Order: " . json_encode($testOrder) . "\n";
        echo "Expected route: /delivery/orders/{$testOrder['id']}/pickup\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
