<?php
$pdo = new PDO('mysql:host=localhost;dbname=smart-store', 'root', '');

echo "=== Distinct delivery_user_id in orders table ===\n";
$stmt = $pdo->query('SELECT DISTINCT delivery_user_id FROM orders WHERE delivery_user_id IS NOT NULL ORDER BY delivery_user_id');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "delivery_user_id: " . $row['delivery_user_id'] . "\n";
}

echo "\n=== Check if user 84 exists ===\n";
$stmt = $pdo->query('SELECT id, name, email, role FROM users WHERE id = 84');
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row) {
    echo "User 84 EXISTS: " . $row['name'] . " | " . $row['email'] . " | " . $row['role'] . "\n";
} else {
    echo "User 84 DOES NOT EXIST\n";
}

echo "\n=== Check shipper 85's orders ===\n";
$stmt = $pdo->query('SELECT id, order_number, delivery_status FROM orders WHERE delivery_user_id = 85 LIMIT 3');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Order " . $row['id'] . " (" . $row['order_number'] . ") - status: " . $row['delivery_status'] . "\n";
}

