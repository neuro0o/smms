<?php
session_start();

// Include db config
include("../../../SMMS/CONFIG/config.php");

// Check if the accommodation ID is provided via POST
if (!isset($_POST['accommodationID'])) {
    die("Error: Accommodation ID not provided.");
}

$accommodationID = (int)$_POST['accommodationID'];

// Fetch accommodation details from the database
$sql = "SELECT * FROM accommodation WHERE accommodationID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $accommodationID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: Accommodation not found.");
}

$accommodation = $result->fetch_assoc();
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

  <!-- topNav css file -->
  <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topNav.css">

  <!-- topHeader css file -->
  <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topHeader.css">

  <link rel="stylesheet" href="../../../SMMS/CSS/USER/accommodationForm.css">


  <title>Booking Accommodation Form</title>
</head>

<body>
  <!-- include topNav.php -->
  <?php include '../../INCLUDES/topHeader.php'; ?>

  <!-- include userNav.php -->
  <?php include '../../INCLUDES/userNav.php'; ?>

  <!-- accommodation details section starts here -->
  <section class="product">
    <h1 id="section-title">Accommodation Booking Form</h1>

    <!-- Display accommodation details -->
    <div class="product-container">
      <div class="card">
        <img src="<?php echo $accommodation['accommodationImg']; ?>" alt="<?php echo $accommodation['accommodationName']; ?>">
        <div>
          <h2><?php echo $accommodation['accommodationName']; ?></h2>
          <h3>RM <?php echo $accommodation['accommodationPrice']; ?> / night</h3>
          <p><?php echo $accommodation['accommodationDesc']; ?></p>

          <!-- Booking form -->
    <form method="post" action="../../MODULES/RESERVATION_MODULE/cart_action.php">
      <input type="hidden" name="accommodationID" value="<?php echo $accommodation['accommodationID']; ?>">

      <div class="form-container">
        <label for="dateFrom">Check-in Date:</label>
        <input type="date" id="dateFrom" name="dateFrom" required>
      </div>

      <div class="form-container">
        <label for="dateUntil">Check-out Date:</label>
        <input type="date" id="dateUntil" name="dateUntil" required>
      </div>

      <div class="form-container">
        <button type="submit">
          <i class="fa fa-calendar-check"></i> Proceed to Cart
        </button>
      </div>
    </form>

       <!-- Back Button -->
       <div class="back-btn-container">
            <button onclick="history.back()" class="back-btn">
              <i class="fa fa-arrow-left"></i> Back
            </button>
          </div>

        </div>
      </div>
    </div>



  </section>

  <!-- include topNav.js -->
  <script src="../../../SMMS/JS/topNav.js"></script>
</body>

</html>