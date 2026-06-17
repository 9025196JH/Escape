<?php
// Speler Dashboard - overzichtspagina voor spelers na het inloggen
// Gemaakt door: Student B (Bashar) & Jehad (Teamaanmaken koppeling)

// Start de sessie en controleer of de speler is ingelogd
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Laad de databaseverbinding om de teamnaam op te halen
require_once 'dbcon.php';

// Haal eventuele bestaande teamnaam op uit de database voor deze gebruiker
// We controleren zowel 'team_name' als 'teamname' voor de zekerheid
$teamNaam = 'Geen team';
if (isset($_SESSION['team_name']) && !empty($_SESSION['team_name'])) {
    $teamNaam = $_SESSION['team_name'];
} elseif (isset($_SESSION['teamname']) && !empty($_SESSION['teamname'])) {
    $teamNaam = $_SESSION['teamname'];
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Speler Dashboard - Escape Room</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <h1>Welkom, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>Je bent ingelogd als <strong>speler</strong>.</p>
    <p>Je huidige team: <strong style="color: #00ff66;"><?php echo htmlspecialchars($teamNaam); ?></strong></p>

    <!-- Navigatie-menu met alle acties voor de speler -->
    <nav style="display: flex; flex-direction: column; gap: 12px; max-width: 400px; margin: 30px auto;">

        <!-- Team aanmaken: Linkt nu naar jouw bestand 'teamaanmaken.php' -->
        <?php if ($teamNaam === 'Geen team'): ?>
            <a href="teamaanmaken.php"
                style="background-color: #1a2234; color: #00ff66; border: 2px solid #00ff66; padding: 15px; border-radius: 8px; text-decoration: none; font-weight: bold; text-align: center;">
                👥 Team aanmaken
            </a>
        <?php else: ?>
            <div style="background-color: #111a2e; color: #aaaaaa; border: 2px dashed #555; padding: 15px; border-radius: 8px; font-weight: bold; text-align: center;">
                ✅ Je zit al in team: <?php echo htmlspecialchars($teamNaam); ?>
            </div>
        <?php endif; ?>

        <!-- Start het spel - ga naar kamer 1 -->
        <a href="rooms/room_1.php"
            style="background-color: #1a2234; color: #00ff66; border: 2px solid #00ff66; padding: 15px; border-radius: 8px; text-decoration: none; font-weight: bold; text-align: center;">
            🎮 Start het spel (Kamer 1)
        </a>

        <!-- Bekijk de scores van alle teams -->
        <a href="scores.php"
            style="background-color: #1a2234; color: #00ff66; border: 2px solid #00ff66; padding: 15px; border-radius: 8px; text-decoration: none; font-weight: bold; text-align: center;">
            🏆 Bekijk scores
        </a>

        <!-- Terug naar home -->
        <a href="index.php"
            style="background-color: #1a2234; color: #00ff66; border: 2px solid #00ff66; padding: 15px; border-radius: 8px; text-decoration: none; font-weight: bold; text-align: center;">
            🏠 Terug naar home
        </a>

        <!-- Uitloggen -->
        <a href="logout.php"
            style="background-color: #891818; color: white; border: 2px solid #ff4444; padding: 15px; border-radius: 8px; text-decoration: none; font-weight: bold; text-align: center;">
            🚪 Uitloggen
        </a>

    </nav>

</body>

</html>