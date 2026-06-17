<?php
// functie: Uitloggen - sessie wissen en terug naar home
// auteur: Bashar (Student B)

// Start de sessie zodat we deze kunnen wissen
session_start();

// Wis alle sessievariabelen
$_SESSION = array();

// Beëindig de sessie
session_destroy();

// Stuur de gebruiker terug naar de homepagina
header("Location: index.php");
exit();
