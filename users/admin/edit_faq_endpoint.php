<?php
require_once '../../database.php';
session_start();

function addFAQ($pdo, $question, $answer, $course_id, $contributor_id) {
    $query = "INSERT INTO FAQ (Question, Answer, ContributorID, CourseID) VALUES (:question, :answer, :contributor_id, :course_id)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':question' => $question, ':answer' => $answer, ':contributor_id' => $contributor_id, ':course_id' => $course_id]);
}

function updateFAQ($pdo, $faq_id, $new_question, $new_answer, $new_course_id) {
    $query = "UPDATE FAQ SET Question = :new_question, Answer = :new_answer, CourseID = :new_course_id WHERE FAQID = :faq_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':new_question' => $new_question, ':new_answer' => $new_answer, ':new_course_id' => $new_course_id, ':faq_id' => $faq_id]);
}

function deleteFAQ($pdo, $faq_id) {
    $query = "DELETE FROM FAQ WHERE FAQID = :faq_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':faq_id' => $faq_id]);
}

try {
    $contributor_id = $_SESSION['user_id']; 

    if (isset($_POST['question'], $_POST['answer'])) {
        // Add FAQ logic
        $course_id = $_POST['course_id'] !== '' ? $_POST['course_id'] : NULL;
        addFAQ($pdo, $_POST['question'], $_POST['answer'], $course_id, $contributor_id);
        $message = 'FAQ added successfully!';
    } elseif (isset($_POST['faq_id'])) {
        if (isset($_POST['new_question'], $_POST['new_answer'])) {
            // Update FAQ logic
            $new_course_id = $_POST['new_course_id'] !== '' ? $_POST['new_course_id'] : NULL;
            updateFAQ($pdo, $_POST['faq_id'], $_POST['new_question'], $_POST['new_answer'], $new_course_id);
            $message = 'FAQ updated successfully!';
        } elseif(isset($_POST['faq_id'], $_POST['new_course_id'])){

            $faq_id = $_POST['faq_id'];
            $new_course_id = $_POST['new_course_id'];

            if (exportFAQ($pdo, $faq_id, $new_course_id)) {
                $message = 'FAQ updated successfully!';
            } else {
                $error = 'Failed to update FAQ. FAQ ID not found or database error occurred.';
            }



        }else {
            // Delete FAQ logic
            deleteFAQ($pdo, $_POST['faq_id']);
            $message = 'FAQ deleted successfully!';
        }
    }
} catch (PDOException $e) {
    $error = 'Database error: ' . $e->getMessage();
}

function exportFAQ($pdo, $faq_id, $new_course_id) {
    try {
        // Fetch FAQ details based on the provided FAQID
        $faqQuery = "SELECT * FROM FAQ WHERE FAQID = :faq_id";
        $stmt = $pdo->prepare($faqQuery);
        $stmt->execute([':faq_id' => $faq_id]);
        $faq = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($faq) {
            // Insert a new row with the fetched FAQ details and the new CourseID
            $insertQuery = "INSERT INTO FAQ (Question, Answer, ContributorID, CourseID) 
                            VALUES (:question, :answer, :contributor_id, :course_id)";
            $insertStmt = $pdo->prepare($insertQuery);
            $insertStmt->execute([
                ':question' => $faq['Question'],
                ':answer' => $faq['Answer'],
                ':contributor_id' => $faq['ContributorID'],
                ':course_id' => $new_course_id
            ]);
            return true; // Return true if insertion is successful
        } else {
            return false; // Return false if FAQ with provided ID is not found
        }
    } catch (PDOException $e) {
        // Handle any database errors
        return false;
    }
}



// Redirect back to the manage FAQs page with a message or error
$_SESSION['message'] = $message ?? NULL;
$_SESSION['error'] = $error ?? NULL;
header('Location: manage_faqs.php');
exit;
?>
