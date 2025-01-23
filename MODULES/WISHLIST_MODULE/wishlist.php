<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include("../../../SMMS/CONFIG/config.php");

// Ensure the user is logged in
$userID = isset($_SESSION['UID']) ? $_SESSION['UID'] : null;

if (!$userID) {
    echo "Please log in to view or add items to your wishlist.";
    exit;
}

// Initialize the wishlist if it doesn't exist
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

// Handle adding item to wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['itemType']) && isset($_POST['itemID'])) {
    $itemType = $_POST['itemType'];
    $itemID = $_POST['itemID'];

    // Fetch the details of the item based on its type
    $itemDetails = [];
    if ($itemType === 'accommodation') {
        $itemSql = "SELECT * FROM accommodation WHERE accommodationID = '$itemID'";
        $itemResult = mysqli_query($conn, $itemSql);
        if ($itemResult && mysqli_num_rows($itemResult) > 0) {
            $itemDetails = mysqli_fetch_assoc($itemResult);
        }
    } elseif ($itemType === 'activity') {
        $itemSql = "SELECT * FROM activity WHERE activityID = '$itemID'";
        $itemResult = mysqli_query($conn, $itemSql);
        if ($itemResult && mysqli_num_rows($itemResult) > 0) {
            $itemDetails = mysqli_fetch_assoc($itemResult);
        }
    } elseif ($itemType === 'food') { // Handle food type
        $itemSql = "SELECT * FROM food WHERE foodID = '$itemID'";
        $itemResult = mysqli_query($conn, $itemSql);
        if ($itemResult && mysqli_num_rows($itemResult) > 0) {
            $itemDetails = mysqli_fetch_assoc($itemResult);
        }
    }

    // If the item was found, add it to the wishlist session
    if (!empty($itemDetails)) {
        $item = [
            'wishlistID' => uniqid(),  // Create a unique wishlist ID
            'itemType' => $itemType,
            'itemID' => $itemDetails['accommodationID'] ?? $itemDetails['activityID'] ?? $itemDetails['foodID'],
            'itemName' => $itemDetails['accommodationName'] ?? $itemDetails['activityName'] ?? $itemDetails['foodName'],
            'itemDesc' => $itemDetails['accommodationDesc'] ?? $itemDetails['activityDesc'] ?? $itemDetails['foodDesc'],
            'itemPrice' => $itemDetails['accommodationPrice'] ?? $itemDetails['activityPrice'] ?? $itemDetails['foodPrice'],
            'itemImg' => $itemDetails['accommodationImg'] ?? $itemDetails['activityImg'] ?? $itemDetails['foodImg']
        ];
        $_SESSION['wishlist'][] = $item;
    }

    // Redirect to wishlist to see the added item
    header("Location: wishlist.php");
    exit;
}

// Handle removing an item from wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['removeWishlistItem'])) {
    $wishlistID = $_POST['wishlistID'];

    // Remove the item from wishlist
    $_SESSION['wishlist'] = array_filter($_SESSION['wishlist'], function ($item) use ($wishlistID) {
        return $item['wishlistID'] !== $wishlistID;
    });

    // Redirect to wishlist to see the updated list
    header("Location: wishlist.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/utils.css">
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topHeader.css">
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topNav.css">
    <link rel="stylesheet" href="../../../SMMS/CSS/USER/wishlist.css">
    <title>Your Wishlist</title>
</head>
<body>
    <!-- include topNav.php -->
    <?php include '../../INCLUDES/topHeader.php'; ?>
    
    <!-- include userNav.php -->
    <?php include '../../INCLUDES/userNav.php'; ?>

    <section class="userHome">
    <h2 id="section-title">Your Wishlist</h2>
    <div class="itemsSection">
        <?php if (!empty($_SESSION['wishlist'])): ?>
            <?php foreach ($_SESSION['wishlist'] as $item): ?>
                <div class="item">
                    <!-- Removed the image tag -->
                    <h3 class="itemName"><?= htmlspecialchars($item['itemName']); ?></h3>
                    <p class="itemDesc"><?= htmlspecialchars($item['itemDesc']); ?></p>
                    <p class="itemPrice">RM<?= number_format($item['itemPrice'], 2); ?></p>
                    <form method="post" class="removeWishlistForm">
                        <input type="hidden" name="wishlistID" value="<?= $item['wishlistID']; ?>">
                        <button type="submit" name="removeWishlistItem" class="removeWishlistButton">
                            <i class="fa fa-trash"></i> Remove
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Your wishlist is empty.</p>
        <?php endif; ?>
    </div>
</section>

    <!-- JS File Inclusion -->
    <script src="../../../SMMS/JS/topNav.js"></script>
</body>
</html>