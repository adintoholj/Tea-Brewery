<?php
session_start();

$host = 'db';
$dbname = 'BuyTeaCraft_db';
$db_user = 'root';
$db_pass = 'password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Convert the username to lowercase so "Admin" and "admin" can't both register
    $username = strtolower(trim($_POST['username']));
    $password = $_POST['password'];

    // Check if user exists using fetch()
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
    $stmt->execute(['username' => $username]);
    
    if ($stmt->fetch()) {
        $message = "<div class='alert alert-warning text-center fw-bold'>That username is already taken!</div>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $insert = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, 'user')");
            $insert->execute(['username' => $username, 'password' => $hashed_password]);
            
            $message = "<div class='alert alert-success text-center fw-bold'>Account created! <a href='login.php' class='alert-link'>Click here to Log In</a></div>";
        } catch (PDOException $e) {
            // Error 23000 is MySQL's code for a "Duplicate Entry" integrity violation
            if ($e->getCode() == 23000) {
                $message = "<div class='alert alert-warning text-center fw-bold'>That username is already taken!</div>";
            } else {
                $message = "<div class='alert alert-danger text-center'>Database error occurred.</div>";
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuyTeaCraft | Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f9f4; }
        .bg-dark-green { background-color: #1b4332; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow-sm border-success" style="width: 25rem;">
        <div class="card-header bg-dark-green text-white text-center py-3">
            <h4 class="mb-0">🍃 Create Account</h4>
        </div>
        <div class="card-body p-4">
            
            <?php if (!empty($message)) echo $message; ?>

            <form action="register.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label text-success fw-bold">Choose a Username</label>
                    <input type="text" class="form-control border-success" id="username" name="username" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label text-success fw-bold">Choose a Password</label>
                    <input type="password" class="form-control border-success" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-success w-100 fw-bold">Register</button>
            </form>
            
            <div class="text-center mt-3">
                <small class="text-muted">Already have an account? <a href="login.php" class="text-success text-decoration-none">Log in here</a></small>
            </div>
            <div class="text-center mt-2">
                <a href="index.php" class="text-secondary text-decoration-none small">← Back to Store</a>
            </div>
        </div>
    </div>

</body>
</html>