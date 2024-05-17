<?php
session_start();
require_once "../../../import/BDD.php";

$email = $_SESSION['email'];

$sql = "SELECT idRole FROM Users where email = '$email'";
$result = mysqli_query($db,$sql);
$result = mysqli_fetch_array($result,MYSQLI_ASSOC);

if ($result['idRole'] !=2){
    header('location: ../../../index.php');
}

$sql = "SELECT nomEvent, lieuEvent, descriptionEvent, typeEvent,roleEvent,createurEvent,dateEvent FROM Event";
$result = mysqli_query($db,$sql);

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Connexion</title>
    <link rel="stylesheet" href="../../css/header.css">
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<header>
    <nav class="navbar">
        <div class="container-nav">
            <div class="brand">
                <h2 class="navbar">Paris 2024</h2>
            </div>

            <div class="nav-links">
                <a href="../../../index.php">Accueil</a>
                <h2 class="navbar active">Evenement</h2>
            </div>

            <div class="auth-buttons">

                <?php echo "<a href='../deconnect.php' class='btn-signup'>Deconnexion</a>"; ?>

            </div>
        </div>
    </nav>
</header>
<body>
    <div class="container-GestionEvent">
        <a href="creeEvent.php">Crée un evenement</a>
    </div>

    <div class="GestionEvent">
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
                        echo "<td><a href='modifierEvent.php?event=$value2'>$value2</a></td> ";
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