<!-- Op deze pagina zie je een overzicht van alle reviews in een tabel.
-->
<?php
include("../dbcon.php");

$sql = "SELECT * FROM reviews";
$stmt = $db_connection->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Alle reviews</title>
</head>
<body>

<h1>Alle reviews</h1>

<table border="1">

<tr>
    <th>ID</th>
    <th>Rating</th>
    <th>Moeilijkheid</th>
    <th>Feedback</th>
</tr>

<?php
while($review = $stmt->fetch()) {
?>

<tr>
    <td><?php echo $review['id']; ?></td>
    <td><?php echo $review['rating']; ?></td>
    <td><?php echo $review['difficulty']; ?></td>
    <td><?php echo $review['feedback']; ?></td>
</tr>

<?php
}
?>

</table>

</body>
</html>