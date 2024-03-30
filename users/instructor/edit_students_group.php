<?php
session_start();
require_once '../../database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["studentGroupFile"])) {
    $filename = $_FILES["studentGroupFile"]["tmp_name"];

    // Open and read the file to extract the course code
    $file = fopen($filename, "r");
    $firstLine = fgets($file); // Reading the course name line
    fclose($file);

    // Extracting the course code using a regex that matches "COMP" followed by digits
    if (preg_match("/COMP\s*(\d{4})/", $firstLine, $matches)) {
        $courseCode = "COMP" . $matches[1]; // Concatenating "COMP" with the numeric part
        echo "Parsed course code: " . $courseCode . "<br>"; // Print the parsed course code for verification
    } else {
        echo "Course code not found in the file.";
        return;
    }

    // Execute SQL query to find the CourseID based on the courseCode
    $sqlCourse = "SELECT CourseID FROM Course WHERE CourseCode = '{$courseCode}'";
    // The SQL query execution should be implemented here based on your DB connection.
    // Assuming $courseId is retrieved successfully from your database after query execution.
    // echo "Course ID (CourseID) found: " . $courseId . "<br>";

    // Process the CSV again to extract the first student's ID for group leader
    $file = fopen($filename, "r");
    while (!feof($file)) { // Skip until student data starts
        $line = fgets($file);
        if (str_getcsv($line)[0] === "Student ID") { // Found the header row
            break;
        }
    }
    $firstStudentData = fgetcsv($file); // Get the first student's data
    $groupLeaderId = $firstStudentData[0]; // Assuming the first column is the student ID
    echo "Group leader ID: " . $groupLeaderId . "<br>"; // Printer for group leader ID
    fclose($file);

    // Generate a random password for the group
    $randomPassword = bin2hex(random_bytes(8)); // 16 characters
    echo "Generated group password: " . $randomPassword . "<br>"; // Printer for password

    // Example SQL to insert the new group, adjusted as needed for your database schema
    $sqlInsertGroup = "INSERT INTO `Group` (CourseID, GroupLeaderID, DatabasePassword, MaxSize) VALUES ('{$courseId}', '{$groupLeaderId}', '{$randomPassword}', 4)";
    // Execute SQL query to insert the new group...
    // If insertion is successful, print a success message
    echo "Group successfully created with leader ID {$groupLeaderId}, password: {$randomPassword}";
}
?>
