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

// Initialize total amounts
$totalAccommodation = 0;
$totalActivities = 0;
$totalFood = 0;

// Query to fetch the purchase details
$query = "
    SELECT 
        r.reservationID, 
        a.accommodationName, 
        f.foodName, 
        fpd.purchaseQty AS foodQuantity, 
        act.activityName, 
        apd.purchaseQty AS activityQuantity, 
        r.totalAmt 
    FROM reservation r
    LEFT JOIN accommodation a ON r.accommodationID = a.accommodationID
    LEFT JOIN food_purchase fp ON fp.userID = '$userID'
    LEFT JOIN food_purchase_detail fpd ON fp.fpID = fpd.fpID
    LEFT JOIN food f ON fpd.foodID = f.foodID
    LEFT JOIN activity_purchase ap ON ap.userID = '$userID'
    LEFT JOIN activity_purchase_detail apd ON ap.apID = apd.apID
    LEFT JOIN activity act ON apd.activityID = act.activityID
    WHERE r.reservedBy = '$userID'
";

$result = mysqli_query($conn, $query);

// Check for query errors
if (!$result) {
    die("Error fetching receipt details: " . mysqli_error($conn));
}

// Calculate totals for accommodation, activities, and food
while ($row = mysqli_fetch_assoc($result)) {
    if (!empty($row['totalAmt'])) {
        $totalAccommodation += $row['totalAmt'];
    }
    if (!empty($row['activityQuantity']) && !empty($row['activityName'])) {
        $activityPriceQuery = "SELECT activityPrice FROM activity WHERE activityName = '{$row['activityName']}'";
        $activityResult = mysqli_query($conn, $activityPriceQuery);
        $activityPrice = mysqli_fetch_assoc($activityResult)['activityPrice'];
        $totalActivities += $activityPrice * $row['activityQuantity'];
    }
    if (!empty($row['foodQuantity']) && !empty($row['foodName'])) {
        $foodPriceQuery = "SELECT foodPrice FROM food WHERE foodName = '{$row['foodName']}'";
        $foodResult = mysqli_query($conn, $foodPriceQuery);
        $foodPrice = mysqli_fetch_assoc($foodResult)['foodPrice'];
        $totalFood += $foodPrice * $row['foodQuantity'];
    }
}

// Calculate grand total
$grandTotal = $totalAccommodation + $totalActivities + $totalFood;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e6f7f5; /* Light teal background */
            margin: 0;
            padding: 0;
        }
        .receipt-container {
            width: 60%;
            margin: 30px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .receipt-header {
            text-align: center;
            color: #008080; /* Teal color */
            margin-bottom: 20px;
        }
        .receipt-header h1 {
            font-size: 24px;
            margin: 0;
        }
        .receipt-details {
            font-size: 16px;
            margin-bottom: 20px;
            color: #333333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #008080; /* Teal header background */
            color: #ffffff;
            text-align: center;
        }
        table td {
            text-align: center;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2; /* Light grey */
        }
        .total-row {
            font-weight: bold;
            background-color: #e0f2f1; /* Light teal */
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        .button {
            padding: 10px 20px;
            background-color: #008080; /* Teal button background */
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #006666; /* Darker teal */
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1>Receipt</h1>
            <p>Thank you for your purchase. Below are the details:</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Reservation ID</th>
                    <th>Accommodation</th>
                    <th>Food</th>
                    <th>Quantity</th>
                    <th>Activity</th>
                    <th>Quantity</th>
                    <th>Grand Total</th> <!-- Replaced Total Amount with Grand Total -->
                </tr>
            </thead>
            <tbody>
                <?php 
                // Fetch and display the data
                mysqli_data_seek($result, 0); // Reset result pointer
                if (mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['reservationID'] ?: '-'; ?></td>
                            <td><?php echo $row['accommodationName'] ?: '-'; ?></td>
                            <td><?php echo $row['foodName'] ?: '-'; ?></td>
                            <td><?php echo $row['foodQuantity'] ?: '-'; ?></td>
                            <td><?php echo $row['activityName'] ?: '-'; ?></td>
                            <td><?php echo $row['activityQuantity'] ?: '-'; ?></td>
                            <td>RM <?php echo number_format($grandTotal, 2); ?></td> <!-- Display Grand Total -->
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No purchases found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="total-row">
            <p><strong>Grand Total: RM <?php echo number_format($grandTotal, 2); ?></strong></p>
        </div>
        <div class="button-container">
            <a href="reservation.php" class="button">View History</a>
        </div>
    </div>
</body>
</html>