<?php
// functie: Scorepagina / Leaderboard voor de spelers
// auteur: Jehad

// Start de sessie op voor het geval dat nodig is binnen de hoofdmap
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// We linken naar de functies van de teams-CRUD om de databaseverbinding te hergebruiken
require_once 'CRUD team/functions.php';

$conn = connectDb();

// Haal alle teams op die een eindtijd hebben en sorteer ze van snel naar traag (ASC)
$sql = "SELECT team_name, end_time FROM teams WHERE end_time IS NOT NULL ORDER BY end_time ASC";
$query = $conn->prepare($sql);
$query->execute();
$scores = $query->fetchAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - Escape Room</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body class="cyber-body">

<!-- We geven deze container een inline breedte-override zodat de tabel mooi de ruimte heeft -->
<div class="cyber-container" style="width: 550px; max-width: 95%;">
    <h1>🏆 Leaderboard</h1>
    <p>De snelste ontsnappingen uit het laboratorium:</p>

    <table class="score-table">
        <thead>
            <tr>
                <th>Pos</th>
                <th>Teamnaam</th>
                <th>Eindtijd ⏳</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($scores)): ?>
                <tr>
                    <td colspan="3" style="text-align: center; color: #55667e; padding: 20px;">
                        Er zijn nog geen eindtijden geregistreerd.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($scores as $index => $score): ?>
                    <tr>
                        <!-- De top 3 krijgt een mooie goud, zilver of bronskleur, de rest is groen -->
                        <td style="font-weight: bold; color: <?php echo $index == 0 ? '#ffd700' : ($index == 1 ? '#c0c0c0' : ($index == 2 ? '#cd7f32' : '#00ff66')); ?>;">
                            #<?php echo $index + 1; ?>
                        </td>
                        <td><?php echo htmlspecialchars($score['team_name']); ?></td>
                        <td style="color: #00ff66; font-weight: bold;"><?php echo htmlspecialchars($score['end_time']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <a href="index.php" class="cyber-back-link">← Terug naar Home</a>
</div>

</body>
</html>