<?php 

    //Sql queries relating to marked entities
    require_once($_SERVER['DOCUMENT_ROOT'] . '/COMP5531_PROJECT_CGA/include/config.php');

    //start session if not started
    if (session_status() !== 2) {
        session_start();
    }

    //Fetch the sid the student by userid
    function getStudentBySid($sid) {
        $query = "SELECT * FROM students WHERE sid='$sid'";
        $result = mysqli_query($_SESSION['SQL_CONN'], $query);
        return $result;
    };

    //Fetch the sid the student by userid
    function getStudentid($userid) {
        $query = "SELECT sid FROM students WHERE userid='$userid'";
        $result = mysqli_query($_SESSION['SQL_CONN'], $query);
        return $result;
    };

    //Fetch all attributes for a student by userid
    function getStudentInfo($userid) {
        $query = "SELECT * FROM students WHERE userid='$userid'";
        $result = mysqli_query($_SESSION['SQL_CONN'], $query);
        return $result;
    };

    //Fetch all courses the student is enrolled in by student id
    function getStudentEnrollment($sid, $term) {
        $query = "SELECT sectionid FROM enrollment WHERE sid='$sid' AND term='$term'";
        $result = mysqli_query($_SESSION['SQL_CONN'], $query);
        return $result;
    };

    //Get enrollment by sid, term, courseid
    function getStudentEnrollmentByIdAndCourse($sid, $term, $courseNumber) {
        $query = "SELECT sectionid FROM enrollment WHERE sid='$sid' AND term='$term' AND course_number='$courseNumber'";
        $result = mysqli_query($_SESSION['SQL_CONN'], $query);
        return $result;
    };

    //Fetches the enrollement row for a student by sectionid
    function getStudentEnrollmentBySection($sid, $sectionid) {
        $query = "SELECT * FROM enrollment WHERE sid='$sid' AND sectionid='$sectionid'";
        $result = mysqli_query($_SESSION['SQL_CONN'], $query);
        return $result;
    };

    //Fetch the name of the student given the userid
    function getStudentName($userid) {
        $query = "SELECT name FROM students WHERE userid='$userid'";
        $result = mysqli_query($_SESSION['SQL_CONN'], $query);
        return $result;
    }

    //Delete a student from a course section
    function deleteStudentFromSection($sid, $sectionid) {
        $query = "DELETE FROM enrollment WHERE sid='$sid' AND sectionid='$sectionid'";
        $result = mysqli_query($_SESSION['SQL_CONN'], $query);
        return $result;
    }

    //Function that inserts a new record in the students table
    function createNewStudent($sid, $name, $userid) {
        $query = "INSERT INTO students (sid, name, userid) VALUES ('$sid', '$name','$userid')";
        $result = mysqli_query($_SESSION['SQL_CONN'], $query);
        return $result;
    }

    //Adds a new record in enrollment table
    function addStudentEnrollment($sid, $term, $courseNumber, $sectionid) {
        $query = "INSERT INTO enrollment (sid, term, course_number, sectionid) VALUES ('$sid', '$term', '$courseNumber', '$sectionid')";
        $result = mysqli_query($_SESSION['SQL_CONN'], $query);
        return $result;
    }

    //update a student record
    function updateStudentBySid($sid, $field, $value) {
        $query = "UPDATE students set $field = '$value' WHERE sid='$sid'";
        $result = mysqli_query($_SESSION['SQL_CONN'], $query);
        return $result;
    }

    //fetch that validates if a student is a group leader in the current term
    function validateStudentIsLeader($sid, $term) {
        $query1 = "SELECT sectionid FROM enrollment WHERE sid= '$sid' AND term='$term'";
        $query2 = "SELECT * FROM group_team WHERE leaderid='$sid' AND sectionid IN ($query1)" ;
        $result = mysqli_query($_SESSION['SQL_CONN'], $query2);
        return $result;
    }

    //Fetch all information of students in a specific section
    function getStudentsBySection($sectionid) {
        $query1 = "SELECT sid FROM enrollment WHERE sectionid='$sectionid'";
        $query = "SELECT * FROM students WHERE sid IN ($query1)";
        $result = mysqli_query($_SESSION['SQL_CONN'], $query);
        return $result;
    }

    //Fetch a students section id by course
    function getStudentSectionid($userid, $courseNumber, $term) {
        $query1 = "SELECT sid FROM students WHERE userid='$userid'";
        $query2 = "SELECT sectionid FROM enrollment WHERE sid IN ($query1)";
        $query = "SELECT sectionid FROM course_section WHERE course_number='$courseNumber' AND term='$term' AND sectionid IN ($query2)";
        $result = mysqli_query($_SESSION['SQL_CONN'], $query);
        return $result;
    }

    //Fetch a students statistics for a marked entity
    function getStudentStatisticsPerEntity($sectionid, $sid, $mid) {
        try {
            $query = "SELECT * FROM student_statistics WHERE sectionid='$sectionid' AND group_mid='$mid' AND sid='$sid'";
            $result = mysqli_query($_SESSION['SQL_CONN'], $query);
            return $result;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    //Fetch a students posts count for a marked entity
    function getStudentPostCount($sectionid, $userid, $mid) {
        try {
            $query = "SELECT count(postid) as post_count FROM post WHERE sectionid='$sectionid' AND mid='$mid' AND userid='$userid'";
            $result = mysqli_query($_SESSION['SQL_CONN'], $query);
            return $result;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
?>