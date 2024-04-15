<?php
session_start();
include('../../database.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['userId'];
    $role = $_POST['role'];
    $action = $_POST['action'];

    try {
        if ($action == 'add') {
            // Add a new role to the user
            $sql = "INSERT INTO UserRole (UserID, RoleID) VALUES (:userId, :role)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':role', $role);
            $stmt->execute();
            echo "Role added successfully!";
        } elseif ($action == 'remove') {
            // Remove the role from the user
            $sql = "DELETE FROM UserRole WHERE UserID = :userId AND RoleID = :role";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':role', $role);
            $stmt->execute();
            echo "Role removed successfully!";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Not a POST request
    echo "Invalid request.";
}
?>
