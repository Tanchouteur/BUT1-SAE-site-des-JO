<?php

require_once "import/BDD.php";
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

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


$searchNom = '';
$searchLieu = '';
$searchDate = '';
$searching = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Erreur : token CSRF invalide");
    }
    if (isset($_POST['search_nom']) && !empty(trim($_POST['search_nom']))) {
        $searchNom = trim($_POST['search_nom']);
        $searching = 1;
    }

    if (isset($_POST['search_lieu']) && !empty(trim($_POST['search_lieu']))) {
        $searchLieu = trim($_POST['search_lieu']);
        $searching = 1;
    }

    if (isset($_POST['search_date']) && !empty(trim($_POST['search_date']))) {
        $searchDate = trim($_POST['search_date']);
        $searching = 1;
    }
}

// Sélection de l'ordre de tri
if (isset($_GET['ord'])) {
    $ord = $_GET['ord'] == 0 ? 1 : 0;
} else {
    $ord = 0;
}

// Sélection du tri
$triOptions = ["nomEvent", "lieuEvent", "dateEvent", "roleEvent", "nbrParticipant"];
$tri = isset($_GET['tri']) && in_array($_GET['tri'], range(0, 4)) ? $triOptions[$_GET['tri']] : "dateEvent";
$order = $ord == 0 ? "ASC" : "DESC";

// Construire la requête SQL de recherche
$searchQuery = "SELECT nomEvent, lieuEvent, descriptionEvent, typeEvent, roleEvent, createurEvent, dateEvent, nbrParticipant FROM Event";
$conditions = [];
$params = [];
$types = '';

if ($searching) {
    if (!empty($searchNom)) {
        $conditions[] = "nomEvent LIKE ?";
        $params[] = "%" . $searchNom . "%";
        $types .= 's';
    }

    if (!empty($searchLieu)) {
        $conditions[] = "lieuEvent LIKE ?";
        $params[] = "%" . $searchLieu . "%";
        $types .= 's';
    }

    if (!empty($searchDate)) {
        $conditions[] = "dateEvent = ?";
        $params[] = $searchDate;
        $types .= 's';
    }

    if (!empty($conditions)) {
        $searchQuery .= " WHERE " . implode(" AND ", $conditions);
    }
}

$searchQuery .= " ORDER BY $tri $order";

$stmt = $db->prepare($searchQuery);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
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
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
            <div class="form-group">
                <input name="search_nom" type="text" id="search_nom" placeholder="Rechercher par nom ..." value="<?php echo htmlspecialchars($searchNom); ?>">
                <input name="search_lieu" type="text" id="search_lieu" placeholder="Rechercher par lieu ..." value="<?php echo htmlspecialchars($searchLieu); ?>">
                <input name="search_date" type="date" id="search_date" placeholder="Rechercher par date ..." value="<?php echo htmlspecialchars($searchDate); ?>">
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
                    if (isset($_SESSION['idRole'])){
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
                        }elseif ($_SESSION['idRole']==2){
                            if ($value2 == 1) {
                                echo "<td class='roleEvent'> Spectateur </td>";
                            } elseif ($value2 == 2) {
                                echo "<td class='roleEvent'> Sportif </td>";
                            } elseif ($value2 == 3) {
                                echo "<td class='roleEvent'> Spectateur et sportif </td>";
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
