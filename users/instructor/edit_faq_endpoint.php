<?php
session_start();
require_once '../../database.php';

if (!isset($_SESSION["selected_course_id"])) {
    die("No course selected.");
}

$selectedCourseId = $_SESSION["selected_course_id"];

// Handling form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $faq_id = $_POST['faq_id'] ?? null;
    $question = $_POST['question'] ?? '';
    $answer = $_POST['answer'] ?? '';

    switch ($action) {
        case 'add':
            if ($question && $answer) {
                $sql = "INSERT INTO FAQ (Question, Answer, CourseID) VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$question, $answer, $selectedCourseId]);
            }
            break;
        case 'update':
            if ($faq_id && $question && $answer) {
                $sql = "UPDATE FAQ SET Question = ?, Answer = ? WHERE FAQID = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$question, $answer, $faq_id]);
            }
            break;
        case 'delete':
            if ($faq_id) {
                $sql = "DELETE FROM FAQ WHERE FAQID = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$faq_id]);
            }
            break;
    }
    header("Location: manage_faqs.php"); // Redirect to avoid form resubmission
    exit();
}
?>
