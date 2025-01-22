<?php
session_start();
// Include db config
include("../../../SMMS/CONFIG/config.php");
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

  <!-- topNav css file -->
  <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topNav.css">

  <!-- topHeader css file -->
  <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topHeader.css">

  <!-- activity css file -->
  <link rel="stylesheet" href="../../../SMMS/CSS/USER/activity.css">

  <title>ACTIVITY PAGE</title>
</head>

<body>

  <!-- include topNav.php -->
  <?php include '../../INCLUDES/topHeader.php'; ?>

  <!-- include userNav.php -->
  <?php include '../../INCLUDES/userNav.php'; ?>

  <!-- activity section starts here -->
  <section class="product">
    <!-- page title -->
    <h1 id="section-title">ACTIVITY PORTFOLIO</h1>
    <div class="category-container">
      <!-- category lists -->
      <a id="category-link" href="activityList.php?cat=0" class="category-link">All</a>
      <?php
      // fetch categories from the activity_category table
      $categoryQuery = "SELECT * FROM activity_category";
      $categoryResult = mysqli_query($conn, $categoryQuery);

      // display categories dynamically
      if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
        while ($category = mysqli_fetch_assoc($categoryResult)) {
          echo '<a id="category-link" href="activityList.php?cat=' . $category['categoryID'] . '" class="category-link">' . $category['categoryName'] . '</a>';
        }
      } else {
        echo '<p id="no-category-link">No categories found.</p>';
      }
      ?>
    </div>

    <!-- activity Section -->
    <div class="product-container">
      <?php
      // fetch selected category from the URL, default to all (0)
      $selectedCategory = isset($_GET['cat']) ? (int) $_GET['cat'] : 0;

      // build query based on selected category
      $activityQuery = "SELECT a.*, c.categoryName 
                        FROM activity a 
                        JOIN activity_category c ON a.activityCategory = c.categoryID";
      if ($selectedCategory > 0) {
        $activityQuery .= " WHERE a.activityCategory = $selectedCategory";
      }
      $activityQuery .= " ORDER BY a.activityPrice ASC";

      // execute query
      $activityResult = mysqli_query($conn, $activityQuery);

      // check if activities exist for the selected category
      if ($activityResult && mysqli_num_rows($activityResult) > 0) {
        while ($activity = mysqli_fetch_assoc($activityResult)) {
          echo '<div id="product-card" class="product-card">';
          echo '<img id="product-card-img" src="' . $activity["activityImg"] . '" alt="' . $activity["activityName"] . '">';
          echo '<h3 id="product-card-name">' . $activity["activityName"] . '</h3>';
          echo '<p id="product-card-category">Category: ' . $activity["categoryName"] . '</p>';
          echo '<p id="product-card-desc">' . $activity["activityDesc"] . '</p>';
          echo '<p id="product-card-price">RM ' . $activity["activityPrice"] . '</p>';

          // check if the user is logged in or not
          if (isset($_SESSION['UID'])) {
            echo '<form id="cart-amount-input" method="post" action="../../MODULES/RESERVATION_MODULE/cart_action.php">
              <input type="hidden" name="activityID" value="' . $activity['activityID'] . '">
              <input type="hidden" name="activityName" value="' . $activity['activityName'] . '">
              <input type="hidden" name="activityPrice" value="' . $activity['activityPrice'] . '">
              <label for="pax-' . $activity['activityID'] . '">Number of Pax:</label>
              <input type="number" id="pax-' . $activity['activityID'] . '" name="pax" min="1" value="1" required>
              <button type="submit">
                <i class="fa fa-calendar"></i> Book Now
              </button>
            </form>';
          } else {
            echo '<h2><i>Login to book this activity.</i></h2>';
          }

          echo '</div>';
        }
      } else {
        echo '<p id="product-not-found">No activities found for this category.</p>';
      }

      mysqli_free_result($activityResult);
      ?>
    </div>
  </section>

  <!-- include topNav.js -->
  <script src="../../../SMMS/JS/topNav.js"></script>

</body>

</html>