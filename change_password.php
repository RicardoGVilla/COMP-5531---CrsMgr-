<!-- <?php
include_once 'database.php';
session_start();

// Redirect to login page if no user ID is stored in the session
if (!isset($_SESSION['change_password_user_id'])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION['change_password_user_id']; // Retrieve the user's ID from the session

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST["new_password"];

    // Update the user's password and NewUser status in the database
    try {
        $stmt = $pdo->prepare("UPDATE `User` SET Password = :newPassword, NewUser = FALSE WHERE UserID = :userID");
        $stmt->execute([
            ':newPassword' => password_hash($newPassword, PASSWORD_DEFAULT),
            ':userID' => $userID
        ]);

        // Optionally, clear the change_password_user_id from the session
        unset($_SESSION['change_password_user_id']);

        // Redirect or notify the user of success
        $_SESSION['message'] = "Your password has been updated successfully.";
        header("Location: login.php");
        exit;
    } catch (PDOException $e) {
        // Handle errors (e.g., log them and show an error message)
        $_SESSION['error'] = "Error updating password: " . $e->getMessage();
    }
}
?> -->

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
    </div>
</body>
</html>
