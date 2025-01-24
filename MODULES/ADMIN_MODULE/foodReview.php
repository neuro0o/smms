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

    <!-- adminList css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/ADMIN/adminList.css">

    <title>FOOD REVIEW LIST</title>
  </head>
  <body>

    <!-- include topNav.php -->
    <?php include '../../INCLUDES/topHeader.php'; ?>
    
    <!-- include adminNav.php -->
    <?php include '../../INCLUDES/adminNav.php'; ?>

    <!-- include headerBanner.php -->
    <?php include '../../INCLUDES/headerBanner.php';?>
    

    <section class="adminHome">
      <div class="list-container">
        <h1 id="admin-listTitle">
          FOOD REVIEW LIST
        </h1><br><br>

        <?php
          // sql query to select food details
          $sql_review = "SELECT r.reviewID, r.reviewText, r.reviewRating, r.reviewDate, u.userName, f.foodName 
          FROM review_food r
          JOIN user u ON r.reviewedBy = u.userID
          JOIN food f ON r.foodID = f.foodID
          ORDER BY r.reviewID ASC";

          // execute query on the database connection
          $result = mysqli_query($conn, $sql_review);

          // get the number of rows returned by the query
          $rowcount = mysqli_num_rows($result);
        ?>

        <!-- start of the table -->
        <table id="list-table">
          <tr>
            <th>REVIEW ID</th>
            <th>FOOD NAME</th>
            <th>REVIEW</th>
            <th>REVIEWED BY</th>
            <th>RATINGS</th>
            <th>REVIEW DATE</th>
          </tr>

        <!-- dynamically create html table row based on output data of each row from blog table -->
        <?php
          if ($rowcount > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row["reviewID"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["foodName"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["reviewText"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["userName"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["reviewRating"]) . " stars" . "</td>";
              echo "<td>" . htmlspecialchars($row["reviewDate"]) . "</td>";
            }
          }
          else {
            echo "<p>No results found.</p>";
          }

          // free result set
          mysqli_free_result($result);
          // close connection
          mysqli_close($conn);
        ?>
      </table>
        <!-- display row count -->
        <h2 id="list-row-count">Total Reviews: <?php echo $rowcount; ?></h2>
      </div>
    </section>

    <!-- include topNav.js -->
    <script src="../../../../SMMS/JS/topNav.js"></script>

  </body>
</html>