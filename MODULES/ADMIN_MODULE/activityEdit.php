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

    <title>ACTIVITY EDIT</title>
  </head>

  <?php
  // check if ID is provided
  if (isset($_GET['id'])) {
    $activityID = intval($_GET['id']);

    // another example to retrieve the existing activity data using prepared statement
    $sql = "SELECT * FROM activity WHERE activityID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $activityID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
      $activityName = $row['activityName'];
      $activityDesc = $row['activityDesc'];
      $activityPrice = $row['activityPrice'];
      $activityImg = $row['activityImg'];
    }
    else {
      echo "Activity not found.";
      exit;
    }
    mysqli_stmt_close($stmt);
  }
  else {
    echo "Invalid request.";
    exit;
  }

  // handle activity update form submission
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $activityName = $_POST['activityName'];
    $activityDesc = $_POST['activityDesc'];
    $activityPrice = $_POST['activityPrice'];

    $uploadDir = '../../IMAGES/ACTIVITY/';
    $activityImg = null;
    $image = null;

    // check if a new image is uploaded
    if (isset($_FILES['activityImg']) && $_FILES['activityImg']['error'] === UPLOAD_ERR_OK) {
      $tmpName = $_FILES['activityImg']['tmp_name'];
      $fileName = basename($_FILES['activityImg']['name']);
      $targetPath = $uploadDir . $fileName;

      // move the uploaded file
      if (move_uploaded_file($tmpName, $targetPath)) {
        $image = $fileName;

        // optional: delete the old image if necessary
        $sql = "SELECT activityImg FROM activity WHERE activityID = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $activityID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $activity = mysqli_fetch_assoc($result);

        if ($activity && $activity['activityImg'] && file_exists($uploadDir . $activity['activityImg'])) {
          unlink($uploadDir . $activity['activityImg']); // deletes the old image file
        }
        mysqli_stmt_close($stmt);
        echo $activityImg;
      }
    }
    
    if ($image) {
      // directory saved to DB
      $activityImg = "/IMAGES/ACTIVITY/" . $image;


      $sql = "UPDATE activity SET activityName = ?, activityDesc = ?, activityPrice = ?, activityImg = ?
      WHERE activityID = ?";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($stmt, "ssdsi", $activityName, $activityDesc, $activityPrice, $activityImg, $activityID);
    }
    else {
      $sql = "UPDATE activity SET activityName = ?, activityDesc = ?, activityPrice = ?,
      WHERE activityID = ?";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($stmt, "ssdi", $activityName, $activityDesc, $activityPrice, $activityID);
    }

    // execute query
    if (mysqli_stmt_execute($stmt)) {
      echo "
            <div id='successMessage'>
              <p> ($activityName) with activity ID of ($activityID) was edited successfully!</p>
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
      echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit;
  }
?>

  <body>

    <!-- include topNav.php -->
    <?php include '../../INCLUDES/topHeader.php'; ?>
    
    <!-- include adminNav.php -->
    <?php include '../../INCLUDES/adminNav.php'; ?>

    <!-- include headerBanner.php -->
    <?php include '../../INCLUDES/headerBanner.php';?>
    

    <section class="adminHome">
    <div class="admin-form">
        <h1 id="form-title">ACTIVITY EDIT FORM</h1><br><br>

        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="activityID" value="<?= isset($activityID) ? htmlspecialchars($activityID) : 'NONE'; ?>">

            <label for="activityName">Activity Name:</label><br>
            <input type="text" name="activityName" id="activityName" value="<?= htmlspecialchars($activityName) ?>" required><br><br>

            <label for="activityDesc">Activity Description:</label><br>
            <textarea id="activityDesc" name="activityDesc" rows="5" cols="80" required><?= htmlspecialchars($activityDesc) ?></textarea><br><br>

            <label for="activityPrice">Activity Price (RM):</label><br>
            <input type="number" id="activityPrice" name="activityPrice" value="<?= htmlspecialchars($activityPrice) ?>" required><br><br>
          
            <label for="activityImg">Activity Image:</label><br>
            <img src="<?= BASE_URL . '/' . htmlspecialchars($activityImg) ?>" style="width:150px;height:150px;text-align: center;"><br><br>
            <input type="file" id="activityImg" name="activityImg" accept="image/*"><br><br>

            <button type="submit">Update</button><br>
        </form>

     </div>
    </section>

    <!-- include topNav.js -->
    <script src="../../../SMMS/JS/topNav.js"></script>

  </body>
</html>