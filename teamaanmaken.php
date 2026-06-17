<?php
// functie: Team aanmaken voor spelers (gekoppeld aan het speler-dashboard)
// auteur: Jehad

session_start(); 

// 1. STRIKTE CONTROLE: Alleen ingelogde spelers mogen hier komen
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// We linken naar de functies van de teams-CRUD om de databaseverbinding te hergebruiken
require_once 'CRUD team/functions.php'; 

$melding = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gekozen_naam = isset($_POST['team_name']) ? trim($_POST['team_name']) : '';
    $member1      = isset($_POST['member1']) ? trim($_POST['member1']) : '';
    $member2      = isset($_POST['member2']) ? trim($_POST['member2']) : '';
    
    if (!empty($gekozen_naam) && !empty($member1)) {
        $conn = connectDb();
        
        // Controleer of de teamnaam al bestaat
        $checkSql = "SELECT id FROM teams WHERE team_name = :team_name";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->execute([':team_name' => $gekozen_naam]);
        
        if ($checkStmt->rowCount() > 0) {
            $melding = "<p style='color: #ff3333; font-weight: bold; text-align: center; margin-bottom: 15px;'>❌ Deze teamnaam bestaat al.</p>";
        } else {
            // Sla de teamnaam op in de sessie voor het dashboard
            $_SESSION['teamname'] = $gekozen_naam;
            $_SESSION['team_name'] = $gekozen_naam;
            
            // Probeer het record op te slaan via de CRUD-functie
            if (insertRecord($_POST) == true) {
                echo "<script>
                    alert('Team succesvol aangemaakt! Veel succes in de Escape Room.');
                    window.location.href = 'speler_dashboard.php';
                </script>";
                exit();
            } else {
                // Fallback: Als insertRecord faalt, voeren we de query direct veilig uit met teamleden
                $sql_force = "INSERT INTO teams (team_name, member1, member2) VALUES (:team_name, :member1, :member2)";
                $stmt_force = $conn->prepare($sql_force);
                $stmt_force->execute([
                    ':team_name' => $gekozen_naam,
                    ':member1'   => $member1,
                    ':member2'   => !empty($member2) ? $member2 : null
                ]);
                
                echo "<script>
                    alert('Team succesvol aangemaakt! Veel succes in de Escape Room.');
                    window.location.href = 'speler_dashboard.php';
                </script>";
                exit();
            }
        }
    } else {
        $melding = "<p style='color: #ff3333; font-weight: bold; text-align: center; margin-bottom: 15px;'>❌ Vul de teamnaam en ten minste Teamlid 1 in.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Aanmaken - Escape Room</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body class="cyber-body">

<div class="cyber-container">
    <h1>Nieuw Team</h1>
    <p style="font-size: 13px; color: #aaaaaa; text-align: center; margin-bottom: 15px;">
        Maak een team aan om samen te spelen. Je wordt daarna direct teruggestuurd naar je dashboard.
    </p>

    <?php echo $melding; ?>

    <!-- Formulier stuurt nu netjes naar zichzelf (teamaanmaken.php) -->
    <form method="POST" action="teamaanmaken.php">
        <div class="cyber-form-group">
            <label for="team_name">Teamnaam</label>
            <input type="text" id="team_name" name="team_name" placeholder="Voer je teamnaam in" required autocomplete="off">
        </div>

        <div class="cyber-form-group" style="margin-top: 15px;">
            <label for="member1">Teamlid 1</label>
            <input type="text" id="member1" name="member1" placeholder="Naam eerste speler" required autocomplete="off">
        </div>

        <div class="cyber-form-group" style="margin-top: 15px;">
            <label for="member2">Teamlid 2 (optioneel)</label>
            <input type="text" id="member2" name="member2" placeholder="Naam tweede speler (optioneel)" autocomplete="off">
        </div>

        <button type="submit" class="cyber-btn" style="margin-top: 20px;">Team Aanmaken</button>
    </form>
    
    <a href="speler_dashboard.php" class="cyber-back-link" style="margin-top: 15px; display: inline-block;">Terug naar dashboard</a>
</div>

</body>
</html>