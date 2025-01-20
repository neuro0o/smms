<?php
  session_start();
  // include db config
  include("../../../SMMS/CONFIG/config.php"); 

  // Check if user is logged in
  if (!isset($_SESSION['UID'])) {
    // redirect to landing page if not logged in
    header("Location: " . BASE_URL . "/index.php");
    exit();
  }

  // get user ID from session
  $userID = $_SESSION['UID'];

  // fetch user data
  $sql_user =  "SELECT userName, userEmail, userPwd, userImg
  FROM user
  WHERE userID = ?";

  $stmt = mysqli_prepare($conn, $sql_user);
  mysqli_stmt_bind_param($stmt, "i", $userID);
  mysqli_stmt_execute($stmt);
  $result_user = mysqli_stmt_get_result($stmt);

  $user_data = mysqli_fetch_assoc($result_user);

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

    <!-- include topNav.php -->
    <?php include '../../INCLUDES/topHeader.php'; ?>
    
    <!-- include userNav.php -->
    <?php include '../../INCLUDES/userNav.php'; ?>

    <!-- include headerBanner.php -->
    <?php include '../../INCLUDES/headerBanner.php';?>
    

    <section class="userHome">
      <div class="profile-container">
        <div class="profile-img">
          <img src="<?php echo BASE_URL . '/' . htmlspecialchars($user_data['userImg']); ?>" alt="profile-img">
        </div>

        <div class="profile-info">
          <table id="profile-table">
            <th colspan="2">PROFILE DETAILS</th>

              <tr>
                <td class="title">NAME</td>
                <td class="content"><?php echo htmlspecialchars($user_data['userName']); ?></td>
              </tr>

              <tr>
                <td class="title">EMAIL</td>
                <td class="content"><?php echo htmlspecialchars($user_data['userEmail']); ?></td>
              </tr>
          
              <tr>
                <td class="title">PASSWORD</td>
                <td class="content">********</td>
              </tr>
          </table><br>

          <button id="edit-profile" onclick="window.location.href='editProfile.php'">EDIT PROFILE</button>

        </div>
      </div>
    </section>

    <!-- include topNav.js -->
    <script src="../../../SMMS/JS/topNav.js"></script>

  </body>
</html>