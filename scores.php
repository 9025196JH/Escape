<?php
// Scorepagina - toont alle teams met hun eindtijd
// Gemaakt door: Student B (Bashar)

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