<?php
$server = "localhost";
$username = "root";
$db = "escape-room";

// Controleer het besturingssysteem (Darwin = Mac/MAMP, WINNT = Windows/XAMPP)
if (PHP_OS === 'Darwin') {
    $password = "root"; 
    $port = "8889"; 
} else {
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