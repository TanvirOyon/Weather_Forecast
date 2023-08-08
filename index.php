<?php
$apiKey = 'c230d8ace15a676b6f960f48a3752880';

if (array_key_exists('submit', $_GET)) {
    if (!empty($_GET['city'])) {
        $city = $_GET['city'];
        $apiUrl = "https://api.openweathermap.org/data/2.5/forecast?q=$city&appid=$apiKey";
        $apiData = file_get_contents($apiUrl);
        $weather = json_decode($apiData, true);

        if (isset($weather['city'])) {
            $cityname = $weather['city']['name'];
            $country = $weather['city']['country'];
            $tempC = $weather['list'][0]['main']['temp'] - 273.15;
            $tempareture = intval($tempC) . "&deg;C";
            $wind = isset($weather['list'][0]['wind']['speed']) ? $weather['list'][0]['wind']['speed'] : 'N/A';
            $humidity = isset($weather['list'][0]['main']['humidity']) ? $weather['list'][0]['main']['humidity'] : 'N/A';
            $icon = isset($weather['list'][0]['weather'][0]['icon']) ? $weather['list'][0]['weather'][0]['icon'] : '';
            $weatherCondition = isset($weather['list'][0]['weather'][0]['description']) ? $weather['list'][0]['weather'][0]['description'] : '';

            $forecastDays = [];
            for ($i = 1; $i < 6; $i++) {
                if (isset($weather['list'][$i * 8])) {
                    $forecastDays[$i]['icon'] = isset($weather['list'][$i * 8]['weather'][0]['icon']) ? $weather['list'][$i * 8]['weather'][0]['icon'] : '';
                    $forecastDays[$i]['tempC'] = isset($weather['list'][$i * 8]['main']['temp']) ? $weather['list'][$i * 8]['main']['temp'] - 273.15 : null;
                    $forecastDays[$i]['temperature'] = isset($forecastDays[$i]['tempC']) ? intval($forecastDays[$i]['tempC']) . "&deg;C" : 'N/A';
                    $forecastDays[$i]['wind'] = isset($weather['list'][$i * 8]['wind']['speed']) ? $weather['list'][$i * 8]['wind']['speed'] : 'N/A';
                    $forecastDays[$i]['humidity'] = isset($weather['list'][$i * 8]['main']['humidity']) ? $weather['list'][$i * 8]['main']['humidity'] : 'N/A';
                }
            }
        } else {
            $error = "City not found.";
        }
    } else {
        $error = "Please enter a city name.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Weather Dashboard</h1>
        <form action="" method="GET">
            <div class="weather-input">
                <h3>Enter a City Name</h3>
                <input name="city" class="city-input" type="text" placeholder="City Name">
                <button name="submit" class="search-btn">Search</button>
            </div>
        </form>

        <?php if (isset($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php elseif (isset($cityname)) : ?>
            <div class="weather-data">
                <div class="current-weather">
                    <div class="details">
                        <h3>Today: <?php echo $cityname . "-" . $country; ?></h3>
                        <h6>Temperature: <?php echo $tempareture; ?></h6>
                        <h6>Wind: <?php echo $wind; ?> M/S</h6>
                        <h6>Humidity: <?php echo $humidity; ?>%</h6>
                    </div>
                    <div class="details">
                        <p><img src="https://openweathermap.org/img/wn/<?php echo $icon; ?>@2x.png"></p>
                        <h6>Condition: <?php echo $weatherCondition; ?></h6>
                    </div>
                </div>
                <div class="days-forecast">
                    <h2>5-Day Weather Forecast</h2>
                    <ul class="weather-cards">
                        <?php foreach ($forecastDays as $index => $day) : ?>
                            <li class="card">
                              <h3><?php echo date('D', strtotime("+$index day")); ?></h3>
                               <p><img src="https://openweathermap.org/img/wn/<?php echo $day['icon']; ?>@2x.png"></p>
                              <h6>Temp: <?php echo $day['temperature']; ?></h6>
                              <h6>Wind: <?php echo $day['wind']; ?> M/S</h6>
                              <h6>Humidity: <?php echo $day['humidity']; ?>%</h6>
                             </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
