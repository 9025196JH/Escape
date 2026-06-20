<?php
// functie: formulier en database insert voor een nieuwe vraag
// auteur: Bashar (Student B)
// Sprint 3 - Vraag toevoegen (alleen admin)

// Laad de functies en controleer of de gebruiker admin is
include 'functions.php';
checkAdmin();

echo "<h1>Insert Vraag</h1>";

// Test of er op de insert-knop is gedrukt 
if (isset($_POST) && isset($_POST['btn_ins'])) {

    // Probeer de vraag toe te voegen aan de database
    if (insertRecord($_POST) == true) {
        echo "<script>alert('Vraag is toegevoegd')</script>";
        echo "<script> location.replace('index.php'); </script>";
    } else {
        echo '<script>alert("Vraag is NIET toegevoegd")</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Vraag Toevoegen</title>
</head>

<body>
    <form method="post">

        <label for="question">Vraag:</label>
        <input type="text" id="question" name="question" required><br>

        <label for="answer">Antwoord:</label>
        <input type="text" id="answer" name="answer" required><br>

        <label for="hint">Hint:</label>
        <input type="text" id="hint" name="hint" required><br>

        <label for="roomId">Room ID:</label>
        <input type="number" id="roomId" name="roomId" min="1" max="3" required><br>

        <input type="submit" name="btn_ins" value="Insert">
    </form>

    <br><br>
    <a href='index.php'>Home</a>
</body>

</html>