<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/COMP5531_PROJECT_CGA/sql/sql_queries.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/COMP5531_PROJECT_CGA/sql/students/student_queries.php');

// Start or resume the session
if(!isset($_SESSION)) {
    session_start();
}

// Redirect to login if user is not logged in
if(empty($_SESSION['userid'])){
    header("Location: login.php");
    die();
}

$userid = $_SESSION['userid'];

// Display course sections for students and instructors once user is authenticated
if($userid) {
    $course_list = array();
   
    // Verify if user is a student 
    if ($_SESSION['user_type'] == "student") {

        // Fetch student ID
        $sidResult = findStudentid($userid);
        $sidRow = mysqli_fetch_assoc($sidResult);

        // Fetch all sections the student is enrolled in
        $enrollmentResult = getStudentEnrollment($sidRow['sid'], $_SESSION['current_term'] . "-" . $_SESSION['current_year']);

    } else {
        // Fetch the faculty member's course sections
        if ($_SESSION['user_type'] === "ta") {
            $fid = mysqli_fetch_assoc(getFacultyByUserid($userid));
            $enrollmentResult = getTaSectionsByTerm($fid['fid'], $_SESSION['current_term'] . "-" . $_SESSION['current_year']);
        } else {
            $enrollmentResult = getFacultyEnrollment($userid, $_SESSION['current_term'] . "-" . $_SESSION['current_year']);
        }
    }

    // Iterate over each section enrollment and populate the course list array
    while ($enrollRow = $enrollmentResult->fetch_assoc()) {
        $sectionResult = getSectionInfo($enrollRow['sectionid']);
        $sectionRow = mysqli_fetch_assoc($sectionResult);

        // For each section, fetch the course information
        $courseResult = getCourseInfoFromCourse($sectionRow['course_number']);
        $courseRow = mysqli_fetch_assoc($courseResult);
        $course_list[$courseRow['course_number']] = $courseRow['course_prefix'] . "-" . strval($courseRow['course_number']);
    }   
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <div class="filler">
        <h2>-- Your Course Enrollment for the CGA System --</h2>
    </div>
    <div class="course-list">
        <div class="course-selection">
            <h2>Please Select A Course</h2>
        </div>
        <?php if(count($course_list) === 0): ?>
            <h3 class="no-course">No Courses in Enrollment Cart!</h3>
        <?php else: ?>
            <?php foreach($course_list as $key => $value): ?>
                <button type="button" id=<?= $value; ?> onclick="selectCourse(this.id)" class="course-option">
                    <span><?= $value; ?> </span>
                </button>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php if ($_SESSION['is_admin'] == 1): ?>
        <?php include('include/admin_dashboard.php');?>
    <?php endif; ?>
    <div class="logout">
        <span>Wrong access role? <a href="logout.php">Logout</a></span>
    </div>
    <script>
        // Set session variable for course id selected and redirect to main.php
        function selectCourse(key) {
            // Split the key to extract course prefix and course number
            const courseSplit = key.split("-");
            const courseID = key.split("-")[1];
            const coursePrefix = key.split("-")[0];
        
            var xml = new XMLHttpRequest();
            xml.onreadystatechange = function() {
                if( xml.readyState==4 && xml.status==200 ){
                    window.location.href="main.php";  
                }
            }
            
            const body = "courseid=" + courseID + "&coursename=" + coursePrefix;
            console.log(body);
            xml.open("POST", "./include/session.php", true);
            xml.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xml.send(body);
        }
        
    </script>
    <script src="./javascript/adminDashboard.js"></script>
</body>
</html>
