]<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- cdn icon link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- utils css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/utils.css">

    <!-- checkout css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/USER/checkout.css">

    <title>ACCOMMODATION CHECKOUT</title>
</head>
<body>

<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("../../CONFIG/config.php");

// Ensure the user is logged in
if (!isset($_SESSION["UID"])) {
    die("Error: User not logged in.");
}

// Ensure the cart is not empty
if (empty($_SESSION["cart_item"])) {
    die("Error: No items in the cart.");
}

// Initialize variables
$purchaseDate = date('Y-m-d');  // Current date for purchase
$totalAmt = 0;

// Calculate the total purchase amount and validate cart items
foreach ($_SESSION["cart_item"] as $item) {
    // Check for the correct key names
    if (!isset($item["accommodationPrice"])) {
        die("Error: Missing accommodation price.");
    }
    // Assuming the price is per night and you want to calculate based on the number of nights
    $dateFrom = new DateTime($item["dateFrom"]);
    $dateUntil = new DateTime($item["dateUntil"]);
    $numberOfNights = $dateUntil->diff($dateFrom)->days;

    if ($numberOfNights <= 0) {
        die("Error: Invalid check-in and check-out dates.");
    }

    $totalAmt += $item["accommodationPrice"] * $numberOfNights; // Calculate total amount
}

// Begin transaction in database
mysqli_begin_transaction($conn);

try {
    // Insert into reservation table
    $stmt = $conn->prepare("INSERT INTO reservation (dateFrom, dateUntil, totalAmt, reservedBy, accommodationID, reservationStatus) VALUES (?, ?, ?, ?, ?, ?)");
    
    // Array to hold line IDs
    $lineIDs = [];

    foreach ($_SESSION["cart_item"] as $item) {
        $accommodationID = $item["accommodationID"];
        $checkInDate = $item["dateFrom"];
        $checkOutDate = $item["dateUntil"];
        $reservationStatus = '1'; // Set the initial status of the reservation

        // Bind parameters and execute for each accommodation
        $stmt->bind_param("ssdisi", $checkInDate, $checkOutDate, $totalAmt, $_SESSION["UID"], $accommodationID, $reservationStatus);
        $stmt->execute();

        // Get the last inserted reservation ID
        $lineIDs[] = $stmt->insert_id;
    }
    $stmt->close();

    // Commit the transaction
    mysqli_commit($conn);

    // Clear the cart
    unset($_SESSION["cart_item"]);

    // Set session notifications
    $_SESSION['notification'] = "Accommodation booking is successful on {$purchaseDate}! Your Booking ID: " . implode(", ", $lineIDs) . ".";
    $_SESSION['notification_type'] = "success";


    // Display success message along with the reservation ID(s)
    echo "
    <div id='checkout-section'>
      <p id='checkout-success-message'>Reservation successful! Reservation ID(s): " . implode(", ", $lineIDs) . "</p>
      <a id='shopping-redirect' href='../../MODULES/RESERVATION_MODULE/purchase_history.php'>My Reservation History</a>
      <a id='shopping-redirect' href='../../MODULES/ACCOMMODATION_MODULE/accommodationList.php'>Continue Shopping</a>
    </div>
    ";

} catch (Exception $e) {
    // Roll back the transaction in case of an error
    mysqli_rollback($conn);
    die("Error processing reservation: " . $e->getMessage());
}

// Close the connection
mysqli_close($conn);
?>

</body>
</html>