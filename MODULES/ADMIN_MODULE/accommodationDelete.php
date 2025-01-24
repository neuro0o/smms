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

    <title>ACCOMMODATION DELETE</title>
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
  if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['id'])) {
      $accommodationID = intval($_GET['id']);
      // delete the accommodation record
      $sql = "DELETE FROM accommodation WHERE accommodationID = ?";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($stmt, "i", $accommodationID);

      if (mysqli_stmt_execute($stmt)) {

          echo "
                <div id='successMessage'>
                  <p>ACCOMMODATION WITH ID ($accommodationID) IS DELETED SUCCESSFULLY!</p>
                  <a id='adminDashboardLink' href='adminHome.php'>
                    Back to Admin Dashboard
                  </a>
                  <br>
                  <a id='viewList' href='accommodationList.php'>
                    View Accommodation List
                  </a>
                  <br>
                  <a id='createLink' href='accommodationForm.php'>
                    Create New Accommodation
                  </a>
                </div>
                ";
      }
      else {
        echo "Error deleting record: " . mysqli_error($conn);
      }

      mysqli_stmt_close($stmt);
  } else {
      echo "Invalid request.";
  }

  mysqli_close($conn);
  ?>
    </section>

    <!-- include topNav.js -->
    <script src="../../../../SMMS/JS/topNav.js"></script>

  </body>
</html>