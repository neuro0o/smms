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
  $search_conditions = [];
  foreach ($keywords as $keyword) {
    if(!empty($keyword)) {
      $search_conditions[] = "foodName LIKE '%" .mysqli_real_escape_string($conn, $keyword) . "%'";
    }
  }

  // execute SQL query based on search conditions and display the results
  $sql = "SELECT * FROM food WHERE " . implode(" OR", $search_conditions);
  $result = mysqli_query($conn, $sql);

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

    <!-- userHome css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/USER/userHome.css">

    <title>SEARCH</title>
  </head>

  <body>

    <!-- include topNav.php -->
    <?php include '../../INCLUDES/topHeader.php'; ?>
    
    <!-- include userNav.php -->
    <?php include '../../INCLUDES/userNav.php'; ?>

    <!-- include headerBanner.php -->
    <?php include '../../INCLUDES/headerBanner.php';?>
    
    <section class="user-home">
      <div id="searchMessage">
        <p>SEARCH RESULT:</p>
      </div>

      <div class="searchResult-container">
        <?php
          if($result) {
            if(mysqli_num_rows($result) > 0) {
              while($row = mysqli_fetch_assoc(($result))) {
                echo "&nbsp;&nbsp;&nbsp;&nbsp;" . "<a class='searchResult' href='" . BASE_URL . "/MODULES/FOOD_MODULE/foodList.php?id=" . htmlspecialchars($row['foodID']) . "'>" . htmlspecialchars($row['foodName']) . "</a><br>";
              }
            }
            else {
              echo "<p>Sorry, no result for '" . htmlspecialchars($search_text) . "'</p>";
            }
          }
          else {
            echo "<p>Error executing query: " . mysqli_error($conn) . "</p>";
          }
        ?>
        <br><a class='homePage-link' href='<?php echo BASE_URL . "/MODULES/USER_MANAGEMENT_MODULE/userHome.php"; ?>'>Back to HOME</a>
      </div>
    </section>


  </body>
</html>