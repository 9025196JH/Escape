<?php
// auteur: Bashar (Student B)
// functie: algemene functies voor de vragen-CRUD
// Sprint 3 - CRUD voor vragen, antwoorden en hints

include_once "config.php";

// Maak een PDO-verbinding met de database
function connectDb()
{
    $servername = SERVERNAME;
    $username = USERNAME;
    $password = PASSWORD;
    $dbname = DATABASE;

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Zorg dat PDO fouten als exception gooit (makkelijker debuggen)
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Standaard fetch als associatieve array (kolomnamen als keys)
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $conn;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}

// Controleer of de ingelogde gebruiker een admin is
// Als dat niet zo is, stuur hem door naar de login-pagina
function checkAdmin()
{
    session_start();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../../login.php");
        exit();
    }
}

// Hoofdfunctie: toon de CRUD-pagina met een menu en de tabel met alle vragen
function crudMain()
{
    // Menu-item om een nieuwe vraag toe te voegen
    $txt = "
    <h1>Crud Vragen</h1>
    <nav>
                <a href='insert.php'>Toevoegen nieuwe vraag</a>
    </nav><br>";
    echo $txt;

    // Haal alle vragen uit de database
    $result = getData(CRUD_TABLE);

    // Print de tabel met vragen
    printCrudTabel($result);
}

// Haal alle rijen op uit de opgegeven tabel
function getData($table)
{
    $conn = connectDb();
    $sql = "SELECT * FROM $table";
    $query = $conn->prepare($sql);
    $query->execute();
    $result = $query->fetchAll();
    return $result;
}

// Haal één rij op uit de tabel questions op basis van het id
function getRecord($id)
{
    $conn = connectDb();
    $sql = "SELECT * FROM " . CRUD_TABLE . " WHERE id = :id";
    $query = $conn->prepare($sql);
    $query->execute([':id' => $id]);
    $result = $query->fetch();
    return $result;
}

// Print een HTML-table met alle vragen
// Per rij zijn er twee knoppen: Wijzig (Wzg) en Verwijder
function printCrudTabel($result)
{
    $table = "<table>";

    // Print de header-rij alleen als er data is
    if (isset($result[0])) {
        $headers = array_keys($result[0]);
        $table .= "<tr>";
        foreach ($headers as $header) {
            $table .= "<th>" . $header . "</th>";
        }
        // Voeg een actie-kopregel toe voor de twee knoppen
        $table .= "<th colspan=2>Actie</th>";
        $table .= "</tr>";

        // Print elke rij met de gegevens en de twee knoppen
        foreach ($result as $row) {
            $table .= "<tr>";
            foreach ($row as $cell) {
                $table .= "<td>" . htmlspecialchars($cell) . "</td>";
            }

            // Wijzig knop - stuurt de gebruiker naar update.php met het id in de URL
            $table .= "<td>
                <form method='post' action='update.php?id=$row[id]' >       
                    <button>Wzg</button>     
                </form></td>";

            // Verwijder knop - stuurt de gebruiker naar delete.php met het id in de URL
            $table .= "<td>
                <form method='post' action='delete.php?id=$row[id]' >       
                    <button>Verwijder</button>       
                </form></td>";

            $table .= "</tr>";
        }
    } else {
        // Als er geen vragen zijn, toon deze melding
        $table .= "<tr><td>Geen vragen gevonden.</td></tr>";
    }
    $table .= "</table>";

    echo $table;
}

// Werk een bestaande vraag bij in de database
function updateRecord($row)
{
    $conn = connectDb();
    $sql = "UPDATE " . CRUD_TABLE .
        " SET 
        question = :question, 
        answer = :answer, 
        hint = :hint,
        roomId = :roomId
    WHERE id = :id
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':question' => $row['question'],
        ':answer' => $row['answer'],
        ':hint' => $row['hint'],
        ':roomId' => $row['roomId'],
        ':id' => $row['id']
    ]);

    // Geef true terug als er precies 1 rij is gewijzigd
    $retVal = ($stmt->rowCount() == 1) ? true : false;
    return $retVal;
}

// Voeg een nieuwe vraag toe aan de database
function insertRecord($post)
{
    $conn = connectDb();
    $sql = "
        INSERT INTO " . CRUD_TABLE . " (question, answer, hint, roomId)
        VALUES (:question, :answer, :hint, :roomId) 
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':question' => $_POST['question'],
        ':answer' => $_POST['answer'],
        ':hint' => $_POST['hint'],
        ':roomId' => $_POST['roomId']
    ]);

    $retVal = ($stmt->rowCount() == 1) ? true : false;
    return $retVal;
}

// Verwijder een vraag uit de database op basis van het id
function deleteRecord($id)
{
    $conn = connectDb();
    $sql = "
    DELETE FROM " . CRUD_TABLE .
        " WHERE id = :id";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':id' => $_GET['id']
    ]);

    $retVal = ($stmt->rowCount() == 1) ? true : false;
    return $retVal;
}
