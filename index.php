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

  <link rel="stylesheet" href="./css/style.css">

  <?php if ($showPopup): ?>

    <script>
      alert("Je moet eerst een account registreren en inloggen voordat je de kamers kunt spelen!");
    </script>
  <?php endif; ?>
</head>

<body>

  <!-- Knoppen rechtsboven: Inloggen en Registreren -->
  <div class="admin-btn" style="display: flex; gap: 10px; justify-content: flex-end; padding: 10px;">
    <!-- Knop voor Inloggen (zowel spelers als admins) -->
    <button>
      <a href="./login.php">Inloggen</a>
    </button>

    <!-- Knop voor Registreren -->
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

  <p style="color: #ce5f0a; font-size: 14px; margin-top: 30px;">
    Maak eerst een account aan via "Registreren", of log in als je al een account hebt.
  </p>

</body>

</html>