<?php
// functie: Scorepagina / Leaderboard voor de spelers
// auteur: Jehad

// Start de sessie op voor het geval dat nodig is
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Laad de centrale databaseverbinding in
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
    <title>Leaderboard - Escape Room</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body class="cyber-body">

<div class="cyber-container" style="width: 750px; max-width: 95%; margin: 40px auto;">
    <h1>🏆 Leaderboard</h1>
    <p>De snelste ontsnappingen uit het laboratorium:</p>

    <!-- Navigatie terug op basis van gebruikersrol -->
    <nav style="margin: 15px 0; text-align: left; font-size: 14px;">
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin/dashboard.php" style="color: #00ff66; margin-right: 20px; text-decoration: none;">← Admin Dashboard</a>
            <?php else: ?>
                <a href="speler_dashboard.php" style="color: #00ff66; margin-right: 20px; text-decoration: none;">← Speler Dashboard</a>
            <?php endif; ?>
        <?php endif; ?>
        <a href="index.php" style="color: #00ff66; text-decoration: none;">🏠 Home</a>
    </nav>

    <table class="score-table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Pos</th>
                <th>Teamnaam</th>
                <th>Teamlid 1</th>
                <th>Teamlid 2</th>
                <th>Eindtijd ⏳</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($teams)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #55667e; padding: 20px;">
                        Er zijn nog geen teams die het spel hebben uitgespeeld. Wees de eerste!
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($teams as $index => $team): ?>
                    <tr>
                        <!-- De top 3 krijgt een gouden, zilveren of bronzen kleur, de rest is groen -->
                        <td style="font-weight: bold; text-align: center; color: <?php echo $index == 0 ? '#ffd700' : ($index == 1 ? '#c0c0c0' : ($index == 2 ? '#cd7f32' : '#00ff66')); ?>;">
                            #<?php echo $index + 1; ?>
                        </td>
                        <td><?php echo htmlspecialchars($team['team_name']); ?></td>
                        <td><?php echo htmlspecialchars($team['member1']); ?></td>
                        <td><?php echo htmlspecialchars($team['member2'] ?? '-'); ?></td>
                        <td style="color: #00ff66; font-weight: bold; text-align: center;">
                            <?php
                            // Formatteer eindtijd van seconden naar mm:ss
                            $minuten = floor($team['end_time'] / 60);
                            $seconden = $team['end_time'] % 60;
                            echo $minuten . ":" . str_pad($seconden, 2, "0", STR_PAD_LEFT);
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <br>
    <a href="index.php" class="cyber-back-link">← Terug naar Home</a>
</div>

</body>
</html>