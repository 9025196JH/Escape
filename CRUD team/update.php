<?php
    // functie: update team / score
    // auteur: Jehad

    require_once('functions.php');

    if(isset($_POST['btn_wzg'])){
        if(updateRecord($_POST) == true){
            echo "<script>alert('Team is gewijzigd')</script>";
            echo "<script> location.replace('index.php'); </script>";
        } else {
            echo '<script>alert("Geen wijzigingen doorgevoerd")</script>';
            echo "<script> location.replace('index.php'); </script>";
        }
    }

    if(isset($_GET['id'])){  
        $id = $_GET['id'];
        $row = getRecord($id);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="style.css">
  <title>Wijzig Team</title>
</head>
<body>
  <h2>Wijzig Team</h2>
  <form method="post">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    <label for="team_name">Teamnaam:</label>
    <input type="text" id="team_name" name="team_name" required value="<?php echo htmlspecialchars($row['team_name']); ?>"><br><br>

    <label for="end_time">Eindtijd (formaat: UU:MM:SS - leeg laten indien nog bezig):</label>
    <input type="text" id="end_time" name="end_time" placeholder="00:15:30" value="<?php echo htmlspecialchars($row['end_time'] ?? ''); ?>"><br><br>

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