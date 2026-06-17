<?php
    // functie: Team toevoegen
    // auteur: Jehad

    echo "<h1>Team Toevoegen</h1>";
    require_once('functions.php');
	 
    if(isset($_POST) && isset($_POST['btn_ins'])){
        if(insertRecord($_POST) == true){
            echo "<script>alert('Team is succesvol aangemaakt!')</script>";
            echo "<script> location.replace('index.php'); </script>";
        } else {
            echo '<script>alert("Team aanmaken is MISLUKT")</script>';
        }
    }
?>
<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="post">
        <!-- Alleen het veld voor Teamnaam is nu aanwezig, net zo simpel als jouw basiscode -->
        <label for="team_name">Teamnaam:</label>
        <input type="text" id="team_name" name="team_name" required><br><br>

        <input type="submit" name="btn_ins" value="Toevoegen">
    </form>
    <br><br>
    <a href='index.php'>Home</a>
</body>
</html>