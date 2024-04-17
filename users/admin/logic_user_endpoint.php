<?php
session_start();
require('../../database.php');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'add':
            $stmt = $pdo->prepare("INSERT INTO `User` (Name, EmailAddress) VALUES (?, ?)");
            $stmt->execute([$_POST['name'], $_POST['email']]);
            $_SESSION['message'] = "User added successfully.";
            break;
        case 'update':
            $stmt = $pdo->prepare("UPDATE `User` SET Name = ?, EmailAddress = ? WHERE UserID = ?");
            $stmt->execute([$_POST['new_name'], $_POST['new_email'], $_POST['user_id']]);
            $_SESSION['message'] = "User updated successfully.";
            break;
        case 'delete':
            $stmt = $pdo->prepare("DELETE FROM `User` WHERE UserID = ?");
            $stmt->execute([$_GET['user_id']]);
            $_SESSION['message'] = "User deleted successfully.";
            break;
        default:
            $_SESSION['error'] = "Invalid action.";
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
}

header('Location: create_user.php');
exit;
?>
