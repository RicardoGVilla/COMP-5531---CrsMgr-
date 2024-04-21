<?php

// code written by:
// Ricardo Gutierrez, 40074308

//code debugged and tested by: 
// Alejandro Araya, 40170778
// Omar Ghandour, 40109052

session_start(); 
require_once '../../database.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Check if user ID is provided
    if (isset($_GET['id'])) {
        $userID = $_GET['id'];

        try {
            // Delete user from the database
            $query = "DELETE FROM User WHERE UserID = :userID";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':userID' => $userID]);

            // Check if user was deleted
            if ($stmt->rowCount() > 0) {
                $_SESSION['message'] = "User deleted successfully";
            } else {
                throw new Exception("User ID {$userID} not found.");
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error deleting user: " . $e->getMessage();
        } catch (Exception $e) {
            $_SESSION['error'] = "Error deleting user: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "User ID not provided.";
    }
}

// Redirect back to manage_user.php
header('Location: manage_user.php');
exit;
?>
