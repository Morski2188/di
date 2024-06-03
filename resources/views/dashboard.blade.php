<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind.output.css') }}" />
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="{{ asset('assets/js/init-alpine.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA1dWBuRm9FZaaZtKo_Ib91QzOrjrdV8uQ&libraries=places"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="//cdn.rawgit.com/Mikhus/canvas-gauges/gh-pages/download/2.1.7/all/gauge.min.js"></script>
</head>
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f3f4f6;
        color: #4b5563;
        margin: 0;
        padding: 0;
        background-image: url("https://wallpapers.com/images/hd/green-and-blue-macos-monterey-cvkbpfrvg2tmo7zj.jpg");
    }

    

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;

    }

    .card {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;

    }

    .card img {
        width: 50px;
        height: 50px;
        margin-bottom: 10px;
    }

    .card h2 {
        margin-bottom: 10px;
        font-size: 24px;
        font-weight: 600;
    }

    .card div {
        margin-bottom: 5px;
    }

    .form-container {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-container label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .form-container input[type="text"] {
        width: calc(97% - 100px);
        padding: 10px;
        border: 1px solid #d1d5db;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .form-container button {

        padding: 10px;
        background-color: #4c51bf;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form-container button:hover {
        background-color: #4338ca;
    }

    .weather-container {
        margin-top: 20px;
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .weather-container img {
        width: 50px;
        height: 50px;
        margin-bottom: 10px;
    }

    .weather-container h2 {
        margin-bottom: 10px;
        font-size: 24px;
        font-weight: 600;
    }

    .weather-container div {
        margin-bottom: 5px;
    }

    .error {
        color: #e53e3e;
    }

    .cards {
        display: flex;
        gap: 20px;

        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        padding-top: 1px;

    }

    .cards>* {
        flex: 0 0 20%;

    }

    .img {
        border-radius: 10px;
    }
</style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <form method="GET" action="{{ route('dashboard') }}">
                <label for="location">Enter Location:</label>
                <div class="relative">
                    <input type="text" id="location" name="location" placeholder="Enter city, state, country">
                    <input type="hidden" id="city" name="city">
                    <input type="hidden" id="state" name="state">
                    <input type="hidden" id="country" name="country">
                    <button type="submit">Get Weather</button>
                </div>
            </form>
        </div>
        

        <div class="container">
            @if(isset($weatherData))
            <div class="card">

                <img class="img" src="{{ $weatherData['weather_icons'][0] }}" alt="Weather Icon">
                <h2>Weather in {{ $weatherData['name'] }}, {{ $weatherData['country'] }}</h2>

            </div>
            <div class="cards">

                <div class="card">
                    <img class="img" src="https://cdn-icons-png.flaticon.com/512/747/747632.png">
                    <div class="temperature">Temperature: {{ $weatherData['temperature'] }} °C</div>
                    <br>

                    <canvas data-type="linear-gauge"
        data-width="160"
        data-height="300"
        data-border-radius="20"
        data-borders="0"
        data-bar-stroke-width="20"
        data-minor-ticks="10"
        data-major-ticks="0,10,20,30,40,50,60,70,80,90,100"
        data-value="<?php echo $weatherData['temperature']; ?>"
        data-units=""
        data-color-value-box-shadow="false"
></canvas>



                </div>

                <div class="card">
                    <img class="img" src="https://cdn-icons-png.flaticon.com/512/7993/7993127.png">
                    <div class="humidity">Humidity: {{ $weatherData['humidity'] }}%</div>
                    <br>
                    <canvas id="humidityCanva" style="width:300px;height:400px"></canvas>
                </div>

                <script>
                    const ctx = document.getElementById('humidityCanva');

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Humidity'],
                            datasets: [{
                                label: '%',
                                data: [<?php echo $weatherData['humidity']; ?>],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    min:0,
                                    max:100
                                }
                            }
                        }
                    });
                </script>

                <div class="card">
                    <img class="img" src="https://cdn-icons-png.flaticon.com/512/7745/7745468.png">
                    <div class="wind-speed">Wind Speed: {{ $weatherData['wind_speed'] }} km/h</div>
                    <canvas style="margin-top:100px;" data-type="radial-gauge" data-width="200" data-height="200" data-units="Km/h" data-title="false" data-value=<?php echo $weatherData['wind_speed']; ?> data-min-value="0" data-max-value="220" data-major-ticks="0,20,40,60,80,100,120,140,160,180,200,220" data-minor-ticks="2" data-stroke-ticks="false" data-highlights='[
                            { "from": 0, "to": 50, "color": "rgba(255,255,255,.15)" },
                            { "from": 50, "to": 100, "color": "rgba(255,255,255,.15)" },
                            { "from": 100, "to": 150, "color": "rgba(255,255,255,.25)" },
                            { "from": 150, "to": 200, "color": "rgba(255,255,225,.25)" },
                            { "from": 200, "to": 220, "color": "rgba(255,255,255,.25)" }
                        ]' data-color-plate="#222" data-color-major-ticks="#f5f5f5" data-color-minor-ticks="#ddd" data-color-title="#fff" data-color-units="#ccc" data-color-numbers="#eee" data-color-needle-start="rgba(240, 128, 128, 1)" data-color-needle-end="rgba(255, 160, 122, .9)" data-value-box="true" data-animation-rule="bounce" data-animation-duration="500" data-font-value="Led" data-animated-value="true"></canvas>
                </div>



            </div>
            @elseif(isset($error))
            <p class="error">{{ $error }}</p>
            @endif
        </div>


        <div class="container">

            <div class="container">
                @if(isset($weatherData))

                <div class="cards">

                    <div class="card">
                        <img class="img" src="https://cdn-icons-png.flaticon.com/512/686/686732.png">

                        <div class="wind-degree">Wind Degree: {{ $weatherData['wind_degree'] }}°</div>

                    </div>

                    <div class="card">
                        <img class="img" src="https://cdn-icons-png.flaticon.com/512/5570/5570290.png">

                        <div class="weather-description">Weather: {{ $weatherData['weather_description'][0] }}</div>

                    </div>
                    


                </div>
                @elseif(isset($error))
                <p class="error">{{ $error }}</p>
                @endif
            </div>


</body>
<script>
    function initAutocomplete() {


        const input = document.getElementById('location');
        const options = {
            types: ['(cities)']

        };
        const autocomplete = new google.maps.places.Autocomplete(input, options);


        autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();
            let city = '';
            let country = '';

            // Itera sobre os componentes do endereço e captura apenas cidade e país
            place.address_components.forEach(component => {
                const types = component.types;
                if (types.includes('locality')) {
                    city = component.long_name;
                } else if (types.includes('country')) {
                    country = component.long_name;
                }
            });

            // Define os valores dos campos ocultos de cidade e país
            document.getElementById('city').value = city;
            document.getElementById('country').value = country;
        });
    }

    document.addEventListener('DOMContentLoaded', initAutocomplete);
</script>

</html>