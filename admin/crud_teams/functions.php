<?php
// auteur: Bashar (Student B)
// functie: algemene functies voor de teams-CRUD
// Sprint 3 - CRUD voor teams (beheer van teams, teamleden en eindtijd)

include_once "config.php";

// Maak een PDO-verbinding met de database
function connectDb(){
    $servername = SERVERNAME;
    $username = USERNAME;
    $password = PASSWORD;
    $dbname = DATABASE;
   
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $conn;
    } 
    catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

// Controleer of de ingelogde gebruiker een admin is
function checkAdmin(){
    session_start();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../../login.php");
        exit();
    }
}

// Hoofdfunctie: toon de CRUD-pagina met menu en tabel met alle teams
function crudMain(){
    $txt = "
    <h1>Crud Teams</h1>
    <nav>
                <a href='insert.php'>Toevoegen nieuw team</a>
    </nav><br>";
    echo $txt;

    $result = getData(CRUD_TABLE);
    printCrudTabel($result);
}

// Haal alle teams op uit de database, gesorteerd op teamnaam
function getData($table){
    $conn = connectDb();
    $sql = "SELECT id, team_name, member1, member2, end_time FROM $table ORDER BY team_name ASC";
    $query = $conn->prepare($sql);
    $query->execute();
    $result = $query->fetchAll();
    return $result;
}

// Haal één team op uit de database op basis van het id
function getRecord($id){
    $conn = connectDb();
    $sql = "SELECT * FROM " . CRUD_TABLE . " WHERE id = :id";
    $query = $conn->prepare($sql);
    $query->execute([':id'=>$id]);
    $result = $query->fetch();
    return $result;
}

// Print een HTML-table met alle teams
function printCrudTabel($result){
    $table = "<table>";

    if (isset($result[0])) {
        $headers = array_keys($result[0]);
        $table .= "<tr>";
        foreach($headers as $header){

            $table .= "<th>" . ucfirst($header) . "</th>";   
        }
        $table .= "<th colspan=2>Actie</th>";
        $table .= "</tr>";

        foreach ($result as $row) {
            $table .= "<tr>";
            foreach ($row as $key => $cell) {

                if ($cell === null) {
                    $table .= "<td>-</td>";
                }
                // Bij end_time tonen we mm:ss in plaats van seconden
                elseif ($key === 'end_time') {
                    $minuten = floor($cell / 60);
                    $seconden = $cell % 60;
                    $table .= "<td>" . $minuten . ":" . str_pad($seconden, 2, "0", STR_PAD_LEFT) . "</td>";
                } else {
                    $table .= "<td>" . htmlspecialchars($cell) . "</td>";  
                }
            }
            
            // Wijzig knop
            $table .= "<td>
                <form method='post' action='update.php?id=$row[id]' >       
                    <button>Wzg</button>     
                </form></td>";

            // Verwijder knop
            $table .= "<td>
                <form method='post' action='delete.php?id=$row[id]' >       
                    <button>Verwijder</button>       
                </form></td>";

            $table .= "</tr>";
        }
    } else {
        $table .= "<tr><td>Geen teams gevonden.</td></tr>";
    }
    $table.= "</table>";

    echo $table;
}

// Werk een bestaand team bij in de database
function updateRecord($row){
    $conn = connectDb();
    $sql = "UPDATE " . CRUD_TABLE .
    " SET 
        team_name = :team_name, 
        member1 = :member1, 
        member2 = :member2
    WHERE id = :id
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':team_name'=>$row['team_name'],
        ':member1'=>$row['member1'],
        ':member2'=>$row['member2'],
        ':id'=>$row['id']
    ]);

    $retVal = ($stmt->rowCount() == 1) ? true : false ;
    return $retVal;
}

// Voeg een nieuw team toe aan de database
function insertRecord($post){
    $conn = connectDb();
    $sql = "
        INSERT INTO " . CRUD_TABLE . " (team_name, member1, member2)
        VALUES (:team_name, :member1, :member2) 
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':team_name'=>$_POST['team_name'],
        ':member1'=>$_POST['member1'],
        ':member2'=>$_POST['member2']
    ]);

    $retVal = ($stmt->rowCount() == 1) ? true : false ;
    return $retVal;  
}

// Verwijder een team uit de database op basis van het id
function deleteRecord($id){
    $conn = connectDb();
    $sql = "
    DELETE FROM " . CRUD_TABLE . 
    " WHERE id = :id";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':id'=>$_GET['id']
    ]);

    $retVal = ($stmt->rowCount() == 1) ? true : false ;
    return $retVal;
}
?>
