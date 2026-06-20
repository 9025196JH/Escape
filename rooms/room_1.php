<?php
// --- SESSIE STARTEN ---
// Dit zorgt ervoor dat de website onthoudt wie de speler is, hoe ver ze zijn en hoeveel tijd er nog over is.
session_start();

// --- VERBINDING MET DE DATABASE ---
// Hier koppelen we de code aan de database waar alle vragen en antwoorden veilig zijn opgeslagen.
require_once '../dbcon.php';

// --- VOORTGANG INITIALISEREN ---
// Als de speler net begint, zetten we het aantal opgeloste vragen netjes op 0.
if (!isset($_SESSION['solved'])) {
    $_SESSION['solved'] = 0;
}

// --- VRAGEN EN HINTS OPHALEN ---
// We vragen aan de database: "Geef mij alle vragen, antwoorden en hints voor Kamer 1".
try {
    $stmt = $db_connection->prepare("SELECT question, answer, hint FROM questions WHERE roomId = 1 ORDER BY id ASC");
    $stmt->execute();
    $riddles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database fout: " . $e->getMessage());
}

// --- STANDAARD INSTELLINGEN ---
// We tellen hoeveel vragen er in totaal zijn en maken alvast wat hulpvariabelen aan.
$totalQuestions = count($riddles);
$feedback = "";
$showModalIndex = null;
$triggerWinJS = false;

// --- ANTWOORD CONTROLEREN ---
// Dit stukje code start pas op het moment dat een speler op de knop "Verzenden" drukt.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_answer'])) {
    
    // Tijd bijwerken: We slaan de seconden die over zijn direct op in het geheugen van de website.
    if (isset($_POST['current_time_left']) && $_POST['current_time_left'] !== '') {
        $_SESSION['time_left'] = (int)$_POST['current_time_left'];
    }

    // Controleren of het antwoord juist is:
    if (isset($_POST['riddle_index']) && $_POST['riddle_index'] !== '') {
        $current_index = (int)$_POST['riddle_index'];
        
        // We halen spaties weg en maken letters klein, zodat "Antwoord" en "antwoord" allebei goed zijn.
        $userAnswer = strtolower(trim($_POST['user_answer']));
        $correctAnswer = strtolower(trim($riddles[$current_index]['answer'] ?? ''));

        // Als het antwoord klopt:
        if ($userAnswer === $correctAnswer) {
            // Verhoog de voortgang van de speler met +1.
            if ($current_index === $_SESSION['solved']) {
                $_SESSION['solved']++;
            }
            
            // Als alle vragen zijn opgelost, krijgt de code een seintje dat de speler heeft GEWONNEN.
            if ($_SESSION['solved'] === $totalQuestions) {
                $triggerWinJS = true; 
            } else {
                $feedback = "<p style='color: #00ff66; font-weight: bold;'>✅ Correct!</p>";
            }
        } else {
            // Als het antwoord fout is: geef rode tekst en zorg dat de vraag in beeld blijft staan.
            $feedback = "<p style='color: #ff3333; font-weight: bold;'>❌ Fout antwoord, probeer het opnieuw!</p>";
            $showModalIndex = $current_index; 
        }
    }
}

// --- DE TIMERTIJD INSTELLEN ---
// Als er al een tijd in het geheugen stond gebruiken we die, anders krijgt de speler 120 seconden.
$TimeLimit = isset($_SESSION['time_left']) ? $_SESSION['time_left'] : 120;
$NextPage  = "room_2.php"; 
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Escape Room - Room 1</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

  <!-- TEAMNAAM TONEN -->
  <!-- Hier laten we de naam van het team groot bovenaan het scherm zien. Staat er niks? Dan tonen we "Gast". -->
  <h1>Team: <?php echo isset($_SESSION['teamname']) ? htmlspecialchars($_SESSION['teamname']) : (isset($_SESSION['team_name']) ? htmlspecialchars($_SESSION['team_name']) : "Gast"); ?></h1>

  <!-- DE VRAAG-BOXEN OP HET SCHERM ZETTEN -->
  <!-- We lopen met een lus (loop) door alle vragen heen en maken voor elke vraag een klikbare box op het scherm. -->
  <div class="container">
    <?php 
    foreach ($riddles as $index => $riddle) : 
        // Box is al opgelost: maak hem groen en zorg dat je er niet meer op kunt klikken.
        if ($index < $_SESSION['solved']) {
            $statusClass = 'box-correct'; 
            $style = 'background-color: green; border-color: green; color: white; display: flex; cursor: default;';
            $onclick = '';
        } 
        // Box is NU aan de beurt: deze is klikbaar en opent de vraag en de juiste hint.
        elseif ($index === $_SESSION['solved']) {
            $statusClass = 'box-active';
            $style = 'display: flex;';
            $onclick = "localOpenModal({$index}, " 
                . htmlspecialchars(json_encode($riddle['question']), ENT_QUOTES) . ", " 
                . htmlspecialchars(json_encode($riddle['hint']), ENT_QUOTES) . ")";
        } 
        // Box is nog op slot: deze verbergen we zodat de speler hem nog niet kan zien.
        else {
            $statusClass = 'hidden';
            $style = 'display: none !important;';
            $onclick = '';
        }
    ?>
    <!-- Dit is de uiteindelijke HTML-box waar de speler op klikt -->
    <div class="box box<?php echo $index + 1; ?> <?php echo $statusClass; ?>" 
         style="<?php echo $style; ?>"
         onclick="<?php echo $onclick; ?>">
      Box <?php echo $index + 1; ?>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- DE POP-UP EN DE DONKERE ACHTERGROND -->
  <!-- Dit is het venster dat tevoorschijn komt als je op een actieve box klikt. -->
  <section class="overlay" id="overlay" onclick="localCloseModal()" style="<?php echo ($showModalIndex !== null) ? 'display: block;' : ''; ?>"></section>

  <section class="modal" id="modal" style="<?php echo ($showModalIndex !== null) ? 'display: block;' : ''; ?>">
    <h2>Escape Room Vraag</h2>
    
    <!-- Hier komt de tekst van de vraag te staan -->
    <p id="riddle"><?php echo ($showModalIndex !== null) ? htmlspecialchars($riddles[$showModalIndex]['question']) : ''; ?></p>
    
    <!-- De gele Hint-knop -->
    <button type="button" id="hintBtn" onclick="showLocalHint()"
        style="background-color: #fcd34d; color: #121824; margin: 10px 0; padding: 8px 14px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-family: monospace;">
        💡 Hint tonen
    </button>
    
    <!-- Hier komt de tekst van de hint te staan als je op de gele knop drukt -->
    <p id="hintText" style="color: #fcd34d; font-style: italic; min-height: 20px; margin: 5px 0; font-family: monospace;"></p>
    
    <!-- Een onzichtbaar opslagplekje voor de hint, zodat de computer onthoudt welke hint bij deze vraag hoort -->
    <input type="hidden" id="current_hint" value="<?php echo ($showModalIndex !== null) ? htmlspecialchars($riddles[$showModalIndex]['hint'], ENT_QUOTES) : ''; ?>">

    <!-- HET INVULVELD EN DE VERZENDKNOP -->
    <form method="POST" action="" onsubmit="updateHiddenTime()">
        <!-- Onzichtbare velden om het vraagnummer en de resterende tijd mee te sturen naar de server -->
        <input type="hidden" id="riddle_index" name="riddle_index" value="<?php echo ($showModalIndex !== null) ? $showModalIndex : ''; ?>">
        <input type="hidden" id="current_time_left" name="current_time_left" value="">
        
        <!-- Het tekstvak waar de speler zijn antwoord typt -->
        <input type="text" name="user_answer" id="answer" placeholder="Typ je antwoord" required autocomplete="off" autofocus>
        <br>
        <button type="submit" name="submit_answer">Verzenden</button>
    </form>
    
    <!-- Hier komt te staan of het antwoord Goed (groen) of Fout (rood) was -->
    <div id="feedback"><?php echo $feedback; ?></div>
  </section>

  <!-- DE LIVE TIMER INLADEN -->
  <!-- Hier plakken we het aparte timer-bestand erin, zodat de klok live gaat aftellen op het scherm. -->
  <?php include '../timer.php'; ?>

  <!-- JAVASCRIPT: CODE DIE RECHTSTREEKS IN DE BROWSER VAN DE SPELER DRAAIT -->
  <script>
    // TIJD VEILIGSTELLEN: Vlak voordat het antwoord wordt verzonden, grijpt deze functie snel de stand van de timer.
    function updateHiddenTime() {
        if (typeof timeLeft !== 'undefined') {
            document.getElementById('current_time_left').value = timeLeft;
        }
    }

    // POP-UP OPENEN: Deze functie vult de pop-up met de juiste vraag en wist het oude antwoord en de oude feedback.
    function localOpenModal(index, riddleText, hintText) {
        document.getElementById('riddle_index').value = index;
        document.getElementById('riddle').innerText = riddleText;
        document.getElementById('current_hint').value = hintText;
        document.getElementById('hintText').innerText = "";
        document.getElementById('answer').value = "";
        document.getElementById('feedback').innerText = "";
        
        document.getElementById('modal').style.display = "block";
        document.getElementById('overlay').style.display = "block";
        document.getElementById('answer').focus(); // Zet de cursor direct klaar in het invulveld
    }

    // POP-UP SLUITEN: Verbergt het venster en de donkere achtergrond weer.
    function localCloseModal() {
        document.getElementById('modal').style.display = "none";
        document.getElementById('overlay').style.display = "none";
    }

    // HINT TONEN: Haalt de verborgen hinttekst tevoorschijn en zet hem op het scherm.
    function showLocalHint() {
        let hint = document.getElementById('current_hint').value;
        document.getElementById('hintText').innerText = hint ? hint : "Geen hint beschikbaar voor deze vraag.";
    }

    // KAMER GEWONNEN: Als alle vragen goed zijn, stopt de timer en sturen we de speler automatisch door naar Kamer 2.
    <?php if ($triggerWinJS): ?>
        if (typeof timerInterval !== 'undefined') {
            clearInterval(timerInterval); 
        }
        window.location.href = "room_2.php?room_clear=1";
    <?php endif; ?>
  </script>

</body>
</html>