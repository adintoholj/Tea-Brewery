<?php
session_start();
header('Content-Type: application/json'); // Tell the browser we are sending JSON back

// Read the JSON payload sent by JavaScript Fetch
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

if ($data) {
    $host = 'db';
    $dbname = 'BuyTeaCraft_db';
    $db_user = 'root';
    $db_pass = 'password';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert the new custom tea into the store
        $stmt = $pdo->prepare("INSERT INTO teas (name, category, description, price) VALUES (:name, :category, :description, :price)");
        
        $stmt->execute([
            'name' => $data['name'],
            'category' => $data['category'],
            'description' => $data['description'],
            'price' => $data['price']
        ]);

        // Send a success message back to the JavaScript
        echo json_encode(['success' => true]);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No data received']);
}
?>