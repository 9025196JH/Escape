<?php
// functie: Inlogpagina voor spelers en admins
// auteur: Bashar (Student B)

// Start de sessie om inloggegevens te bewaren
session_start();

// Als de gebruiker al is ingelogd, stuur hem door op basis van rol
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: speler_dashboard.php");
    }
    exit();
}

// Laad de centrale databaseverbinding in
require_once 'dbcon.php';

$foutmelding = "";

// Controleer of het formulier is verzonden
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_login'])) {
    $username    = trim($_POST['username']);
    $password    = $_POST['password'];
    $gekozenRol  = $_POST['rol']; // wat de gebruiker heeft gekozen in het formulier

    // Controleer of beide velden zijn ingevuld
    if (!empty($username) && !empty($password)) {
        // Zoek de gebruiker op in de database tabel 'users'
        $stmt = $db_connection->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Controleer of de gebruiker bestaat en het wachtwoord klopt
        // password_verify vergelijkt het ingevoerde wachtwoord met de hash in de database
        if ($user && password_verify($password, $user['password'])) {
            // Controleer of de gekozen rol overeenkomt met de rol in de database
            // Dit voorkomt dat een speler zich als admin probeert in te loggen
            if ($user['role'] !== $gekozenRol) {
                $foutmelding = "Dit account is geen " . $gekozenRol . ". Kies de juiste rol.";
            } else {
                // Sla de sessiegegevens op
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role']     = $user['role'];

                // Stuur door op basis van rol
                // Admins gaan naar het admin-dashboard met alle beheer-links
                // Spelers gaan naar het speler-dashboard met speel- en score-links
                if ($user['role'] === 'admin') {
                    header("Location: admin/dashboard.php");
                } else {
                    header("Location: speler_dashboard.php");
                }
                exit();
            }
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

        <!-- Korte uitleg -->
        <p style="font-size: 13px; color: #aaaaaa; margin-bottom: 15px;">
            Kies hieronder of je als speler of als admin inlogt.
        </p>

        <!-- Toon een foutmelding als er iets misgaat -->
        <?php if (!empty($foutmelding)): ?>
            <p style="color: #ff3333; font-weight: bold; text-align: center;"><?php echo $foutmelding; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <!-- Keuze tussen speler en admin -->
            <label style="display: block; text-align: left; margin-bottom: 8px; color: #00ff66;">
                Ik log in als:
            </label>
            <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                    <input type="radio" name="rol" value="speler" checked>
                    <span>Speler</span>
                </label>
                <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                    <input type="radio" name="rol" value="admin">
                    <span>Admin</span>
                </label>
            </div>

            <label for="username">Gebruikersnaam</label>
            <input type="text" id="username" name="username" placeholder="Voer je gebruikersnaam in" required>

            <label for="password">Wachtwoord</label>
            <input type="password" id="password" name="password" placeholder="Voer je wachtwoord in" required>

            <button type="submit" name="btn_login">Inloggen</button>
        </form>

        <br>
        <p style="text-align: center;">
            Nog geen account? <a href="registreren.php" style="color: #00ff66;">Registreer hier</a>
        </p>
        <p style="text-align: center;">
            <a href="index.php" style="color: #00ff66;">Terug naar home</a>
        </p>
    </div>

</body>

</html>