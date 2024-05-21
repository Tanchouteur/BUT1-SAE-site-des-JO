<?php

require_once "import/BDD.php";
session_start();

$updateQuery = "
    UPDATE Event e
    JOIN (
        SELECT nomEvent, COUNT(*) AS participant_count
        FROM ParticipationEvent
        GROUP BY nomEvent
    ) pe ON e.nomEvent = pe.nomEvent
    SET e.nbrParticipant = COALESCE(pe.participant_count, 0);
";
$db->query($updateQuery);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "Formulaire soumis<br>"; // Pour déboguer
    if (isset($_POST['search']) && !empty(trim($_POST['search']))) {
        $searchTerm = trim($_POST['search']);

        $searching = 1;
    } else {

        $searching = 0;
    }
} else {

    $searching = 0;
}



//Selection de l'ordre de tri
if (isset($_GET['ord'])){
    if($_GET['ord'] == 0){
        $ord = 1;
    }elseif ($_GET['ord'] == 1) {
        $ord = 0;
    }
}else{
    $ord = 0;
}

//tri
if (isset($_GET['tri'])){
    $tri = $_GET['tri'];
}else{
    $tri = 2;
}
$searchQuery = "";
if ($searching) {
    $searchQuery = "WHERE nomEvent LIKE ?";
}

$orderClause = "";
if ($ord == 0) {
    $orderClause = "ASC";
} else {
    $orderClause = "DESC";
}

$columns = ["nomEvent", "lieuEvent", "dateEvent", "roleEvent", "nbrParticipant"];
$column = isset($columns[$tri]) ? $columns[$tri] : "dateEvent";
$sql = "SELECT nomEvent, lieuEvent, descriptionEvent, typeEvent, roleEvent, createurEvent, dateEvent, nbrParticipant FROM Event $searchQuery ORDER BY $column $orderClause";

$stmt = $db->prepare($sql);

if ($searching) {
    $searchTermWildcard = "%" . $searchTerm . "%";
    $stmt->bind_param('s', $searchTermWildcard);
}

$stmt->execute();
$resultEvent = $stmt->get_result();

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
    <link rel="icon" href="src/img/favicon.png" type="image/png">
    <link rel="stylesheet" href="src/css/styles.css">
    <link rel="stylesheet" href="src/css/header.css">
</head>

<?php require_once "import/header.php"; ?>

<body>
<div class="GestionEvent">
    <div>
        <h2>Liste des évenement</h2>
        <form action="index.php" method="post">
            <div class="form-group">
                <input name="search" type="text" id="search" placeholder="Rechercher ..." value="<?php if (isset($searchTerm)){ echo htmlspecialchars($searchTerm);} ?>">
                <button type="submit">Rechercher</button>
            </div>
        </form>
    </div>
    <table>
        <thead>
        <tr>
            <th class="nomEvent"><a <?php if ($tri == 0){ echo "style='color: #00139c'";}?> href="?tri=0&ord=<?php echo "$ord";?>">Nom Event</a></th>
            <th class="lieuEvent"><a <?php if ($tri == 1){ echo "style='color: #00139c'";}?> href="?tri=1&ord=<?php echo "$ord";?>">Lieux<a/></th>
            <th class="descriptionEvent">Description</th>
            <th class="typeEvent">Type</th>
            <th class="roleEvent"><a <?php if ($tri == 3){ echo "style='color: #00139c'";}?> href="?tri=3&ord=<?php echo "$ord";?>">Accès</a></th>
            <th class="createurEvent">Créateur de l'évenement</th>
            <th class="dateEvent"><a <?php if ($tri == 2){ echo "style='color: #00139c'";}?> href="?tri=2&ord=<?php echo "$ord";?>">Date</a></th>
            <th class="nbrParticipant"><a <?php if ($tri == 4){ echo "style='color: #00139c'";}?> href="?tri=4&ord=<?php echo "$ord";?>">Participant</a></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($resultEvent as $key => $value) {
            echo "<tr>";
            foreach ($value as $key2 => $value2) {
                if ($key2 != "roleEvent") {
                    if ($key2 == "nomEvent") {
                        echo "<td class='nomEvent'><a class='btn-ListEvent' href='src/PHP/Event/pageEvent.php?event=$value2'> " . $value2 . "</a></td>";
                    }elseif($key2 == "lieuEvent"){
                        echo "<td class='lieuEvent'> $value2 </td>";
                    }elseif ($key2 == "descriptionEvent"){
                        echo "<td class='descriptionEvent'> $value2 </td>";
                    }elseif ($key2 == "typeEvent"){
                        echo "<td class='typeEvent'> $value2 </td>";
                    }elseif ($key2 == "dateEvent"){
                        echo "<td class='dateEvent'> $value2 </td>";
                    }elseif ($key2 == "createurEvent"){
                        echo "<td class='createurEvent'> $value2 </td>";
                    }elseif ($key2 == "nbrParticipant"){
                        echo "<td class='nbrParticipant'> $value2 </td>";
                    }
                } else {
                    if (isset($_SESSION['email'])&& $_SESSION['idRole'] <2) {
                        if (in_array($value['nomEvent'], $tabEvent)) {
                            echo "<td class='roleEvent'><a class='btn-ListEvent' href='src/PHP/Event/desInscriptionEvent.php?event=" . $value['nomEvent'] . "'>Desinscription</a></td>";
                        } else {
                            if ($_SESSION['idRole'] == 0) {
                                echo "<td class='roleEvent'><a class='btn-ListEvent' href='src/PHP/Event/inscriptionEvent.php?event=" . $value['nomEvent'] . "'>Inscription</a></td>";
                            } elseif ($_SESSION['idRole'] == 1) {
                                echo "<td class='roleEvent'><a class='btn-ListEvent' href='src/PHP/Event/inscriptionEvent.php?event=" . $value['nomEvent'] . "'>Je participe</a></td>";
                            }
                        }
                    } elseif (!isset($_SESSION['email']) || $_SESSION['idRole'] ==2){
                        if ($value2 == 1) {
                            echo "<td class='roleEvent'> Spectateur </td>";
                        } elseif ($value2 == 2) {
                            echo "<td class='roleEvent'> Sportif </td>";
                        } elseif ($value2 == 3) {
                            echo "<td class='roleEvent'> Spectateur et sportif </td>";
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
