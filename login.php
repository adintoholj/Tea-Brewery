<?php
// Session start
session_start();

// Database connect
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

$error_message = "";

// Form submission check
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Look for the user in the database securely 
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify the user exists AND the password matches
    if ($user && password_verify($password, $user['password'])) {
        
        // Assign id badge if success
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // 'admin' or 'user'

        // Back to store redirection
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BuyTeaCraft | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f9f4; }
        .bg-dark-green { background-color: #1b4332; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow-sm border-success" style="width: 25rem;">
        <div class="card-header bg-dark-green text-white text-center py-3">
            <h4 class="mb-0">🍃 Welcome Back</h4>
        </div>
        <div class="card-body p-4">
            <!--Wrong password message-->
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger py-2 text-center" role="alert">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label text-success fw-bold">Username</label>
                    <input type="text" class="form-control border-success" id="username" name="username" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label text-success fw-bold">Password</label>
                    <input type="password" class="form-control border-success" id="password" name="password" required>
                </div>
                <button type="submit" name="login_submit" class="btn btn-success w-100 fw-bold">Log In</button>
            </form>
            
            <div class="text-center mt-3">
                <small class="text-muted">Don't have an account? <a href="register.php" class="text-success text-decoration-none">Register here</a></small>
            </div>
            <div class="text-center mt-2">
                <a href="index.php" class="text-secondary text-decoration-none small">← Back to Store</a>
            </div>
        </div>
    </div>

</body>
</html>