<?php
// functie: Team aanmaken voor spelers (in de hoofdmap)
// auteur: Jehad
session_start(); 

require_once 'CRUD team/functions.php'; 

$melding = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gekozen_naam = isset($_POST['team_name']) ? trim($_POST['team_name']) : '';
    
    if (!empty($gekozen_naam)) {
        $conn = connectDb();
        $checkSql = "SELECT id FROM teams WHERE team_name = :team_name";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->execute([':team_name' => $gekozen_naam]);
        
        if ($checkStmt->rowCount() > 0) {
            $melding = "<p style='color: #ff3333; font-weight: bold; text-align: center; margin-bottom: 15px;'>❌ Deze teamnaam bestaat al.</p>";
        } else {
            
            $_SESSION['teamname'] = $gekozen_naam;
            $_SESSION['team_name'] = $gekozen_naam;
            
            if (insertRecord($_POST) == true) {
                echo "<script>
                    alert('Team succesvol aangemaakt! Veel succes in de Escape Room.');
                    window.location.href = 'index.php';
                </script>";
                exit();
            } else {
                // تفعيل الإدخال الإجباري في حال حدوث أي تعارض في الدالات الأساسية
                $sql_force = "INSERT INTO teams (team_name) VALUES (:team_name)";
                $stmt_force = $conn->prepare($sql_force);
                $stmt_force->execute([':team_name' => $gekozen_naam]);
                
                echo "<script>
                    alert('Team succesvol aangemaakt! Veel succes in de Escape Room.');
                    window.location.href = 'index.php';
                </script>";
                exit();
            }
        }
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

    <?php echo $melding; ?>

    <form method="POST" action="teamaanmaken.php">
        <div class="cyber-form-group">
            <label for="team_name">Teamnaam</label>
            <input type="text" id="team_name" name="team_name" placeholder="Voer je teamnaam in" required autocomplete="off">
        </div>

        <button type="submit" class="cyber-btn">Team Aanmaken</button>
    </form>
    
    <a href="index.php" class="cyber-back-link">Terug naar Home</a>
</div>

</body>
</html>