<?php
// credentials
$host = 'db'; // The name of your database container
$dbname = 'BuyTeaCraft_db';
$user = 'root';
$pass = 'password';

// PDO connect
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// ask 4 all teas
$stmt = $pdo->query("SELECT * FROM teas");
$teas = $stmt->fetchAll(PDO::FETCH_ASSOC); // $teas array
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BrewLab | Custom Teas</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fff9; } 
        .navbar { background-color: #2d6a4f !important; } 
    </style>
</head>
<body>

<nav class="navbar navbar-dark shadow-sm mb-5">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">🍃 BuyTeaCraft</a>
        
        <div>
            <a href="login.php" class="btn btn-outline-light btn-sm fw-bold">Login</a>
        </div>
    </div>
</nav>

<div class="row row-cols-1 row-cols-md-2 g-3">
    <!-- foreach -->
    <?php foreach ($teas as $tea): ?>
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <span class="badge bg-success mb-2"><?php echo htmlspecialchars($tea['category']); ?></span>
                    <h5 class="card-title fw-bold"><?php echo htmlspecialchars($tea['name']); ?></h5>
                    <p class="card-text text-muted"><?php echo htmlspecialchars($tea['description']); ?></p>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-success">$<?php echo number_format($tea['price'], 2); ?></span>
                        <button class="btn btn-sm btn-outline-success">Buy Now</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

</div> 

</body>
</html>