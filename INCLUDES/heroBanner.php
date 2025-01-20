<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- cdn icon link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <!-- utils css file -->
  <link rel="stylesheet" href="../../SMMS/CSS/MISC/utils.css">

  <!-- banner css file -->
  <link rel="stylesheet" href="../../SMMS/CSS/MISC/heroBanner.css">

  <title>HERO BANNER</title>
</head>
<body>
  <div class="hero-banner">
    <h1 class="hero-welcome-message" >
      WELCOME TO
    </h1>

    <h2 class="hero-site-name" >
      <mark>MANTANANI</mark> 
    </h2>

    <h3 class="hero-site-desc">
      A PLACE TO ENJOY YOUR STAYCATION<br>
      WHILE STAYING CONNECTED WITH NATURE
    </h3>

    <div class="hero-button">
      <a href="<?php echo BASE_URL . '/MODULES/USER_MANAGEMENT_MODULE/AUTH/registerForm.php'; ?>">
          <button type="button" id="register-button">REGISTER</button>
      </a>
      <a href="<?php echo BASE_URL . '/MODULES/USER_MANAGEMENT_MODULE/AUTH/loginForm.php'; ?>">
          <button type="button" id="login-button">LOGIN</button>
      </a>
    </div>
  </div>
</body>
</html>