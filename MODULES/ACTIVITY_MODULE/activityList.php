<?php
session_start();
// Include db config
include("../../../SMMS/CONFIG/config.php");

// Initialize variables
$activityDetails = null;
$selectedCategory = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;

// Check if a specific activity ID is provided
if (isset($_GET['id'])) {
    $activityID = intval($_GET['id']);

    // Fetch the specific activity based on the activity ID using prepared statements
    $stmt = $conn->prepare("SELECT a.activityID, a.activityName, a.activityDesc, a.activityPrice, a.activityImg, c.categoryName
                             FROM activity a
                             JOIN activity_category c ON a.activityCategory = c.categoryID
                             WHERE a.activityID = ?");
    $stmt->bind_param("i", $activityID);
    $stmt->execute();
    $activityResult = $stmt->get_result();

    if ($activityResult && $activityResult->num_rows > 0) {
        $activityDetails = $activityResult->fetch_assoc();
    } else {
        echo '<p>Activity not found.</p>';
    }
    $stmt->close();
}

// Fetch categories from the activity_category table
$categoryQuery = "SELECT * FROM activity_category";
$categoryResult = mysqli_query($conn, $categoryQuery);
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

    <!-- headerBanner css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/headerBanner.css">

    <!-- topNav css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topNav.css">

    <!-- topHeader css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topHeader.css">

    <!-- foodList css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/USER/activity.css">

    <title>Food Page</title>
  </head>
<body>

<!-- Include topNav.php -->
<?php include '../../INCLUDES/topHeader.php'; ?>

<!-- Include userNav.php -->
<?php include '../../INCLUDES/userNav.php'; ?>

<!-- Activity section starts here -->
<section class="product">
    <h1 id="section-title">ACTIVITY PORTFOLIO</h1>
    <div class="category-container">
        <a id="category-link" href="activityList.php?cat=0" class="category-link">All</a>
        <?php
        // Display categories dynamically
        if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
            while ($category = mysqli_fetch_assoc($categoryResult)) {
                echo '<a id="category-link" href="activityList.php?cat=' . $category['categoryID'] . '" class="category-link">' . htmlspecialchars($category['categoryName']) . '</a>';
            }
        } else {
            echo '<p id="no-category-link">No categories found.</p>';
        }
        ?>
    </div>

    <!-- Activity Section -->
    <div class="product-container">
        <?php
        // Check if a specific activity ID was provided
        if ($activityDetails) {
            // Construct the full image URL using BASE_URL
            $imageURL = BASE_URL . '/' . $activityDetails['activityImg'];
            ?>
            <div class="centered-card">
                <div id="product-card" class="product-card">
                    <img id="product-card-img" src="<?php echo $imageURL; ?>" alt="<?php echo htmlspecialchars($activityDetails["activityName"]); ?>">
                    <h3 id="product-card-name"><?php echo htmlspecialchars($activityDetails["activityName"]); ?></h3>
                    <p id="product-card-category">Category: <?php echo htmlspecialchars($activityDetails["categoryName"]); ?></p>
                    <p id="product-card-desc"><?php echo htmlspecialchars($activityDetails["activityDesc"]); ?></p>
                    <p id="product-card-price">RM <?php echo htmlspecialchars($activityDetails["activityPrice"]); ?></p>

                    <!-- Check if the user is logged in or not -->
                    <?php if (isset($_SESSION['UID'])): ?>
                        <form id="cart-amount-input" method="post" action="../../MODULES/RESERVATION_MODULE/activity_cart_action.php?action=add&id=<?php echo htmlspecialchars($activityDetails['activityID']); ?>">
                            <input type="hidden" name="activityID" value="<?php echo htmlspecialchars($activityDetails['activityID']); ?>">
                            <input type="hidden" name="activityName" value="<?php echo htmlspecialchars($activityDetails['activityName']); ?>">
                            <input type="hidden" name="activityPrice" value="<?php echo htmlspecialchars($activityDetails['activityPrice']); ?>">
                            <label for="pax-<?php echo htmlspecialchars($activityDetails['activityID']); ?>">Number of Pax:</label>
                            <input type="number" id="pax-<?php echo htmlspecialchars($activityDetails['activityID']); ?>" name="quantity" min="1" value="1" required>
                            <button type="submit">
                                <i class="fa fa-calendar"></i> Book Now
                            </button>
                        </form><br>

                        <form method="POST" action="../WISHLIST_MODULE/wishlist.php">
                            <input type="hidden" name="itemType" value="activity">
                            <input type="hidden" name="itemID" value="<?php echo htmlspecialchars($activityDetails['activityID']); ?>">
                            <input type="hidden" name="itemName" value="<?php echo htmlspecialchars($activityDetails['activityName']); ?>">
                            <input type="hidden" name="itemPrice" value="<?php echo htmlspecialchars($activityDetails['activityPrice']); ?>">
                            <input type="hidden" name="itemImg" value="<?php echo htmlspecialchars($activityDetails['activityImg']); ?>">
                            <button type="submit" class="wishlist-button">
                                <i class="fa fa-heart"></i> Add to Wishlist
                            </button>
                        </form>

                    <?php else: ?>
                        <h2><i>Login to book this activity.</i></h2>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        } else {
            // Build query based on selected category
            $activityQuery = "SELECT a.*, c.categoryName 
                              FROM activity a 
                              JOIN activity_category c ON a.activityCategory = c.categoryID";
            if ($selectedCategory > 0) {
                $activityQuery .= " WHERE a.activityCategory = $selectedCategory";
            }
            $activityQuery .= " ORDER BY a.activityPrice ASC";

            // Execute query
            $activityResult = mysqli_query($conn, $activityQuery);

            // Check if activities exist for the selected category
            if ($activityResult && mysqli_num_rows($activityResult) > 0) {
                while ($activity = mysqli_fetch_assoc($activityResult)) {
                    // Construct the full image URL using BASE_URL
                    $imageURL = BASE_URL . '/' . $activity['activityImg'];
                    ?>
                    <div id="product-card" class="product-card">
                        <img id="product-card-img" src="<?php echo $imageURL; ?>" alt="<?php echo htmlspecialchars($activity["activityName"]); ?>">
                        <h3 id="product-card-name"><?php echo htmlspecialchars($activity["activityName"]); ?></h3>
                        <p id="product-card-category">Category: <?php echo htmlspecialchars($activity["categoryName"]); ?></p>
                        <p id="product-card-desc"><?php echo htmlspecialchars($activity["activityDesc"]); ?></p>
                        <p id="product-card-price">RM <?php echo htmlspecialchars($activity["activityPrice"]); ?></p>

                        <!-- Check if the user is logged in or not -->
                        <?php if (isset($_SESSION['UID'])): ?>
                            <form id="cart-amount-input" method="post" action="../../MODULES/RESERVATION_MODULE/activity_cart_action.php?action=add&id=<?php echo htmlspecialchars($activity['activityID']); ?>">
                                <input type="hidden" name="activityID" value="<?php echo htmlspecialchars($activity['activityID']); ?>">
                                <input type="hidden" name="activityName" value="<?php echo htmlspecialchars($activity['activityName']); ?>">
                                <input type="hidden" name="activityPrice" value="<?php echo htmlspecialchars($activity['activityPrice']); ?>">
                                <label for="pax-<?php echo htmlspecialchars($activity['activityID']); ?>">Number of Pax:</label>
                                <input type="number" id="pax-<?php echo htmlspecialchars($activity['activityID']); ?>" name="quantity" min="1" value="1" required>
                                <button type="submit">
                                    <i class="fa fa-calendar"></i> Book Now
                                </button>
                            </form><br>

                            <!-- Add to Wishlist form for activity -->
                            <form method="POST" action="../WISHLIST_MODULE/wishlist.php">
                                <input type="hidden" name="itemType" value="activity">
                                <input type="hidden" name="itemID" value="<?php echo htmlspecialchars($activity['activityID']); ?>">
                                <input type="hidden" name="itemName" value="<?php echo htmlspecialchars($activity['activityName']); ?>">
                                <input type="hidden" name="itemPrice" value="<?php echo htmlspecialchars($activity['activityPrice']); ?>">
                                <input type="hidden" name="itemImg" value="<?php echo htmlspecialchars($activity['activityImg']); ?>">
                                <button type="submit" class="wishlist-button">
                                    <i class="fa fa-heart"></i> Add to Wishlist
                                </button>
                            </form><br>
                        <?php else: ?>
                            <h2><i>Login to book this activity.</i></h2>
                        <?php endif; ?>
                    </div>
                    <?php
                }
            } else {
                echo '<p id="product-not-found">No activities found for this category.</p>';
            }
            mysqli_free_result($activityResult);
        }
        ?>
    </div>
</section>

<!-- Include topNav.js -->
<script src="../../../SMMS/JS/topNav.js"></script>

</body>
</html>