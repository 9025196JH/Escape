<?php
// Room 2 - Het geheime laboratorium
// Gemaakt door: Student B (Bashar)

// Start de sessie om voortgang en teamnaam te onthouden
session_start();

// Controleer of de gebruiker NIET is ingelogd
if (!isset($_SESSION['user_id'])) {
    // Stuur de gebruiker terug naar de index met een foutmelding in de URL
    header("Location: ../index.php?error=not_logged_in");
    exit();
}

// Laad de centrale databaseverbinding in
require_once '../dbcon.php';

// Als de teamnaam nog niet bestaat, gebruik "Gast"
if (!isset($_SESSION['team_name'])) {
    $_SESSION['team_name'] = 'Gast';
}

// Als de voortgang nog niet bestaat, start deze op 0 opgeloste vragen
if (!isset($_SESSION['solved'])) {
    $_SESSION['solved'] = 0;
}

// Timer instellingen
$TimeLimit = 120;              // 2 minuten per kamer
$NextPage  = "room_3.php";    // Volgende kamer bij winst (Room 3 van Student C)

// Sla de starttijd op in de sessie zodat win.php de eindtijd kan berekenen
if (!isset($_SESSION['room2_start'])) {
    $_SESSION['room2_start'] = time();
}

try {
    // Haal de vragen op uit de database voor roomId = 2 (deze kamer)
    // Inclusief hint kolom - Sprint 3: hint per vraag tonen
    $stmt = $db_connection->prepare("SELECT question, answer, hint FROM questions WHERE roomId = 2 ORDER BY id ASC");
    $stmt->execute();
    $riddles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database fout: " . $e->getMessage());
}

$totalQuestions = count($riddles);
$feedback = "";
$showModalIndex = null;

// Antwoord controle - wordt uitgevoerd als de speler een antwoord heeft verzonden
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_answer'])) {
    $current_index = (int)$_POST['riddle_index'];
    $userAnswer = strtolower(trim($_POST['user_answer']));
    $correctAnswer = strtolower(trim($riddles[$current_index]['answer']));

    // Vergelijk het antwoord van de speler met het juiste antwoord
    if ($userAnswer === $correctAnswer) {
        // Als de speler de huidige vraag goed heeft, ga naar de volgende
        if ($current_index === $_SESSION['solved']) {
            $_SESSION['solved']++;
        }

        // Als alle vragen zijn opgelost, ga naar de volgende kamer (room_3)
        if ($_SESSION['solved'] === $totalQuestions) {
            $_SESSION['solved'] = 0; // Reset voortgang voor een volgende keer
            header("Location: " . $NextPage);
            exit();
        }

        $feedback = "<p style='color: #00ff66; font-weight: bold;'>✅ Correct!</p>";
    } else {
        // Bij een fout antwoord blijft de modal open zodat de speler opnieuw kan proberen
        $feedback = "<p style='color: #ff3333; font-weight: bold;'>❌ Fout antwoord, probeer het opnieuw!</p>";
        $showModalIndex = $current_index;
    }
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escape Room - Room 2</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <!-- Teamnaam wordt uit de sessie gehaald en op elke pagina getoond -->
    <h1>Team: <?php echo isset($_SESSION['team_name']) ? htmlspecialchars($_SESSION['team_name']) : "Gast"; ?></h1>
    <h2>Room 2: Het geheime laboratorium</h2>
    <p>Los de vragen één voor één op. Alleen als je alles goed hebt, kom je uit de kamer.</p>

    <!-- De HTML code voor de timer van Student B -->
    <div id="timer" style="position: fixed; top: 20px; right: 20px; background-color: #333; color: #0f0; padding: 15px; border-radius: 10px; font-size: 24px; font-weight: bold; border: 2px solid #0f0; z-index: 9999; font-family: monospace;">
        02:00
    </div>

    <div class="container">
        <?php
        // Loop door alle vragen en toon ze één voor één
        // - Opgeloste vragen: groen en niet klikbaar
        // - Huidige vraag: zichtbaar en klikbaar
        // - Toekomstige vragen: verborgen (komen pas na het oplossen van de vorige)
        foreach ($riddles as $index => $riddle) :
            if ($index < $_SESSION['solved']) {
                // Deze vraag is al goed beantwoord
                $statusClass = 'box-correct';
                $style = 'background-color: green; border-color: green; color: white; display: flex; cursor: default;';
                $onclick = '';
            } elseif ($index === $_SESSION['solved']) {
                // Dit is de huidige vraag die de speler moet oplossen
                $statusClass = 'box-active';
                $style = 'display: flex;';
                // htmlspecialchars(ENT_QUOTES) zet " om naar &quot; zodat de aanhalingstekens
                // van json_encode niet botsen met de dubbele quotes van het onclick-attribuut.
                // De browser decodeert &quot; weer naar " voordat JavaScript wordt uitgevoerd.
                $onclick = "openModal({$index}, "
                    . htmlspecialchars(json_encode($riddle['question']), ENT_QUOTES) . ", "
                    . htmlspecialchars(json_encode($riddle['hint']), ENT_QUOTES) . ")";
            } else {
                // Deze vraag is nog niet beschikbaar - verberg hem
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
    <section class="overlay" id="overlay" onclick="closeModal()" style="<?php echo ($showModalIndex !== null) ? 'display: block;' : ''; ?>"></section>

    <section class="modal" id="modal" style="<?php echo ($showModalIndex !== null) ? 'display: block;' : ''; ?>">
        <h2>Escape Room Vraag</h2>
        <p id="riddle"><?php echo ($showModalIndex !== null) ? htmlspecialchars($riddles[$showModalIndex]['question']) : ''; ?></p>

        <!-- Hint knop - Sprint 3 functie door Student B (Bashar) -->
        <!-- Geel gekleurd (#fcd34d) zodat het opvalt binnen het groene lab-thema -->
        <button type="button" id="hintBtn" onclick="showHint()"
            style="background-color: #fcd34d; color: #121824; margin: 10px 0; padding: 8px 14px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-family: 'Courier New', monospace;">
            💡 Hint tonen
        </button>
        <p id="hintText" style="color: #fcd34d; font-style: italic; min-height: 22px; margin: 8px 0; font-family: 'Courier New', monospace;"></p>

        <!-- Hidden veld om de hint van de huidige vraag tijdelijk op te slaan voor JavaScript -->
        <input type="hidden" id="current_hint" value="<?php echo ($showModalIndex !== null) ? htmlspecialchars($riddles[$showModalIndex]['hint'], ENT_QUOTES) : ''; ?>">

        <form method="POST" action="">
            <input type="hidden" id="riddle_index" name="riddle_index" value="<?php echo ($showModalIndex !== null) ? $showModalIndex : ''; ?>">
            <input type="text" name="user_answer" id="answer" placeholder="Typ je antwoord" required autocomplete="off">
            <br>
            <button type="submit" name="submit_answer">Verzenden</button>
        </form>

        <div id="feedback"><?php echo $feedback; ?></div>
    </section>

    <!-- JavaScript voor de timer en de modal -->
    <script>
        // Timer logica - telt af van 120 seconden naar 0
        let timeLeft = <?php echo $TimeLimit; ?>;
        let timerElement = document.getElementById('timer');
        let timerInterval;

        // Toon de tijd in mm:ss formaat
        function updateTimerDisplay() {
            let minutes = Math.floor(timeLeft / 60);
            let seconds = timeLeft % 60;

            if (seconds < 10) {
                seconds = "0" + seconds;
            }
            timerElement.innerText = minutes + ":" + seconds;
        }

        // Tel elke seconde 1 af
        timerInterval = setInterval(function() {
            timeLeft = timeLeft - 1;
            updateTimerDisplay();

            // Als de tijd op is, ga naar de verliespagina
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                window.location.href = "../lose.php";
            }
        }, 1000);

        // Modal logica - opent het popup-venster met de vraag
        function openModal(index, riddleText, hintText) {
            document.getElementById('riddle_index').value = index;
            document.getElementById('riddle').innerText = riddleText;
            document.getElementById('answer').value = "";
            document.getElementById('feedback').innerText = "";

            // Reset de hint voor de nieuwe vraag
            document.getElementById('hintText').innerText = "";
            document.getElementById('current_hint').value = hintText;
            document.getElementById('hintBtn').style.display = "inline-block";

            document.getElementById('modal').style.display = "block";
            document.getElementById('overlay').style.display = "block";
        }

        // Sluit het popup-venster
        function closeModal() {
            document.getElementById('modal').style.display = "none";
            document.getElementById('overlay').style.display = "none";
        }

        // Hint tonen functie - Sprint 3 door Student B (Bashar)
        // Toont de hint onder de hint-knop en verbergt daarna de knop
        function showHint() {
            let hint = document.getElementById('current_hint').value;
            if (hint && hint !== "") {
                document.getElementById('hintText').innerText = "💡 " + hint;
                document.getElementById('hintBtn').style.display = "none";
            }
        }
    </script>

</body>

</html>