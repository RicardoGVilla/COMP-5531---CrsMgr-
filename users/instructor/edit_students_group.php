<?php
session_start();
require_once '../../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["studentGroupFile"])) {
    $filename = $_FILES["studentGroupFile"]["tmp_name"];

    // Open and read the file
    if (($handle = fopen($filename, "r")) !== FALSE) {
        $firstLine = fgets($handle); // Get the first line to extract the course code
        $courseCode = preg_match("/COMP\s*\d{4}/", $firstLine, $matches) ? str_replace(" ", "", $matches[0]) : null;

        // Skip lines until the headers
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($data[0] == 'Student ID') {
                // Next line after headers is the group leader
                $groupLeaderData = fgetcsv($handle, 1000, ",");
                $groupLeaderId = $groupLeaderData[0];
                break;
            }
        }

        // Generate a random password
        $randomPassword = bin2hex(random_bytes(8));

        // Get the rest of the student IDs
        $studentIds = [];
        while (($studentData = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $studentIds[] = $studentData[0];
        }

        fclose($handle);

       // Now you can echo out the variables directly:
    echo "Course Code: " . htmlspecialchars($courseCode) . "<br>";
    echo "Group Leader ID: " . htmlspecialchars($groupLeaderId) . "<br>";
    echo "Random Password: " . htmlspecialchars($randomPassword) . "<br>";
    echo "Student IDs: " . htmlspecialchars(implode(", ", $studentIds)) . "<br>";
    } else {
        echo "Error opening the file.";
    }
}
?>
