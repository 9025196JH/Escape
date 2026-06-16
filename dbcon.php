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

if (PHP_OS === "Darwin") {
    // Mac / MAMP
    $password = "root";
    $port = "8889";
} else {
    // Windows / XAMPP
    $password = "";
    $port = "3306";

}

try {
    $db_connection = new PDO("mysql:host=$server;port=$port;dbname=$db", $username, $password);
    $db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database fout: " . $e->getMessage();
}
?>
