<?php

require_once "import/BDD.php";
session_start();
if (isset($_GET['tri'])){
    if($_GET['tri'] == 0){
        $sql = "SELECT nomEvent, lieuEvent, descriptionEvent, typeEvent, roleEvent, createurEvent, dateEvent FROM Event ORDER BY nomEvent ASC";
    }elseif ($_GET['tri'] == 1) {
        $sql = "SELECT nomEvent, lieuEvent, descriptionEvent, typeEvent, roleEvent, createurEvent, dateEvent FROM Event ORDER BY lieuEvent ASC";
    }elseif ($_GET['tri'] == 2) {
        $sql = "SELECT nomEvent, lieuEvent, descriptionEvent, typeEvent, roleEvent, createurEvent, dateEvent FROM Event ORDER BY dateEvent ASC";
    }
}

$resultEvent = mysqli_query($db, $sql);

$tabEvent = [];
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    $sql = "SELECT emailParticipant, nomEvent FROM ParticipationEvent WHERE emailParticipant = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultParticipation = $stmt->get_result();
    $resultParticipation = $resultParticipation->fetch_all();

    foreach ($resultParticipation as $participation) {
        $tabEvent[] = $participation[1];
    }
}
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
        <tr><th><a href="?tri=0">Non Event</a></th><th><a href="?tri=1">Lieux<a/></th><th>Description</th><th>Type</th><th>Role</th><th>Créateur de l'évenement</th><th><a href="?tri=2">Date</a></th></tr>
        </thead>
        <tbody>
        <?php
        foreach ($resultEvent as $key => $value) {
            echo "<tr>";
            foreach ($value as $key2 => $value2) {
                if ($key2 != "roleEvent") {
                    echo "<td> $value2 </td>";
                } else {
                    if (isset($_SESSION['email'])&& $_SESSION['idRole'] <2) {
                        if (in_array($value['nomEvent'], $tabEvent)) {
                            echo "<td><a class='btn-ListEvent' href='src/PHP/Event/desInscriptionEvent.php?event=" . $value['nomEvent'] . "'>Desinscription</a></td>";
                        } else {
                            if ($_SESSION['idRole'] == 0) {
                                echo "<td><a class='btn-ListEvent' href='src/PHP/Event/inscriptionEvent.php?event=" . $value['nomEvent'] . "'>Inscription</a></td>";
                            } elseif ($_SESSION['idRole'] == 1) {
                                echo "<td><a class='btn-ListEvent' href='src/PHP/Event/inscriptionEvent.php?event=" . $value['nomEvent'] . "'>Je participe</a></td>";
                            }
                        }
                    } elseif (!isset($_SESSION['email']) || $_SESSION['idRole'] ==2){
                        if ($value2 == 1) {
                            echo "<td> Spectateur </td>";
                        } elseif ($value2 == 2) {
                            echo "<td> Sportif </td>";
                        } elseif ($value2 == 3) {
                            echo "<td> Spectateur et sportif </td>";
                        }
                    }
                }
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
