<?php
$server = "localhost";
$username = "root";
<<<<<<< HEAD
=======
$password = "";
>>>>>>> dc2e2a58b8c1d1facb239245b434a83e6aea0888
$db = "escape-room";

// Controleer of de server op een Mac (MAMP) draait
if (strpos(strtolower($_SERVER['SERVER_SOFTWARE']), 'mamp') !== false || PHP_OS === 'DARWIN') {
    $password = "root"; // Wachtwoord voor MAMP (Mac)
} else {
    $password = "";     // Wachtwoord voor Laragon / XAMPP (Windows)
}

try {
    $db_connection = new PDO(
        "mysql:host=$server;dbname=$db",
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