<?php
    // functie: Account registreren
    // auteur: Jouw Naam

    echo "<h1>Account Registreren</h1>";
    require_once('functions.php');
	 
    if(isset($_POST) && isset($_POST['btn_ins'])){
        if(insertRecord($_POST) == true){
            echo "<script>alert('Account is succesvol aangemaakt!')</script>";
            echo "<script> location.replace('index.php'); </script>";
        } else {
            echo '<script>alert("Account aanmaken is MISLUKT")</script>';
        }
    }
?>
<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="post">
        <label for="username">Gebruikersnaam:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Wachtwoord:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="role">Rol:</label>
        <select id="role" name="role" required>
            <option value="speler">Speler</option>
            <option value="admin">Admin</option>
        </select><br><br>

        <input type="submit" name="btn_ins" value="Registreren">
    </form>
    <br><br>
    <a href='index.php'>Home</a>
</body>
</html>