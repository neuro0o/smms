<?php
session_start();
include("../../../SMMS/CONFIG/config.php");

// Ensure the user is logged in
if (!isset($_SESSION['UID'])) {
    die("Error: You must log in to view this page.");
}

// Get the user ID
$userID = $_SESSION['UID'];

// Fetch Accommodation History
$accommodationHistoryQuery = "
SELECT 
    r.reservationID, 
    a.accommodationName, 
    r.dateFrom, 
    r.dateUntil, 
    r.totalAmt 
FROM reservation r
JOIN accommodation a ON r.accommodationID = a.accommodationID
WHERE r.reservedBy = '$userID'
ORDER BY r.dateFrom DESC";
$accommodationHistoryResult = mysqli_query($conn, $accommodationHistoryQuery);
if (!$accommodationHistoryResult) {
    die("Error fetching accommodation history: " . mysqli_error($conn));
}

// Fetch Activity History
$activityHistoryQuery = "
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
$activityHistoryResult = mysqli_query($conn, $activityHistoryQuery);
if (!$activityHistoryResult) {
    die("Error fetching activity history: " . mysqli_error($conn));
}

// Fetch Food History
$foodHistoryQuery = "
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
$foodHistoryResult = mysqli_query($conn, $foodHistoryQuery);
if (!$foodHistoryResult) {
    die("Error fetching food history: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History</title>
    <link rel="stylesheet" type="text/css" href="CSS/USER/history.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f9f9;
            color: #333;
        }

        .history-container {
            width: 80%;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #00695c;
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

        .review-button {
            background-color: #00796b;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .review-button:hover {
            background-color: #004d40;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #00796b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .back-button:hover {
            background-color: #004d40;
        }
    </style>
</head>
<body>
    <div class="history-container">
        <h1>Purchase History</h1>

        <!-- Accommodation History Section -->
        <h2>Accommodation History </h2>
        <table>
            <tr>
                <th>Reservation ID</th>
                <th>Accommodation Name</th>
                <th>Date From</th>
                <th>Date Until</th>
                <th>Total Amount</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($accommodationHistoryResult)): ?>
            <tr>
                <td><?php echo $row['reservationID']; ?></td>
                <td><?php echo $row['accommodationName']; ?></td>
                <td><?php echo $row['dateFrom']; ?></td>
                <td><?php echo $row['dateUntil']; ?></td>
                <td><?php echo $row['totalAmt']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <!-- Activity History Section -->
        <h2>Activity History</h2>
        <table>
            <tr>
                <th>Line ID</th>
                <th>Activity Name</th>
                <th>Activity Price</th>
                <th>Quantity</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($activityHistoryResult)): ?>
            <tr>
                <td><?php echo $row['lineID']; ?></td>
                <td><?php echo $row['activityName']; ?></td>
                <td><?php echo $row['activityPrice']; ?></td>
                <td><?php echo $row['activityQuantity']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <!-- Food History Section -->
        <h2>Food History</h2>
        <table>
            <tr>
                <th>Line ID</th>
                <th>Food Name</th>
                <th>Food Price</th>
                <th>Quantity</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($foodHistoryResult)): ?>
            <tr>
                <td><?php echo $row['lineID']; ?></td>
                <td><?php echo $row['foodName']; ?></td>
                <td><?php echo $row['foodPrice']; ?></td>
                <td><?php echo $row['foodQuantity']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <a href="../../MODULES/USER_MANAGEMENT_MODULE/userHome.php" class="back-button">Back to Home</a>
    </div>
</body>
</html>