<?php

require_once "import/BDD.php";


$sql = "SELECT nomEvent, lieuEvent, descriptionEvent, typeEvent,roleEvent,createurEvent,dateEvent FROM Event ORDER BY dateEvent ASC ";
$resultEvent = mysqli_query($db,$sql);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeux Olympiques 2024</title>
    <link rel="stylesheet" href="src/css/styles.css">
    <link rel="stylesheet" href="src/css/header.css">
</head>

<?php require_once "import/header.php"; ?>

<body>
<div class="container" id="Acceuil">

</div>

<div class="GestionEvent">
    <h2>Liste des évenement</h2>
    <table>
        <thead>
        <tr><th>Non Event</th><th>Lieux</th><th>Description</th><th>Type</th><th>Role</th><th>Créateur de l'évenement</th><th>Date</th></tr>
        </thead>
        <tbody>
        <?php
        foreach ($resultEvent as $key => $value) {
            echo "<tr>";
            foreach ($value as $key2 => $value2) {
                echo "<td> $value2 </td>";
            }
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>

</body>

<?php require_once "import/footer.php"; ?>

</html>
