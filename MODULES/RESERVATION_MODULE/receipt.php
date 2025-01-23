<?php
// Start session
session_start();

// Include database configuration
include("../../../SMMS/CONFIG/config.php");

// Ensure the user is logged in
if (!isset($_SESSION['UID'])) {
    die("Error: You must log in to view this page.");
}

// Get the user ID
$userID = $_SESSION['UID'];

// Initialize totals
$totalAccommodation = 0;
$totalActivities = 0;
$totalFood = 0;

// Fetch Accommodation Data
$accommodationQuery = "
SELECT 
    r.reservationID, 
    a.accommodationName, 
    r.dateFrom, 
    r.dateUntil, 
    r.totalAmt 
FROM reservation r
JOIN accommodation a ON r.accommodationID = a.accommodationID
WHERE r.reservedBy = '$userID' AND r.reservationStatus = 3
ORDER BY r.dateFrom DESC";
$accommodationResult = mysqli_query($conn, $accommodationQuery);
$accommodationData = [];
while ($row = mysqli_fetch_assoc($accommodationResult)) {
    $accommodationData[] = $row;
    $totalAccommodation += $row['totalAmt'];
}

// Fetch Activity Data
$activityQuery = "
SELECT 
    apd.lineID, 
    act.activityName, 
    act.activityPrice, 
    apd.purchaseQty AS activityQuantity
FROM activity_purchase ap
JOIN activity_purchase_detail apd ON ap.apID = apd.apID
JOIN activity act ON apd.activityID = act.activityID
WHERE ap.userID = '$userID'
ORDER BY ap.purchaseDate DESC";
$activityResult = mysqli_query($conn, $activityQuery);
$activityData = [];
while ($row = mysqli_fetch_assoc($activityResult)) {
    $activityData[] = $row;
    $totalActivities += $row['activityPrice'] * $row['activityQuantity'];
}

// Fetch Food Data
$foodQuery = "
SELECT 
    fpd.lineID, 
    f.foodName, 
    f.foodPrice, 
    fpd.purchaseQty AS foodQuantity
FROM food_purchase fp
JOIN food_purchase_detail fpd ON fp.fpID = fpd.fpID
JOIN food f ON fpd.foodID = f.foodID
WHERE fp.userID = '$userID'
ORDER BY fp.purchaseDate DESC";
$foodResult = mysqli_query($conn, $foodQuery);
$foodData = [];
while ($row = mysqli_fetch_assoc($foodResult)) {
    $foodData[] = $row;
    $totalFood += $row['foodPrice'] * $row['foodQuantity'];
}

// Calculate grand total
$grandTotal = $totalAccommodation + $totalActivities + $totalFood;

// Clear the cart after generating the receipt
$clearCartQueries = [
    "DELETE FROM reservation WHERE reservedBy = '$userID' AND reservationStatus = 3",
    "DELETE FROM activity_purchase_detail WHERE apID IN (
        SELECT apID FROM activity_purchase WHERE userID = '$userID'
    )",
    "DELETE FROM activity_purchase WHERE userID = '$userID'",
    "DELETE FROM food_purchase_detail WHERE fpID IN (
        SELECT fpID FROM food_purchase WHERE userID = '$userID'
    )",
    "DELETE FROM food_purchase WHERE userID = '$userID'"
];

foreach ($clearCartQueries as $query) {
    if (!mysqli_query($conn, $query)) {
        die("Error clearing cart data: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link rel="stylesheet" type="text/css" href="CSS/USER/receipt.css">
    <style>
        /* Teal Color Theme */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f9f9;
            color: #333;
        }

        .receipt-container {
            width: 80%;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .receipt-header h1 {
            color: #00695c;
            font-size: 2em;
        }

        .receipt-header p {
            color: #00796b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            border: 1px solid #00796b;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #004d40;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #e0f2f1;
        }

        .grand-total {
            font-size: 1.2em;
            font-weight: bold;
            color: #004d40;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .button-container a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #00796b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .button-container a:hover {
            background-color: #004d40;
        }

        .button-container a:active {
            background-color: #00332e;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1>Receipt</h1>
            <p>Thank you for your purchase. Below are the details:</p>
        </div>

        <!-- Accommodation Section -->
        <?php if (!empty($accommodationData)): ?>
            <h2>Accommodation</h2>
            <table>
                <thead>
                    <tr>
                        <th>Reservation ID</th>
                        <th>Accommodation</th>
                        <th>Check-In</th>
                        <th>Check-Out</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($accommodationData as $row): ?>
                        <tr>
                            <td><?= $row['reservationID']; ?></td>
                            <td><?= $row['accommodationName']; ?></td>
                            <td><?= $row['dateFrom']; ?></td>
                            <td><?= $row['dateUntil']; ?></td>
                            <td>RM <?= number_format($row['totalAmt'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Activity Section -->
        <?php if (!empty($activityData)): ?>
            <h2>Activities</h2>
            <table>
                <thead>
                    <tr>
                        <th>Activity</th>
                        <th>Price per Pax</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activityData as $row): ?>
                        <tr>
                            <td><?= $row['activityName']; ?></td>
                            <td>RM <?= number_format($row['activityPrice'], 2); ?></td>
                            <td><?= $row['activityQuantity']; ?></td>
                            <td>RM <?= number_format($row['activityPrice'] * $row['activityQuantity'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Food Section -->
        <?php if (!empty($foodData)): ?>
            <h2>Food</h2>
            <table>
                <thead>
                    <tr>
                        <th>Food</th>
                        <th>Price per Item</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($foodData as $row): ?>
                        <tr>
                            <td><?= $row['foodName']; ?></td>
                            <td>RM <?= number_format($row['foodPrice'], 2); ?></td>
                            <td><?= $row['foodQuantity']; ?></td>
                            <td>RM <?= number_format($row['foodPrice'] * $row['foodQuantity'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Grand Total -->
        <div class="grand-total">
            <p><strong>Grand Total: RM <?= number_format($grandTotal, 2); ?></strong></p>
        </div>

        <!-- Button for history -->
        <div class="button-container">
            <a href="reservation.php" class="button">View History</a>
        </div>
    </div>
</body>
</html>
