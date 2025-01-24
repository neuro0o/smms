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

    <title>FOOD EDIT</title>
  </head>

  <?php
  // check if ID is provided
  if (isset($_GET['id'])) {
    $foodID = intval($_GET['id']);

    // another example to retrieve the existing food data using prepared statement
    $sql = "SELECT * FROM food WHERE foodID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $foodID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
      $foodName = $row['foodName'];
      $foodDesc = $row['foodDesc'];
      $foodPrice = $row['foodPrice'];
      $foodImg = $row['foodImg'];
    }
    else {
      echo "Food not found.";
      exit;
    }
    mysqli_stmt_close($stmt);
  }
  else {
    echo "Invalid request.";
    exit;
  }

  // handle food update form submission
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $foodName = $_POST['foodName'];
    $foodDesc = $_POST['foodDesc'];
    $foodPrice = $_POST['foodPrice'];

    $uploadDir = '../../IMAGES/FOOD/';
    $foodImg = null;
    $image = null;

    // check if a new image is uploaded
    if (isset($_FILES['foodImg']) && $_FILES['foodImg']['error'] === UPLOAD_ERR_OK) {
      $tmpName = $_FILES['foodImg']['tmp_name'];
      $fileName = basename($_FILES['foodImg']['name']);
      $targetPath = $uploadDir . $fileName;

      // move the uploaded file
      if (move_uploaded_file($tmpName, $targetPath)) {
        $image = $fileName;

        // optional: delete the old image if necessary
        $sql = "SELECT foodImg FROM food WHERE foodID = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $foodID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $food = mysqli_fetch_assoc($result);

        if ($food && $food['foodImg'] && file_exists($uploadDir . $food['foodImg'])) {
          unlink($uploadDir . $food['foodImg']); // deletes the old image file
        }
        mysqli_stmt_close($stmt);
        echo $foodImg;
      }
    }
    
    if ($image) {
      // directory saved to DB
      $foodImg = "/IMAGES/FOOD/" . $image;


      $sql = "UPDATE food SET foodName = ?, foodDesc = ?, foodPrice = ?, foodImg = ?
      WHERE foodID = ?";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($stmt, "ssdsi", $foodName, $foodDesc, $foodPrice, $foodImg, $foodID);
    }
    else {
      $sql = "UPDATE food SET foodName = ?, foodDesc = ?, foodPrice = ?,
      WHERE foodID = ?";
      $stmt = mysqli_prepare($conn, $sql);
      mysqli_stmt_bind_param($stmt, "ssdi", $foodName, $foodDesc, $foodPrice, $foodID);
    }

    // execute query
    if (mysqli_stmt_execute($stmt)) {
      echo "
            <div id='successMessage'>
              <p> ($foodName) with food ID of ($foodID) was edited successfully!</p>
              <a id='adminDashboardLink' href='adminHome.php'>
                Back to Admin Dashboard
              </a>
              <br>
              <a id='viewList' href='foodList.php'>
                View Food List
              </a>
              <br>
              <a id='createLink' href='foodForm.php'>
                Create New Food
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
        <h1 id="form-title">FOOD EDIT FORM</h1><br><br>

        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="foodID" value="<?= isset($foodID) ? htmlspecialchars($foodID) : 'NONE'; ?>">

            <label for="foodName">Food Name:</label><br>
            <input type="text" name="foodName" id="foodName" value="<?= htmlspecialchars($foodName) ?>" required><br><br>

            <label for="foodDesc">Food Description:</label><br>
            <textarea id="foodDesc" name="foodDesc" rows="5" cols="80" required><?= htmlspecialchars($foodDesc) ?></textarea><br><br>

            <label for="foodPrice">Food Price (RM):</label><br>
            <input type="number" id="foodPrice" name="foodPrice" value="<?= htmlspecialchars($foodPrice) ?>" required><br><br>
          
            <label for="foodImg">Food Image:</label><br>
            <img src="<?= BASE_URL . '/MODULES/FOOD_MODULE/' . htmlspecialchars($foodImg) ?>" style="width:150px;height:150px;text-align: center;"><br><br>
            <input type="file" id="foodImg" name="foodImg" accept="image/*"><br><br>

            <button type="submit">Update</button><br>
        </form>

     </div>
    </section>

    <!-- include topNav.js -->
    <script src="../../../SMMS/JS/topNav.js"></script>

  </body>
</html>