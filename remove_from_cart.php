<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tea_id'])) {
    $tea_id = (int)$_POST['tea_id'];

    // If the tea exists in the cart, remove it completely
    if (isset($_SESSION['cart'][$tea_id])) {
        unset($_SESSION['cart'][$tea_id]);
    }
}

// Send them right back to the cart
header('Location: cart.php');
exit();
?>