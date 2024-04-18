<?php
// Start the session
session_start();

// Check if user is logged in and has a user ID stored in session
if (!isset($_SESSION["user"]["UserID"])) {
    header("Location: ../../login.php"); // Redirect to login page if not logged in
    exit;
}

// Check if selected course information is available in session
if (!isset($_SESSION["selectedCourseName"])) {
    // Redirect to choose-class.php to select a course if no course is selected
    header("Location: choose-class.php");
    exit;
}

$selectedCourseID = $_SESSION["selectedCourseName"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - CrsMgr+</title>
    <link rel="stylesheet" href="../../css/index.css">
</head>
<body>
    <div class="page">
        <header class="header">
            <h1>Welcome Student <?php echo htmlspecialchars($_SESSION["user"]["Name"]); ?></h1>
        </header>

        <div class="sidebar">
            <button onclick="location.href='contact_information.php'">Contact Information</button>
            <button onclick="location.href='faq-information.php'">FAQ</button>
            <button onclick="location.href='group-information.php'">My Group Information </button>
<<<<<<< HEAD
            <button onclick="location.href='manage-files.php'"> Manage Files </button>
            <button onclick="location.href='internal_emails.php'">Email</button>
=======
            <button onclick="location.href='internal_email.php'">Internal Email Communication </button>
>>>>>>> fe459858416ee95ee4f3bbaf24563ffc49584d8b
        </div>

        <main class="main">
            <h2>Current Course: <?php echo htmlspecialchars($_SESSION["selectedCourseName"]); ?></h2>
            <h3>Welcome To Course Student Home Page</h3>
            <p>This webpage will help you access, submit, manage and maintain the information of the course section(s) you take. The features offered as menus in the left window are explained below.</p>
            <b>Features</b>
            <ul type="disc"> 
                <li><b>Contact Information</b></li>
                    <ul type="disc">
                        <li>List the contact information for the instructor, tutor, maker and lab instructor as applicable for the course.</li>  
                    </ul>
                <br>

                <li><b>Course Material</b></li>
                    <ul type="disc">
                        <li>View information of course materials set up by instructor/coordinator</li> 
                        <li>Download course material files--assignment/project/solution/course outline/tutorial slides</li>
                        <li>Read announcements made by instructor/coordinator</li>
                        <li>Check due dates for assigned course works and the schduled time for on-line assessments</li>
                        <li><b>Note: The material posted are copyrighted by the authors and are not to be copied or distributed in any format. They may only be used for study during the term by registerd students.Any other useage violates the copyrights of the authors.</b></li>
                    </ul>
                <br>

                <li><b>Tutorial and Lab</b></li>
                    <ul type="disc">
                        <li>List information for tutorial and lab time slots </li> 
                        <li>Vote for tutorial and lab time slots if a vote is set by the instructor</li>            
                    </ul>
                <br>

                <li><b>Course Group</b></li>
                    <ul type="disc">
                        <li>Join course groups before preset deadline</li>
                        <li>Vote for group leader before preset deadline</li>
                        <li>List detail information of your current group</li>      
                    </ul>
                <br>
      
                <li><b>Peer Review</b></li>
                    <ul type="disc">
                        <li>Participate in peer-review to evaluate the contribution made by the members to the group work; this has to be done before a preset deadline</li>
                        <li>View your peer-review scores given by other group members. This may be accessible only after a deadline</li>
                        <li><b> Note: If you do not evaluate (assign score to) your peers, you will be considered to not have participated in the group work and would get a ZERO for the work</b></li>       
                    </ul>
                <br>      
  
  
                <li><b>Reserve Meeting Time Slots</b></li>
                    <ul type="disc">
                        <li>Reserve time slots for personal consultation</li>
                        <li>Reserve time slots for individual or group demo. For group work, only the group leader can reserve a slot. However, this must be done after consulting all members of the group since they have to be present for group demos</li>
                        <li>Cancel/change reservations before preset deadline(set by instructor)</li>   
                        <li><b> Note: Conuslt members of the group before reserving a group demo time slot since all members of the group need to be present.</b></li>
                    </ul>
                <br>            
   
                <li><b>Assignment/Project Upload</b></li>
                    <ul type="disc">
                        <li>Upload/Update your submission files before preset deadline</li>
                        <li>Upload your late submission files with preset penalty</li>
                        <li>View your current upload info for your individual/group works </li>
                        <li>Download your uploaded file for verification purpose </li>   
                        <li><b> Note: For group work, only the group leader can upload files. Make sure the group members agree on the contents of the files being uploaded.</b></li>
                    </ul>
                <br>        
      
                <li><b>On line Assessment</b></li>
                    <ul type="disc">
                        <li>Take on line assessment during preset time-windows</li>
                        <li>Review your on line assessment within preset time-windows when the review is available</li>             
                    </ul>
                <br> 

                <li><b>Course Grades</b></li>
                    <ul type="disc">
                        <li>View your personal grades for all your course works: assignment/project/assessment</li>
                        <li>View the grade distribution in your class</li>   
                        <li>For group work, the grade for each member of the group depends on the peer evaluations if peer review is required by instructor</li> 
                    </ul>
                <br>         
                   
                <li><b>Change Password</b></li>
                    <ul type="disc">
                        <li>Change your password for access to the system</li> 
                    </ul>
                <br> 

                <li><b>Change Email</b></li>
                    <ul type="disc">
                        <li>Update your email which is recorded in the system. <b>Make sure to change your email address to the ones as required by the instructor.</b></li>  
                    </ul>
                <br>  
            </ul>
        </main>

        <footer class="footer">
            <button onclick="location.href='home.php'">Home</button>
            <button onclick="location.href='../../logout.php'">Logout</button>
        </footer>
    </div>
</body>
</html>