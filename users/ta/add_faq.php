<?php

// code logic written by:
// Ricardo Gutierrez, 40074308

// front end written by: 
// Paulina Valero, 40289881



session_start();
include_once '../../database.php';

if (!isset($_SESSION["user"]["UserID"]) || !isset($_SESSION["selectedCourseId"])) {
    header("Location: ../../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['question'], $_POST['answer'])) {
    $question = $_POST['question'];
    $answer = $_POST['answer'];
    $contributorId = $_SESSION["user"]["UserID"];
    $courseId = $_SESSION["selectedCourseId"];

    $query = "INSERT INTO FAQ (Question, Answer, ContributorID, CourseID) VALUES (:question, :answer, :contributorId, :courseId)";
    $stmt = $pdo->prepare($query);

    $stmt->bindParam(':question', $question);
    $stmt->bindParam(':answer', $answer);
    $stmt->bindParam(':contributorId', $contributorId);
    $stmt->bindParam(':courseId', $courseId);

    if ($stmt->execute()) {
        echo "<script>alert('FAQ added successfully!'); window.location.href='manage_faqs.php';</script>";
    } else {
        echo "<script>alert('Error adding FAQ.'); window.history.back();</script>";
    }
} else {
    // Redirect back if no POST data is found
    header("Location: manage_faqs.php");
    exit;
}
?>
