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

    <title>ACTIVITY FORM</title>
  </head>
  <body>

    <!-- include topNav.php -->
    <?php include '../../INCLUDES/topHeader.php'; ?>
    
    <!-- include adminNav.php -->
    <?php include '../../INCLUDES/adminNav.php'; ?>

    <!-- include headerBanner.php -->
    <?php include '../../INCLUDES/headerBanner.php';?>
    

    <section class="adminHome">
    <div class="admin-form">
        <h1 id="form-title">ACTIVITY FORM</h1><br><br>

        <form action="activityInsert.php" method="POST" enctype="multipart/form-data">
          <label for="activityName">Activity Name:</label><br>
          <input type="text" name="activityName" id="activityName" required><br><br>

          <label for="activityCategory">Activity Category:</label><br> 
          <select id="activityCategory" name="activityCategory" required>
            <option value="">-- Select Category --</option>
            <?php
              // fetch activity categories from the database
              $categoryQuery = "SELECT * FROM activity_category";
              $categoryResult = mysqli_query($conn, $categoryQuery);

                // display activity categories dynamically in dropdown list
                if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
                  while ($category = mysqli_fetch_assoc($categoryResult)) {
                    echo '<option value="' . htmlspecialchars($category['categoryID']) . '">' 
                        . htmlspecialchars($category['categoryName']) . '</option>';
                  }
                } else {
                  echo '<option value="">No categories available</option>';
                }
                ?>
                </select><br><br>

          <label for="activityDesc">Activity Description:</label><br>
          <textarea id="activityDesc" name="activityDesc" rows="5" cols="80" required></textarea><br><br>

          <label for="activityPrice">Activity Price (RM):</label><br>
          <input type="number" id="activityPrice" name="activityPrice" min="0.01" step="0.01" required><br><br>
          
          <label for="activityImg">Activity Image:</label><br>
          <input type="file" id="activityImg" name="activityImg" accept="image/*" required><br><br>

          <button type="submit">Submit</button><br>
        </form>

     </div>
    </section>

    <!-- include topNav.js -->
    <script src="../../../../SMMS/JS/topNav.js"></script>

  </body>
</html>