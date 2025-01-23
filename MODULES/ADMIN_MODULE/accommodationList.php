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

    <title>ACCOMMODATION LIST</title>
  </head>
  <body>

    <!-- include topNav.php -->
    <?php include '../../INCLUDES/topHeader.php'; ?>
    
    <!-- include adminNav.php -->
    <?php include '../../INCLUDES/adminNav.php'; ?>

    <!-- include headerBanner.php -->
    <?php include '../../INCLUDES/headerBanner.php';?>
    

    <section class="adminHome">
      <div class="accom-container">
        <h1 id="admin-listTitle">
          ACCOMMODATION LIST
        </h1><br><br>

        <?php
          // sql query to select accommodation details
          $sql_accom = "SELECT a.accommodationID, a.accommodationName, a.accommodationDesc,
          a.accommodationPrice
          FROM accommodation a
          ORDER BY a.accommodationID ASC";

          // execute query on the database connection
          $result = mysqli_query($conn, $sql_accom);

          // get the number of rows returned by the query
          $rowcount = mysqli_num_rows($result);
        ?>

        <!-- start of the table -->
        <table id="accom-table">
          <tr>
            <th>ACCOMMODATION ID</th>
            <th>ACCOMMODATION NAME</th>
            <th>ACCOMMODATION DESCRIPTION</th>
            <th>ACCOMMODATION PRICE (RM)</th>
            <th>ACTIONS</th>
          </tr>

        <!-- dynamically create html table row based on output data of each row from blog table -->
        <?php
          if ($rowcount > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row["accommodationID"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["accommodationName"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["accommodationDesc"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["accommodationPrice"]) . "</td>";

              echo "<td>";
                    echo "<a href='accommodationEdit.php?id=" . urlencode($row["accommodationID"]) . "'>Edit</a> | ";
                    echo "<a href='accommodationDelete.php?id=" . urlencode($row["accommodationID"]) . "' onclick='return confirm(\"Are you sure you want to delete this accommodation?\");'>Delete</a>";
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
        <h2 id="list-row-count">Total Accommodations: <?php echo $rowcount; ?></h2>
      </div>
    </section>

    <!-- include topNav.js -->
    <script src="../../../../SMMS/JS/topNav.js"></script>

  </body>
</html>