<?php 
// code written and reviewed by:
// Jacob Carlone - ID:40000996 
// Tharushan Selliah - ID: 40184870 
// You Sik Jeon - ID: 40214984 
    if(!isset($_SESSION)) {
        session_start();
    }
    require_once($_SERVER['DOCUMENT_ROOT'] . '/COMP5531_PROJECT_CGA/sql/sql_queries.php');
 
    //Fetch all the courses in the database
    $courses = getAllCourses();
    $course_array = array();
    while ($courseRow = $courses->fetch_assoc()) {
        $course_array[$courseRow['course_number']] = $courseRow['course_prefix'] . "-" . strval($courseRow['course_number']);
    }

    //Fetch all users
    //Get all the users from the database
    $userList = array();
    $usersResult = getAllUsers();

    //Iterate over all users and append them to user list
    while ($userRow = $usersResult->fetch_assoc()) {
        //Add user to user list only if it is not the current user - user cannot delete themselves
        if ($_SESSION['userid'] !== $userRow['userid']) {
            $userList[$userRow['userid']] = $userRow['email'];
        }
    }
?>

<!DOCTYPE html>
<html>
    <body>
        <div class="admin-dashboard">
            <div class="admin-header">
                <h2>-- Admin Dashboard Section --</h2>
            </div>
            <div class="actions-contaner">
                <div class="admin-actions">
                    <div class="list-header">
                        <h3>User Actions</h3>
                    </div>
                    <div class="list">
                        <button type="button" id="add-user" class="list-option">
                            <span>Add User</span>
                        </button>
                        <button type="button" id="update-user" class="list-option">
                            <span>Update User</span>
                        </button>
                        <button type="button" id="delete-user" class="list-option">
                            <span>Delete User</span>
                        </button>
                    </div>
                </div>
                <div class="admin-actions">
                    <div class="list-header">
                        <h3>Course/Faculty Actions</h3>
                    </div>
                    <div class="list">
                        <button type="button" id="add-course" class="list-option">
                            <span>Add New Course</span>
                        </button>
                        <button type="button" id="add-course-section" class="list-option">
                            <span>Add New Course Section</span>
                        </button>
                        <button type="button" id="add-instructor" class="list-option">
                            <span>Add New Instructor</span>
                        </button>
                    </div>
                </div>
            </div>
            <?php if (isset($_SESSION['admin_delete_active']) and $_SESSION['admin_delete_active'] === "true"): ?>
            <div class="delete-users">
                <select id="delete_user-select" name="delete_user" required>
                <?php foreach($userList as $key => $value): ?>
                    <option value=<?= $key; ?> class="select-option"><?= $value; ?></option>
                <?php endforeach; ?>
                </select>
                <button type="button" id="delete-user-btn">Delete User</button>
            </div>
            <?php endif; ?>
            <div class="admin-courses">
                <select id="courses-dropdown" name="course-dropdown">
                <?php foreach($course_array as $key => $value): ?>
                    <option value=<?= $value; ?> class="select-option"><?= $value; ?></option>
                <?php endforeach; ?>
                </select>
                <button type="button" id="admin-view-course">View Course</button>
            </div>
        </div>
    </body>
</html>

