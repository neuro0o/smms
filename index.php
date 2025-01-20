<?php
  // include db config
  include("../SMMS/CONFIG/config.php"); 
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- cdn icon link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- utils css file -->
    <link rel="stylesheet" href="../SMMS/CSS/MISC/utils.css">
    
    <!-- landing page css file -->
    <link rel="stylesheet" href="../SMMS/CSS/MISC/landingPage.css">

    <title>LANDING PAGE</title>
  </head>
  <body>
    
  <section class="landing-page">

    <!-- include heroBanner.php -->
    <?php include './INCLUDES/heroBanner.php';?>

    <?php
      // alert message if user has logged out successfully
      if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
        echo '<script type="text/javascript">alert("Logout successful!");</script>';
      }
    ?>

    <div class="title">
      <h2 id="section-title">
        Why Join Us?
      </h2>
    </div>

    <div class="join-card">
      <div class="join-card-explore">
        <h2 id="card-title">🌟 Explore Local Adventures</h2>
        <p id="card-desc">
          From relaxing sunset walk to exciting scuba diving,
          we have got a whole world of local adventures waiting for you to discover!
        </p>
      </div>

      <div class="join-card-book">
        <h2 id="card-title">🏠 Book Your Stay with Ease</h2>
        <p id="card-desc">
          Planning a getaway? Simply book your accommodation online and secure your
          room in advance—stress-free and ready to relax!
        </p>
      </div>

      <div class="join-card-wishlist">
        <h2 id="card-title">💖 Create Your Dream Wishlist</h2>
        <p id="card-desc">
          Got dreams of a fabulous vacation but need to save up? No worries! Add your dream
          vacations to your wishlist and start planning for the future!
        </p>
      </div>

      <div class="join-card-weather">
        <h2 id="card-title">☀️ Stay on Top of the Weather</h2>
        <p id="card-desc">
          Capture that perfect holiday photo with our live weather
          forecast—because we know you want your memories to be as beautiful as the view!
        </p>
      </div>

    </div>
  </section>

  </body>
</html>