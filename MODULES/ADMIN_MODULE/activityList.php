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

    <title>ACTIVITY LIST</title>
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
          ACTIVITY LIST
        </h1><br><br>

        <?php
          // sql query to select activity details
          $sql_activity = "SELECT ac.activityID, ac.activityName, ac.activityCategory, ad.categoryID, ad.categoryName, ac.activityDesc,
          ac.activityPrice
          FROM activity ac, activity_category ad
          WHERE ac.activityCategory = ad.categoryID
          ORDER BY ac.activityID ASC";

          // execute query on the database connection
          $result = mysqli_query($conn, $sql_activity);

          // get the number of rows returned by the query
          $rowcount = mysqli_num_rows($result);
        ?>

        <!-- start of the table -->
        <table id="list-table">
          <tr>
            <th>ACTIVITY ID</th>
            <th>CATEGORY NAME</th>
            <th>ACTIVITY NAME</th>
            <th>ACTIVITY DESCRIPTION</th>
            <th>ACTIVITY PRICE (RM)</th>
            <th>ACTIONS</th>
          </tr>

        <!-- dynamically create html table row based on output data of each row from blog table -->
        <?php
          if ($rowcount > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row["activityID"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["categoryName"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["activityName"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["activityDesc"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["activityPrice"]) . "</td>";

              echo "<td>";
                    echo "<a href='activityEdit.php?id=" . urlencode($row["activityID"]) . "'>Edit</a> | ";
                    echo "<a href='activityDelete.php?id=" . urlencode($row["activityID"]) . "' onclick='return confirm(\"Are you sure you want to delete this activity?\");'>Delete</a>";
                  echo "</td>";
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
        <h2 id="list-row-count">Total Activities: <?php echo $rowcount; ?></h2>
      </div>
    </section>

    <!-- include topNav.js -->
    <script src="../../../../SMMS/JS/topNav.js"></script>

  </body>
</html>