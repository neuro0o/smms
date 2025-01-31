<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Avoid redirecting if you're already on the userHome.php page
if (!isset($_SESSION['notification']) && !isset($_GET['redirected'])) {
    $_SESSION['notification'] = "Welcome";
    $_SESSION['notification_type'] = "success";  // Options: success, info, warning, error

    // Add a query parameter to prevent infinite redirection loop
    header("Location: userHome.php?redirected=true");
    exit;
}

// Include database configuration
include("../../../SMMS/CONFIG/config.php");

// Display notifications if set
if (isset($_SESSION["notification"])): ?>
  <script>
      window.onload = function() {
          displayMessage('<?php echo $_SESSION["notification"]; ?>', '<?php echo $_SESSION["notification_type"]; ?>');
      }
  </script>
  <?php 
  unset($_SESSION["notification"]);
  unset($_SESSION["notification_type"]);
endif;

// Prevent accidental output before header call
ob_start();
?>

<!-- HTML and body content below -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CDN Icon Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- CSS Files -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/utils.css">
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/headerBanner.css">
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topNav.css">
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topHeader.css">
    <link rel="stylesheet" href="../../../SMMS/CSS/USER/userHome.css">
    <link rel="stylesheet" href="../../../SMMS/CSS/USER/notification.css">
    <script src="../../JS/notiPopup.js"></script>

    <title>USER HOME</title>
</head>
<body>

    <!-- Notification Display (Triggered by PHP session) -->
    <?php if (isset($_SESSION["notification"])): ?>
        <script>
            window.onload = function() {
                displayMessage('<?php echo $_SESSION["notification"]; ?>', '<?php echo $_SESSION["notification_type"]; ?>');
            }
        </script>
        <?php 
        unset($_SESSION["notification"]); 
        unset($_SESSION["notification_type"]); 
        ?>
    <?php endif; ?>

    <!-- Include topHeader -->
    <?php include '../../INCLUDES/topHeader.php'; ?>

    <!-- Include userNav -->
    <?php include '../../INCLUDES/userNav.php'; ?>

    <!-- Include headerBanner -->
    <?php include '../../INCLUDES/headerBanner.php'; ?>

    <section class="userHome">
        <h1 id="section-title">Discover What Makes Us Special!</h1>

        <!-- Local Adventure Card -->
        <div class="info-card">
            <div class="info-card-img-left">
                <img src="../../IMAGES/MISC/atv.jpg" alt="ATV Adventure">
            </div>  
            <div class="info-card-desc-left">
                <h1>Explore Local Adventures!</h1><br>
                <p>From immersive snorkeling experience to exciting ATV rides, a whole world of local adventures awaits you!</p>
            </div>
        </div>

        <!-- Accommodation Card -->
        <div class="info-card">
            <div class="info-card-desc-right">
                <h1>Book Your Stay with Ease!</h1><br>
                <p>Planning a getaway? Simply book your accommodation online and secure your room in advance—stress-free and ready to relax!</p>
            </div>
            <div class="info-card-img-right">
                <img src="../../IMAGES/MISC/homestay.jpg" alt="Homestay">
            </div>  
        </div>

        <!-- Wishlist Card -->
        <a href="../WISHLIST_MODULE/wishlist.php" style="text-decoration: none; color: inherit;">
            <div class="info-card">
                <div class="info-card-img-left">
                    <img src="../../IMAGES/MISC/wishlist.jpg" alt="Wishlist">
                </div> 
                <div class="info-card-desc-left">
                    <h1>Create Your Dream Wishlist!</h1><br>
                    <p>Got dreams of a fabulous vacation but need to save up? No worries! Add your dream vacations to your wishlist and start planning for the future!</p>
                </div>
            </div>
        </a>

<!-- Weather Forecast Card -->
        <div class="info-card">
            <div class="info-card-desc-right">
                <h1>Stay on Top of the Weather!</h1><br>
                <p>Capture that perfect holiday photo with our live weather forecast—because we know you want your memories to be as beautiful as the view!</p>
            </div>
            <div class="info-card-img-right">
                <img src="../../IMAGES/MISC/weather.jpg" alt="Weather">
            </div>  
        </div><br><br><br><br><br>

        <!-- Include weather module -->
        <?php include '../../MODULES/WEATHER_FORECAST_MODULE/weather.php'; ?>
    </section>

    <!-- Include topNav.js -->
    <script src="../../../SMMS/JS/topNav.js"></script>
</body>
</html>

<?php
// After the output is buffered, perform the redirect to prevent the header warning
ob_end_flush();
exit;
?>