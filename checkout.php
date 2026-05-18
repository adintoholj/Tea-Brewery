<?php
session_start();

// Empty the cart memory
unset($_SESSION['cart']);

// Send them back to the homepage with a success flag in the URL
header('Location: index.php?checkout=success');
exit();
?>