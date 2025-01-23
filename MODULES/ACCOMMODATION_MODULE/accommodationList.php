<?php
session_start();
// include db config
include("../../../SMMS/CONFIG/config.php");

// check if a specific accommodation ID is provided
if (isset($_GET['id'])) {
    $accommodationID = intval($_GET['id']);

    // fetch the specific accommodation based on the accommodation ID
    $sql_accommodation = "SELECT a.accommodationID, a.accommodationName, a.accommodationDesc, a.accommodationPrice, a.accommodationImg
    FROM accommodation a
    WHERE a.accommodationID = $accommodationID";
    $accommodationResult = mysqli_query($conn, $sql_accommodation);
    
    if ($accommodationResult && mysqli_num_rows($accommodationResult) > 0) {
        $accommodationDetails = mysqli_fetch_assoc($accommodationResult);
    } else {
        echo '<p>Accommodation not found.</p>';
    }
} else {
    // Set sorting to default by price ascending if no sort option is provided
    $sortOption = "ORDER BY accommodationPrice ASC";

    // Fetch accommodations
    $sql_accommodation = "SELECT * FROM accommodation $sortOption";
    $result = mysqli_query($conn, $sql_accommodation);
    $rowcount = mysqli_num_rows($result);
}
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

        <!-- Check if a specific accommodation is being viewed -->
        <?php if (isset($accommodationDetails)): ?>
            <div class="centered-card">
                <div class="card">
                    <?php
                    // Construct the full image URL using BASE_URL
                    $imageURL = BASE_URL . '/' . $accommodationDetails["accommodationImg"];
                    ?>
                    <img src="<?php echo $imageURL; ?>" alt="<?php echo htmlspecialchars($accommodationDetails["accommodationName"]); ?>">
                    <div>
                        <h2><?php echo htmlspecialchars($accommodationDetails["accommodationName"]); ?></h2>
                        <h3>RM <?php echo htmlspecialchars($accommodationDetails["accommodationPrice"]); ?> / night</h3>
                        <p><?php echo htmlspecialchars($accommodationDetails["accommodationDesc"]); ?></p>
                        <div class="socials">
                            <!-- Add booking button or login prompt -->
                            <?php if (isset($_SESSION['UID'])): ?>
                                <form method="post" action="../../MODULES/RESERVATION_MODULE/accommodationForm.php"> 
                                    <input type="hidden" name="accommodationID" value="<?php echo $accommodationDetails['accommodationID']; ?>">
                                    <button><i class="fa fa-bed"></i> Book Now</button>
                                </form>
                            <?php else: ?>
                                <h4><i>Login to book accommodation.</i></h4>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- accommodation Section -->
            <div class="product-container">
                <?php
                // Check if accommodations exist
                if ($rowcount > 0) {
                    while ($accommodation = mysqli_fetch_assoc($result)) {
                        // Construct the full image URL using BASE_URL
                        $imageURL = BASE_URL . '/' . $accommodation["accommodationImg"];

                        echo '<div class="card">';
                        echo '<img src="' . $imageURL . '" alt="' . htmlspecialchars($accommodation["accommodationName"]) . '">';
                        echo '<div>';
                        echo '<h2>' . htmlspecialchars($accommodation["accommodationName"]) . '</h2>';
                        echo '<h3>RM ' . htmlspecialchars($accommodation["accommodationPrice"]) . ' / night</h3>';
                        echo '<p>' . htmlspecialchars($accommodation["accommodationDesc"]) . '</p>';
                        echo '<div class="socials">';
                        
                        // Add booking button or login prompt
                        if (isset($_SESSION['UID'])) {
                            echo '<form method="post" action="../../MODULES/RESERVATION_MODULE/accommodationForm.php"> 
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
        <?php endif; ?>
    </section>
    <!-- accommodation section ends here -->


    <!-- include topNav.js -->
    <script src="../../../SMMS/JS/topNav.js"></script>

</body>

</html>