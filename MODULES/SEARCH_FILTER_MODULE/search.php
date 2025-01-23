<?php
  session_start();
  include_once("../../CONFIG/config.php");

  // validate input search text and split into keywords using explode function
  $search_text = '';
  if (!empty($_POST["search_text"])) {
      $search_text = trim($_POST["search_text"]);
  }

  $keywords = explode(" ", $search_text);

  // initialize SQL query search conditions using an array
  $search_conditions_food = [];
  $search_conditions_accommodation = [];
  $search_conditions_activity = [];

  foreach ($keywords as $keyword) {
    if (!empty($keyword)) {
      $escaped_keyword = mysqli_real_escape_string($conn, $keyword);
      $search_conditions_food[] = "foodName LIKE '%" . $escaped_keyword . "%'";
      $search_conditions_accommodation[] = "accommodationName LIKE '%" . $escaped_keyword . "%'";
      $search_conditions_activity[] = "activityName LIKE '%" . $escaped_keyword . "%'";
    }
  }

  // execute SQL queries based on search conditions and display the results
  $sql_food = "SELECT * FROM food WHERE " . implode(" OR ", $search_conditions_food);
  $sql_accommodation = "SELECT * FROM accommodation WHERE " . implode(" OR ", $search_conditions_accommodation);
  $sql_activity = "SELECT * FROM activity WHERE " . implode(" OR ", $search_conditions_activity);

  $result_food = mysqli_query($conn, $sql_food);
  $result_accommodation = mysqli_query($conn, $sql_accommodation);
  $result_activity = mysqli_query($conn, $sql_activity);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CDN icon link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Utils CSS file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/utils.css">

    <!-- HeaderBanner CSS file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/headerBanner.css">

    <!-- TopNav CSS file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topNav.css">

    <!-- TopHeader CSS file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topHeader.css">

    <!-- UserHome CSS file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/USER/userHome.css">

    <title>SEARCH</title>
  </head>

  <body>
    <!-- include topNav.php -->
    <?php include '../../INCLUDES/topHeader.php'; ?>

    <!-- include userNav.php -->
    <?php include '../../INCLUDES/userNav.php'; ?>

    <!-- include headerBanner.php -->
    <?php include '../../INCLUDES/headerBanner.php'; ?>

    <section class="user-home">
      <div id="searchMessage">
          <p>SEARCH RESULT:</p>
      </div>

      <div class="searchResult-container">
        <?php
          // display food results
          if ($result_food) {
            if (mysqli_num_rows($result_food) > 0) {
                while ($row = mysqli_fetch_assoc($result_food)) {
                  echo "&nbsp;&nbsp;&nbsp;&nbsp;" . "<a class='searchResult' href='" . BASE_URL .
                  "/MODULES/FOOD_MODULE/foodList.php?id=" . htmlspecialchars($row['foodID']) . "'>" . htmlspecialchars($row['foodName']) . "</a><br>";
                }
            }
          }

          // display accommodation results
          if ($result_accommodation) {
            if (mysqli_num_rows($result_accommodation) > 0) {
              while ($row = mysqli_fetch_assoc($result_accommodation)) {
                echo "&nbsp;&nbsp;&nbsp;&nbsp;" . "<a class='searchResult' href='" . BASE_URL .
                "/MODULES/ACCOMMODATION_MODULE/accommodationList.php?id=" . htmlspecialchars($row['accommodationID']) . "'>" . htmlspecialchars($row['accommodationName']) . "</a><br>";
              }
            }
          }

          // display activity results
          if ($result_activity) {
            if (mysqli_num_rows($result_activity) > 0) {
              while ($row = mysqli_fetch_assoc($result_activity)) {
                  echo "&nbsp;&nbsp;&nbsp;&nbsp;" . "<a class='searchResult' href='" . BASE_URL .
                  "/MODULES/ACTIVITY_MODULE/activityList.php?id=" . htmlspecialchars($row['activityID']) . "'>" . htmlspecialchars($row['activityName']) . "</a><br>";
              }
            }
          }

          // if no results found in any category
          if ( !mysqli_num_rows($result_food) && !mysqli_num_rows($result_accommodation) && !mysqli_num_rows($result_activity)) {
            echo "<p>Sorry, no results for '" . htmlspecialchars($search_text) . "'</p>";
          }
        ?>
        <br><a class='homePage-link' href='<?php echo BASE_URL . "/MODULES/USER_MANAGEMENT_MODULE/userHome.php"; ?>'>Back to HOME</a>
      </div>
    </section>
  </body>
</html>