<?php
session_start();
include("../../../SMMS/CONFIG/config.php");

// Check if user is logged in
if (!isset($_SESSION['UID'])) {
    die("Error: You must log in to view your purchase history.");
}

// Get the logged-in user's ID
$userID = $_SESSION['UID'];

// Query to fetch reservation and purchase details
$query = "
SELECT 
    r.reservationID, r.dateFrom, r.dateUntil, r.totalAmt,
    a.accommodationName, a.accommodationID, 
    f.foodName, f.foodID, fpd.purchaseQty AS foodQuantity,
    act.activityName, act.activityID, apd.purchaseQty AS activityQuantity
FROM reservation r
LEFT JOIN accommodation a ON r.accommodationID = a.accommodationID
LEFT JOIN food_purchase fp ON fp.userID = r.reservedBy
LEFT JOIN food_purchase_detail fpd ON fp.fpID = fpd.fpID
LEFT JOIN food f ON fpd.foodID = f.foodID
LEFT JOIN activity_purchase ap ON ap.userID = r.reservedBy
LEFT JOIN activity_purchase_detail apd ON ap.apID = apd.apID
LEFT JOIN activity act ON apd.activityID = act.activityID
WHERE r.reservedBy = '$userID'
ORDER BY r.dateFrom DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #007bff;
            color: #fff;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        .button {
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Purchase History</h1>
        <h2>Accommodations</h2>
        <table>
            <thead>
                <tr>
                    <th>Reservation ID</th>
                    <th>Accommodation Name</th>
                    <th>Date From</th>
                    <th>Date Until</th>
                    <th>Total Amount</th>
                    <th>Review</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                mysqli_data_seek($result, 0);
                $accommodationFound = false;
                while ($row = mysqli_fetch_assoc($result)):
                    if ($row['accommodationName']):
                        $accommodationFound = true; ?>
                        <tr>
                            <td><?php echo $row['reservationID']; ?></td>
                            <td><?php echo $row['accommodationName']; ?></td>
                            <td><?php echo $row['dateFrom']; ?></td>
                            <td><?php echo $row['dateUntil']; ?></td>
                            <td>RM <?php echo number_format($row['totalAmt'], 2); ?></td>
                            <td>
                                <a href="../REVIEW_MANAGEMENT_MODULE/review.php?type=accommodation&id=<?php echo $row['accommodationID']; ?>" class="button">Review</a>
                            </td>
                        </tr>
                    <?php endif;
                endwhile; ?>
                <?php if (!$accommodationFound): ?>
                    <tr>
                        <td colspan="6">No accommodations found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2>Foods</h2>
        <table>
            <thead>
                <tr>
                    <th>Reservation ID</th>
                    <th>Food Name</th>
                    <th>Quantity</th>
                    <th>Review</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                mysqli_data_seek($result, 0);
                $foodFound = false;
                while ($row = mysqli_fetch_assoc($result)):
                    if ($row['foodName']):
                        $foodFound = true; ?>
                        <tr>
                            <td><?php echo $row['reservationID']; ?></td>
                            <td><?php echo $row['foodName']; ?></td>
                            <td><?php echo $row['foodQuantity']; ?></td>
                            <td>
                                <a href="../REVIEW_MANAGEMENT_MODULE/review.php?type=food&id=<?php echo $row['foodID']; ?>" class="button">Review</a>
                            </td>
                        </tr>
                    <?php endif;
                endwhile; ?>
                <?php if (!$foodFound): ?>
                    <tr>
                        <td colspan="4">No food items found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2>Activities</h2>
        <table>
            <thead>
                <tr>
                    <th>Reservation ID</th>
                    <th>Activity Name</th>
                    <th>Quantity</th>
                    <th>Review</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                mysqli_data_seek($result, 0);
                $activityFound = false;
                while ($row = mysqli_fetch_assoc($result)):
                    if ($row['activityName']):
                        $activityFound = true; ?>
                        <tr>
                            <td><?php echo $row['reservationID']; ?></td>
                            <td><?php echo $row['activityName']; ?></td>
                            <td><?php echo $row['activityQuantity']; ?></td>
                            <td>
                                <a href="../REVIEW_MANAGEMENT_MODULE/review.php?type=activity&id=<?php echo $row['activityID']; ?>" class="button">Review</a>
                            </td>
                        </tr>
                    <?php endif;
                endwhile; ?>
                <?php if (!$activityFound): ?>
                    <tr>
                        <td colspan="4">No activities found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
