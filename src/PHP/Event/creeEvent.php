<?php
session_start();
require_once "../../../import/BDD.php";

$email = $_SESSION['email'];

$sql = "SELECT idRole FROM Users where email = '$email'";
$result = mysqli_query($db,$sql);
$result = mysqli_fetch_array($result,MYSQLI_ASSOC);

if ($result['idRole'] <2){
    header('location: ../../../index.php');
}

$status = "";
if (!empty($_POST)) {
    $nomEvent = $_POST["nomEvent"];
    $lieuEvent = $_POST['lieuEvent']; // Correction ici
    $descriptionEvent = $_POST["descriptionEvent"];
    $typeEvent = $_POST["typeEvent"];
    $createurEvent = $_SESSION["email"];
    $dateEvent = $_POST["dateEvent"];

    // Combinaison des valeurs des cases à cocher
    $roleEvent = 0;
    if (isset($_POST['spectateur'])) {
        $roleEvent += 1;
    }
    if (isset($_POST['sportif'])) {
        $roleEvent += 2;
    }

    $sql2 = "select * from Event where nomEvent=?";
    $stmt2 = $db->prepare($sql2);
    $stmt2->bind_param("s", $nomEvent);
    $stmt2->execute();
    $result = $stmt2->get_result();
    $row = $result->fetch_assoc();

    $sql = "INSERT INTO Event (nomEvent, lieuEvent, descriptionEvent, typeEvent,roleEvent,createurEvent,dateEvent) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    if (!isset($row["nomEvent"])) {
        if ($stmt) {
            $stmt->bind_param("ssssiss", $nomEvent, $lieuEvent, $descriptionEvent, $typeEvent, $roleEvent, $createurEvent, $dateEvent);
            if ($stmt->execute()) {
                $msg = "Événement créé avec succès!";
                header("location: gestionEvent.php?status=1&msg=$msg");
            } else {
                $msg = "Erreur lors de l'exécution de la requête : " . $stmt->error;
                header("location: creeEvent.php?status=0&msg=$msg");
            }
            $stmt->close();
        } else {
            $msg = "Erreur lors de la préparation de la requête : " . $db->error;
            header("location:creeEvent.php?status=0&msg=$msg");
        }
    }else{
        $msg = "Evenement deja existant ";
        header("location: creeEvent.php?status=0&msg=$msg");
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Connexion</title>
    <link rel="stylesheet" href="../../css/header.css">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/formulaire.css">
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
        <a href="gestionEvent.php">Gestion des evenement</a>
    </div>
    <?php
    if (isset($_GET['status'])&&isset($_GET['msg'])) {
        if ($_GET['status']==0) {
            echo "<h2 style='color: #6c2401'> " . $_GET['msg'] . "</h2>";
        }else if ($_GET['status']==1) {
            echo "<h2 style='color: #016c23'> " . $_GET['msg'] . "</h2>";
        }
    }?>
    <form action="creeEvent.php" method="post">

        <div class="form-group">
            <label for="nomEvent">Nom de l'évenement : </label>
            <input type="text" id="nomEvent" name="nomEvent" required>
        </div>
        <div class="form-group">
            <label for="lieuEvent">Lieux de l'évenement :</label>
            <input type="text" id="lieuEvent" name="lieuEvent" required>
        </div>
        <div class="form-group">
            <label for="descriptionEvent">Description : </label>
            <input type="text" id="descriptionEvent" name="descriptionEvent" required>
        </div>

        <div class="form-group">
            <label for="typeEvent">Type d'évenement : </label>
            <select id="typeEvent" name="typeEvent" required >
                <option value="1">Type 1</option>
                <option value="2">Type 2</option>
                <option value="3">Type 3</option>
            </select>
        </div>

        <div class="form-group">
            <label for="roleEvent">Type d'évenement : </label>
            <div>
                <input type="checkbox" id="spectateur" name="spectateur" value="0"/>
                <label for="spectateur">Spectateur</label>
            </div>

            <div>
                <input type="checkbox" id="sportif" name="sportif" value="1"/>
                <label for="sportif">Sportif</label>
            </div>

        </div>
        <div class="form-group">
            <label for="dateEvent">Date de l'évenement : </label>
            <input type="date" id="dateEvent" name="dateEvent"/>
        </div>


        <button type="submit">Crée l'évenement</button>
    </form>
    <?php if (!empty($status)) { echo "<p>$status</p>"; } ?>
</body>
</html>
