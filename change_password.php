<?php
// Start the session
session_start();

// Check if the user is logged in, otherwise redirect to the login page.
if (!isset($_SESSION['user']['UserID'])) {
    header('Location: ../../login.php');
    exit;
}

// Include database connection
require_once 'database.php'; 

// Handle the password change on form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_password'])) {
    $userId = $_SESSION['user']['UserID'];
    $newPassword = $_POST['new_password']; 

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Prepare SQL query to update the user's password and NewUser status
    $query = "UPDATE `User` SET Password = :newPassword, NewUser = FALSE WHERE UserID = :userId";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['newPassword' => $hashedPassword, 'userId' => $userId]);

    // After updating, redirect or display a success message
    header('Location: home.php'); // Redirect to home page after successful update
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="../../css/index.css">
</head>
<body>
    <div class="container">
        <h2>Change Password</h2>
        <form action="change_password.php" method="post">
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <button type="submit">Update Password</button>
        </form>
        <footer class="footer">
            <button onclick="location.href='home.php'">Return to Home Page</button>
        </footer>
    </div>
</body>
</html>
