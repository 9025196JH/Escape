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

// Scorepagina - toont alle teams met hun eindtijd


// Start de sessie om te controleren of de speler is ingelogd
session_start();

// Laad de databaseverbinding
require_once 'dbcon.php';

// Haal alle teams op die een eindtijd hebben, gesorteerd op snelste tijd
try {
    $stmt = $db_connection->prepare("
        SELECT team_name, member1, member2, end_time 
        FROM teams 
        WHERE end_time IS NOT NULL 
        ORDER BY end_time ASC
    ");
    $stmt->execute();
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $teams = [];
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scores - Escape Room</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <h1>🏆 Scorebord</h1>
    <p>Dit is het overzicht van alle teams en hun eindtijd. De snelste teams staan bovenaan.</p>

    <!-- Navigatie terug -->
    <nav style="margin: 20px;">
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin/dashboard.php" style="color: #00ff66; margin-right: 20px;">← Admin Dashboard</a>
            <?php else: ?>
                <a href="speler_dashboard.php" style="color: #00ff66; margin-right: 20px;">← Speler Dashboard</a>
            <?php endif; ?>
        <?php endif; ?>
        <a href="index.php" style="color: #00ff66;">🏠 Home</a>
    </nav>

    <!-- Tabel met alle teams en hun eindtijd -->
    <?php if (!empty($teams)): ?>
        <table style="margin: 20px auto; border-collapse: collapse; width: 80%;">

            <tr style="background-color: #1a2234; color: #00ff66;">
                <th style="padding: 12px; border: 2px solid #00ff66;">Plaats</th>
                <th style="padding: 12px; border: 2px solid #00ff66;">Teamnaam</th>
                <th style="padding: 12px; border: 2px solid #00ff66;">Teamlid 1</th>
                <th style="padding: 12px; border: 2px solid #00ff66;">Teamlid 2</th>
                <th style="padding: 12px; border: 2px solid #00ff66;">Eindtijd</th>
            </tr>

            <?php foreach ($teams as $index => $team): ?>
                <tr style="background-color: #1a2234; color: white;">
                    <td style="padding: 10px; border: 1px solid #00ff66; text-align: center;">
                        <?php echo $index + 1; ?>
                    </td>
                    <td style="padding: 10px; border: 1px solid #00ff66;">
                        <?php echo htmlspecialchars($team['team_name']); ?>
                    </td>
                    <td style="padding: 10px; border: 1px solid #00ff66;">
                        <?php echo htmlspecialchars($team['member1']); ?>
                    </td>
                    <td style="padding: 10px; border: 1px solid #00ff66;">
                        <?php echo htmlspecialchars($team['member2'] ?? '-'); ?>
                    </td>
                    <td style="padding: 10px; border: 1px solid #00ff66; text-align: center; color: #00ff66; font-weight: bold;">
                        <?php
                        // Format eindtijd van seconden naar mm:ss
                        $minuten = floor($team['end_time'] / 60);
                        $seconden = $team['end_time'] % 60;
                        echo $minuten . ":" . str_pad($seconden, 2, "0", STR_PAD_LEFT);
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
    <?php else: ?>
        <p style="color: #aaaaaa; margin-top: 30px;">
            Er zijn nog geen teams die het spel hebben uitgespeeld.<br>
            Wees de eerste!
        </p>
    <?php endif; ?>

</body>


</html>