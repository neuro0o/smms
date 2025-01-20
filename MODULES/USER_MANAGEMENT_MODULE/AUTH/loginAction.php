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

    <title>LOGIN ACTION</title>
  </head>

  <body>
    <?php

      // get login values from login form
      $userEmail = mysqli_real_escape_string($conn, $_POST['userEmail']);
      $userPwd = mysqli_real_escape_string($conn, $_POST['userPwd']);

      $sql = "SELECT * FROM user
      WHERE userEmail = '$userEmail' LIMIT 1"; 
      $result = mysqli_query($conn, $sql);

      if (mysqli_num_rows($result) == 1) {
        
        // check password hash
        $row = mysqli_fetch_assoc($result);
        if (password_verify($userPwd, $row['userPwd'])) {
          echo '<script type="text/javascript">		
                  alert("Login successful!");
                </script>';

          // set session variables
          $_SESSION["UID"] = $row["userID"];
          $_SESSION["userName"] = $row["userName"];
          $_SESSION["userRole"] = $row["userRole"];
          
          // redirect based on user role
          if ($row['userRole'] == 1) { // role: admin
            echo '<script type="text/javascript">
                    window.location.href = "' . ADMIN_BASE_URL . '/adminHome.php";
                  </script>';
          }
          else if ($row['userRole'] == 2) { // role: user
            echo '<script type="text/javascript">
                    window.location.href = "' . BASE_URL . '/MODULES/USER_MANAGEMENT_MODULE/userHome.php";
                  </script>';
          }
          else { // unknown user/role
            echo '<script type="text/javascript">
                    alert("Unknown user role.");
                    window.location.href = "' . BASE_URL . '/index.php";
                  </script>';
          }
          exit();	
        }
        else {
          echo '<p class="error-login">Login error, user email and password are incorrect.</p><br>';
          echo '<a class="error-redirect" href="' . BASE_URL . '/index.php"> | BACK |</a> &nbsp;&nbsp;&nbsp; <br>';
        }	
      }
      else {
        echo "<p class='error-login'>Login error, <b><mark>$userEmail</mark></b> does not exist.</p><br>";
        echo '<a class="error-redirect" href="' . BASE_URL . '/index.php"> | BACK |</a>&nbsp;&nbsp;&nbsp; <br>';	
      } 
      mysqli_close($conn);
    ?>
  </body>
</html>