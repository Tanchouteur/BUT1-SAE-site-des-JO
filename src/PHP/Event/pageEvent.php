<?php

session_start();

if (!isset($_GET['event'])){
    header('location: ../../../index.php');
}elseif ($_GET['event'] == ""){
    header('location: ../../../index.php');
}else{
    $event = $_GET['event'];
}

if (isset($_SESSION['email']) && isset($_SESSION['idRole']) && isset($_SESSION['nom'])) {
    $email = $_SESSION['email'];
    $idRole = $_SESSION['idRole'];
    $nom = $_SESSION['nom'];
}

require_once "../../../import/BDD.php";

$sql = "SELECT nomEvent, lieuEvent, descriptionEvent, typeEvent,roleEvent,createurEvent,dateEvent FROM Event where nomEvent = '$event'";
$result = mysqli_query($db,$sql);
$result = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['commentaire'])){
        $new_commentaire = $_POST['commentaire'];
        $new_commentaire = trim($new_commentaire); // Supprimer les espaces en début et fin
        $sql = "INSERT INTO Commentaire (nom, email, idRole, event, commentaire) VALUES ('$nom', '$email', '$idRole', '$event', '$new_commentaire')";
        $insertCom = mysqli_query($db,$sql);
        $status = 1;
    }
}

$commentaire = "SELECT * FROM Commentaire where event = '$event'";
$resultCommentaire = mysqli_query($db,$commentaire);
$resultCommentaire = $resultCommentaire->fetch_all();



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P2024 - Page de l'Evenement</title>
    <link rel="stylesheet" href="../../css/header.css">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/formulaire.css">
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
            </div>

            <div class="auth-buttons">

                <?php
                if (isset($_SESSION['email']) && isset($_SESSION['idRole']) && isset($_SESSION['nom'])){
                    echo "<a class='btn-navBar' href='../deconnect.php' class='btn-signup'>Deconnexion</a>";
                }else{
                    echo "<a class='btn-navBar' href='../../html/singin.php' class='btn-signup'>Connexion</a>";
                    echo "<a class='btn-navBar' href='../../html/singup.php' class='btn-signup'>Inscription</a>";
                }
                ?>

            </div>
        </div>
    </nav>
</header>
<body>

    <div class="GestionPageEvent EventPresentation">
        <h2>Titre de l'évenement :  <?php echo $result['nomEvent'];?></h2>
        <h3>Lieux de l'évenement :  <?php echo $result['lieuEvent'];?></h3>
        <h4>Type de l'évenement : <?php echo $result['typeEvent'];?> - </h4>
        <h4>Les role de l'évenement :  <?php if ($result['roleEvent'] == 1) {
                    echo "<td> Spectateur </td>";
                } elseif ($result['roleEvent'] == 2) {
                    echo "<td> Sportif </td>";
                } elseif ($result['roleEvent'] == 3) {
                    echo "<td> Spectateur et sportif </td>";
                } ?>
        </h4>
        <h4>Description de l'évenement :  <?php echo $result['descriptionEvent'];?></h4>
        <h4>Créateur de l'évenement : <?php echo $result['createurEvent'];?></h4>
        <h4>Date de l'évenement : <?php echo $result['dateEvent'];?></h4>
    </div>

    <div class="GestionCom"><!--div des comm-->
        <div class="sectionCom">
        <?php
        if (count($resultCommentaire) > 0) {
            foreach ($resultCommentaire as $commentaire) {

                if($commentaire[3] == 0){
                    $nomRole = 'Spectateur';
                }elseif ($commentaire[3] == 1){
                    $nomRole = 'Sportif';
                }elseif ($commentaire[3] == 2){
                    $nomRole = "Organisateur";
                }

                echo "<div class='";
                if ($commentaire[1] == $nom) { echo "myCom";}
                echo " commentaire'>
                        <h2>$commentaire[1] ($nomRole)</h2>
                        <span>$commentaire[5]</span>
                       
                      </div>";
            }
        }
        ?>
        </div>

        <?php
        if (isset($_SESSION['email']) && isset($_SESSION['idRole']) && isset($_SESSION['nom'])) {
            echo "<form action='pageEvent.php?event=$event' method='post'>
            <div class='form-group'>
                <label for='commentaire'>Commentaire : </label>
                <input type='text' id='commentaire' name='commentaire' placeholder='Blabla...' required>
            </div>
            <button type='submit'>Envoyer</button>
            </form>";
        }else{
            echo "Vous devez etre connecter pour envoyer des commentaires";
        }
        ?>
    </div>
</body>
</html>
