<?php
// jehad
// admin
// Start de sessie om bij te houden wie er inlogt
session_start();

// Laad jullie centrale databaseverbinding in
require_once '../dbcon.php'; 

$foutmelding = "";

// Controleer of het formulier is verzonden
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // Zoek de admin op in de database tabel 'users'
        // We controleren hier EXTRA of de rol ook echt 'admin' is
        $stmt = $db_connection->prepare("SELECT * FROM users WHERE username = :username AND role = 'admin'");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Controleer of de admin bestaat en het gehashte wachtwoord klopt
        if ($user && password_verify($password, $user['password'])) {
            // Sla de sessiegegevens op
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // VERBETERD: Stuur de admin door naar het centrale dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $foutmelding = "Onjuiste inloggegevens of je account is geen Admin.";
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
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="login-container">
    <h1>Admin Login</h1>

    <!-- Toon een foutmelding als er iets misgaat -->
    <?php if (!empty($foutmelding)): ?>
        <p style="color: #ff3333; font-weight: bold; text-align: center;"><?php echo $foutmelding; ?></p>
    <?php endif; ?>

    <!-- POST-methode toegevoegd om gegevens veilig te verzenden -->
    <form method="POST" action="">
        <label for="username">Gebruikersnaam / Email</label>
        <!-- Name-attribuut toegevoegd voor PHP koppeling -->
        <input type="text" id="username" name="username" placeholder="Voer je naam in" required>

        <label for="password">Wachtwoord</label>
        <!-- Name-attribuut toegevoegd voor PHP koppeling -->
        <input type="password" id="password" name="password" placeholder="Voer wachtwoord in" required>

        <!-- Submit-knop heeft nu een name gekregen voor de PHP check -->
        <button type="submit" name="btn_login">Inloggen</button>
    </form>
</div>

</body>
</html>
