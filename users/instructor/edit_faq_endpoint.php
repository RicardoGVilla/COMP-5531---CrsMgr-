<?php
include_once '../../database.php'; 

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST['course']) && isset($_POST['question']) && isset($_POST['answer'])) {
        // Retrieve data from the form
        $course = $_POST['course'];
        $question = $_POST['question'];
        $answer = $_POST['answer'];

        // Insert the FAQ into the database
        try {
            $stmt = $pdo->prepare("INSERT INTO FAQ (Question, Answer, CourseID) VALUES (:question, :answer, :course)");

            // Bind parameters
            $stmt->bindParam(':question', $question);
            $stmt->bindParam(':answer', $answer);
            $stmt->bindParam(':course', $course);

            // Execute the statement
            $stmt->execute();

            // Redirect back to the page with a success message
            header("Location: course_faqs.php?success=faq_added");
            exit();
        } catch (PDOException $e) {
            // Handle database errors
            die("Error: " . $e->getMessage());
        }
    } else {
        // Handle the case where required fields are not set
        echo "One or more required fields are not set.";
    }
} else {
    // Handle the case where the form is not submitted
    echo "Form is not submitted.";
}
?>
