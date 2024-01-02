<!-- change_password.html -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Change Password</title>
</head>
<body>
  <h2>Change Password</h2>
  <form action="../control/change_password.php" method="post">
    <label for="email">Email:</label>
    <input type="email" name="email" required>
    <br>
    <label for="new_password">New Password:</label>
    <input type="password" name="new_password" required>
    <br>
    <input type="submit" value="Change Password">
  </form>
</body>
</html>
