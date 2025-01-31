<!DOCTYPE html>
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

    <title>FOOD CHECKOUT</title>
  </head>
</html>

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
$purchaseAmt = 0;

// Calculate the total purchase amount and validate cart items
foreach ($_SESSION["cart_item"] as $item) {
    // Check for the correct key names
    if (!isset($item["foodPrice"], $item["quantity"])) {
        die("Error: Missing product price or quantity.");
    }
    $purchaseAmt += $item["foodPrice"] * $item["quantity"];
}

// Begin transaction in database
mysqli_begin_transaction($conn);

try {
    // Insert into food_purchase table
    $stmt = $conn->prepare("INSERT INTO food_purchase (userID, purchaseDate, purchaseAmt) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $_SESSION["UID"], $purchaseDate, $purchaseAmt);
    $stmt->execute();
    $fpID = $stmt->insert_id;
    $stmt->close();

    // Insert into food_purchase_detail table
    $stmt = $conn->prepare("INSERT INTO food_purchase_detail (fpID, foodID, purchaseQty) VALUES (?, ?, ?)");

    // Array to hold line IDs
    $lineIDs = [];

    foreach ($_SESSION["cart_item"] as $item) {
        $foodID = $item["foodID"];
        $quantity = $item["quantity"];
        $stmt->bind_param("isi", $fpID, $foodID, $quantity);
        $stmt->execute();

        // Get the last inserted line IDs
        $lineIDs[] = $stmt->insert_id;
    }
    $stmt->close();

    // Commit the transaction
    mysqli_commit($conn);

    // Clear the cart
    unset($_SESSION["cart_item"]);

    // Set session notifications
    $_SESSION['notification'] = "Food purchase successful! Your Purchase ID is {$fpID}.";
    $_SESSION['notification_type'] = "success";

    // Display success message along with the purchase ID and line IDs
    echo "
    <div id='checkout-section'>
      <p id='checkout-success-message'>Purchase successful! Food Purchase ID is: {$fpID}</p>
      <p id='checkout-success-message'>Line IDs: " . implode(", ", $lineIDs) . "</p>
      <a id='shopping-redirect' href='../../MODULES/RESERVATION_MODULE/purchase_history.php'>My Purchase History</a>
      <a id='shopping-redirect' href='../../MODULES/FOOD_MODULE/foodList.php'>Continue Shopping</a>
    </div>
    ";

} catch (Exception $e) {
    // Roll back the transaction in case of an error
    mysqli_rollback($conn);
    die("Error processing purchase: " . $e->getMessage());
}

// Close the connection
mysqli_close($conn);
?>