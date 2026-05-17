<?php
session_start();
session_destroy(); // Shred the ID badge
header("Location: index.php"); // Send back to store
exit();
?>