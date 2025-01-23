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

    <title>FOOD LIST</title>
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
          FOOD LIST
        </h1><br><br>

        <?php
          // sql query to select food details
          $sql_food = "SELECT f.foodID, f.foodName, f.foodCategory, fc.categoryID, fc.categoryName, f.foodDesc,
          f.foodPrice
          FROM food f, food_category fc
          WHERE f.foodCategory = fc.categoryID
          ORDER BY f.foodID ASC";

          // execute query on the database connection
          $result = mysqli_query($conn, $sql_food);

          // get the number of rows returned by the query
          $rowcount = mysqli_num_rows($result);
        ?>

        <!-- start of the table -->
        <table id="list-table">
          <tr>
            <th>FOOD ID</th>
            <th>CATEGORY NAME</th>
            <th>FOOD NAME</th>
            <th>FOOD DESCRIPTION</th>
            <th>FOOD PRICE (RM)</th>
            <th>ACTIONS</th>
          </tr>

        <!-- dynamically create html table row based on output data of each row from blog table -->
        <?php
          if ($rowcount > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row["foodID"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["categoryName"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["foodName"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["foodDesc"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["foodPrice"]) . "</td>";

              echo "<td>";
                    echo "<a href='foodEdit.php?id=" . urlencode($row["foodID"]) . "'>Edit</a> | ";
                    echo "<a href='foodDelete.php?id=" . urlencode($row["foodID"]) . "' onclick='return confirm(\"Are you sure you want to delete this food entry?\");'>Delete</a>";
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
        <h2 id="list-row-count">Total Food: <?php echo $rowcount; ?></h2>
      </div>
    </section>

    <!-- include topNav.js -->
    <script src="../../../../SMMS/JS/topNav.js"></script>

  </body>
</html>