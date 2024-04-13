<?php
session_start();
require_once '../../database.php';

// Processing form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $faqId = $_POST['faqId'] ?? null;
    $courseCode = $_POST['courseCode'] ?? null; // This will be used for adding FAQs
    $question = $_POST['question'] ?? '';
    $answer = $_POST['answer'] ?? '';
    $action = $_POST['action'] ?? 'add'; // The default action is add

    if (empty($question) || empty($answer)) {
        redirectWithError("Question and answer cannot be empty.");
    }

    try {
        switch ($action) {
            case 'add':
                if (empty($courseCode)) {
                    redirectWithError("Course code is required for adding a FAQ.");
                }
                // SQL to add a new FAQ
                $sql = "INSERT INTO FAQ (Question, Answer, ContributorID, CourseID) VALUES (?, ?, ?, (SELECT CourseID FROM Course WHERE CourseCode = ?))";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$question, $answer, $_SESSION['user_id'], $courseCode]);
                break;

            case 'update':
                if (empty($faqId)) {
                    redirectWithError("FAQ ID is required for updating.");
                }
                // SQL to update an existing FAQ
                $sql = "UPDATE FAQ SET Question = ?, Answer = ? WHERE FAQID = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$question, $answer, $faqId]);
                break;

            case 'delete':
                if (empty($faqId)) {
                    redirectWithError("FAQ ID is required for deletion.");
                }
                // SQL to delete an FAQ
                $sql = "DELETE FROM FAQ WHERE FAQID = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$faqId]);
                break;

            default:
                redirectWithError("Invalid action.");
        }
        $_SESSION['success_message'] = "FAQ successfully {$action}ed.";
    } catch (PDOException $e) {
        redirectWithError("Database error: " . $e->getMessage());
    }
    
    header("Location: manage_faqs.php");
    exit;
} else {
    header("Location: manage_faqs.php");
    exit;
}
?>
