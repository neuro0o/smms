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

    <title>ACCOMMODATION FORM</title>
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
        <h1 id="form-title">ACCOMMODATION FORM</h1><br><br>

        <form action="accommodationInsert.php" method="POST" enctype="multipart/form-data">
          <label for="accommodationName">Accommodation Name:</label><br>
          <input type="text" name="accommodationName" id="accommodationName" required><br><br>

          <label for="accommodationDesc">Accommodation Description:</label><br>
          <textarea id="accommodationDesc" name="accommodationDesc" rows="5" cols="80" required></textarea><br><br>

          <label for="accommodationPrice">Accommodation Price (RM):</label><br>
          <input type="number" id="accommodationPrice" name="accommodationPrice" min="0.01" step="0.01" required><br><br>
          
          <label for="accommodationImg">Accommodation Image:</label><br>
          <input type="file" id="accommodationImg" name="accommodationImg" accept="image/*" required><br><br>

          <button type="submit">Submit</button><br>
        </form>

     </div>
    </section>

    <!-- include topNav.js -->
    <script src="../../../../SMMS/JS/topNav.js"></script>

  </body>
</html>