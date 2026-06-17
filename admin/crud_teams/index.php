<?php
// functie: Programma CRUD teams - overzichtspagina
// auteur: Bashar (Student B)
// Sprint 3 - Teams beheren (alleen voor admin)

// Initialisatie - laad de functies in
include 'functions.php';

// Alleen admins mogen deze pagina zien
checkAdmin();

// Aanroep van de hoofdfunctie: toont het menu en de tabel met teams
crudMain();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud Teams</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <br><br>
    <a href="../dashboard.php">← Admin Dashboard</a> &nbsp; | &nbsp;
    <a href="../../index.php">Home</a> &nbsp; | &nbsp;
    <a href="../../logout.php">Uitloggen</a>

</body>
</html>
