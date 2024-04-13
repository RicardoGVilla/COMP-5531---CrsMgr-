<?php
// Check the role selected from the form submission
if (isset($_POST['role'])) {
    if ($_POST['role'] == 'TA') {
        // Redirect to the TA home page
        header('Location: home.php');
        exit;
    } elseif ($_POST['role'] == 'Student') {
        // Redirect to the Student home page
        header('Location: ../student/home.php');
        exit;
    }
}

// Redirect to the login page if no role is selected or if the form wasn't submitted
header('Location: ../../login.php');
exit;
?>
