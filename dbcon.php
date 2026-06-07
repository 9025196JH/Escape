<?php
$server = "localhost";
$username = "root";
$password = "root";
$db = "escape-room";

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