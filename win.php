
<?php
session_start();
// Jehad
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Gewonnen!</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

<div class="win-container">
    <h1>🎉 Escape Succesvol!</h1>

    <p class="team">
        Team: <?php echo isset($_SESSION['teamname']) ? $_SESSION['teamname'] : "Onbekend"; ?>
    </p>

    <p>
        Jullie hebben het laboratorium weten te ontsnappen!
        Alle puzzels zijn opgelost en de deuren zijn geopend 🔬
    </p>

    <p class="score">
        Score: 100 punten
    </p>

    <button onclick="location.href='index.php'/button>
</div>

</body>

</html>
