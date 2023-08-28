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

$user_id = $_SESSION['user_id'];


$travels_sql = "SELECT * FROM travels WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 5";
$travels_result = $conn->query($travels_sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Diary - Моя страница</title>
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
            <form method="post" action="add_travel.php">
                <button type="submit">Добавить запись</button>
            </form>
        </div>

        <div class="container">
            <h2>Последние 5 путешествий</h2>
            <?php
            if ($travels_result->num_rows > 0) {
                while ($row = $travels_result->fetch_assoc()) {
                    $travel_id = $row['id'];
                    $place = $row['place'];
                    $cost = $row['cost'];
                    $location = $row['location'];

                    echo "<p><strong>$place</strong> - Стоимость: $cost</p>";
                    echo "<button class='show-map' data-location='$location' data-travel-id='$travel_id'>Показать на карте</button>";
                }
            } else {
                echo "Нет информации о путешествиях.";
            }
            ?>
            <div id="map"></div>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Travel Diary</p>
    </footer>

    <script>
        var showMapButtons = document.querySelectorAll('.show-map');

        showMapButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var location = this.getAttribute('data-location');
                var travelId = this.getAttribute('data-travel-id');

                var mapDiv = document.getElementById('map');

                if (mapDiv.style.display === 'none') {
                    mapDiv.style.display = 'block';
                    this.textContent = 'Скрыть карту';
                    mapDiv.innerHTML = `<iframe width="600" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                    src="https://www.openstreetmap.org/export/embed.html?bbox=&amp;layer=mapnik&amp;marker=${location}"></iframe><br/><small><a
                    href="https://www.openstreetmap.org/?mlat=${location}#map=16/${location}" target="_blank">Просмотреть
                    увеличенную карту</a></small>`;
                } else {
                    mapDiv.style.display = 'none';
                    this.textContent = 'Показать на карте';
                    mapDiv.innerHTML = '';
                }
            });
        });
    </script>
</body>
</html>
