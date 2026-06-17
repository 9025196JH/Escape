<?php
// functie: Simpele pagina om een team aan te maken (voor spelers)
// auteur: Bashar (Student B)

// Start de sessie om de teamnaam in op te slaan
session_start();

// Controleer of de speler is ingelogd
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Laad de databaseverbinding
require_once 'dbcon.php';

$melding = "";

// Als het formulier is verstuurd, sla het team op
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_create_team'])) {
    $teamName = trim($_POST['team_name']);
    $member1  = trim($_POST['member1']);
    $member2  = trim($_POST['member2']);

    if (!empty($teamName) && !empty($member1)) {
        try {
            // Sla het team op in de database
            // INSERT IGNORE voorkomt een foutmelding als de teamnaam al bestaat (UNIQUE KEY)
            $stmt = $db_connection->prepare("
                INSERT IGNORE INTO teams (team_name, member1, member2) 
                VALUES (:team_name, :member1, :member2)
            ");
            $stmt->execute([
                ':team_name' => $teamName,
                ':member1'   => $member1,
                ':member2'   => $member2
            ]);

            // Sla de teamnaam op in de sessie zodat deze op elke pagina getoond kan worden
            $_SESSION['team_name'] = $teamName;

            // Stuur de speler door naar het speler-dashboard
            header("Location: speler_dashboard.php");
            exit();
        } catch (PDOException $e) {
            $melding = "<p style='color: #ff3333;'>❌ Fout bij opslaan: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        $melding = "<p style='color: #ff3333;'>❌ Vul minstens de teamnaam en het eerste teamlid in.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Aanmaken - Escape Room</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="login-container">
        <h1>Team Aanmaken</h1>
        <p style="font-size: 13px; color: #aaaaaa; margin-bottom: 15px;">
            Maak een team aan zodat je samen kunt spelen en jouw eindtijd wordt opgeslagen.
        </p>

        <!-- Toon eventuele foutmeldingen -->
        <?php echo $melding; ?>

        <form method="POST" action="create_team.php">
            <label for="team_name">Teamnaam:</label>
            <input type="text" id="team_name" name="team_name" required placeholder="Bijv. The Escapers">

            <label for="member1">Teamlid 1:</label>
            <input type="text" id="member1" name="member1" required placeholder="Naam van het eerste teamlid">

            <label for="member2">Teamlid 2 (optioneel):</label>
            <input type="text" id="member2" name="member2" placeholder="Naam van het tweede teamlid">

            <button type="submit" name="btn_create_team">Team aanmaken</button>
        </form>

        <br>
        <p style="text-align: center;">
            <a href="speler_dashboard.php" style="color: #00ff66;">Terug naar dashboard</a>
        </p>
    </div>

</body>

</html>