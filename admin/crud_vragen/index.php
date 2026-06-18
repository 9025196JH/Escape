<?php
// functie: Programma CRUD vragen - overzichtspagina
// auteur: Bashar (Student B)
// Sprint 3 - Vragen beheren (alleen voor admin)

// Initialisatie - laad de functies in
include 'functions.php';

// Alleen admins mogen deze pagina zien
// Als een niet-admin deze pagina opent, wordt hij doorgestuurd naar login.php
checkAdmin();

// Aanroep van de hoofdfunctie: toont het menu en de tabel met vragen
crudMain();
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud Vragen</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <br><br>
    <a href="../dashboard.php">← Admin Dashboard</a> &nbsp; | &nbsp;
    <a href="../../index.php">Home</a>

</body>

</html>