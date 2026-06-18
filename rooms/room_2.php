<?php
// Room 2 - Het geheime laboratorium
// Gemaakt door: Student B (Bashar) & Gecorrigeerd voor kamerwissel

// Start de sessie om voortgang en teamnaam te onthouden
session_start();

if (!isset($_SESSION['team_name']) && !isset($_SESSION['teamname'])) {
    header("Location: ../index.php");
    exit();
}

// Laad de centrale databaseverbinding in
require_once '../dbcon.php';

// Als de teamnaam nog niet bestaat, gebruik "Gast"
$teamName =
    $_SESSION['team_name']
    ?? $_SESSION['teamname']
    ?? 'Gast';

// SLIMME RESET: Controleer of de speler nieuw binnenkomt in deze kamer
if (!isset($_SESSION['current_room']) || $_SESSION['current_room'] !== 2) {
    $_SESSION['solved'] = 0;          // Reset de voortgang naar 0 voor Kamer 2
    $_SESSION['current_room'] = 2;    // Onthoud dat de speler nu in Kamer 2 zit
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
            unset($_SESSION['current_room']); // Wis kamer-ID voor de volgende kamer
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
    <h1>Team: <?php echo htmlspecialchars($teamName); ?></h1>
    <h2>Room 2: Het geheime laboratorium</h2>
    <p>Los de vragen één voor één op. Alleen als je alles goed hebt, kom je uit de kamer.</p>

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
                $onclick = "openModal({$index}, "
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

    <section class="overlay" id="overlay" onclick="closeModal()" style="<?php echo ($showModalIndex !== null) ? 'display: block;' : ''; ?>"></section>

    <section class="modal" id="modal" style="<?php echo ($showModalIndex !== null) ? 'display: block;' : ''; ?>">
        <h2>Escape Room Vraag</h2>
        <p id="riddle"><?php echo ($showModalIndex !== null) ? htmlspecialchars($riddles[$showModalIndex]['question']) : ''; ?></p>

        <button type="button" id="hintBtn" onclick="showHint()"
            style="background-color: #fcd34d; color: #121824; margin: 10px 0; padding: 8px 14px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-family: 'Courier New', monospace;">
            💡 Hint tonen
        </button>
        <p id="hintText" style="color: #fcd34d; font-style: italic; min-height: 22px; margin: 8px 0; font-family: 'Courier New', monospace;"></p>

        <input type="hidden" id="current_hint" value="<?php echo ($showModalIndex !== null) ? htmlspecialchars($riddles[$showModalIndex]['hint'], ENT_QUOTES) : ''; ?>">

        <form method="POST" action="">
            <input type="hidden" id="riddle_index" name="riddle_index" value="<?php echo ($showModalIndex !== null) ? $showModalIndex : ''; ?>">
            <input type="text" name="user_answer" id="answer" placeholder="Typ je antwoord" required autocomplete="off">
            <br>
            <button type="submit" name="submit_answer">Verzenden</button>
        </form>

        <div id="feedback"><?php echo $feedback; ?></div>
    </section>

    <script>
        function openModal(index, question, hint) {
            document.getElementById('riddle_index').value = index;
            document.getElementById('riddle').innerText = question;
            document.getElementById('current_hint').value = hint;
            document.getElementById('hintText').innerText = "";
            document.getElementById('answer').value = "";
            document.getElementById('feedback').innerText = "";
            document.getElementById('modal').style.display = "block";
            document.getElementById('overlay').style.display = "block";
        }

        function closeModal() {
            document.getElementById('modal').style.display = "none";
            document.getElementById('overlay').style.display = "none";
        }

        function showHint() {
            let hint = document.getElementById('current_hint').value;
            document.getElementById('hintText').innerText = hint ? hint : "Geen hint beschikbaar voor deze vraag.";
        }
    </script>

    <?php include '../timer.php'; ?>
</body>

</html>