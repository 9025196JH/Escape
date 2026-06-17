<?php
    // functie: update een bestaand team
    // auteur: Bashar (Student B)
    // Sprint 3 - Team wijzigen (alleen admin)

    include 'functions.php';
    checkAdmin();

    // Test of er op de wijzig-knop is gedrukt 
    if(isset($_POST['btn_wzg'])){

        if(updateRecord($_POST) == true){
            echo "<script>alert('Team is gewijzigd')</script>";
            echo "<script> location.replace('index.php'); </script>";
        } else {
            echo '<script>alert("Team is NIET gewijzigd")</script>';
        }
    }

    // Test of er een id is meegegeven in de URL
    if(isset($_GET['id'])){  
        $id = $_GET['id'];
        $row = getRecord($id);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Wijzig Team</title>
</head>
<body>
  <h2>Wijzig Team</h2>
  <form method="post">
    
    <input type="hidden" id="id" name="id" required value="<?php echo $row['id']; ?>"><br>

    <label for="team_name">Teamnaam:</label>
    <input type="text" id="team_name" name="team_name" required value="<?php echo htmlspecialchars($row['team_name']); ?>"><br>

    <label for="member1">Teamlid 1:</label>
    <input type="text" id="member1" name="member1" required value="<?php echo htmlspecialchars($row['member1']); ?>"><br>

    <label for="member2">Teamlid 2 (optioneel):</label>
    <input type="text" id="member2" name="member2" value="<?php echo htmlspecialchars($row['member2']); ?>"><br>

    <input type="submit" name="btn_wzg" value="Wijzig">
  </form>
  <br><br>
  <a href='index.php'>Home</a>
</body>
</html>

<?php
    } else {
        echo "Geen id opgegeven<br>";
    }
?>
