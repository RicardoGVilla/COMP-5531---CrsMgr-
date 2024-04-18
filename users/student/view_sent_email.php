<?php
session_start();
require_once '../../database.php'; // Adjust path as needed

if (!isset($_SESSION["user"]["UserID"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['email_id'])) {
    $email_id = $_GET['email_id'];
    $stmt = $pdo->prepare("SELECT InternalEmail.Subject, InternalEmail.Body, InternalEmail.Timestamp, GROUP_CONCAT(`User`.Name SEPARATOR ', ') AS Recipients FROM InternalEmail JOIN EmailRecipient ON InternalEmail.EmailID = EmailRecipient.EmailID JOIN `User` ON EmailRecipient.RecipientID = `User`.UserID WHERE InternalEmail.EmailID = ? GROUP BY InternalEmail.EmailID");
    $stmt->execute([$email_id]);
    $email = $stmt->fetch(PDO::FETCH_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Sent Email - CrsMgr+</title>
    <link rel="stylesheet" href="../../css/index.css">
    <style>
        .email-system {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            padding: 20px;
        }
        .email-content h1, p {
            margin-bottom: 10px;
        }
        a {
            color: #0056b3;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <header class="header">
            <h1>Welcome Student <?php echo htmlspecialchars($_SESSION['userName']); ?></h1>
        </header>
        <!-- Sidebar -->
        <div class="sidebar">
            <button onclick="location.href='contact_information.php'">Contact Information</button>
            <button onclick="location.href='faq-information.php'">FAQ</button>
            <button onclick="location.href='group-information.php'">My Group Information </button>
            <button onclick="location.href='manage-files.php'"> Manage Files </button>
            <button onclick="location.href='internal_emails.php'">Email</button>
        </div>
        <!-- Main content area -->
        <main class="main">
            <div class="email-system">
                <?php if ($email): ?>
                    <h1><?php echo htmlspecialchars($email['Subject']); ?></h1>
                    <p><strong>To:</strong> <?php echo htmlspecialchars($email['RecipientNames']); ?></p>
                    <p><strong>Sent:</strong> <?php echo $email['Timestamp']; ?></p>
                    <p><?php echo nl2br(htmlspecialchars($email['Body'])); ?></p>
                <?php else: ?>
                    <p>Email not found or you do not have permission to view it.</p>
                <?php endif; ?>
            </div>
        </main>
        <!-- Footer -->
        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>
