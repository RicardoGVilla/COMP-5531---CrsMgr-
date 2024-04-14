<?php
session_start();
include_once '../../database.php';

if (!isset($_SESSION["user"]["UserID"]) || !isset($_SESSION["selectedCourseId"])) {
    header("Location: ../../login.php");
    exit;
}

$selectedCourseId = $_SESSION["selectedCourseId"];

// Fetch existing FAQs
$query = "
    SELECT FAQID, Question, Answer, ContributorID
    FROM FAQ
    WHERE CourseID = :courseId
    ORDER BY FAQID;
";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':courseId', $selectedCourseId, PDO::PARAM_INT);
$stmt->execute();
$faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage FAQs</title>
</head>
<body>
    <h1>Course FAQs</h1>
    <?php if ($faqs): ?>
        <table border="1">
            <tr>
                <th>Question</th>
                <th>Answer</th>
            </tr>
            <?php foreach ($faqs as $faq): ?>
                <tr>
                    <td><?= htmlspecialchars($faq['FAQID']) ?></td>
                    <td><?= htmlspecialchars($faq['Question']) ?></td>
                    <td><?= htmlspecialchars($faq['Answer']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No FAQs found for this course.</p>
    <?php endif; ?>

    <h2>Add FAQ</h2>
    <form action="add_faq.php" method="post">
        <p>
            <label for="question">Question:</label>
            <input type="text" name="question" id="question" required>
        </p>
        <p>
            <label for="answer">Answer:</label>
            <textarea name="answer" id="answer" required></textarea>
        </p>
        <p>
            <input type="submit" value="Submit">
        </p>
    </form>
</body>
</html>
