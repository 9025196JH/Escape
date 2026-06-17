<?php
// auteur: Bashar (Student B)
// functie: verwijder een team op basis van het id
// Sprint 3 - Team verwijderen (alleen admin)

include 'functions.php';
checkAdmin();

if(isset($_GET['id'])){

    if(deleteRecord($_GET['id']) == true){
        echo '<script>alert("Team id: ' . $_GET['id'] . ' is verwijderd")</script>';
        echo "<script> location.replace('index.php'); </script>";
    } else {
        echo '<script>alert("Team is NIET verwijderd")</script>';
    }
}
?>
