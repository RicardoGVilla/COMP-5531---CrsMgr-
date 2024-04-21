<?php
// code written by:
// Ricardo Gutierrez, 40074308

session_start();

// Unset specific session variables
unset($_SESSION['user']);

// Redirect the user to the login page or any other page as needed
header("Location: login.php");
exit;
?>
