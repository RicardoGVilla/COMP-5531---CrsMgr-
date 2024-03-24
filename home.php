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
        <!--
        <?php if(count($course_list) === 0): ?>
            <h3 class="no-course">No Courses in Enrollment Cart!</h3>
        <?php else: ?>
            <?php foreach($course_list as $key => $value): ?>
                <button type="button" id=<?= $value; ?> onclick="selectCourse(this.id)" class="course-option">
                    <span><?= $value; ?> </span>
                </button>
            <?php endforeach; ?>
        <?php endif; ?>
        -->
    </div>
    <!--
    <?php if ($_SESSION['is_admin'] == 1): ?>
        <?php include ('include/admin_dashboard.php'); ?>
    <?php endif; ?>
    -->
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
