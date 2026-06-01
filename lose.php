<?php session_start(); 
 // Bashar ?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Tijd is op!</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="lose-page">

    <p class="lose-icon">💀</p>

    <h1 class="lose-title">De Tijd is op!</h1>

    <p class="lose-subtitle">Helaas is het je niet gelukt om de missie binnen de tijd te voltooien.</p>

    <div class="lose-team">
        Team:
        <span>
            <?php echo isset($_SESSION['team_name'])
                ? htmlspecialchars($_SESSION['team_name'])
                : 'Onbekend'; ?>
        </span>
    </div>

    <a href="rooms/room_1.php" class="lose-btn">🔄 Probeer opnieuw</a>
    <a href="index.php" class="lose-btn-secondary">🏠 Terug naar home</a>

</body>

</html>