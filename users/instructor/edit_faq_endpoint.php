<?php

// code written by:
// Ricardo Gutierrez, 40074308


session_start(); 
require_once '../../database.php'; 

// Check if a course is selected
$selectedCourseId = isset($_SESSION['selected_course_id']) ? $_SESSION['selected_course_id'] : null;
if (!$selectedCourseId) {
    die("No course selected."); 
}

// Function to add a new FAQ
function addFaq($pdo, $courseId, $question, $answer) {
    try {
        $sql = "INSERT INTO FAQ (CourseID, Question, Answer) VALUES (:courseId, :question, :answer)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':courseId', $courseId);
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':answer', $answer);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

// Function to update an existing FAQ
function updateFaq($pdo, $faqId, $newQuestion, $newAnswer, $newCourseId) {
    try {
        $sql = "UPDATE FAQ SET Question = :newQuestion, Answer = :newAnswer, CourseID = :newCourseId WHERE FAQID = :faqId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newQuestion', $newQuestion);
        $stmt->bindParam(':newAnswer', $newAnswer);
        $stmt->bindParam(':newCourseId', $newCourseId);
        $stmt->bindParam(':faqId', $faqId);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

// Function to delete an FAQ
function deleteFaq($pdo, $faqId) {
    try {
        $sql = "DELETE FROM FAQ WHERE FAQID = :faqId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':faqId', $faqId);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

// Determine which form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['question']) && isset($_POST['answer'])) {
        // Add FAQ
        $result = addFaq($pdo, $selectedCourseId, $_POST['question'], $_POST['answer']);
    } elseif (isset($_POST['faq_id']) && isset($_POST['new_question']) && isset($_POST['new_answer']) && isset($_POST['new_course_id'])) {
        // Update FAQ
        $result = updateFaq($pdo, $_POST['faq_id'], $_POST['new_question'], $_POST['new_answer'], $_POST['new_course_id']);
    } elseif (isset($_POST['faq_id'])) {
        // Delete FAQ
        $result = deleteFaq($pdo, $_POST['faq_id']);
    }

    if ($result === true) {
        $message = "Operation successful.";
    } else {
        $error = "Operation failed: " . $result;
    }

    // Redirect back to the manage FAQs page with a message or error
    header('Location: manage_faqs.php?message=' . urlencode($message ?? '') . '&error=' . urlencode($error ?? ''));
    exit();
}
?>
