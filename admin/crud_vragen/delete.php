<?php
// auteur: Bashar (Student B)
// functie: verwijder een vraag op basis van het id
// Sprint 3 - Vraag verwijderen (alleen admin)

include 'functions.php';

// Alleen admins mogen deze pagina zien
checkAdmin();

// Haal het id uit de URL en probeer de vraag te verwijderen
if(isset($_GET['id'])){

    if(deleteRecord($_GET['id']) == true){
        // Succes: toon melding en ga terug naar het overzicht
        echo '<script>alert("Vraag id: ' . $_GET['id'] . ' is verwijderd")</script>';
        echo "<script> location.replace('index.php'); </script>";
    } else {
        // Fout: toon melding
        echo '<script>alert("Vraag is NIET verwijderd")</script>';
    }
}
?>
