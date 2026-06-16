<?php
$server = "localhost";
$username = "root";
$db = "escape-room";

// Controleer of de server op een Mac (MAMP) draait
if (strpos(strtolower($_SERVER['SERVER_SOFTWARE']), 'mamp') !== false || PHP_OS === 'DARWIN') {
    $password = "root"; 
    $port = "8889"; 
} else {
    $password = "";     
    $port = "3306"; 
}

try {
    // De poort is nu dynamisch toegevoegd aan de connectiestring
    $db_connection = new PDO(
        "mysql:host=$server;port=$port;dbname=$db",
        $username,
        $password
    );

    $db_connection->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {
    echo "Verbinding mislukt: " . $e->getMessage();
}