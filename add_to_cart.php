<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tea_id'])) {
    $tea_id = (int)$_POST['tea_id'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$tea_id])) {
        $_SESSION['cart'][$tea_id]++;
    } else {
        $_SESSION['cart'][$tea_id] = 1;
    }
}

header('Location: index.php');
exit();
?>