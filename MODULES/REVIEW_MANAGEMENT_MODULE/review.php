<?php
session_start();
include("../../../SMMS/CONFIG/config.php");

// Check if user is logged in
if (!isset($_SESSION['UID'])) {
    die("Error: You must log in to submit a review.");
}

$userID = $_SESSION['UID'];

// Get type and id from the URL
if (!isset($_GET['type']) || !isset($_GET['id'])) {
    die("Error: Invalid review request.");
}

$type = $_GET['type'];
$itemID = $_GET['id'];

// Determine the table and columns based on the type
$table = "";
$idColumn = "";
$additionalIDColumn = "";

switch ($type) {
    case 'activity':
        $table = "review_activity";
        $idColumn = "activityID";
        $additionalIDColumn = "apID";
        break;
    case 'food':
        $table = "review_food";
        $idColumn = "foodID";
        $additionalIDColumn = "fpID";
        break;
    case 'accommodation':
        $table = "review_reservation";
        $idColumn = "accommodationID";
        $additionalIDColumn = "reservationID";
        break;
    default:
        die("Error: Invalid review type.");
}

// Check if the review form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reviewText = mysqli_real_escape_string($conn, $_POST['reviewText']);
    $reviewRating = (int)$_POST['reviewRating'];
    $reviewDate = date("Y-m-d");
    $additionalID = $_POST['additionalID'];

    // Insert the review into the appropriate table
    $query = "
        INSERT INTO $table (reviewText, reviewRating, reviewDate, reviewedBy, $idColumn, $additionalIDColumn) 
        VALUES ('$reviewText', $reviewRating, '$reviewDate', '$userID', $itemID, $additionalID)
    ";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Review submitted successfully!'); window.location.href='../../MODULES/USER_MANAGEMENT_MODULE/userHome.php';</script>";
    } else {
        echo "<script>alert('Error submitting review.');</script>";
    }
}

// Fetch additionalID for the item being reviewed
$query = "";
if ($type === "activity") {
    $query = "SELECT apID FROM activity_purchase_detail WHERE activityID = $itemID AND apID IN 
        (SELECT apID FROM activity_purchase WHERE userID = '$userID')";
} elseif ($type === "food") {
    $query = "SELECT fpID FROM food_purchase_detail WHERE foodID = $itemID AND fpID IN 
        (SELECT fpID FROM food_purchase WHERE userID = '$userID')";
} elseif ($type === "accommodation") {
    $query = "SELECT reservationID FROM reservation WHERE accommodationID = $itemID AND reservedBy = '$userID'";
}

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die("Error: No matching purchase found for review.");
}

$additionalID = $row[$additionalIDColumn];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit a Review</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e0f7f7; /* Light teal background */
        }
        .container {
            width: 60%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #008080; /* Teal color */
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
            padding: 10px;
            font-size: 14px;
        }
        select, textarea, input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #008080; /* Teal button background */
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #006666; /* Darker teal on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Submit a Review</h1>
        <form action="review.php?type=<?php echo $type; ?>&id=<?php echo $itemID; ?>" method="POST">
            <input type="hidden" name="additionalID" value="<?php echo $additionalID; ?>">
            <label for="reviewText">Write Your Review</label>
            <textarea name="reviewText" id="reviewText" required></textarea>

            <label for="reviewRating">Rating (1 - Lowest, 5 - Highest)</label>
            <select name="reviewRating" id="reviewRating" required>
                <option value="">Select Rating</option>
                <option value="1">1 - Very Poor</option>
                <option value="2">2 - Poor</option>
                <option value="3">3 - Average</option>
                <option value="4">4 - Good</option>
                <option value="5">5 - Excellent</option>
            </select>

            <button type="submit">Submit Review</button>
        </form>
    </div>
</body>
</html>