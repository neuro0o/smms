<?php
  session_start();
  // include db config
  include("../../../SMMS/CONFIG/config.php");  
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

    <!-- adminForm css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/ADMIN/adminForm.css">

    <title>ACTIVITY INSERT</title>
  </head>
  <body>

    <!-- include topNav.php -->
    <?php include '../../INCLUDES/topHeader.php'; ?>
    
    <!-- include adminNav.php -->
    <?php include '../../INCLUDES/adminNav.php'; ?>

    <!-- include headerBanner.php -->
    <?php include '../../INCLUDES/headerBanner.php';?>
    

    <section class="adminHome">
    <?php
    // handle form submission
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $activityName = mysqli_real_escape_string($conn, $_POST['activityName']);
      $activityCategory = mysqli_real_escape_string($conn, $_POST['activityCategory']);
      $activityDesc = mysqli_real_escape_string($conn, $_POST['activityDesc']);
      $activityPrice = mysqli_real_escape_string($conn, $_POST['activityPrice']);

      // handle image upload
      $target_dir = "../../IMAGES/ACTIVITY/";
      $target_path = "IMAGES/ACTIVITY/";
      $target_file = $target_dir . basename($_FILES["activityImg"]["name"]);
      $target_fileDB = $target_path . basename($_FILES["activityImg"]["name"]);
      $upload_ok = 1;

      // check if image file is an actual image
      $check = getimagesize($_FILES["activityImg"]["tmp_name"]);
      if ($check !== false) {
        $upload_ok = 1;
      }
      else {
        echo "File is not an image.";
        $upload_ok = 0;
      }

      // move uploaded file to target directory
      if ($upload_ok && move_uploaded_file($_FILES["activityImg"]["tmp_name"], $target_file)) {

        $sql = "INSERT INTO activity (activityName, activityCategory, activityDesc, activityPrice, activityImg)
        VALUES ('$activityName', '$activityCategory', '$activityDesc', '$activityPrice', '$target_fileDB')";

        if (mysqli_query($conn, $sql)) {
          echo "
            <div id='successMessage'>
              <p>NEW ACTIVITY CREATED SUCCESSFULLY!</p>
              <a id='adminDashboardLink' href='adminHome.php'>
                Back to Admin Dashboard
              </a>
              <br>
              <a id='viewList' href='activityList.php'>
                View Activity List
              </a>
              <br>
              <a id='createLink' href='activityForm.php'>
                Create New Activity
              </a>
            </div>
            ";
        }
        else {
          echo "<br>Error: " . $sql . "<br>" . mysqli_error($conn);
        }
      }
      else {
        echo "Sorry, there was an error uploading your file.";
      }
    }
    mysqli_close($conn);
  ?>
    </section>

    <!-- include topNav.js -->
    <script src="../../../../SMMS/JS/topNav.js"></script>

  </body>
</html>