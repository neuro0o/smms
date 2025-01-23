<?php
  session_start();
  // include db config
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

    <!-- headerBanner css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/headerBanner.css">

    <!-- topNav css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topNav.css">

    <!-- topHeader css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topHeader.css">

    <!-- userHome css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/USER/userHome.css">

    <title>USER HOME</title>
  </head>
  <body>

    <!-- include topNav.php -->
    <?php include '../../INCLUDES/topHeader.php'; ?>
    
    <!-- include userNav.php -->
    <?php include '../../INCLUDES/userNav.php'; ?>

    <!-- include headerBanner.php -->
    <?php include '../../INCLUDES/headerBanner.php';?>
    

    <section class="userHome">
      <h1 id="section-title">Discover What Makes Us Special!</h1>
      
      <!-- local adventure card -->
      <div class="info-card">
        <div class="info-card-img-left">
          <img src="../../IMAGES/MISC/atv.jpg" alt="">
        </div>  

        <div class="info-card-desc-left">
          <h1>Explore Local Adventures!</h1><br>
          <p>
            From immersive snorkeling experience to exciting ATV ride,
            we have got a whole world of local adventures waiting for you to discover!
          </p>
        </div>
      </div>

      <!-- accommodation card -->
      <div class="info-card">
        <div class="info-card-desc-right">
          <h1>Book Your Stay with Ease!</h1><br>
          <p>
            Planning a getaway? Simply book your accommodation online and secure your
            room in advance—stress-free and ready to relax!
          </p>
        </div>

        <div class="info-card-img-right">
          <img src="../../IMAGES/MISC/homestay.jpg" alt="">
        </div>  
      </div>

      <!-- wishlist card -->
      <a href="../WISHLIST_MODULE/wishlist.php" style="text-decoration: none; color: inherit;"> <!-- Make the card clickable -->
      <div class="info-card">
          <div class="info-card-img-left">
             <img src="../../IMAGES/MISC/wishlist.jpg" alt="">
      </div> 

        <div class="info-card-desc-left">
          <h1>Create Your Dream Wishlist!</h1><br>
          <p>
            Got dreams of a fabulous vacation but need to save up? No worries! Add your dream
            vacations to your wishlist and start planning for the future!
          </p>
        </div>
      </div>

      <!-- weather forecast card -->
      <div class="info-card">
        <div class="info-card-desc-right">
          <h1>Stay on Top of the Weather!</h1><br>
          <p>
            Capture that perfect holiday photo with our live weather
            forecast—because we know you want your memories to be as beautiful as the view!
          </p>
        </div>

        <div class="info-card-img-right">
          <img src="../../IMAGES/MISC/weather.jpg" alt="">
        </div>  
      </div><br><br><br><br><br>

      <!-- include weather.php -->
      <?php include '../../MODULES/WEATHER_FORECAST_MODULE/weather.php';?>
    </section>

    <!-- include topNav.js -->
    <script src="../../../SMMS/JS/topNav.js"></script>

  </body>
</html>