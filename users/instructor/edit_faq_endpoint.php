<?php
session_start();
require_once '../../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["user"]["UserID"])) {
    $userID = $_SESSION["user"]["UserID"];
    $courseCode = $_POST['courseCode']; 
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    // First, get the CourseID based on the CourseCode
    $stmt = $pdo->prepare("SELECT CourseID FROM Course WHERE CourseCode = ?");
    $stmt->execute([$courseCode]);
    $courseID = $stmt->fetchColumn();

    if ($courseID) {
        // Now, insert the new FAQ with the ContributorID (UserID) and CourseID
        $insertStmt = $pdo->prepare("INSERT INTO FAQ (Question, Answer, ContributorID, CourseID) VALUES (?, ?, ?, ?)");
        $insertSuccess = $insertStmt->execute([$question, $answer, $userID, $courseID]);

        if ($insertSuccess) {
            // Redirect or show success message, depending on your application structure
            header("Location: manage_faqs.php"); // Adjust the redirection URL as needed
        } else {
            // Handle insertion error
            echo "An error occurred while inserting the FAQ.";
        }
    } else {
        echo "Course not found.";
    }
} else {
    // Redirect to login page or show an error if the user is not logged in
    header("Location: login.php?error=not_logged_in");
}
?>
