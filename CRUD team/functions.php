<?php
// auteur: Jehad
// functie: algemene functies voor teams

include_once "config.php";

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

function crudMain(){
    $txt = "
    <h1>Beheer Teams</h1>
    <nav>
		<a href='insert.php'>Nieuw team toevoegen</a>
    </nav><br>";
    echo $txt;

    $result = getData(CRUD_TABLE);
    if(!empty($result)){
        printCrudTabel($result);
    } else {
        echo "Geen teams gevonden.";
    }
}

function getData($table){
    $conn = connectDb();
    $sql = "SELECT id, team_name, end_time FROM $table";
    $query = $conn->prepare($sql);
    $query->execute();
    return $query->fetchAll();
}

function getRecord($id){
    $conn = connectDb();
    $sql = "SELECT * FROM " . CRUD_TABLE . " WHERE id = :id";
    $query = $conn->prepare($sql);
    $query->execute([':id'=>$id]);
    return $query->fetch();
}

function printCrudTabel($result){
    $table = "<table><tr>";
    $headers = array_keys($result[0]);
    foreach($headers as $header){
        $table .= "<th>" . ucfirst(str_replace('_', ' ', $header)) . "</th>";   
    }
    $table .= "<th colspan=2>Actie</th></tr>";

    foreach ($result as $row) {
        $table .= "<tr>";
        foreach ($row as $cell) {
            $table .= "<td>" . htmlspecialchars($cell ?? 'Nog bezig...') . "</td>";  
        }
        
        $table .= "<td>
            <form method='post' action='update.php?id=$row[id]' >       
                <button type='submit'>Wzg</button>	 
            </form></td>";

        $table .= "<td>
            <form method='post' action='delete.php?id=$row[id]' >       
                <button type='submit'>Verwijder</button>	 
            </form></td>";

        $table .= "</tr>";
    }
    $table.= "</table>";
    echo $table;
}

function insertRecord($post){
    $conn = connectDb();
    $sql = "INSERT INTO " . CRUD_TABLE . " (team_name) VALUES (:team_name)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':team_name' => $post['team_name']
    ]);
    return ($stmt->rowCount() == 1);  
}

function updateRecord($post){
    $conn = connectDb();
    $end_time = !empty($post['end_time']) ? $post['end_time'] : null;

    $sql = "UPDATE " . CRUD_TABLE . " SET team_name = :team_name, end_time = :end_time WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':team_name' => $post['team_name'],
        ':end_time'  => $end_time,
        ':id'        => $post['id']
    ]);
    return ($stmt->rowCount() == 1);
}

function deleteRecord($id){
    $conn = connectDb();
    $sql = "DELETE FROM " . CRUD_TABLE . " WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id'=>$id]);
    return ($stmt->rowCount() == 1);
}
?>