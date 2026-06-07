<?php
// Op deze pagina kan je een review toevoegen
// Een speler vult het formulier in
// Gegevens worden opgeslagen in de database
include("../dbcon.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $rating = $_POST["rating"];
    $difficulty = $_POST["difficulty"];
    $feedback = $_POST["feedback"];

    $sql = "INSERT INTO reviews (rating, difficulty, feedback)
            VALUES (:rating, :difficulty, :feedback)";

    $stmt = $db_connection->prepare($sql);

    $stmt->execute([
        ":rating" => $rating,
        ":difficulty" => $difficulty,
        ":feedback" => $feedback
    ]);

    echo "Review opgeslagen!";
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Review toevoegen</title>
</head>
<body>

<h1>Laat een review achter</h1>

<form method="POST">

    <label>Rating (1-5)</label><br>
    <input type="number" name="rating" min="1" max="5" required><br><br>

    <label>Moeilijkheid</label><br>
    <select name="difficulty">
        <option>Makkelijk</option>
        <option>Normaal</option>
        <option>Moeilijk</option>
    </select><br><br>

    <label>Feedback</label><br>
    <textarea name="feedback" required></textarea><br><br>

    <button type="submit">Review versturen</button>

</form>

</body>
</html>