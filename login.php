<!DOCTYPE html>
<html>
<head>
    <title>CGA System</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
    <h2>Welcome to CrsMgr+ -- Please Login to Continue</h2>
    <div id="login-form" class="form-body">
        <form id="login-form" class="login-email" action="login.php" method="post">
            <div class="input-group">
                <input id="email" name="email" type="email" placeholder="Email Address" required>
            </div>
            <div class="input-group">
                <input id="password" name="password" type="password" placeholder="Password" required> 
            </div>
            <div class="input-group">
                <button class="btn" name="submit" type="submit">Login</button>
            </div>
            <p class="login-forgot-text">Forgot password? <u>Click here</u></p>
        </form>
    </div>
</body>
</html>
