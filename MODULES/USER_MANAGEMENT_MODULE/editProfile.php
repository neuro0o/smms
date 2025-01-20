<?php
  session_start();
  include("../../../SMMS/CONFIG/config.php"); 

  // check if user is logged in
  if (!isset($_SESSION['UID'])) {
      // redirect to landing page if not logged in
      header("Location: " . BASE_URL . "/index.php");
      exit();
  }

  // get user ID from session
  $userID = $_SESSION['UID'];

  // fetch user data
  $sql_editUser = "SELECT userName, userEmail, userImg
  FROM user
  WHERE userID = ?";
  $stmt = mysqli_prepare($conn, $sql_editUser);
  mysqli_stmt_bind_param($stmt, "i", $userID);
  mysqli_stmt_execute($stmt);
  $result_editUser = mysqli_stmt_get_result($stmt);

  if ($row = mysqli_fetch_assoc($result_editUser)) {
      $userEmail = $row['userEmail'];
      $userName = $row['userName'];
      $userImg = $row['userImg'];
  } else {
      echo "User not found.";
      exit;
  }
  mysqli_stmt_close($stmt);

  // handle editProfile form submission
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $newUserName = mysqli_real_escape_string($conn, $_POST['userName']);
      $newUserEmail = mysqli_real_escape_string($conn, $_POST['userEmail']);
      $currentPwd = mysqli_real_escape_string($conn, $_POST['currentPwd']);
      $newPwd = mysqli_real_escape_string($conn, $_POST['newPwd']);

      // verify current password
      $sql_verifyPwd = "SELECT userPwd
      FROM user
      WHERE userID = ?";
      $stmt_verifyPwd = mysqli_prepare($conn, $sql_verifyPwd);
      mysqli_stmt_bind_param($stmt_verifyPwd, "i", $userID);
      mysqli_stmt_execute($stmt_verifyPwd);
      $result_verifyPwd = mysqli_stmt_get_result($stmt_verifyPwd);
      $row_verifyPwd = mysqli_fetch_assoc($result_verifyPwd);

      if (password_verify($currentPwd, $row_verifyPwd['userPwd'])) {
        // initialize new profile image path
        $imgPath = $userImg;

        // update profile image if uploaded
        if (!empty($_FILES['profileImg']['name'])) {
            // delete the previous image only if it is not "default.png"
            if ($userImg && $userImg !== '/IMAGES/PROFILE/default.png' && file_exists("../../../SMMS" . $userImg)) {
              unlink("../../../SMMS" . $userImg);
            }

            // set new image path
            $imgPath = '/IMAGES/PROFILE/' . basename($_FILES['profileImg']['name']);
            move_uploaded_file($_FILES['profileImg']['tmp_name'], "../../../SMMS" . $imgPath);
        }

        // update user data only if changed
        $updateFields = [];
        if ($newUserName != $userName) {
            $updateFields[] = "userName = '$newUserName'";
        }
        if ($newUserEmail != $userEmail) {
            $updateFields[] = "userEmail = '$newUserEmail'";
        }
        if (!empty($newPwd)) {
            $newPwdHash = password_hash($newPwd, PASSWORD_DEFAULT);
            $updateFields[] = "userPwd = '$newPwdHash'";
        }
        if ($imgPath != $userImg) {
            $updateFields[] = "userImg = '$imgPath'";
        }

        if (count($updateFields) > 0) {
            $sql_update = "UPDATE user
            SET " . implode(", ", $updateFields) . " 
            WHERE userID = ?";
            $stmt = mysqli_prepare($conn, $sql_update);
            mysqli_stmt_bind_param($stmt, "i", $userID);
            mysqli_stmt_execute($stmt);
        }

        // redirect to profile.php once done
        header("Location: " . BASE_URL . "/MODULES/USER_MANAGEMENT_MODULE/profile.php");
        exit();
      } 
      else {
        echo "Current password is incorrect.";
      }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- cdn icon link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- utils css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/utils.css">

    <!-- headerBanner css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/headerBanner.css">

    <!-- topNav css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topNav.css">

    <!-- topHeader css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topHeader.css">

    <!-- profile css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/USER/profile.css">

    <title>USER HOME</title>
  </head>
  <body>
    <section class="userHome">
     <div class="profile-form">
        <h1 id="form-title">EDIT PROFILE</h1><br><br>

        <form method="POST" enctype="multipart/form-data">
          <label for="currentPwd">Current Password:</label><br>
          <input type="password" name="currentPwd" placeholder="Enter correct password to edit" required><br><br>

          <hr class="dashed"><br><br>

          <label for="profileImg">Profile Image:</label><br>
          <input type="file" name="profileImg" id="profileImg"><br><br>

          <label for="userName">Username:</label><br>
          <input type="text" name="userName" value="<?php echo htmlspecialchars($userName); ?>" required><br><br>

          <label for="userEmail">Email:</label><br>
          <input type="email" name="userEmail" value="<?php echo htmlspecialchars($userEmail); ?>" required><br><br>

          <label for="newPwd">New Password:</label><br>
          <input type="password" name="newPwd" placeholder="optional"><br><br>          

          <button type="submit">Save</button><br>
        </form>

     </div>
    </section>

    <!-- include topNav.js -->
    <script src="../../../SMMS/JS/topNav.js"></script>

  </body>
</html>