<?php
session_start(); // Start the session.
require_once '../../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if action is set
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            // Add user logic
            // Get form data
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'];

            try {
                // Insert user into the database
                $query = "INSERT INTO User (Name, EmailAddress, Password) VALUES (:name, :email, :password)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([':name' => $name, ':email' => $email, ':password' => password_hash($password, PASSWORD_DEFAULT)]);

                // Get the user ID of the newly inserted user
                $userID = $pdo->lastInsertId();

                // Insert user role into UserRole table
                $queryUserRole = "INSERT INTO UserRole (UserID, RoleID) VALUES (:userID, :roleID)";
                $stmtUserRole = $pdo->prepare($queryUserRole);
                $stmtUserRole->execute([':userID' => $userID, ':roleID' => $role]);

                $_SESSION['message'] = "User added successfully"; // Set success message.
            } catch (PDOException $e) {
                $_SESSION['error'] = $e->getMessage(); // Set error message.
            }
        } elseif ($_POST['action'] === 'delete') {
            // Delete user logic
            $userID = $_POST['user_id'];
            
            try {
                $query = "DELETE FROM User WHERE UserID = :userID";
                $stmt = $pdo->prepare($query);
                if(!$stmt->execute([':userID' => $userID])){
                    // If execute returns false, manually throw an exception
                    throw new Exception('Failed to delete the user.');
                }
                if($stmt->rowCount() == 0){
                
                    throw new Exception("User ID {$userID} not found.");
                }
                $_SESSION['message'] = "User deleted successfully";
            } catch (Exception $e) {
                $_SESSION['error'] = "Error deleting user: " . $e->getMessage();
            }
        }
        elseif ($_POST['action'] === 'update') {
            $userID = $_POST['user_id']; 
            $newName = $_POST['new_name'];
            $newEmail = $_POST['new_email'];
            $newPassword = $_POST['new_password'];
            $newRole = $_POST['new_role'];
        
            try {
                $pdo->beginTransaction();
        
                // Start building the update query
                $updateUserQuery = "UPDATE User SET Name = :newName, EmailAddress = :newEmail";
        
                // Parameters for the SQL statement
                $params = [
                    ':newName' => $newName,
                    ':newEmail' => $newEmail,
                    ':userID' => $userID
                ];
        
                // Conditionally add password to the update statement if provided
                if (!empty($newPassword)) {
                    $updateUserQuery .= ", Password = :newPassword";
                    $params[':newPassword'] = password_hash($newPassword, PASSWORD_DEFAULT);
                }
        
                // Finalize the update statement with the WHERE clause
                $updateUserQuery .= " WHERE UserID = :userID";
        
                $stmtUpdateUser = $pdo->prepare($updateUserQuery);
                $stmtUpdateUser->execute($params);
        
                // Update user role if it's changed
                $queryUpdateUserRole = "UPDATE UserRole SET RoleID = :roleID WHERE UserID = :userID";
                $stmtUpdateUserRole = $pdo->prepare($queryUpdateUserRole);
                $stmtUpdateUserRole->execute([':roleID' => $newRole, ':userID' => $userID]);
        
                $pdo->commit();
                $_SESSION['message'] = "User updated successfully";
            } catch (PDOException $e) {
                $pdo->rollBack();
                $_SESSION['error'] = "Error updating user: " . $e->getMessage();
            }
        }
        

        
    }

    // Redirect back to manage_user.php
    header('Location: manage_user.php');
    exit;
}

// Function to retrieve user roles
function getUserRoles($userID, $pdo) {
    $roles = [];
    $query = "SELECT RoleName FROM Role JOIN UserRole ON Role.RoleID = UserRole.RoleID WHERE UserID = :userID";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':userID' => $userID]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $roles[] = $row['RoleName'];
    }
    
    // Check if the user has both Student and TA roles
    $hasStudentRole = in_array('Student', $roles);
    $hasTARole = in_array('TA', $roles);
    if ($hasStudentRole && $hasTARole) {
        return ['TA'];
    }
    
    return $roles;
}


?>
