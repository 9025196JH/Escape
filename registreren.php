<?php
// functie: Registratiepagina voor spelers (en eventueel admins)
// auteur: Jouw Naam

// We linken naar de submap waar je functies staan met behoud van de spatie
require_once 'CRUD registeren/functions.php';

$melding = "";

// Controleer of het formulier is verstuurd
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_register'])) {
    
    // Controleren of de gebruikersnaam al bestaat om dubbele accounts te voorkomen
    $conn = connectDb();
    $checkSql = "SELECT id FROM " . CRUD_TABLE . " WHERE username = :username";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->execute([':username' => $_POST['username']]);
    
    if ($checkStmt->rowCount() > 0) {
        $melding = "<p style='color: #ff3333; font-weight: bold;'>❌ Deze gebruikersnaam is al bezet. Kies een andere naam.</p>";
    } else {
        // Gebruik de insertRecord functie uit je CRUD om de gebruiker veilig op te slaan
        if (insertRecord($_POST) == true) {
            // Succes-pop-up tonen via JavaScript en daarna terugsturen naar de startpagina
            echo "<script>
                alert('Account succesvol aangemaakt! Je kunt nu inloggen.');
                window.location.href = 'index.php';
            </script>";
            exit();
        } else {
            $melding = "<p style='color: #ff3333; font-weight: bold;'>❌ Er is iets misgegaan. Probeer het opnieuw.</p>";
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
    <!-- We linken direct naar jullie centrale stylesheet voor dezelfde look -->
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

    <div class="registration-container" style="max-width: 400px; margin: 50px auto; padding: 20px; background-color: #f4f4f4; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1);">
        <h1>Account Aanmaken</h1>
        <p>Maak een account aan om de escape room te kunnen spelen.</p>

        <!-- Toon eventuele foutmeldingen -->
        <?php echo $melding; ?>

        <form method="post" action="registreren.php">
            <div style="margin-bottom: 15px;">
                <label for="username" style="display: block; font-weight: bold; margin-bottom: 5px;">Gebruikersnaam:</label>
                <input type="text" id="username" name="username" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label for="password" style="display: block; font-weight: bold; margin-bottom: 5px;">Wachtwoord:</label>
                <input type="password" id="password" name="password" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <!-- Standaard staat de rol op 'speler' voor nieuwe registraties via deze pagina -->
            <input type="hidden" name="role" value="speler">

            <button type="submit" name="btn_register" style="background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%;">
                Account Registreren
            </button>
        </form>
        
        <br>
        <a href="index.php" style="display: block; text-align: center; color: #333;">Terug naar Home</a>
    </div>

</body>
</html>