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

    <title>ACCOMMODATION EDIT</title>
  </head>

  <?php
  // check if ID is provided
  if (isset($_GET['id'])) {
    $accommodationID = intval($_GET['id']);

    // another example to retrieve the existing accommodation data using prepared statement
    $sql = "SELECT * FROM accommodation WHERE accommodationID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $accommodationID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
      $accommodationName = $row['accommodationName'];
      $accommodationDesc = $row['accommodationDesc'];
      $accommodationPrice = $row['accommodationPrice'];
      $accommodationImg = $row['accommodationImg'];
    }
    else {
      echo "Accommodation not found.";
      exit;
    }
    mysqli_stmt_close($stmt);
  }
  else {
    echo "Invalid request.";
    exit;
  }

  // handle accommodation update form submission
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accommodationName = $_POST['accomName'];
    $accommodationDesc = $_POST['accomDesc'];
    $accommodationPrice = $_POST['accomPrice'];

    $uploadDir = '../../IMAGES/ACCOMMODATION/';
    $accommodationImg = null;
    $image = null;

    // check if a new image is uploaded
    if (isset($_FILES['accommodationImg']) && $_FILES['accommodationImg']['error'] === UPLOAD_ERR_OK) {
      $tmpName = $_FILES['accommodationImg']['tmp_name'];
      $fileName = basename($_FILES['accommodationImg']['name']);
      $targetPath = $uploadDir . $fileName;

      // move the uploaded file
      if (move_uploaded_file($tmpName, $targetPath)) {
        $image = $fileName;

        // optional: delete the old image if necessary
        $sql = "SELECT accommodationImg FROM accommodation WHERE accommodationID = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $accommodationID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $accommodation = mysqli_fetch_assoc($result);

        if ($accommodation && $accommodation['accommodationImg'] && file_exists($uploadDir . $accommodation['accommodationImg'])) {
          unlink($uploadDir . $accommodation['accommodationImg']); // deletes the old image file
        }
        mysqli_stmt_close($stmt);
        echo $accommodationImg;
      }
    }
    
    if ($image) {
      // directory saved to DB
      $accommodationImg = "/IMAGES/ACCOMMODATION/" . $image;


      $sql = "UPDATE accommodation SET accommodationName = ?, accommodationDesc = ?, accommodationPrice = ?, accommodationImg = ?
      WHERE accommodationID = ?";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($stmt, "ssdsi", $accommodationName, $accommodationDesc, $accommodationPrice, $accommodationImg, $accommodationID);
    }
    else {
      $sql = "UPDATE accommodation SET accommodationName = ?, accommodationDesc = ?, accommodationPrice = ?,
      WHERE accommodationID = ?";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($stmt, "ssdi", $accommodationName, $accommodationDesc, $accommodationPrice, $accommodationID);
    }

    // execute query
    if (mysqli_stmt_execute($stmt)) {
      echo "
            <div id='successMessage'>
              <p> ($accommodationName) with accommodation ID of ($accommodationID) was edited successfully!</p>
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
        <h1 id="form-title">ACCOMMODATION EDIT FORM</h1><br><br>

        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="accomID" value="<?= isset($accommodationID) ? htmlspecialchars($accommodationID) : 'NONE'; ?>">

            <label for="accomName">Accommodation Name:</label><br>
            <input type="text" name="accomName" id="accomName" value="<?= htmlspecialchars($accommodationName) ?>" required><br><br>

            <label for="accomDesc">Accommodation Description:</label><br>
            <textarea id="accomDesc" name="accomDesc" rows="5" cols="80" required><?= htmlspecialchars($accommodationDesc) ?></textarea><br><br>

            <label for="accomPrice">Accommodation Price (RM):</label><br>
            <input type="number" id="accomPrice" name="accomPrice" value="<?= htmlspecialchars($accommodationPrice) ?>" required><br><br>
          
            <label for="accommodationImg">Accommodation Image:</label><br>
            <img src="<?= BASE_URL . '/' . htmlspecialchars($accommodationImg) ?>" style="width:150px;height:150px;text-align: center;"><br><br>
            <input type="file" id="accommodationImg" name="accommodationImg" accept="image/*"><br><br>

            <button type="submit">Update</button><br>
        </form>

     </div>
    </section>

    <!-- include topNav.js -->
    <script src="../../../SMMS/JS/topNav.js"></script>

  </body>
</html>