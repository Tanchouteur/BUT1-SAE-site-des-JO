<?php

session_start();
require_once "../../../import/BDD.php";

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

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    if (isset($_SESSION['idRole']) && $_SESSION['idRole'] != 2) {
        header('Location: ../../../index.php');
    }
}else{
    header('Location: ../../../index.php');
}

$sql = "SELECT idRole FROM Users where email = '$email'";
$result = mysqli_query($db,$sql);
$result = mysqli_fetch_array($result,MYSQLI_ASSOC);

if ($result['idRole'] !=2){
    header('location: ../../../index.php');
}

$sql = "SELECT nomEvent, lieuEvent, descriptionEvent, typeEvent,roleEvent,createurEvent,dateEvent FROM Event";
$result = mysqli_query($db,$sql);

if (isset($_GET['status'])&&isset($_GET['msg'])){
    $status=$_GET['status'];
    $msg=$_GET['msg'];
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P2024 - Gestion Evenement</title>
    <link rel="stylesheet" href="../../css/header.css">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="icon" href="../../img/favicon.png" type="image/png">
</head>
<header>
    <nav class="navbar">
        <div class="container-nav">
            <div class="brand">
                <img class="logo" src="../../img/Paris2024.png">
            </div>

            <div class="nav-links">
                <a class='btn-navBar' href="../../../index.php">Accueil</a>
                <a class='btn-navBar' href="#">Evenement</a>
            </div>

            <div class="auth-buttons">

                <?php echo "<a class='btn-navBar' href='../deconnect.php' class='btn-signup'>Deconnexion</a>"; ?>

            </div>
        </div>
    </nav>
</header>
<body>
    <div class="container-GestionEvent">
        <a class="btn-ListEvent" href="creeEvent.php">Crée un evenement</a>
    </div>

    <div class="GestionEvent">
        <div class="titleBox">
            <h2 class="EventTitle">Liste des évenement</h2>
            <?php
            if (isset($_GET['status'])&&isset($_GET['msg'])) {
                if ($_GET['status']==0) {
                    echo "<h2 style='color: #6c2401'> " . $_GET['msg'] . "</h2>";
                }else if ($_GET['status']==1) {
                    echo "<h2 style='color: #016c23'> " . $_GET['msg'] . "</h2>";
                }
            }?>
        </div>
        <table>
            <thead>
            <tr><th>Non Event</th><th>Lieux</th><th>Description</th><th>Type</th><th>Role</th><th>Créateur de l'évenement</th><th>Date</th></tr>
            </thead>
            <tbody>
            <?php
            foreach ($result as $key => $value) {
                echo "<tr>";
                foreach ($value as $key2 => $value2) {
                    if($key2=='nomEvent'){
                        echo "<td><a class='btn-ListEvent' href='modifierEvent.php?event=$value2'>$value2</a></td> ";
                    }else{
                        echo "<td> $value2 </td>";
                    }
                }
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>


</body>
</html>