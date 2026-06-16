<?php
// auteur: Jouw Naam
// functie: algemene functies voor gebruikers-CRUD

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
    <h1>Beheer Accounts / Registreren</h1>
    <nav>
		<a href='insert.php'>Nieuw account registreren</a>
    </nav><br>";
    echo $txt;

    $result = getData(CRUD_TABLE);
    if(!empty($result)){
        printCrudTabel($result);
    } else {
        echo "Geen gebruikers gevonden.";
    }
}

function getData($table){
    $conn = connectDb();
    $sql = "SELECT id, username, role FROM $table"; // Wachtwoord verbergen we in het overzicht
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
    
    // VEILIGHEID: Alleen headers pakken als er daadwerkelijk data in de tabel zit
    if (isset($result[0])) {
        $headers = array_keys($result[0]);
        foreach($headers as $header){
            $table .= "<th>" . ucfirst($header) . "</th>";   
        }
    }
    
    $table .= "<th colspan=2>Actie</th></tr>";

    foreach ($result as $row) {
        $table .= "<tr>";
        foreach ($row as $cell) {
            $table .= "<td>" . htmlspecialchars($cell) . "</td>";  
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

    // Veilig het wachtwoord versleutelen
    $password_veilig = password_hash($post['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO " . CRUD_TABLE . " (username, password, role) VALUES (:username, :password, :role)";
    $stmt = $conn->prepare($sql);
    
    $stmt->execute([
        ':username' => $post['username'],
        ':password' => $password_veilig,
        ':role'     => $post['role']
    ]);

    return ($stmt->rowCount() == 1);  
}

function updateRecord($post){
    $conn = connectDb();

    // Als er een nieuw wachtwoord is ingevuld, hashen we dat. Anders behouden we het oude wachtwoord.
    if(!empty($post['password'])){
        $password_veilig = password_hash($post['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE " . CRUD_TABLE . " SET username = :username, password = :password, role = :role WHERE id = :id";
        $params = [
            ':username' => $post['username'],
            ':password' => $password_veilig,
            ':role'     => $post['role'],
            ':id'       => $post['id']
        ];
    } else {
        $sql = "UPDATE " . CRUD_TABLE . " SET username = :username, role = :role WHERE id = :id";
        $params = [
            ':username' => $post['username'],
            ':role'     => $post['role'],
            ':id'       => $post['id']
        ];
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
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