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

    <title>FOOD INSERT</title>
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
      $foodName = mysqli_real_escape_string($conn, $_POST['foodName']);
      $foodCategory = mysqli_real_escape_string($conn, $_POST['foodCategory']);
      $foodDesc = mysqli_real_escape_string($conn, $_POST['foodDesc']);
      $foodPrice = mysqli_real_escape_string($conn, $_POST['foodPrice']);

      // handle image upload
      $target_dir = "../../IMAGES/FOOD/";
      $target_path = "IMAGES/FOOD/";
      $target_file = $target_dir . basename($_FILES["foodImg"]["name"]);
      $target_fileDB = $target_path . basename($_FILES["foodImg"]["name"]);
      $upload_ok = 1;

      // check if image file is an actual image
      $check = getimagesize($_FILES["foodImg"]["tmp_name"]);
      if ($check !== false) {
        $upload_ok = 1;
      }
      else {
        echo "File is not an image.";
        $upload_ok = 0;
      }

      // move uploaded file to target directory
      if ($upload_ok && move_uploaded_file($_FILES["foodImg"]["tmp_name"], $target_file)) {

        $sql = "INSERT INTO food (foodName, foodCategory, foodDesc, foodPrice, foodImg)
        VALUES ('$foodName', '$foodCategory', '$foodDesc', '$foodPrice', '$target_fileDB')";

        if (mysqli_query($conn, $sql)) {
          echo "
            <div id='successMessage'>
              <p>NEW FOOD ENTRY IS CREATED SUCCESSFULLY!</p>
              <a id='adminDashboardLink' href='adminHome.php'>
                Back to Admin Dashboard
              </a>
              <br>
              <a id='viewList' href='foodList.php'>
                View Food List
              </a>
              <br>
              <a id='createLink' href='foodForm.php'>
                Create New Food Entry
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