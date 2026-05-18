<?php
session_start();

// THE BOUNCER: In case of manual URL typing, If they don't have an ID badge, kick them to the login screen
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$host = 'db';
$dbname = 'BuyTeaCraft_db';
$user = 'root';
$pass = 'password';

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);

$cart_items = [];
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
    $stmt = $pdo->query("SELECT * FROM teas WHERE id IN ($ids)");
    $teas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($teas as $tea) {
        $quantity = $_SESSION['cart'][$tea['id']];
        $subtotal = $tea['price'] * $quantity;
        $total_price += $subtotal;
        
        $tea['quantity'] = $quantity;
        $tea['subtotal'] = $subtotal;
        $cart_items[] = $tea;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BuyTeaCraft | Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background-color: #f4f9f4; }</style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-success fw-bold mb-4">🍃 Your Shopping Cart</h2>

    <?php if (empty($cart_items)): ?>
        <div class="alert alert-warning">Your cart is empty. <a href="index.php">Go back to the store.</a></div>
    <?php else: ?>
        <table class="table table-bordered bg-white shadow-sm">
            <thead class="table-success">
                <tr>
                    <th>Tea</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th></th> </tr>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): ?>
                    <tr>
                        <td class="fw-bold"><?php echo htmlspecialchars($item['name']); ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td class="fw-bold text-success">$<?php echo number_format($item['subtotal'], 2); ?></td>
                        <td class="text-end">
                            <form action="remove_from_cart.php" method="POST" class="m-0">
                                <input type="hidden" name="tea_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger fw-bold">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="index.php" class="btn btn-outline-success">← Keep Shopping</a>
            <div class="text-end">
                <h4 class="fw-bold text-success">Total: $<?php echo number_format($total_price, 2); ?></h4>
                <form action="checkout.php" method="POST">
                    <button type="submit" class="btn btn-success btn-lg mt-2 fw-bold">Proceed to Checkout</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>