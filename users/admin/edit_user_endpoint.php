<?php
include('../../database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    switch ($_POST['action']) {
        case 'add':
            $name = $_POST['name'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            $password = password_hash("defaultPassword", PASSWORD_DEFAULT); // Default password hash

            // Insert user into the database
            $stmt = $pdo->prepare("INSERT INTO `User` (Name, EmailAddress, Password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $password]);

            $userId = $pdo->lastInsertId();

            // Assign role to the new user
            $stmt = $pdo->prepare("INSERT INTO UserRole (UserID, RoleID) VALUES (?, ?)");
            $stmt->execute([$userId, $role]);

            header('Location: manage_user.php?success=Added');
            break;

            case 'update_role':
                $userId = $_POST['user_id'];
                $newRoleId = $_POST['new_role_id'];
            
                // Start a transaction
                $pdo->beginTransaction();
            
                try {
                    // Check if the user already has this new role
                    $roleCheckStmt = $pdo->prepare("SELECT 1 FROM UserRole WHERE UserID = ? AND RoleID = ?");
                    $roleCheckStmt->execute([$userId, $newRoleId]);
            
                    if ($roleCheckStmt->rowCount() > 0) {
                        // The user already has the role
                        echo "<script>alert('User already has this role. No changes were made.');</script>";
                    } else {
                        // The user doesn't have the role, add it
                        $insertRoleStmt = $pdo->prepare("INSERT INTO UserRole (UserID, RoleID) VALUES (?, ?)");
                        $insertRoleStmt->execute([$userId, $newRoleId]);
                        echo "<script>alert('Role added successfully.');</script>";
                    }
            
                    // Commit the transaction
                    $pdo->commit();
                } catch (Exception $e) {
                    // An error occurred, rollback the transaction
                    $pdo->rollBack();
                    echo "<script>alert('An error occurred while updating the role: " . $e->getMessage() . "');</script>";
                }
                break;
            

        case 'delete_role':
            $userId = $_POST['user_id'];
            $roleId = $_POST['role_id'];

            // Delete specific role from UserRole table
            $stmt = $pdo->prepare("DELETE FROM UserRole WHERE UserID = ? AND RoleID = ?");
            $stmt->execute([$userId, $roleId]);

            header('Location: manage_user.php?success=RoleDeleted');
            break;
        
        default:
            // Redirect back if action is not specified or is unknown
            header('Location: manage_user.php?error=Invalid action');
            break;
    }
    exit;
}

?>
