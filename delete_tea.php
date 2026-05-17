<?php
session_start();

// Ensure the user is logged in AND is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // If they aren't an admin, kick them back to the store immediately
    header("Location: index.php");
    exit();
}

// Check if an ID was passed in the URL (e.g., ?id=3)
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    
    $host = 'db';
    $dbname = 'BuyTeaCraft_db';
    $db_user = 'root';
    $db_pass = 'password';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Execute the Delete Command securely
        $stmt = $pdo->prepare("DELETE FROM teas WHERE id = :id");
        $stmt->execute(['id' => $_GET['id']]);

    } catch (PDOException $e) {
        // If something goes wrong, stop the script 
        die("Database error: " . $e->getMessage());
    }
}

// Send the Admin back to the homepage after deletion
header("Location: index.php");
exit();
?>