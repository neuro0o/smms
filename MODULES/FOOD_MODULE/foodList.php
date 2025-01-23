<?php
  session_start();
  include("../../../SMMS/CONFIG/config.php");

  // Fetch food categories from the database
  $categorySql = "SELECT * FROM food_category";
  $categoryResult = mysqli_query($conn, $categorySql);

  // Get the categoryID from the URL, if it exists
  $categoryID = isset($_GET['categoryID']) ? intval($_GET['categoryID']) : null;

  // Build the SQL query for food items
  if ($categoryID) {
      // Fetch food items for the selected category
      $sql_food = "SELECT f.foodID, f.foodName, f.foodDesc, f.foodPrice, f.foodImg, c.categoryName
                   FROM food f
                   JOIN food_category c ON f.foodCategory = c.categoryID
                   WHERE f.foodCategory = $categoryID";
  } else {
      // Fetch all food items
      $sql_food = "SELECT f.foodID, f.foodName, f.foodDesc, f.foodPrice, f.foodImg, c.categoryName
                   FROM food f
                   JOIN food_category c ON f.foodCategory = c.categoryID";
  }

  // Execute the food query
  $result = mysqli_query($conn, $sql_food);

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
    <link rel="stylesheet" href="../../../SMMS/CSS/USER/foodList.css">

    <title>Food Page</title>
  </head>
  <body>

    <!-- include topNav.php -->
    <?php include '../../INCLUDES/topHeader.php'; ?>
    
    <!-- include userNav.php -->
    <?php include '../../INCLUDES/userNav.php'; ?>

    <!-- include headerBanner.php -->
    <?php include '../../INCLUDES/headerBanner.php';?>

    <section class="userHome">
    <h2>Food Categories</h2>
    <div class="foodCategorySection">
        <?php while ($categoryRow = mysqli_fetch_assoc($categoryResult)): ?>
            <div class="foodCategoryItem">
                <a href="foodList.php?categoryID=<?= $categoryRow['categoryID']; ?>" class="categoryLink">
                    <?= $categoryRow['categoryName']; ?>
                </a>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Food Items List Section -->
    <div class="foodList">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="foodItem">
                <img src="../../../SMMS/images/food/<?= $row['foodImg']; ?>" alt="<?= $row['foodName']; ?>" class="foodImg">
                <h3>
                    <a href="javascript:void(0);" onclick="openFoodPopup(<?= htmlspecialchars(json_encode($row)); ?>)" class="food-name">
                        <?= $row['foodName']; ?>
                    </a>
                </h3>
                <p class="foodCategory"><?= $row['categoryName']; ?></p>
                <p class="foodPrice">RM<?= number_format($row['foodPrice'], 2); ?></p>
            </div>
        <?php endwhile; ?>
    </div>
  </section>

    <!-- Popup Structure -->
    <div class="food-popup" id="food-popup" style="display: none;">
    <span class="food-popup-close-btn" onclick="closeFoodPopup()">&times;</span>
      <div class="food-popup-content">
        <img id="food-popup-img" src="" alt="Food Image" />
        <h2 id="food-popup-name"></h2>
        <p><strong>Category:</strong> <span id="food-popup-category"></span></p>
        <p id="food-popup-desc"></p>
        <p ><strong>Price:</strong> RM <span id="food-popup-price"></span></p>
        <form method="post" id="food-popup-cart-form" action="">
          <h3 label for="food-popup-quantity">Quantity:</h3></label>
          <input type="number" id="food-popup-quantity" name="quantity" value="1" min="1" max="999" position: center; required />
          <form action="cart_action.php" method="post">
            <input type="hidden" name="foodID" value="<?php echo $foodID; ?>"> 
            <button type="submit" name="add_to_cart">
              <i class="fa fa-shopping-cart"></i> Add to Cart
            </button>
          </form>
        </form>
      </div>
    </div>

    <!-- Overlay -->
    <div class="food-popup-overlay" id="food-popup-overlay" style="display: none;"></div>

    <!-- JS File Inclusion -->
    <script src="../../../SMMS/JS/topNav.js"></script>
    <script src="../../../SMMS/JS/foodPopup.js"></script>
  </body>
</html>