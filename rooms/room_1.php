<?php
// Start de sessie om de voortgang en teamnaam te onthouden
session_start();

// Laad de centrale databaseverbinding in
require_once '../dbcon.php';

// Als de voortgang nog niet bestaat, start deze op 0 opgeloste vragen
if (!isset($_SESSION['solved'])) {
    $_SESSION['solved'] = 0;
}

// 1. HAAL DE RIDDLES EN HINTS OP (Nu inclusief 'hint' in de SELECT)
try {
    $stmt = $db_connection->prepare("SELECT question, answer, hint FROM questions WHERE roomId = 1 ORDER BY id ASC");
    $stmt->execute();
    $riddles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database fout: " . $e->getMessage());
}

$totalQuestions = count($riddles);
$feedback = "";
$showModalIndex = null;

// Antwoord controle via PHP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_answer'])) {
    
    // Tijd direct bijwerken vanuit het meegestuurde formulier-veld
    if (isset($_POST['current_time_left']) && $_POST['current_time_left'] !== '') {
        $_SESSION['time_left'] = (int)$_POST['current_time_left'];
    }

    if (isset($_POST['riddle_index']) && $_POST['riddle_index'] !== '') {
        $current_index = (int)$_POST['riddle_index'];
        $userAnswer = strtolower(trim($_POST['user_answer']));
        $correctAnswer = strtolower(trim($riddles[$current_index]['answer'] ?? ''));

        if ($userAnswer === $correctAnswer) {
            if ($current_index === $_SESSION['solved']) {
                $_SESSION['solved']++;
            }
            
            if ($_SESSION['solved'] === $totalQuestions) {
                $triggerWinJS = true; 
            } else {
                $feedback = "<p style='color: #00ff66; font-weight: bold;'>✅ Correct!</p>";
            }
        } else {
            $feedback = "<p style='color: #ff3333; font-weight: bold;'>❌ Fout antwoord, probeer het opnieuw!</p>";
            $showModalIndex = $current_index;
        }
    }
}

// TIMER LOGICA: Gebruik de opgeslagen sessie-tijd
if (isset($_SESSION['time_left'])) {
    $TimeLimit = $_SESSION['time_left'];
} else {
    $TimeLimit = 180; 
}

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

  <h1>Team: <?php echo isset($_SESSION['teamname']) ? htmlspecialchars($_SESSION['teamname']) : (isset($_SESSION['team_name']) ? htmlspecialchars($_SESSION['team_name']) : "Gast"); ?></h1>

  <div class="container">
    <?php 
    foreach ($riddles as $index => $riddle) : 
        if ($index < $_SESSION['solved']) {
            $statusClass = 'box-correct'; 
            $style = 'background-color: green; border-color: green; color: white; display: flex; cursor: default;';
            $onclick = '';
        } elseif ($index === $_SESSION['solved']) {
            $statusClass = 'box-active';
            $style = 'display: flex;';
            // json_encode zorgt ervoor dat de tekst veilig naar JavaScript wordt gestuurd zonder te crashen op aanhalingstekens
            $onclick = "localOpenModal({$index}, " 
                . htmlspecialchars(json_encode($riddle['question']), ENT_QUOTES) . ", " 
                . htmlspecialchars(json_encode($riddle['hint']), ENT_QUOTES) . ")";
        } else {
            $statusClass = 'hidden';
            $style = 'display: none !important;';
            $onclick = '';
        }
    ?>
    <div class="box box<?php echo $index + 1; ?> <?php echo $statusClass; ?>" 
         style="<?php echo $style; ?>"
         onclick="<?php echo $onclick; ?>">
      Box <?php echo $index + 1; ?>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- De Popup en Overlay structuur -->
  <section class="overlay" id="overlay" onclick="localCloseModal()" style="<?php echo ($showModalIndex !== null) ? 'display: block;' : ''; ?>"></section>

  <section class="modal" id="modal" style="<?php echo ($showModalIndex !== null) ? 'display: block;' : ''; ?>">
    <h2>Escape Room Vraag</h2>
    <p id="riddle"><?php echo ($showModalIndex !== null) ? htmlspecialchars($riddles[$showModalIndex]['question']) : ''; ?></p>
    
    <!-- SPRINT 3: Hint Elementen toegevoegd -->
    <button type="button" id="hintBtn" onclick="showLocalHint()"
        style="background-color: #fcd34d; color: #121824; margin: 10px 0; padding: 8px 14px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-family: monospace;">
        💡 Hint tonen
    </button>
    <p id="hintText" style="color: #fcd34d; font-style: italic; min-height: 20px; margin: 5px 0; font-family: monospace;"></p>
    
    <!-- Onzichtbare opslagplek voor de JavaScript hinttekst -->
    <input type="hidden" id="current_hint" value="<?php echo ($showModalIndex !== null) ? htmlspecialchars($riddles[$showModalIndex]['hint'], ENT_QUOTES) : ''; ?>">

    <!-- Formulier stopt NU direct de actuele JavaScript timeLeft variabele in het formulier -->
    <form method="POST" action="" onsubmit="updateHiddenTime()">
        <input type="hidden" id="riddle_index" name="riddle_index" value="<?php echo ($showModalIndex !== null) ? $showModalIndex : ''; ?>">
        
        <!-- Onzichtbaar veld voor de seconden -->
        <input type="hidden" id="current_time_left" name="current_time_left" value="">
        
        <input type="text" name="user_answer" id="answer" placeholder="Typ je antwoord" required autocomplete="off">
        <br>
        <button type="submit" name="submit_answer">Verzenden</button>
    </form>
    
    <div id="feedback"><?php echo $feedback; ?></div>
  </section>

  <!-- DE TIMER INCLUSIE -->
  <?php include '../timer.php'; ?>

  <script>
    // Zorg dat de exacte JavaScript tijd in het formulier wordt gezet vlak voor verzenden
    function updateHiddenTime() {
        if (typeof timeLeft !== 'undefined') {
            document.getElementById('current_time_left').value = timeLeft;
        }
    }

    // EIGEN GEÏSOLEERDE MODAL FUNCTIES
    function localOpenModal(index, riddleText, hintText) {
        document.getElementById('riddle_index').value = index;
        document.getElementById('riddle').innerText = riddleText;
        document.getElementById('current_hint').value = hintText; // Sla de hint tijdelijk op
        document.getElementById('hintText').innerText = "";       // Maak oude hinttekst leeg
        document.getElementById('answer').value = "";
        document.getElementById('feedback').innerText = "";
        
        document.getElementById('modal').style.display = "block";
        document.getElementById('overlay').style.display = "block";
    }

    // Sluit de popup
    function localCloseModal() {
        document.getElementById('modal').style.display = "none";
        document.getElementById('overlay').style.display = "none";
    }

    // Toont de opgeslagen hint in de popup
    function showLocalHint() {
        let hint = document.getElementById('current_hint').value;
        document.getElementById('hintText').innerText = hint ? hint : "Geen hint beschikbaar voor deze vraag.";
    }

    // Als de kamer is gewonnen, stuur door met de reset parameter in de URL
    <?php if (isset($triggerWinJS) && $triggerWinJS === true): ?>
        if (typeof timerInterval !== 'undefined') {
            clearInterval(timerInterval); 
        }
        window.location.href = "room_2.php?action=reset_progress";
    <?php endif; ?>
  </script>

</body>
</html>