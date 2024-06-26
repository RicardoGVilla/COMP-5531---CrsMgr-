<?php
// code logic written by:
// Ricardo Gutierrez, 40074308

// front end written by: 
// Paulina Valero, 40289881

//code debugged and tested by: 
// Alejandro Araya, 40170778
// Omar Ghandour, 40109052

session_start();

require_once('../../database.php');

// Check if user is logged in
if (!isset($_SESSION["user"]["UserID"])) {
    header("Location: ../../login.php"); // Redirect to login page if not logged in
    exit;
}

// Check if there's a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['question'])) {
    $question = $_POST['question'];
    $courseId = $_SESSION['selectedCourseId'] ?? null;

    // Ensure that a course is selected
    if ($courseId === null) {
        die("No course selected.");
    }

    // Prepare SQL query to insert FAQ
    $sql = "INSERT INTO FAQ (CourseID, Question) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute([$courseId, $question]);
        //echo "FAQ added successfully!";
    } catch (PDOException $e) {
        die("Error adding FAQ: " . $e->getMessage());
    }
} else {
    // Redirect back to the FAQ page if the required fields are not set
    header("Location: faq-information.php");
    exit;
}

// Optionally redirect back to the FAQs page or send a success message
header("Location: faq-information.php");
exit;
?>
