
<!DOCTYPE html>
<html>
<head>
    <title>Choose Your Role</title>
</head>
<body>
    <div id="roleChoiceModal" style="display:block;">
        <h2>Choose Your Role</h2>
        <form action="role_redirect.php" method="post">
            <input type="radio" id="ta" name="role" value="TA" checked>
            <label for="ta">Teaching Assistant</label><br>
            <input type="radio" id="student" name="role" value="Student">
            <label for="student">Student</label><br>
            <input type="submit" value="Proceed">
        </form>
    </div>
</body>
</html>
