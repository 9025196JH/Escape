<?php
// functie: Inlogpagina voor spelers
// auteur: Bashar (Student B)

// Start de sessie om inloggegevens te bewaren
session_start();

// Als de gebruiker al is ingelogd, stuur hem door naar speler dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: speler_dashboard.php");
    exit();
}

// Laad de centrale databaseverbinding in
require_once 'dbcon.php';

$foutmelding = "";

// Controleer of het formulier is verzonden
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_login'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Controleer of beide velden zijn ingevuld
    if (!empty($username) && !empty($password)) {

        // Zoek de gebruiker op in de database
        $stmt = $db_connection->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Controleer wachtwoord
        if ($user && password_verify($password, $user['password'])) {

            // Sla sessiegegevens op
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Stuur speler door naar dashboard
            header("Location: speler_dashboard.php");
            exit();

        } else {
            $foutmelding = "Onjuiste inloggegevens.";
        }

    } else {
        $foutmelding = "Vul alle velden in.";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen - Escape Room</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="login-container">
        <h1>Inloggen</h1>

        <p style="font-size: 13px; color: #aaaaaa; margin-bottom: 15px;">
            Log in om verder te gaan.
        </p>

        <!-- Toon foutmelding -->
        <?php if (!empty($foutmelding)): ?>
            <p style="color: #ff3333; font-weight: bold; text-align: center;">
                <?php echo $foutmelding; ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="">

            <label for="username">Gebruikersnaam</label>
            <input type="text" id="username" name="username" placeholder="Voer je gebruikersnaam in" required>

            <label for="password">Wachtwoord</label>
            <input type="password" id="password" name="password" placeholder="Voer je wachtwoord in" required>

            <button type="submit" name="btn_login">Inloggen</button>

        </form>

        <br>

        <p style="text-align: center;">
            Nog geen account?
            <a href="registreren.php" style="color: #00ff66;">Registreer hier</a>
        </p>

        <p style="text-align: center;">
            <a href="index.php" style="color: #00ff66;">Terug naar home</a>
        </p>

    </div>

</body>

</html>