<?php
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

  <!-- register css file -->
  <link rel="stylesheet" href="../../../../SMMS/CSS/MISC/register.css">

  <title>REGISTER</title>
</head>
<body>
  <section class="register">
    
    <!-- container for register form -->
    <div class="register-form">
      <!-- form title -->
      <h2 id="form-title">REGISTER</h2>

      <!-- form details -->
      <form action="<?php echo BASE_URL;?>/MODULES/USER_MANAGEMENT_MODULE/AUTH/registerAction.php" method="post">

        <label for="reguserName">User Name:</label><br>
        <input type="text" id="reguserName" name="userName" required><br><br>

        <label for="reguserEmail">User Email:</label><br>
        <input type="email" id="reguserEmail" name="userEmail" required><br><br>

        <label for="reguserPwd">Password:</label><br>
        <input type="password" id="reguserPwd" name="userPwd" required"><br><br>

        <label for="regconfirmPwd">Confirm Password:</label><br>
        <input type="password" id="regconfirmPwd" name="confirmPwd" required"><br><br>

        <!-- buttons -->
        <button type="submit" value="Register">Register</button>
        <button type="reset" value="Reset">Reset</button><br>
      </form>
    </div>
  </section>
</body>
</html>