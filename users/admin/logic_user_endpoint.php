<?php

// code logic written by:
// Ricardo Gutierrez, 40074308

// front end written by: 
// Paulina Valero, 40289881

//code debugged and tested by: 
// Alejandro Araya, 40170778
// Omar Ghandour, 40109052

session_start(); 

// Include database connection
require('../../database.php'); 

// Function to generate a random password
function generateRandomPassword($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomPassword = '';
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomPassword;
}

$action = $_POST['action'] ?? '';

// Handle actions
try {
    $response = '';
    if ($action === 'add') {
        // Generate a new password for the new user
        $newPassword = generateRandomPassword();
        // Hash the generated password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Set NewUser to true
        $_SESSION['NewUser'] = true;

        // Prepare SQL and bind parameters
        $stmt = $pdo->prepare("INSERT INTO `User` (Name, EmailAddress, Password, NewUser) VALUES (?, ?, ?, TRUE)");
        $stmt->execute([$_POST['name'], $_POST['email'], $hashedPassword]);

        $response = "User added successfully. Password is: " . $newPassword;
    } elseif ($action === 'update') {
        // Prepare SQL and bind parameters
        $stmt = $pdo->prepare("UPDATE `User` SET Name = ?, EmailAddress = ? WHERE UserID = ?");
        $stmt->execute([$_POST['new_name'], $_POST['new_email'], $_POST['user_id']]);

        $response = "User updated successfully.";
    } elseif ($action === 'delete') {
        // Prepare SQL and bind parameters
        $stmt = $pdo->prepare("DELETE FROM `User` WHERE UserID = ?");
        $stmt->execute([$_POST['user_id']]);

        $response = "User deleted successfully.";
    } else {
        throw new Exception("Invalid action.");
    }
} catch (Exception $e) {
    $response = "Error: " . $e->getMessage();
    http_response_code(500); // Set the response code to 500 if there's an error
}

// Return the response
echo $response;
?>
