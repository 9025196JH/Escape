<?php
// Start de sessie om te controleren of de gebruiker is ingelogd
session_start();

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
  <!-- Let op: zorg dat je pad naar style.css klopt met jouw mappenstructuur -->
  <link rel="stylesheet" href="./css/style.css">
  
  <?php if ($showPopup): ?>
  <!-- Stukje JavaScript dat direct een pop-up melding geeft als je niet bent ingelogd -->
  <script>
    alert("Je moet eerst een account registreren en inloggen voordat je de kamers kunt spelen!");
  </script>
  <?php endif; ?>
</head>

<body>

  <div class="admin-btn" style="display: flex; gap: 10px; justify-content: flex-end; padding: 10px;">
    <!-- Knop voor Admin Inloggen -->
    <button>
      <a href="./admin/admin.php">Admin inloggen</a>
    </button>
    
    <!-- NIEUW: Knop voor Registreren (verwijst naar jouw insert.php) -->
    <button style="background-color: #28a745;">
  <a href="registreren.php" style="color: white; text-decoration: none; font-weight: bold;">Registreren</a>
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

  <!-- De links sturen we nu eerst langs een controle-script of we controleren het direct in de kamer-bestanden -->
  <button>
    <a href="./rooms/room_1.php">Klik hier voor een demonstratie van kamer 1</a>
  </button>

  <button>
    <a href="./rooms/room_3.php">Klik hier voor een demonstratie van kamer 3</a>
  </button>

</body>

</html>