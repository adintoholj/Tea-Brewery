<?php
session_start();

// THE BOUNCER: In case of manual URL typing, If they don't have an ID badge, kick them to the login screen
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Empty the cart memory
unset($_SESSION['cart']);

// Send them back to the homepage with a success flag in the URL
header('Location: index.php?checkout=success');
exit();
?>