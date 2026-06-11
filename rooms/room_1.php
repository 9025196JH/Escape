<?php
// Start de sessie om de voortgang en teamnaam te onthouden
session_start();

// Laad de centrale databaseverbinding in
require_once '../dbcon.php'; 

// Als de voortgang nog niet bestaat, start deze op 0 opgeloste vragen
if (!isset($_SESSION['solved'])) {
    $_SESSION['solved'] = 0;
}

// timer
$TimeLimit = isset($TimeLimit) ? $TimeLimit : 120; // 2 minuten
$NextPage  = isset($NextPage) ? $NextPage : "room_2.php"; // Volgende kamer bij winst

try {
    // Haal de vragen op uit de juiste tabel (questions) en kolom (roomId)
    // De variabele is nu correct veranderd naar $db_connection uit dbcon.php
    $stmt = $db_connection->prepare("SELECT question, answer FROM questions WHERE roomId = 1 ORDER BY id ASC");
    $stmt->execute();
    $riddles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database fout: " . $e->getMessage());
}

$totalQuestions = count($riddles);
$feedback = "";
$showModalIndex = null;

// antwoord controle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_answer'])) {
    $current_index = (int)$_POST['riddle_index'];
    $userAnswer = strtolower(trim($_POST['user_answer']));
    $correctAnswer = strtolower(trim($riddles[$current_index]['answer']));

    if ($userAnswer === $correctAnswer) {
        if ($current_index === $_SESSION['solved']) {
            $_SESSION['solved']++;
        }
        
        if ($_SESSION['solved'] === $totalQuestions) {
            $_SESSION['solved'] = 0; // Reset voortgang voor herstart
            header("Location: " . $NextPage);
            exit();
        }
        
        $feedback = "<p style='color: #00ff66; font-weight: bold;'>✅ Correct!</p>";
    } else {
        $feedback = "<p style='color: #ff3333; font-weight: bold;'>❌ Fout antwoord, probeer het opnieuw!</p>";
        $showModalIndex = $current_index;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Escape Room - Room 1</title>
  <link rel="stylesheet" href="../css/style.css">
</head>

<body>
  <h1>Team: <?php echo isset($_SESSION['team_name']) ? htmlspecialchars($_SESSION['team_name']) : "Gast"; ?></h1>
  
  <!-- De HTML code voor de timer van Student B -->
  <div id="timer" style="position: fixed; top: 20px; right: 20px; background-color: #333; color: #0f0; padding: 15px; border-radius: 10px; font-size: 24px; font-weight: bold; border: 2px solid #0f0; z-index: 9999; font-family: monospace;">
      02:00
  </div>

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
            $onclick = "openModal({$index}, '" . addslashes($riddle['question']) . "')";
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
  <section class="overlay" id="overlay" onclick="closeModal()" style="<?php echo ($showModalIndex !== null) ? 'display: block;' : ''; ?>"></section>

  <section class="modal" id="modal" style="<?php echo ($showModalIndex !== null) ? 'display: block;' : ''; ?>">
    <h2>Escape Room Vraag</h2>
    <p id="riddle"><?php echo ($showModalIndex !== null) ? htmlspecialchars($riddles[$showModalIndex]['question']) : ''; ?></p>
    
    <form method="POST" action="">
        <input type="hidden" id="riddle_index" name="riddle_index" value="<?php echo ($showModalIndex !== null) ? $showModalIndex : ''; ?>">
        <input type="text" name="user_answer" id="answer" placeholder="Typ je antwoord" required autocomplete="off">
        <br>
        <button type="submit" name="submit_answer">Verzenden</button>
    </form>
    
    <div id="feedback"><?php echo $feedback; ?></div>
  </section>

  <!-- De JavaScript code van Student B + modal handlers -->
  <script>
    // Timer logica
    let timeLeft = <?php echo $TimeLimit; ?>;
    let nextPage = "<?php echo $NextPage; ?>";
    let timerElement = document.getElementById('timer');
    let timerInterval;

    function updateTimerDisplay() {
        let minutes = Math.floor(timeLeft / 60);
        let seconds = timeLeft % 60;

        if (seconds < 10) {
            seconds = "0" + seconds;
        }
        timerElement.innerText = minutes + ":" + seconds;
    }

    timerInterval = setInterval(function() {
        timeLeft = timeLeft - 1;
        updateTimerDisplay();

        if (timeLeft <= 0) {
            clearInterval(timerInterval);

            window.location.href = "../lose.php";
        }
    }, 1000);

    // Modal logica
    function openModal(index, riddleText) {
        document.getElementById('riddle_index').value = index;
        document.getElementById('riddle').innerText = riddleText;
        document.getElementById('answer').value = "";
        document.getElementById('feedback').innerText = "";
        
        document.getElementById('modal').style.display = "block";
        document.getElementById('overlay').style.display = "block";
    }

    function closeModal() {
        document.getElementById('modal').style.display = "none";
        document.getElementById('overlay').style.display = "none";
    }
  </script>

</body>
</html>