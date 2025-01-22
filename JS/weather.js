
// function to fetch weather
function getWeather() {

  // API key for OpenWeatherMap API
  const apiKey = 'd86d8af4a0fc748290759500dc274bf8';

  // get user location input from search bar
  const location = document.getElementById('location').value;

  // display alert message if no location is inserted
  if (!location) {
      alert('Please enter a location');
      return;
  }

  // URLs for fetching current weather and forecast data
  const currentWeatherUrl = `https://api.openweathermap.org/data/2.5/weather?q=${location}&appid=${apiKey}`;
  const forecastUrl = `https://api.openweathermap.org/data/2.5/forecast?q=${location}&appid=${apiKey}`;

  // fetch current weather data from OpenWeatherMap API
  fetch(currentWeatherUrl)
      .then(response => response.json())
      .then(data => {
          displayWeather(data);

          // URLs for fetching UV index data
          const uvUrl = `https://api.openweathermap.org/data/2.5/uvi?appid=${apiKey}&lat=${data.coord.lat}&lon=${data.coord.lon}`;
          fetch(uvUrl)
              .then(response => response.json())
              .then(uvData => {
                  document.getElementById('uv-index').innerHTML = `UV Index: ${uvData.value}`;
              })
              // display alert message if uv index fetching process failed
              .catch(error => {
                  console.error('Error fetching UV index:', error);
              });
      })
      // display alert message if current weather data fetching process failed
      .catch(error => {
          console.error('Error fetching current weather data:', error);
          alert('Error fetching current weather data. Please try again.');
      });

  // fetch daily and hourly forecast data
  fetch(forecastUrl)
      .then(response => response.json())
      .then(data => {
          displayHourlyForecast(data.list);
          displayDailyForecast(data.list);
      })
      .catch(error => {
          console.error('Error fetching forecast data:', error);
          alert('Error fetching forecast data. Please try again.');
      });
}

// function to display current weather info
function displayWeather(data) {
  const tempDivInfo = document.getElementById('temp-div');
  const weatherInfoDiv = document.getElementById('weather-info');
  const weatherIcon = document.getElementById('weather-icon');
  const humidityElem = document.getElementById('humidity');
  const windSpeedElem = document.getElementById('wind-speed');
  const uvIndexElem = document.getElementById('uv-index');

  // clear existing content in weather and temperature info div
  weatherInfoDiv.innerHTML = '';
  tempDivInfo.innerHTML = '';
  humidityElem.innerHTML = '';
  windSpeedElem.innerHTML = '';
  uvIndexElem.innerHTML = '';

  // display alert message if location input not found
  if (data.cod === '404') {
    // clear all sections in case of invalid location
    document.getElementById('temp-div').innerHTML = '';
    document.getElementById('weather-info').innerHTML = '';
    document.getElementById('weather-icon').style.display = 'none';
    document.getElementById('humidity').innerHTML = '';
    document.getElementById('wind-speed').innerHTML = '';
    document.getElementById('uv-index').innerHTML = '';
    document.getElementById('hourly-forecast').innerHTML = '';
    document.getElementById('daily-forecast').innerHTML = '';

    weatherInfoDiv.innerHTML = `<p>${data.message}</p>`;
  }
  else {
    const locationName = data.name;
    const temperature = Math.round(data.main.temp - 273.15); // convert Kelvin to Celsius
    const description = data.weather[0].description;
    const humidity = data.main.humidity;
    const windSpeed = data.wind.speed;
    const iconCode = data.weather[0].icon;
    const iconUrl = `https://openweathermap.org/img/wn/${iconCode}@4x.png`;

    const temperatureHTML = `<p>${temperature}°C</p>`;
    const weatherHtml = `<p>${locationName}</p><p>${description}</p>`;

    tempDivInfo.innerHTML = temperatureHTML;
    weatherInfoDiv.innerHTML = weatherHtml;
    weatherIcon.src = iconUrl;
    weatherIcon.alt = description;
    weatherIcon.style.display = 'block';

    humidityElem.innerHTML = `Humidity: ${humidity}%`;
    windSpeedElem.innerHTML = `Wind Speed: ${windSpeed} m/s`;
  }
}

// function to display hourly forecast
function displayHourlyForecast(hourlyData) {
  const hourlyForecastDiv = document.getElementById('hourly-forecast');
  hourlyForecastDiv.innerHTML = ''; 

  // divide hourly forecast into 8 entries, with interval of 3 hours in-between for each hourly forecast entries
  const next24Hours = hourlyData.slice(0, 8);

  next24Hours.forEach(item => {
      const dateTime = new Date(item.dt * 1000); 
      const hour = dateTime.getHours();
      const temperature = Math.round(item.main.temp - 273.15); 
      const iconCode = item.weather[0].icon;
      const iconUrl = `https://openweathermap.org/img/wn/${iconCode}.png`;

      const hourlyItemHtml = `
          <div class="hourly-item">
              <span>${hour}:00</span>
              <img src="${iconUrl}" alt="Hourly Weather Icon">
              <span>${temperature}°C</span>
          </div>
      `;

      hourlyForecastDiv.innerHTML += hourlyItemHtml;
  });
}

// function to display daily forecast
function displayDailyForecast(hourlyData) {
  const dailyForecastDiv = document.getElementById('daily-forecast');

  // clear existing content in daily forecast div  
  dailyForecastDiv.innerHTML = ''; 

  // array to store daily forecast data
  const dailyData = {};

  const currentDate = new Date().toDateString();

  hourlyData.forEach(item => {
    const dateTime = new Date(item.dt * 1000);
    const dateKey = dateTime.toDateString();

    // skip current date from daily forecast list
    if (dateKey === currentDate) {
      return;
    }

    if (!dailyData[dateKey]) {
      dailyData[dateKey] = {
          temp: [],
          humidity: [],
          windSpeed: [],
          icon: item.weather[0].icon
      };
    }

    dailyData[dateKey].temp.push(item.main.temp);
    dailyData[dateKey].humidity.push(item.main.humidity);
    dailyData[dateKey].windSpeed.push(item.wind.speed);
  });

  // calculate averages
  for (const date in dailyData) {
      const temps = dailyData[date].temp;
      const averageTemp = Math.round(temps.reduce((a, b) => a + b, 0) / temps.length - 273.15);
      const averageHumidity = Math.round(dailyData[date].humidity.reduce((a, b) => a + b, 0) / dailyData[date].humidity.length);
      const averageWindSpeed = (dailyData[date].windSpeed.reduce((a, b) => a + b, 0) / dailyData[date].windSpeed.length).toFixed(1);

      const iconUrl = `https://openweathermap.org/img/wn/${dailyData[date].icon}.png`;

      const dailyItemHtml = `
          <div class="daily-item">
              <span>${date}</span>
              <img src="${iconUrl}" alt="Daily Weather Icon">
              <span>${averageTemp}°C</span>
              <span>Humidity: ${averageHumidity}%</span>
              <span>Wind: ${averageWindSpeed} m/s</span>
          </div>
      `;

      dailyForecastDiv.innerHTML += dailyItemHtml;
  }
}