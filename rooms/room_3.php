<?php
// Start de sessie
session_start();
include("../dbcon.php");

 $TimeLimit = 120; // 2 minuten
 $NextPage = "../win.php"; // Volgende pagina bij winst

 $sql = "SELECT * FROM questions WHERE roomId = 3";
 $stmt = $db_connection->query($sql);
 $questions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Room 3</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h1>Escape Room 3</h1>

<!-- Student B: Include de timer hier -->
<?php include '../timer.php'; ?>

<div class="container">

<?php foreach($questions as $index => $question){ ?>

    <div class="box"
         onclick="openModal(<?php echo $index; ?>)"
         data-index="<?php echo $index; ?>"
         data-riddle="<?php echo $question['question']; ?>"
         data-answer="<?php echo $question['answer']; ?>">
        Vraag <?php echo $index + 1; ?>
    </div>

<?php } ?>

</div>

<div class="overlay" id="overlay" onclick="closeModal()"></div>

<div class="modal" id="modal">
    <h2>Vraag</h2>
    <p id="riddle"></p>

    <input type="text" id="answer" placeholder="Typ je antwoord">
    <button onclick="checkAnswer()">Controleer</button>

    <p id="feedback"></p>
</div>

<script src="../js/app.js"></script>

</body>
</html>
