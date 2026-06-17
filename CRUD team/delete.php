<?php
// auteur: Jehad
// functie: verwijder een team op basis van de id
include 'functions.php';

// Haal team uit de database
if(isset($_GET['id'])){

    // test of verwijderen gelukt is
    if(deleteRecord($_GET['id']) == true){
        echo '<script>alert("Teamcode: ' . $_GET['id'] . ' is verwijderd")</script>';
        echo "<script> location.replace('index.php'); </script>";
    } else {
        echo '<script>alert("Team is NIET verwijderd")</script>';
        echo "<script> location.replace('index.php'); </script>";
    }
}
?>