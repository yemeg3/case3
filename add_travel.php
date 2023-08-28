<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "gamemeg9_root";
$password = "vostcorp12Qaq";
$dbname = "gamemeg9_root";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if (isset($_POST['submit_travel'])) {
    $user_id = $_SESSION['user_id'];


    $places_data = $_POST['places'];

    foreach ($places_data as $place) {
        $place_to_visit = $place['place_to_visit'];
        $location = $place['location'];
        $cost = $place['cost'];


        $insert_sql = "INSERT INTO travels (user_id, place, location, cost) VALUES ('$user_id', '$place_to_visit', '$location', '$cost')";

        if ($conn->query($insert_sql) !== TRUE) {
            echo "Ошибка: " . $conn->error;
        }
    }

    echo "Информация о путешествиях добавлена.";
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Diary - Рассказать о путешествии</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Travel Diary</h1>
    </header>

    <nav>
        <ul>
            <li><a href="dashboard.php">Моя страница</a></li>
            <li><a href="other_travels.php">Путешествия других пользователей</a></li>
            <li><a href="logout.php">Выйти</a></li>
        </ul>
    </nav>

    <main>
        <div class="container">
            <h2>Рассказать о путешествии</h2>
            <form method="post" action="">
              <form method="post">
                  <div id="places_container">
                      <div class="place">
                          <label for="place_to_visit">Место посещения:</label><br>
                          <input type="text" name="places[0][place_to_visit]" required><br>

                          <label for="location_input">Местоположение:</label><br>
                          <input type="text" id="location_input_0" name="places[0][location_input]" required><br>
                          <input type="hidden" id="location_0" name="places[0][location]" required>

                          <button type="button" onclick="geocodeAddress(0)">Подтвердить местоположение</button><br>

                          <label for="cost">Стоимость путешествия:</label><br>
                          <input type="text" name="places[0][cost]" required><br>
                      </div>
                  </div>

                  <button type="button" id="add_place">Добавить место</button><br>
                  <button type="submit" name="submit_travel">Рассказать о путешествии</button>
              </form>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Travel Diary</p>
    </footer>

    <script>
        let placesContainer = document.getElementById('places_container');
        let addButton = document.getElementById('add_place');
        let placeCount = 1;

        addButton.addEventListener('click', function () {
            let newPlace = document.createElement('div');
            newPlace.className = 'place';
            newPlace.innerHTML = `
                <label for="place_to_visit">Место посещения:</label><br>
                <input type="text" name="places[${placeCount}][place_to_visit]" required><br>

                <label for="location_input">Местоположение:</label><br>
                <input type="text" id="location_input_${placeCount}" name="places[${placeCount}][location_input]" required><br>
                <input type="hidden" id="location_${placeCount}" name="places[${placeCount}][location]" required>

                <button type="button" onclick="geocodeAddress(${placeCount})">Подтвердить местоположение</button><br>

                <label for="cost">Стоимость путешествия:</label><br>
                <input type="text" name="places[${placeCount}][cost]" required><br>
            `;
            placesContainer.appendChild(newPlace);
            placeCount++;
        });

        function geocodeAddress(placeIndex) {
            let locationInput = document.getElementById(`location_input_${placeIndex}`);
            let locationOutput = document.getElementById(`location_${placeIndex}`);

            let address = locationInput.value;
            let nominatimURL = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json`;

            fetch(nominatimURL)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        let latitude = data[0].lat;
                        let longitude = data[0].lon;
                        locationOutput.value = `${latitude}, ${longitude}`;
                        alert(`Геокодирование успешно: ${locationOutput.value}`);
                    } else {
                        alert(`Местоположение не найдено`);
                    }
                })
                .catch(error => {
                    alert(`Ошибка геокодирования: ${error}`);
                });
        }

    </script>
</body>
</html>
