<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <link rel="icon" href="../src/logoSnipped.png">
    <meta charset="utf-8">
    <title>Login | CareerConnect</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="container">
        <a href="../index.php">
            <img class="logo" src="../src/logo2Snipped.png" alt="Logo">
        </a>
        <div class="wrapper">
            <div class="title">Login</div>
            <form action="../control/loginctrl.php" method="post">
                <div class="field">
                    <input type="text" name="email" required>
                    <label>Email Address</label>
                </div>
                <div class="field">
                    <input type="password" name="password" id="password" required>
                    <label>Password</label>
                </div>
                <div class="content">
                    <label>
                        <input style="margin-right: 10px; margin-top: 10px;" type="checkbox" id="showPassword" onclick="togglePasswordVisibility()">Show Password
                    </label>
                </div>
                <div class="field">
                    <input type="submit" value="Login">
                </div>
            </form>
        </div>
    </div>
    <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementById("password");
            var checkbox = document.getElementById("showPassword");

            passwordField.type = checkbox.checked ? "text" : "password";
        }
    </script>
</body>
</html>
