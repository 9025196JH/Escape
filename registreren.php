<?php
// functie: Registratiepagina voor spelers (en eventueel admins)
// auteur: Jehad

require_once 'CRUD registeren/functions.php'; 

$melding = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_register'])) {
    
    $conn = connectDb();
    $checkSql = "SELECT id FROM " . CRUD_TABLE . " WHERE username = :username";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->execute([':username' => $_POST['username']]);
    
    if ($checkStmt->rowCount() > 0) {
        $melding = "<p style='color: #ff3333; font-weight: bold; text-align: center; margin-bottom: 15px;'>❌ Deze gebruikersnaam is al bezet.</p>";
    } else {
        if (insertRecord($_POST) == true) {
            echo "<script>
                alert('Account succesvol aangemaakt! Je kunt nu inloggen.');
                window.location.href = 'index.php';
            </script>";
            exit();
        } else {
            $melding = "<p style='color: #ff3333; font-weight: bold; text-align: center; margin-bottom: 15px;'>❌ Er is iets misgegaan. Probeer het opnieuw.</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren - Escape Room</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body class="cyber-body">

<div class="cyber-container">
    <h1>Registreren</h1>

    <?php echo $melding; ?>

    <form method="POST" action="registreren.php">
        <div class="cyber-form-group">
            <label for="username">Gebruikersnaam / Email</label>
            <input type="text" id="username" name="username" placeholder="Voer je naam in" required autocomplete="off">
        </div>

        <div class="cyber-form-group">
            <label for="password">Wachtwoord</label>
            <input type="password" id="password" name="password" placeholder="Voer wachtwoord in" required>
        </div>

        <input type="hidden" name="role" value="speler">

        <button type="submit" name="btn_register" class="cyber-btn">Account Aanmaken</button>
    </form>
    
    <a href="index.php" class="cyber-back-link">Terug naar Home</a>
</div>

</body>
</html>