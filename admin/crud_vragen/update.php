<?php
    // functie: update een bestaande vraag
    // auteur: Bashar (Student B)
    // Sprint 3 - Vraag wijzigen (alleen admin)

    // Laad de functies en controleer of de gebruiker admin is
    include 'functions.php';
    checkAdmin();

    // Test of er op de wijzig-knop is gedrukt 
    if(isset($_POST['btn_wzg'])){

        // Probeer de wijziging op te slaan in de database
        if(updateRecord($_POST) == true){
            echo "<script>alert('Vraag is gewijzigd')</script>";
            echo "<script> location.replace('index.php'); </script>";
        } else {
            echo '<script>alert("Vraag is NIET gewijzigd")</script>';
        }
    }

    // Test of er een id is meegegeven in de URL (bijv. update.php?id=3)
    if(isset($_GET['id'])){  
        // Haal alle info van de betreffende vraag op
        $id = $_GET['id'];
        $row = getRecord($id);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Wijzig Vraag</title>
</head>
<body>
  <h2>Wijzig Vraag</h2>
  <form method="post">
    
    <!-- Hidden veld met het id van de vraag (komt uit de URL) -->
    <input type="hidden" id="id" name="id" required value="<?php echo $row['id']; ?>"><br>

    <label for="question">Vraag:</label>
    <input type="text" id="question" name="question" required value="<?php echo htmlspecialchars($row['question']); ?>"><br>

    <label for="answer">Antwoord:</label>
    <input type="text" id="answer" name="answer" required value="<?php echo htmlspecialchars($row['answer']); ?>"><br>

    <label for="hint">Hint:</label>
    <input type="text" id="hint" name="hint" required value="<?php echo htmlspecialchars($row['hint']); ?>"><br>

    <label for="roomId">Room ID:</label>
    <input type="number" id="roomId" name="roomId" min="1" max="3" required value="<?php echo htmlspecialchars($row['roomId']); ?>"><br>

    <input type="submit" name="btn_wzg" value="Wijzig">
  </form>
  <br><br>
  <a href='index.php'>Home</a>
</body>
</html>

<?php
    } else {
        // Als er geen id is meegegeven, toon deze melding
        echo "Geen id opgegeven<br>";
    }
?>
