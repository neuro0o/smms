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
    $reservationID = intval($_GET['id']);

    // another example to retrieve the existing category data using prepared statement
    $sql = "SELECT * FROM reservation WHERE reservationID = ?";
    $stmt_select = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt_select, "i", $categoryID);
    mysqli_stmt_execute($stmt_select);
    $result = mysqli_stmt_get_result($stmt_select);

    if ($row = mysqli_fetch_assoc($result)) {
      $accommodationID = $row['accommodationID'];
      $reservedBy = $row['reservedBy'];
      $dateFrom = $row['dateFrom'];
      $dateUntil = $row['dateUntil'];
      $totalAmt = $row['totalAmt'];
      $reservationStatus = $row['reservationStatus'];
    }
    else {
      echo "Reservation not found.";
      exit;
    }
    mysqli_stmt_close($stmt_select);
  }
  else {
    echo "Invalid request.";
    exit;
  }

  // handle category update form submission
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $acccommodationID = $_POST['accommodationID'];
    $reservedBy = $_POST['reservedBy'];
    $dateFrom = $_POST['dateFrom'];
    $dateUntil = $_POST['dateUntil'];
    $totalAmt = $_POST['totalAmt'];
    $reservationStatus = $_POST['reservationStatus'];

    // update reservation using stmt_update
    $sql_update = "UPDATE reservation SET accommodationID = ?, reservedBy = ?, dateFrom = ?, dateUntil = ?, totalAmt = ?, reservationStatus = ?
    WHERE reservationID = ?";
    $stmt_update = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "iissdii", $acccommodationID, $reservedBy, $dateFrom, $dateUntil, $totalAmt, $reservationStatus);

    // execute query
    if (mysqli_stmt_execute($stmt_update)) {
      echo "
            <div id='successMessage'>
              <p> Reservation with the ID of ($reservationID) was edited successfully!</p>
              <a id='adminDashboardLink' href='adminHome.php'>
                Back to Admin Dashboard
              </a>
              <br>
              <a id='viewList' href='reservationList.php'>
                View Reservation List
              </a>
            </div>
          ";
    }
    else {
      echo "Error: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt_update);
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
        <h1 id="form-title">RESERVATION EDIT FORM</h1><br><br>

        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="reservationID" value="<?= isset($reservationID) ? htmlspecialchars($reservationID) : 'NONE'; ?>">

            <label for="accommodationID">Accommodation Name:</label><br>
            <input type="hidden" id="accommodationID" name="accommodationID" value="<?php echo $_SESSION['UID']; ?>" readonly>
            <input type="text" value="<?php echo $accommodationName; ?>" readonly><br><br>

            <label for="reservedBy">Reserved By:</label><br>
            <input type="hidden" id="reservedBy" name="reservedBy" value="<?php echo $_SESSION['UID']; ?>" readonly>
            <input type="text" value="<?php echo $userName; ?>" readonly><br><br>

            <label for="dateFrom">Check In Date:</label>
            <input type="date" id="dateFrom" name="dateFrom" value="<?= htmlspecialchars($dateFrom) ?>" readonly><br><br>

            <label for="dateUntil">Check Out Date:</label>
            <input type="date" id="dateUntil" name="dateUntil" value="<?= htmlspecialchars($dateUntil) ?>" readonly><br><br>

            <label for="totalAmt">Total Amount (RM):</label><br>
            <input type="number" id="totalAmt" name="totalAmt" value="<?= htmlspecialchars($totalAmt) ?>" readonly><br><br>

            <label for="reservationStatus">Status:</label><br>
            <select id="reservationStatus" name="reservationStatus" required>
            <option value="">Pending</option>
            <option value="1">Confirm</option>
            <option value="2">Cancel</option>
            </select><br><br>

            <button type="submit">Update</button><br>
        </form>

     </div>
    </section>

    <!-- include topNav.js -->
    <script src="../../../SMMS/JS/topNav.js"></script>

  </body>
</html>