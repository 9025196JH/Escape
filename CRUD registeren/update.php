<?php
    // functie: update account/gebruiker
    // auteur: Jouw Naam

    require_once('functions.php');

    if(isset($_POST['btn_wzg'])){
        if(updateRecord($_POST) == true){
            echo "<script>alert('Account is gewijzigd')</script>";
            echo "<script> location.replace('index.php'); </script>";
        } else {
            echo '<script>alert("Geen wijzigingen doorgevoerd")</script>';
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
  <title>Wijzig Account</title>
</head>
<body>
  <h2>Wijzig Account</h2>
  <form method="post">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

    <label for="username">Gebruikersnaam:</label>
    <input type="text" id="username" name="username" required value="<?php echo htmlspecialchars($row['username']); ?>"><br><br>

    <label for="password">Nieuw Wachtwoord (leeg laten om te behouden):</label>
    <input type="password" id="password" name="password"><br><br>

    <label for="role">Rol:</label>
    <select id="role" name="role" required>
        <option value="speler" <?php if($row['role'] == 'speler') echo 'selected'; ?>>Speler</option>
        <option value="admin" <?php if($row['role'] == 'admin') echo 'selected'; ?>>Admin</option>
    </select><br><br>

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