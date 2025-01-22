<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- cdn icon link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- utils css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/utils.css">

    <!-- weather css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/USER/weather.css">

    <title>WEATHER FORECAST</title>
  </head>
  <body>
    
    <div class="weather-container">
      <br><br><br>
      <h1>🌤️ LIVE-WEATHER FORECAST 🌤️</h1><br><br><br>

      <input type="text" id="location" placeholder="Enter the location, e.g., Kota Belud.">
      <button onclick="getWeather()">Search</button><br><br><br>

      <div id="temp-div"></div>

      <img id="weather-icon" alt="Weather Icon">

      <div id="weather-info"></div>

      <div id="additional-info">
        <p id="humidity"></p>
        <p id="wind-speed"></p>
        <p id="uv-index"></p>
      </div>

      <div id="hourly-forecast"></div>

      <div id="daily-forecast"></div>

      
    </div>

    <script src="../../../smms/JS/weather.js"></script>
  </body>
</html>