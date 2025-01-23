
<?php
session_start();

// Include database configuration
include("../../../SMMS/CONFIG/config.php");

// Check if the user is logged in
if (!isset($_SESSION['UID'])) {
    die("Error: You must log in to view your cart.");
}

$userID = $_SESSION['UID'];

//Handle Accommodation Section /
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accommodationID'])) {
    $accommodationID = (int)$_POST['accommodationID'];
    $dateFrom = mysqli_real_escape_string($conn, $_POST['dateFrom']);
    $dateUntil = mysqli_real_escape_string($conn, $_POST['dateUntil']);

    // Calculate total amount (you may need additional logic to get the price)
    $queryPrice = "SELECT accommodationPrice FROM accommodation WHERE accommodationID = '$accommodationID'";
    $resultPrice = mysqli_query($conn, $queryPrice);
    $rowPrice = mysqli_fetch_assoc($resultPrice);
    $totalAmt = (strtotime($dateUntil) - strtotime($dateFrom)) / 86400 * $rowPrice['accommodationPrice'];

    $insertAccommodation = "
        INSERT INTO reservation (dateFrom, dateUntil, totalAmt, reservedBy, accommodationID, reservationStatus)
        VALUES ('$dateFrom', '$dateUntil', '$totalAmt', '$userID', '$accommodationID', 3)"; // 3: Pending

    if (!mysqli_query($conn, $insertAccommodation)) {
        die("Error: " . mysqli_error($conn));
    }
}

//Handle Activity Section /
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['activityID'])) {
    $activityID = (int)$_POST['activityID'];
    $activityName = mysqli_real_escape_string($conn, $_POST['activityName']);
    $activityPrice = (float)$_POST['activityPrice'];
    $pax = (int)$_POST['pax'];

    // Insert into activity_purchase table (if not exists for user)
    $checkPurchaseQuery = "SELECT apID FROM activity_purchase WHERE userID = '$userID'";
    $checkPurchaseResult = mysqli_query($conn, $checkPurchaseQuery);

    if ($checkPurchaseResult && mysqli_num_rows($checkPurchaseResult) > 0) {
        $purchaseRow = mysqli_fetch_assoc($checkPurchaseResult);
        $apID = $purchaseRow['apID'];
    } else {
        $insertPurchaseQuery = "INSERT INTO activity_purchase (userID, purchaseDate) VALUES ('$userID', NOW())";
        if (mysqli_query($conn, $insertPurchaseQuery)) {
            $apID = mysqli_insert_id($conn);
        } else {
            die("Error: " . mysqli_error($conn));
        }
    }

    // Insert into activity_purchase_detail
    $insertDetailQuery = "
        INSERT INTO activity_purchase_detail (apID, activityID, purchaseQty)
        VALUES ('$apID', '$activityID', '$pax')";
    if (!mysqli_query($conn, $insertDetailQuery)) {
        die("Error: " . mysqli_error($conn));
    }
}

//Handle Removal Requests /
if (isset($_GET['remove']) && isset($_GET['type'])) {
    $id = (int)$_GET['remove'];
    $type = $_GET['type'];

    if ($type === 'accommodation') {
        $deleteQuery = "DELETE FROM reservation WHERE reservationID = '$id' AND reservedBy = '$userID'";
    } elseif ($type === 'activity') {
        $deleteQuery = "DELETE FROM activity_purchase_detail WHERE lineID = '$id' AND apID IN (
            SELECT apID FROM activity_purchase WHERE userID = '$userID'
        )";
    } elseif ($type === 'food') {
        $deleteQuery = "DELETE FROM food_purchase_detail WHERE lineID = '$id' AND fpID IN (
            SELECT fpID FROM food_purchase WHERE userID = '$userID'
        )";
    }

    if (!mysqli_query($conn, $deleteQuery)) {
        die("Error: " . mysqli_error($conn));
    } else {
        header("Location: cart_action.php");
        exit;
    }
}

//Fetch Data for Each Section /

// Accommodations
$accommodationQuery = "
SELECT r.reservationID, r.dateFrom, r.dateUntil, r.totalAmt, r.reservationStatus, a.accommodationName, a.accommodationPrice
FROM reservation r
JOIN accommodation a ON r.accommodationID = a.accommodationID
WHERE r.reservedBy = '$userID'
ORDER BY r.dateFrom DESC";
$accommodationResult = mysqli_query($conn, $accommodationQuery);


// Activities
$activityQuery = "
SELECT apd.lineID, ap.purchaseDate, apd.purchaseQty, act.activityName, act.activityPrice
FROM activity_purchase ap
LEFT JOIN activity_purchase_detail apd ON ap.apID = apd.apID
LEFT JOIN activity act ON apd.activityID = act.activityID
WHERE ap.userID = '$userID'
ORDER BY ap.purchaseDate DESC";
$activityResult = mysqli_query($conn, $activityQuery);

// Food
$foodQuery = "
SELECT fpd.lineID, fp.purchaseDate, fpd.purchaseQty, f.foodName, f.foodPrice
FROM food_purchase fp
LEFT JOIN food_purchase_detail fpd ON fp.fpID = fpd.fpID
LEFT JOIN food f ON fpd.foodID = f.foodID
WHERE fp.userID = '$userID'
ORDER BY fp.purchaseDate DESC";
$foodResult = mysqli_query($conn, $foodQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/utils.css">
    <link rel="stylesheet" href="../../../SMMS/CSS/USER/cart.css">
    <title>Your Cart</title>
</head>
<body>
    <section class="cart">
        <h1>Your Cart</h1>

        <!-- Accommodations Section -->
        <h2>Accommodations</h2>
        <table>
            <thead>
                <tr>
                    <th>Accommodation</th>
                    <th>Check-In</th>
                    <th>Check-Out</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($accommodationResult)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['accommodationName']); ?></td>
                        <td><?= htmlspecialchars($row['dateFrom']); ?></td>
                        <td><?= htmlspecialchars($row['dateUntil']); ?></td>
                        <td>RM <?= number_format($row['totalAmt'], 2); ?></td>
                        <td><?= $row['reservationStatus'] == 3 ? 'Pending' : ($row['reservationStatus'] == 1 ? 'Confirmed' : 'Cancelled'); ?></td>
                        <td><a href="cart_action.php?remove=<?= $row['reservationID']; ?>&type=accommodation" class="button delete-button">Remove</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="../../MODULES/ACCOMMODATION_MODULE/accommodationList.php" class="continue-button">Continue Shopping</a>

        <!-- Activities Section -->
        <h2>Activities</h2>
        <table>
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>Price per Pax</th>
                    <th>Number of Pax</th>
                    <th>Total Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($activityResult)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['activityName']); ?></td>
                        <td>RM <?= number_format($row['activityPrice'], 2); ?></td>
                        <td><?= $row['purchaseQty']; ?></td>
                        <td>RM <?= number_format($row['activityPrice'] * $row['purchaseQty'], 2); ?></td>
                        <td><a href="cart_action.php?remove=<?= $row['lineID']; ?>&type=activity" class="button delete-button">Remove</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="../../MODULES/ACTIVITY_MODULE/activityList.php" class="continue-button">Continue Shopping</a>

<!-- Food Section -->
        <h2>Food</h2>
        <table>
            <thead>
                <tr>
                    <th>Food</th>
                    <th>Price per Item</th>
                    <th>Quantity</th>
                    <th>Total Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($foodResult)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['foodName']); ?></td>
                        <td>RM <?= number_format($row['foodPrice'], 2); ?></td>
                        <td><?= $row['purchaseQty']; ?></td>
                        <td>RM <?= number_format($row['foodPrice'] * $row['purchaseQty'], 2); ?></td>
                        <td><a href="cart_action.php?remove=<?= $row['lineID']; ?>&type=food" class="button delete-button">Remove</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="../../MODULES/FOOD_MODULE/foodList.php" class="continue-button">Continue Shopping</a>

        <!-- Checkout Button -->
        <a href="../../MODULES/RESERVATION_MODULE/receipt.php" class="checkout-button">Proceed to Checkout</a>
    </section>
</body>
</html>