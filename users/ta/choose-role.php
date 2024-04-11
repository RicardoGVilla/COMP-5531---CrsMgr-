
<!DOCTYPE html>
<html>
<head>
    <title>Choose Your Role</title>
    <link rel="stylesheet" href="../../css/index.css"> 
</head>
<body>
    <div class="hundredvh-container">
        <div id="roleChoiceModal" class=" table-wrapper" style="display:block;">
            <div class="login-header">
                <h2>Choose Your Role</h2>
            </div>
            <form class="inline-form" action="role_redirect.php" method="post">
                <div class="">
                    <div class="">
                        <input type="radio" id="ta" name="role" value="TA" checked>
                        <label for="ta">Teaching Assistant</label>
                    </div>
                    <input type="radio" id="student" name="role" value="Student">
                    <label for="student">Student</label>
                </div>
                <input class="button is-primary" type="submit" value="Proceed">
            </form>
        </div>
    </div>
</body>
</html>
