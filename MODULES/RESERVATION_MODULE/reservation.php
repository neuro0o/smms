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

    <!-- Link to external CSS (reservation.css) -->
    <link rel="stylesheet" href="../../../SMMS/CSS/USER/reservation.css">

    <!-- cdn icon link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- utils css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/utils.css">

    <!-- topNav css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topNav.css">

    <!-- topHeader css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topHeader.css">

    <title>Purchase History</title>
</head>

<body>

    <!-- include topNav.php -->
    <?php include '../../INCLUDES/topHeader.php'; ?>

    <!-- include userNav.php -->
    <?php include '../../INCLUDES/userNav.php'; ?>

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
                                <?php
                                $accommodationReviewQuery = "
                                    SELECT reviewID 
                                    FROM review_reservation
                                    WHERE reviewedBy = '$userID' 
                                    AND accommodationID = '{$row['accommodationID']}' 
                                    AND reservationID = '{$row['reservationID']}'
                                ";
                                $accommodationReviewResult = mysqli_query($conn, $accommodationReviewQuery);
                                if (mysqli_num_rows($accommodationReviewResult) > 0): ?>
                                    <span class="button complete">Complete</span>
                                <?php else: ?>
                                    <a href="../REVIEW_MANAGEMENT_MODULE/review.php?type=accommodation&id=<?php echo $row['accommodationID']; ?>"
                                        class="button">Review</a>
                                <?php endif; ?>
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
                                <?php
                                $foodReviewQuery = "
                                    SELECT reviewID 
                                    FROM review_food 
                                    WHERE reviewedBy = '$userID' 
                                    AND foodID = '{$row['foodID']}' 
                                    AND fpID = '{$row['reservationID']}'
                                ";
                                $foodReviewResult = mysqli_query($conn, $foodReviewQuery);
                                if (mysqli_num_rows($foodReviewResult) > 0): ?>
                                    <span class="button complete">Complete</span>
                                <?php else: ?>
                                    <a href="../REVIEW_MANAGEMENT_MODULE/review.php?type=food&id=<?php echo $row['foodID']; ?>"
                                        class="button">Review</a>
                                <?php endif; ?>
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
                                <?php
                                // Correct query to check if a review for the activity exists
                                $activityReviewQuery = "
                            SELECT reviewID 
                            FROM review_activity 
                            WHERE reviewedBy = '$userID' 
                            AND activityID = '{$row['activityID']}' 
                            AND apID = '{$row['reservationID']}'
                        ";
                                $activityReviewResult = mysqli_query($conn, $activityReviewQuery);

                                if (!$activityReviewResult) {
                                    // Debugging in case of query error
                                    echo "Error: " . mysqli_error($conn);
                                }

                                // Check if the query returned any rows
                                if (mysqli_num_rows($activityReviewResult) > 0): ?>
                                    <span class="button complete">Complete</span>
                                <?php else: ?>
                                    <a href="../REVIEW_MANAGEMENT_MODULE/review.php?type=activity&id=<?php echo $row['activityID']; ?>"
                                        class="button">Review</a>
                                <?php endif; ?>
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