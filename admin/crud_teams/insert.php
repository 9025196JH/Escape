<?php
    // functie: formulier en database insert voor een nieuw team
    // auteur: Bashar (Student B)
    // Sprint 3 - Team toevoegen (alleen admin)

    include 'functions.php';
    checkAdmin();

    echo "<h1>Insert Team</h1>";

    // Test of er op de insert-knop is gedrukt 
    if(isset($_POST) && isset($_POST['btn_ins'])){

        if(insertRecord($_POST) == true){
            echo "<script>alert('Team is toegevoegd')</script>";
            echo "<script> location.replace('index.php'); </script>";
        } else {
            echo '<script>alert("Team is NIET toegevoegd")</script>';
        }
    }
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Team Toevoegen</title>
</head>
<body>
    <form method="post">

        <label for="team_name">Teamnaam:</label>
        <input type="text" id="team_name" name="team_name" required><br>

        <label for="member1">Teamlid 1:</label>
        <input type="text" id="member1" name="member1" required><br>

        <label for="member2">Teamlid 2 (optioneel):</label>
        <input type="text" id="member2" name="member2"><br>

        <input type="submit" name="btn_ins" value="Insert">
    </form>
    
    <br><br>
    <a href='index.php'>Home</a>
</body>
</html>
