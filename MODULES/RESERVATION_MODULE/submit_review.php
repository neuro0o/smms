<?php
  session_start();
  include("../../../SMMS/CONFIG/config.php");

  // Ensure the user is logged in
  if (!isset($_SESSION['UID'])) {
      die("Error: You must log in to leave a review.");
  }

  // Get the user ID
  $userID = $_SESSION['UID'];

  // Get the type and ID from the URL
  $type = isset($_GET['type']) ? $_GET['type'] : '';
  $itemID = isset($_GET['id']) ? $_GET['id'] : '';

  // Check if type and ID are valid
  if (empty($type) || empty($itemID)) {
      die("Error: Invalid review request.");
  }

  // Initialize variables for form
  $reviewText = '';
  $reviewRating = 0;
  $successMessage = '';

  // Handle form submission
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Get form data
      $reviewText = mysqli_real_escape_string($conn, $_POST['reviewText']);
      $reviewRating = intval($_POST['reviewRating']);
      
      // Validate rating
      if ($reviewRating < 1 || $reviewRating > 5) {
          die("Error: Rating must be between 1 and 5.");
      }
      
      // Get the current date
      $reviewDate = date('Y-m-d H:i:s');
      
      // Insert review based on type
      if ($type == 'activity') {
        $insertQuery = "
        INSERT INTO review_activity (reviewText, reviewRating, reviewDate, reviewedBy, activityID, apID)
        VALUES ('$reviewText', '$reviewRating', '$reviewDate', '$userID', 
                (SELECT activityID FROM activity_purchase_detail WHERE apID = (SELECT apID FROM activity_purchase WHERE userID = '$userID' AND apID = '$itemID' LIMIT 1)), 
                '$itemID'
        )";
      } elseif ($type == 'food') {
          $insertQuery = "
          INSERT INTO review_food (reviewText, reviewRating, reviewDate, reviewedBy, foodID, fpID)
          VALUES ('$reviewText', '$reviewRating', '$reviewDate', '$userID', 
                (SELECT foodID FROM food_purchase_detail WHERE fpID = (SELECT fpID FROM food_purchase WHERE userID = '$userID' AND fpID = '$itemID' LIMIT 1)), 
                '$itemID'
        )";
      } elseif ($type == 'accommodation') {
          $insertQuery = "
          INSERT INTO review_reservation (reviewText, reviewRating, reviewDate, reviewedBy, accommodationID, reservationID)
          VALUES ('$reviewText', '$reviewRating', '$reviewDate', '$userID', '$itemID', (SELECT reservationID FROM reservation WHERE accommodationID = '$itemID' AND reservedBy = '$userID' LIMIT 1))
          ";
      } else {
          die("Error: Invalid review type.");
      }

      // Execute query
      if (mysqli_query($conn, $insertQuery)) {
        $_SESSION['review_success'] = true; // Set a session variable to indicate success
        $_SESSION['review_success'] = "Review submitted successfully!";
        header("Location: purchase_history.php"); // Redirect to purchase history  
      } else {
          die("Error submitting review: " . mysqli_error($conn));
      }
  }

  // Get the details of the item being reviewed (for display purposes)
  $itemDetailsQuery = '';
  if ($type == 'activity') {
      $itemDetailsQuery = "SELECT activityName FROM activity WHERE activityID = '$itemID'";
  } elseif ($type == 'food') {
      $itemDetailsQuery = "SELECT foodName FROM food WHERE foodID = '$itemID'";
  } elseif ($type == 'accommodation') {
      $itemDetailsQuery = "SELECT accommodationName FROM accommodation WHERE accommodationID = '$itemID'";
  }

  $itemDetailsResult = mysqli_query($conn, $itemDetailsQuery);
  $itemDetails = mysqli_fetch_assoc($itemDetailsResult);
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Leave a Review</title>
      <link rel="stylesheet" type="text/css" href="CSS/USER/review.css">
      <style>
          body {
              font-family: Arial, sans-serif;
              background-color: #f4f9f9;
              color: #333;
          }

          .review-container {
              width: 50%;
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

          form {
              display: flex;
              flex-direction: column;
          }

          textarea, select {
              margin-bottom: 10px;
              padding: 10px;
              border: 1px solid #00796b;
              border-radius: 5px;
          }

          textarea {
              height: 150px;
              resize: none;
          }

          .submit-button {
              padding: 10px;
              background-color: #00796b;
              color: white;
              border: none;
              border-radius: 5px;
              cursor: pointer;
          }

          .submit-button:hover {
              background-color: #004d40;
          }

          .success-message {
              background-color: #c8e6c9;
              color: #2e7d32;
              padding: 10px;
              margin-bottom: 10px;
              border-radius: 5px;
          }
      </style>
  </head>
  <body>

      <div class="review-container">
          <h1>Leave a Review</h1>

          <?php if (!empty($successMessage)): ?>
          <div class="success-message"><?php echo $successMessage; ?></div>
          <?php endif; ?>

          <form action="submit_review.php?type=<?php echo $type; ?>&id=<?php echo $itemID; ?>" method="POST">
            <label for="reviewText">Review Text:</label>
            <textarea name="reviewText" required><?php echo $reviewText; ?></textarea>

            <label for="reviewRating">Rating:</label>
            <select name="reviewRating" required>
                <option value="">Select Rating</option>
                <option value="1" <?php if ($reviewRating == 1) echo 'selected'; ?>>1 (Lowest)</option>
                <option value="2" <?php if ($reviewRating == 2) echo 'selected'; ?>>2</option>
                <option value="3" <?php if ($reviewRating == 3) echo 'selected'; ?>>3</option>
                <option value="4" <?php if ($reviewRating == 4) echo 'selected'; ?>>4</option>
                <option value="5" <?php if ($reviewRating == 5) echo 'selected'; ?>>5 (Highest)</option>
            </select>

            <button type="submit" class="submit-button">Submit Review</button>
          </form>

          <p><strong>Reviewing: </strong><?php echo $itemDetails['activityName'] ?? $itemDetails['foodName'] ?? $itemDetails['accommodationName']; ?></p>
      </div>

  </body>
  </html>