<?php
  session_start();
  // include db config
  include ("../../../../SMMS/CONFIG/config.php");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- cdn icon link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- utils css file -->
    <link rel="stylesheet" href="../../../../SMMS/CSS/MISC/utils.css">

    <!-- login css file -->
    <link rel="stylesheet" href="../../../../SMMS/CSS/MISC/login.css">

    <title>LOGIN</title>
  </head>
  <body>
    <section class="login">
      <!-- container for login form -->
      <div class="login-form">
        <!-- form title -->
        <h2 id="form-title">LOGIN</h2>

        <!-- form details -->
        <form action="<?php echo BASE_URL; ?>/MODULES/USER_MANAGEMENT_MODULE/AUTH/loginAction.php" method="post">
          <label for="userEmail">User Email:</label><br>
          <input type="email" id="userEmail" name="userEmail" required><br><br>

          <label for="userPwd">Password:</label><br>
          <input type="password" id="userPwd" name="userPwd" required"><br><br>

          <!-- buttons -->
          <button type="submit" value="Login">Login</button>
          <button type="reset" value="Reset">Reset</button><br>
        </form>

      </div>
    </section>
  </body>
</html>