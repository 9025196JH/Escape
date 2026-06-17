<?php
// Start de sessie om te controleren of de gebruiker is ingelogd
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// TIJDELIJKE TEST-LOGICA: Zorgt ervoor dat je groepsgenoten de teamnaam ALTIJD 
// kunnen lezen in de kamers, ook zolang de inlogpagina er nog niet is!
if (isset($_SESSION['team_name'])) {
    $_SESSION['teamname'] = $_SESSION['team_name'];
}

// Hier vangen we op of iemand onberechtigd op een kamer klikte (via de URL parameters)
$showPopup = false;
if (isset($_GET['error']) && $_GET['error'] == 'not_logged_in') {
  $showPopup = true;
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Escape Room</title>
  <link rel="stylesheet" href="./css/style.css">

  <?php if ($showPopup): ?>
    <script>
      alert("Je moet eerst een account registreren en inloggen voordat je de kamers kunt spelen!");
    </script>
  <?php endif; ?>
</head>

<body>

  <!-- Knoppen rechtsboven: Inloggen, Registreren, Team en Admin Beheer -->
  <div class="admin-btn" style="display: flex; gap: 10px; justify-content: flex-end; padding: 10px; flex-wrap: wrap;">
    
    <!-- Knop voor Inloggen -->
    <button>
      <a href="./login.php" style="text-decoration: none; font-weight: bold;">Inloggen</a>
    </button>

    <!-- Knop voor Registreren -->
    <button style="background-color: #28a745;">
      <a href="registreren.php" style="color: white; text-decoration: none; font-weight: bold;">Registreren</a>
    </button>

    <!-- Knop voor Team Aanmaken -->
    <button style="background-color: #007bff;">
      <a href="teamaanmaken.php" style="color: white; text-decoration: none; font-weight: bold;">Team Aanmaken</a>
    </button>

    <!-- NIEUW: Knop voor Admin Paneel (Linkt naar admin/admin.php) -->
    <button style="background-color: #dc3545;">
      <a href="admin/admin.php" style="color: white; text-decoration: none; font-weight: bold;">🔒 Admin Beheer</a>
    </button>

  </div>

  <h1>Welkom</h1>

  <p>
    Jullie zijn opgesloten in een geheim laboratorium waar een experiment fout is gegaan.
    De wetenschappers zijn verdwenen en de deuren zijn automatisch op slot gegaan.
  </p>

  <p>
    Om te ontsnappen moeten jullie puzzels oplossen en de juiste codes vinden.
    In elke kamer wachten nieuwe raadsels die jullie stap voor stap dichter bij de uitgang brengen.
  </p>

  <p>
    Wees snel, werk samen en blijf rustig... want de tijd tikt ⏳
  </p>

  <!-- Demonstratie knoppen voor de kamers -->
  <div style="margin-bottom: 20px;">
    <button>
      <a href="./rooms/room_1.php">Klik hier voor een demonstratie van kamer 1</a>
    </button>

    <button>
      <a href="./rooms/room_3.php">Klik hier voor een demonstratie van kamer 3</a>
    </button>
  </div>

  <!-- Knop om naar het openbare Leaderboard (Scorepagina) te gaan -->
  <button style="background-color: #ffc107;">
    <a href="scores.php" style="color: black; text-decoration: none; font-weight: bold;">🏆 Bekijk Scorepagina (Leaderboard)</a>
  </button>

  <p style="color: #ce5f0a; font-size: 14px; margin-top: 30px;">
    Maak eerst een account aan via "Registreren", of log in als je al een account hebt.
  </p>

</body>
</html>