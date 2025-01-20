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

    <title>REGISTER ACTION</title>
  </head>
  <body>
    <?php

      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userName = mysqli_real_escape_string($conn, $_POST['userName']);
        $userEmail = mysqli_real_escape_string($conn, $_POST['userEmail']);
        $userRole = 2; // default role (user)
        $userPwd = mysqli_real_escape_string($conn, $_POST['userPwd']);
        $confirmPwd = mysqli_real_escape_string($conn, $_POST['confirmPwd']);

        // validate pwd and confirmPwd
        if ($userPwd !== $confirmPwd) {
          echo "<script>
                  alert('Registration unsuccessful, password and confirm password do not match!');
                  window.history.back();
                </script>";
          exit;
        }

        // check if userEmail already exist
        $sql = "SELECT * FROM user 
        WHERE userEmail = '$userEmail' LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
          echo "<p><b>Error:<b> User already exist, please register a new user.</p>";
        }
        else {
          // user does not exist, insert new user record, hash the password
          $pwdHash = trim(password_hash($_POST['userPwd'], PASSWORD_DEFAULT));
          $defaultUserImg = '/IMAGES/PROFILE/default.png';

          $sql = "INSERT INTO user (userName, userEmail, userRole, userPwd, userImg)
          VALUES ('$userName', '$userEmail', '$userRole', '$pwdHash', '$defaultUserImg')";

          if(mysqli_query($conn, $sql)) {
            echo '<script type = "text/javascript">
                    alert("Registration successful, please login!");
                  </script>';
            echo '<script type="text/javascript">
                    window.location.href = "' . BASE_URL . '/index.php";
                  </script>';
            exit();
          }
          else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
          }
        }
      }
      mysqli_close($conn);
    ?>
  </body>
</html>