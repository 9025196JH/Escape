<?php
// Admin Dashboard - overzicht van alle beschikbare acties voor de admin
// Gemaakt door: Student B (Bashar)
// Sprint 3 - Administratief overzicht voor de admin

// Start de sessie en controleer of de gebruiker een admin is
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Als de gebruiker geen admin is, stuur hem naar de login-pagina
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Escape Room</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h1>Admin Dashboard</h1>
<p>Welkom, <?php echo htmlspecialchars($_SESSION['username']); ?>! Je bent ingelogd als <strong>admin</strong>.</p>
<p>Kies hieronder wat je wilt doen.</p>

<!-- Navigatie-menu met alle acties voor de admin -->
<nav style="display: flex; flex-direction: column; gap: 12px; max-width: 400px; margin: 30px auto;">

    <!-- Sprint 3 - Student B: CRUD voor vragen (eigen taak) -->
    <a href="crud_vragen/index.php"
       style="background-color: #1a2234; color: #00ff66; border: 2px solid #00ff66; padding: 15px; border-radius: 8px; text-decoration: none; font-weight: bold;">
        📝 CRUD Vragen (beheer vragen, antwoorden en hints)
    </a>

    <!-- Sprint 3 - CRUD voor teams (beheer teams, teamleden en scores) -->
    <a href="crud_teams/index.php"
       style="background-color: #1a2234; color: #00ff66; border: 2px solid #00ff66; padding: 15px; border-radius: 8px; text-decoration: none; font-weight: bold;">
        👥 CRUD Teams (beheer teams en teamleden)
    </a>

    <!-- Scorepagina bekijken -->
    <a href="../scores.php"
       style="background-color: #1a2234; color: #00ff66; border: 2px solid #00ff66; padding: 15px; border-radius: 8px; text-decoration: none; font-weight: bold;">
        🏆 Scorebord (bekijk alle eindtijden)
    </a>

    <!-- Student A: CRUD voor gebruikers/registraties -->
    <a href="../CRUD registeren/index.php"
       style="background-color: #1a2234; color: #00ff66; border: 2px solid #00ff66; padding: 15px; border-radius: 8px; text-decoration: none; font-weight: bold;">
        👤 CRUD Registraties (beheer gebruikers)
    </a>

    <!-- Student C: Overzicht van alle reviews -->
    <a href="show_all_reviews.php"
       style="background-color: #1a2234; color: #00ff66; border: 2px solid #00ff66; padding: 15px; border-radius: 8px; text-decoration: none; font-weight: bold;">
        ⭐ Alle Reviews (overzicht van feedback)
    </a>

    <!-- Algemeen: Terug naar home -->
    <a href="../index.php"
       style="background-color: #1a2234; color: #00ff66; border: 2px solid #00ff66; padding: 15px; border-radius: 8px; text-decoration: none; font-weight: bold;">
        🏠 Terug naar home
    </a>

    <!-- Algemeen: Uitloggen -->
    <a href="../logout.php"
       style="background-color: #891818; color: white; border: 2px solid #ff4444; padding: 15px; border-radius: 8px; text-decoration: none; font-weight: bold;">
        🚪 Uitloggen
    </a>

</nav>

</body>
</html>
