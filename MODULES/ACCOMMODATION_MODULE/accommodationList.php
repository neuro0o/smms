<?php
session_start();
// include db config
include("../../../SMMS/CONFIG/config.php");

// Set sorting to default by price ascending if no sort option is provided
$sortOption = "ORDER BY accommodationPrice ASC";

// Fetch accommodations
$sql_accommodation = "SELECT * FROM accommodation $sortOption";
$result = mysqli_query($conn, $sql_accommodation);
$rowcount = mysqli_num_rows($result);
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

    <!-- headerBanner css file
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/headerBanner.css"> -->

    <!-- topNav css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topNav.css">

    <!-- topHeader css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topHeader.css">

    <!-- accommodation css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/USER/accommodation.css">

    <title>ACCOMMODATION PAGE</title>
</head>

<body>

    <!-- include topNav.php -->
    <?php include '../../INCLUDES/topHeader.php'; ?>

    <!-- include userNav.php -->
    <?php include '../../INCLUDES/userNav.php'; ?>


    <!-- accommodation section starts here -->
    <section class="product">
        <!-- page title -->
        <h1 id="section-title">ACCOMMODATION PORTFOLIO</h1>
        <h2>Choose Your Ideal Accommodation</h2>

        <!-- accommodation Section -->
        <div class="product-container">
            <?php
            // Check if accommodations exist
            if ($rowcount > 0) {
                while ($accommodation = mysqli_fetch_assoc($result)) {
                    echo '<div class="card">';
                    echo '<img src="' . $accommodation["accommodationImg"] . '" alt="' . $accommodation["accommodationName"] . '">';
                    echo '<div>';
                    echo '<h2>' . $accommodation["accommodationName"] . '</h2>';
                    echo '<h3>RM ' . $accommodation["accommodationPrice"] . ' / night</h3>';
                    echo '<p>' . $accommodation["accommodationDesc"] . '</p>';
                    echo '<div class="socials">';

                    // Add booking button or login prompt
                    if (isset($_SESSION['UID'])) {
                        echo '<form method="post" action="../../MODULES/ACCOMMODATION_MODULE/accommodationForm.php"> 
                                <input type="hidden" name="accommodationID" value="' . $accommodation['accommodationID'] . '">
                                <button><i class="fa fa-bed"></i> Book Now</button>
                              </form>';
                    } else {
                        echo '<h4><i>Login to book accommodation.</i></h4>';
                    }

                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p id="product-not-found">No accommodations available.</p>';
            }

            // Free result set
            mysqli_free_result($result);
            ?>
        </div>
    </section>
    <!-- accommodation section ends here -->


    <!-- include topNav.js -->
    <script src="../../../SMMS/JS/topNav.js"></script>

</body>

</html>