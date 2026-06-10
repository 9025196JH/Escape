<?php
// Room 2
// Gemaakt door: Student B

session_start();
require_once '../dbcon.php';

if (!isset($_SESSION['teamname'])) {
  $_SESSION['teamname'] = 'Onbekend team';
}

$questions = [];

try {
  $stmt = $db_connection->prepare("SELECT * FROM questions WHERE roomId = 2 ORDER BY id ASC");
  $stmt->execute();
  $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $questions = [];
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Room 2</title>
  <link rel="stylesheet" href="../css/style.css">
</head>

<body class="room2-page">

  <?php
  $TimeLimit = 120;
  $NextPage = '../win.php';
  include '../timer.php';
  ?>

  <header class="room2-topbox">
    <div class="room2-titlebox">
      <h1>Room 2: Het geheime laboratorium</h1>
      <p class="room2-teamline">Team: <?php echo htmlspecialchars($_SESSION['teamname']); ?></p>
      <p class="room2-text">Los de vragen één voor één op. Alleen als je alles goed hebt, kom je uit de kamer.</p>
    </div>
  </header>

  <main class="room2-stage">
    <div class="room2-scene">
      <?php foreach ($questions as $index => $question): ?>
        <button
          type="button"
          class="room2-box <?php echo $index === 0 ? '' : 'room2-box-locked'; ?>"
          id="room2Box<?php echo $index; ?>"
          data-index="<?php echo $index; ?>"
          data-riddle="<?php echo htmlspecialchars($question['question'], ENT_QUOTES); ?>"
          data-answer="<?php echo htmlspecialchars($question['answer'], ENT_QUOTES); ?>"
          data-hint="<?php echo htmlspecialchars($question['hint'], ENT_QUOTES); ?>"
          <?php echo $index === 0 ? '' : 'disabled'; ?>
          onclick="room2OpenModal(<?php echo $index; ?>)">
          Vraag <?php echo $index + 1; ?>
          <span class="room2-check" id="room2Check<?php echo $index; ?>"></span>
        </button>
      <?php endforeach; ?>
    </div>
  </main>

  <div class="room2-overlay" id="room2Overlay" onclick="room2CloseModal()"></div>

  <section class="room2-modal" id="room2Modal">
    <h2 id="room2QuestionTitle">Vraag</h2>
    <p id="room2Riddle"></p>

    <button type="button" class="room2-hint-btn" onclick="room2ShowHint()" title="Hint">💡</button>
    <p id="room2HintText" class="room2-hint-text"></p>

    <input type="text" id="room2Answer" placeholder="Typ je antwoord">
    <button type="button" onclick="room2CheckAnswer()">Controleer</button>
    <button type="button" class="room2-close-btn" onclick="room2CloseModal()">Sluiten</button>

    <p id="room2Feedback"></p>
  </section>

  <script src="../js/app.js"></script>
</body>

</html>