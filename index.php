<?php
session_start();
$cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
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
            <a href="cart.php" class="btn btn-warning btn-sm fw-bold me-3">🛒 Cart (<?php echo $cart_count; ?>)</a>

            <?php if (isset($_SESSION['username'])): ?>
                <span class="text-light me-3">Hello, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</span>
                <a href="logout.php" class="btn btn-outline-danger btn-sm fw-bold">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-light btn-sm fw-bold">Login</a>
                <a href="register.php" class="btn btn-light btn-sm fw-bold">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?php if (isset($_GET['checkout']) && $_GET['checkout'] === 'success'): ?>
    <div class="container mt-3">
        <div class="alert alert-success text-center fw-bold">🎉 Checkout successful! Your tea is brewing.</div>
    </div>
<?php endif; ?>

<!--linked to the modal box that is hidden -->
<div class="container mt-4 mb-4 text-center">
    <button type="button" class="btn btn-success btn-lg fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#customTeaModal">
        Open Custom Tea Builder
    </button>
</div>

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
    
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="delete_tea.php?id=<?php echo $tea['id']; ?>" class="btn btn-sm btn-outline-danger fw-bold">Delete</a>
                        <?php else: ?>
                            <!-- buy now -->
                            <form action="add_to_cart.php" method="POST" class="m-0">
                                <input type="hidden" name="tea_id" value="<?php echo $tea['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-success fw-bold">Buy Now</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

</div> 

<!--Bootstrap modals-->

<div class="modal fade" id="customTeaModal" tabindex="-1" aria-labelledby="customTeaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-success">
                
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="customTeaModalLabel">Build Your Custom Blend</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <form id="customTeaForm">
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-success">1. Choose Your Base ($3.00)</label>
                            <select class="form-select border-success" id="teaBase" name="teaBase" required>
                                <option value="Green Tea">Green Tea</option>
                                <option value="Black Tea">Black Tea</option>
                                <option value="Oolong">Oolong</option>
                                <option value="White Tea">White Tea</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">2. Add Toppings ($0.50 each)</label>
                            
                            <div class="form-check mb-2">
                                <input class="form-check-input border-success topping-checkbox" type="checkbox" value="Honey" id="topHoney">
                                <label class="form-check-label" for="topHoney">Organic Honey</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input border-success topping-checkbox" type="checkbox" value="Lemon" id="topLemon">
                                <label class="form-check-label" for="topLemon">Fresh Lemon Slice</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input border-success topping-checkbox" type="checkbox" value="Ginger" id="topGinger">
                                <label class="form-check-label" for="topGinger">Crushed Ginger</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input border-success topping-checkbox" type="checkbox" value="Mint" id="topMint">
                                <label class="form-check-label" for="topMint">Mint Leaves</label>
                            </div>
                        </div>

                    </form>
                </div>
                
                <div class="modal-footer d-flex justify-content-between bg-light">
                    <span class="fs-5 fw-bold text-success">Total: $<span id="modalTotalPrice">3.00</span></span>
                    <button type="button" class="btn btn-success fw-bold" id="saveCustomTeaBtn">Add to Cart</button>
                </div>
                
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.topping-checkbox');
            const priceDisplay = document.getElementById('modalTotalPrice');
            const basePrice = 3.00;

            // Update total price when a topping is clicked
            checkboxes.forEach(box => {
                box.addEventListener('change', () => {
                    let total = basePrice;
                    checkboxes.forEach(b => {
                        if (b.checked) total += 0.50; // Add 50 cents per topping
                    });
                    priceDisplay.textContent = total.toFixed(2);
                });
            });

            // Send data to the backend without reloading (ajax fetch)
            document.getElementById('saveCustomTeaBtn').addEventListener('click', function() {
                const baseTea = document.getElementById('teaBase').value;
                
                // Grab all checked toppings and put them in an array
                const toppings = Array.from(checkboxes)
                                     .filter(b => b.checked)
                                     .map(b => b.value);
                
                const finalPrice = document.getElementById('modalTotalPrice').textContent;

                // Create the package of data to send
                const payload = {
                    name: "Custom " + baseTea + " Blend",
                    category: "Custom",
                    description: "Base: " + baseTea + " with " + (toppings.length > 0 ? toppings.join(', ') : "no toppings") + ".",
                    price: finalPrice
                };

                // Fetch API Call 
                fetch('save_custom_tea.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        alert("✨ Custom blend added to the store!");
                        location.reload(); // Refresh for seeing new tea card
                    } else {
                        alert("Error saving custom tea.");
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
</body>
</html>

</body>
</html>