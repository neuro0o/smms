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

    <title>RESERVATION LIST</title>
</head>

<body>

    <!-- include topNav.php -->
    <?php include '../../INCLUDES/topHeader.php'; ?>

    <!-- include adminNav.php -->
    <?php include '../../INCLUDES/adminNav.php'; ?>

    <!-- include headerBanner.php -->
    <?php include '../../INCLUDES/headerBanner.php'; ?>


    <section class="adminHome">
        <div class="list-container">
            <h1 id="admin-listTitle">
                RESERVATION LIST
            </h1><br><br>

            <?php
            // sql query to select reservation details
            $sql_reservation = "SELECT rs.reservationID, a.accommodationName, u.userName, rs.dateFrom, rs.dateUntil,
            rs.totalAmt, rs.reservationStatus
            FROM reservation rs
            JOIN user u ON rs.reservedBy = u.userID
            JOIN accommodation a ON rs.accommodationID = a.accommodationID
            ORDER BY rs.reservationID ASC";

            // execute query on the database connection
            $result = mysqli_query($conn, $sql_reservation);

            // get the number of rows returned by the query
            $rowcount = mysqli_num_rows($result);
            ?>

            <!-- start of the table -->
            <table id="list-table">
                <tr>
                    <th>RESERVATION ID</th>
                    <th>ACCOMMODATION NAME</th>
                    <th>RESERVED BY</th>
                    <th>CHECK IN DATE</th>
                    <th>CHECK OUT DATE</th>
                    <th>TOTAL AMOUNT (RM)</th>
                    <th>STATUS</th>
                </tr>

                <!-- dynamically create html table row based on output data of each row from blog table -->
                <?php
                if ($rowcount > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["reservationID"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["accommodationName"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["userName"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["dateFrom"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["dateUntil"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["totalAmt"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["reservationStatus"]) . "</td>";
                    }
                } else {
                    echo "<h1>No results found.</h1>";
                }

                // free result set
                mysqli_free_result($result);
                // close connection
                mysqli_close($conn);
                ?>
            </table>
            <!-- display row count -->
            <h2 id="list-row-count">Total Reservations: <?php echo $rowcount; ?></h2>
        </div>
    </section>

    <!-- include topNav.js -->
    <script src="../../../../SMMS/JS/topNav.js"></script>

</body>

</html>